<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CustomerDebt;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Quotation::with(['client', 'user', 'convertedSale']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_nuit', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $quotations = $query->latest('date')->latest('id')->paginate(12)->withQueryString();

        $counts = [
            'all'       => Quotation::count(),
            'draft'     => Quotation::where('status', 'draft')->count(),
            'sent'      => Quotation::where('status', 'sent')->count(),
            'approved'  => Quotation::where('status', 'approved')->count(),
            'converted' => Quotation::where('status', 'converted')->count(),
        ];

        return view('quotations.index', compact('quotations', 'counts'));
    }

    public function create(): View
    {
        $tenantId = current_tenant_id() ?? auth()->user()?->tenant_id;

        $clients = Client::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $nextNumber = Quotation::generateNextNumber($tenantId);

        return view('quotations.create', compact('clients', 'products', 'nextNumber'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id'            => 'nullable|exists:clients,id',
            'customer_name'        => 'required|string|max:255',
            'customer_nuit'        => 'nullable|string|max:30',
            'customer_email'       => 'nullable|email|max:255',
            'customer_phone'       => 'nullable|string|max:50',
            'customer_address'     => 'nullable|string|max:255',
            'date'                 => 'required|date',
            'valid_until'          => 'nullable|date|after_or_equal:date',
            'tax_regime'           => 'required|in:normal,exempt,simplified',
            'tax_rate'             => 'nullable|numeric|min:0|max:100',
            'tax_exemption_reason' => 'nullable|string|max:255',
            'prices_include_tax'   => 'nullable|boolean',
            'notes'                => 'nullable|string',
            'terms_conditions'     => 'nullable|string',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'nullable|exists:products,id',
            'items.*.item_name'    => 'required|string|max:255',
            'items.*.description'  => 'nullable|string',
            'items.*.quantity'     => 'required|numeric|min:0.01',
            'items.*.unit_price'   => 'required|numeric|min:0',
            'items.*.discount'     => 'nullable|numeric|min:0',
            'items.*.is_exempt'    => 'nullable|boolean',
        ]);

        try {
            $tenantId = current_tenant_id() ?? auth()->user()?->tenant_id;

            $quotation = DB::transaction(function () use ($validated, $tenantId, $request) {
                $quotationNumber = Quotation::generateNextNumber($tenantId);

                $quotation = Quotation::create([
                    'tenant_id'            => $tenantId,
                    'client_id'            => $validated['client_id'] ?? null,
                    'user_id'              => auth()->id(),
                    'quotation_number'     => $quotationNumber,
                    'customer_name'        => $validated['customer_name'],
                    'customer_nuit'        => $validated['customer_nuit'] ?? null,
                    'customer_email'       => $validated['customer_email'] ?? null,
                    'customer_phone'       => $validated['customer_phone'] ?? null,
                    'customer_address'     => $validated['customer_address'] ?? null,
                    'date'                 => $validated['date'],
                    'valid_until'          => $validated['valid_until'] ?? null,
                    'tax_regime'           => $validated['tax_regime'],
                    'tax_rate'             => (float) ($validated['tax_rate'] ?? 16.0),
                    'tax_exemption_reason' => $validated['tax_exemption_reason'] ?? null,
                    'prices_include_tax'   => $request->boolean('prices_include_tax', true),
                    'status'               => 'draft',
                    'notes'                => $validated['notes'] ?? null,
                    'terms_conditions'     => $validated['terms_conditions'] ?? null,
                ]);

                foreach ($validated['items'] as $itemData) {
                    $quotation->items()->create([
                        'tenant_id'       => $tenantId,
                        'product_id'      => $itemData['product_id'] ?? null,
                        'item_name'       => $itemData['item_name'],
                        'description'     => $itemData['description'] ?? null,
                        'quantity'        => (float) $itemData['quantity'],
                        'unit_price'      => (float) $itemData['unit_price'],
                        'discount_amount' => (float) ($itemData['discount'] ?? 0),
                        'tax_rate'        => (float) ($validated['tax_rate'] ?? 16.0),
                        'is_tax_exempt'   => !empty($itemData['is_exempt']),
                    ]);
                }

                $quotation->calculateTotals();

                return $quotation;
            });

            return redirect()->route('quotations.show', $quotation)
                ->with('success', "Cotação {$quotation->quotation_number} emitida com sucesso!");
        } catch (\Exception $e) {
            Log::error('Erro ao criar cotação: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao salvar cotação: ' . $e->getMessage());
        }
    }

    public function show(Quotation $quotation): View
    {
        $quotation->load(['items.product', 'client', 'user', 'convertedSale']);
        return view('quotations.show', compact('quotation'));
    }

    public function downloadPdf(Quotation $quotation)
    {
        $quotation->load(['items.product', 'client', 'user']);

        $pdf = Pdf::loadView('documents.quotation_pdf', compact('quotation'))
            ->setPaper('a4', 'portrait');

        $cleanNumber = str_replace(['/', '\\'], '_', $quotation->quotation_number);
        return $pdf->download("Cotacao_{$cleanNumber}.pdf");
    }

    public function updateStatus(Request $request, Quotation $quotation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,approved,rejected',
        ]);

        if ($quotation->status === 'converted') {
            return back()->with('error', 'Não é possível alterar o estado de uma cotação já convertida em venda.');
        }

        $quotation->update(['status' => $validated['status']]);

        return back()->with('success', "Estado da cotação atualizado para {$quotation->status_label}.");
    }

    /**
     * Converter Cotação em Factura / Recibo / Venda (Estilo ZBIZ Plus)
     */
    public function convertToSale(Request $request, Quotation $quotation): RedirectResponse
    {
        if (!$quotation->canBeConverted()) {
            return back()->with('error', 'Esta cotação já foi convertida ou encontra-se rejeitada.');
        }

        $validated = $request->validate([
            'invoice_type'   => 'required|in:receipt,cash_invoice,invoice,proforma',
            'payment_method' => 'required|in:cash,card,mpesa,emola,transfer,credit',
            'due_date'       => 'nullable|date',
        ]);

        try {
            $tenantId = current_tenant_id() ?? $quotation->tenant_id ?? auth()->user()?->tenant_id;

            $sale = DB::transaction(function () use ($quotation, $tenantId, $validated) {
                $invoiceType = $validated['invoice_type'];
                $invoiceNumber = Sale::generateNextInvoiceNumber($tenantId, $invoiceType);
                $method = $validated['payment_method'];
                $total = (float) $quotation->total_amount;

                $sale = Sale::create([
                    'tenant_id'          => $tenantId,
                    'user_id'            => auth()->id(),
                    'quotation_id'       => $quotation->id,
                    'sale_date'          => now(),
                    'due_date'           => $validated['due_date'] ?? ($invoiceType === 'invoice' ? now()->addDays(30) : null),
                    'customer_name'      => $quotation->customer_name,
                    'customer_nuit'      => $quotation->customer_nuit,
                    'customer_phone'     => $quotation->customer_phone,
                    'customer_address'   => $quotation->customer_address,
                    'subtotal'           => $quotation->subtotal,
                    'discount_amount'    => $quotation->discount_amount,
                    'total_amount'       => $total,
                    'tax_regime'         => $quotation->tax_regime,
                    'tax_rate'           => $quotation->tax_rate,
                    'tax_amount'         => $quotation->tax_amount,
                    'prices_include_tax' => $quotation->prices_include_tax,
                    'invoice_type'       => $invoiceType,
                    'invoice_number'     => $invoiceNumber,
                    'payment_method'     => $method,
                    'cash_amount'        => $method === 'cash' ? $total : 0,
                    'card_amount'        => in_array($method, ['card', 'transfer']) ? $total : 0,
                    'mpesa_amount'       => $method === 'mpesa' ? $total : 0,
                    'emola_amount'       => $method === 'emola' ? $total : 0,
                    'status'             => $method === 'credit' ? 'pending' : 'completed',
                    'notes'              => "Convertido da Cotação {$quotation->quotation_number}. " . ($quotation->notes ?? ''),
                ]);

                foreach ($quotation->items as $qItem) {
                    $productId = $qItem->product_id;

                    // Se for item avulso sem product_id, localizar primeiro produto ativo como referência técnica ou criar apenas se existir product_id
                    if ($productId) {
                        SaleItem::create([
                            'sale_id'    => $sale->id,
                            'product_id' => $productId,
                            'quantity'   => (int) ceil((float) $qItem->quantity),
                            'unit_price' => (float) $qItem->unit_price,
                        ]);

                        $product = Product::find($productId);
                        if ($product) {
                            $product->decrement('stock_quantity', (int) ceil((float) $qItem->quantity));
                            StockMovement::create([
                                'product_id'     => $product->id,
                                'user_id'        => auth()->id(),
                                'quantity'       => -((int) ceil((float) $qItem->quantity)),
                                'type'           => 'sale',
                                'reference_type' => 'Sale',
                                'reference_id'   => $sale->id,
                                'notes'          => "Venda via Cotação {$quotation->quotation_number}",
                            ]);
                        }
                    }
                }

                if ($method === 'credit') {
                    CustomerDebt::create([
                        'sale_id'          => $sale->id,
                        'user_id'          => auth()->id(),
                        'customer_name'    => $quotation->customer_name,
                        'total_amount'     => $total,
                        'remaining_amount' => $total,
                        'status'           => 'pending',
                        'notes'            => "Factura Comercial {$invoiceNumber} convertida da Cotação {$quotation->quotation_number}",
                    ]);
                }

                $quotation->update([
                    'status'            => 'converted',
                    'converted_sale_id' => $sale->id,
                    'converted_at'      => now(),
                ]);

                return $sale;
            });

            return redirect()->route('sales.show', $sale)
                ->with('success', "Cotação {$quotation->quotation_number} convertida com sucesso em {$sale->official_invoice_title} ({$sale->invoice_number})!");
        } catch (\Exception $e) {
            Log::error('Erro ao converter cotação: ' . $e->getMessage());
            return back()->with('error', 'Erro ao converter cotação em factura: ' . $e->getMessage());
        }
    }
}


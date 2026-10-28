<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\CashShift;
use App\Notifications\SaleCompletedNotification;
use App\Notifications\LowStockNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        // Apply date filters if provided
        $salesQuery = Sale::query();

        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'today':
                    $salesQuery->whereDate('sale_date', Carbon::today());
                    break;
                case 'yesterday':
                    $salesQuery->whereDate('sale_date', Carbon::yesterday());
                    break;
                case 'lastWeek':
                    $salesQuery->whereBetween('sale_date', [Carbon::now()->subWeek(), Carbon::now()]);
                    break;
                case 'lastMonth':
                    $salesQuery->whereBetween('sale_date', [Carbon::now()->subMonth(), Carbon::now()]);
                    break;
            }
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $salesQuery->whereBetween('sale_date', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Total de vendas acumuladas
        $totalSalesAmount = Sale::sum('total_amount');

        // Vendas do dia
        $todaySalesAmount = Sale::whereDate('sale_date', Carbon::today())->sum('total_amount');

        // Total de transações realizadas
        $totalSales = Sale::count();

        // Vendas pendentes
        $pendingSalesCount = Sale::where('status', 'pending')->count();

        // Paginação das vendas
        $sales = $salesQuery
            ->orderBy('sale_date', 'desc')
            ->paginate(6);

        return view('sales.index', compact('sales', 'totalSalesAmount', 'todaySalesAmount', 'totalSales', 'pendingSalesCount'));
    }

    public function create()
    {
        // Obter produtos ativos
        $products = Product::where('is_active', true)->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $validated = $request->validate([
            'user_id' => auth()->id(),
            'customer_name' => $order->customer_name ?? 'Cliente',
            'payment_method' => 'required|string',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'cash_amount' => 'nullable|numeric|min:0',
            'card_amount' => 'nullable|numeric|min:0',
            'mpesa_amount' => 'nullable|numeric|min:0',
            'emola_amount' => 'nullable|numeric|min:0',
        ]);

        // Iniciar transação para garantir que a venda e o estoque sejam atualizados de forma atômica
        DB::beginTransaction();

        try {
            // Calcular o valor total da venda
            $total_amount = 0;
            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['id']);
                $total_amount += $product->price * $item['quantity'];
            }

            // Criar a venda
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'sale_date' => now(),
                'total_amount' => $total_amount,
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
                'cash_amount' => $validated['cash_amount'] ?? 0,
                'card_amount' => $validated['card_amount'] ?? 0,
                'mpesa_amount' => $validated['mpesa_amount'] ?? 0,
                'emola_amount' => $validated['emola_amount'] ?? 0,
            ]);

            // Criar os itens da venda e atualizar o estoque
            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['id']);
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price
                ]);

                // Atualizar o estoque
                $product->decrement('stock_quantity', $item['quantity']);

                // Registrar movimentação de estoque
                StockMovement::create([
                    'product_id' => $item['id'],
                    'user_id' => auth()->id(),
                    'quantity' => -$item['quantity'],
                    'type' => 'sale',
                    'reference_type' => 'Sale',
                    'reference_id' => $sale->id,
                    'notes' => 'Venda Manual'
                ]);

                // Verificar estoque baixo
                if ($product->stock_quantity <= $product->min_stock_level) {
                    $users = User::whereIn('role', ['admin', 'manager', 'chef', 'waiter'])->get();
                    Notification::send($users, new LowStockNotification($product));
                }
            }

            // Notificar venda concluída
            $users = User::whereIn('role', ['admin', 'manager'])->get();
            Notification::send($users, new SaleCompletedNotification($sale));

            // Commit da transação
            DB::commit();

            // Redirecionar de volta para a lista de vendas com uma mensagem de sucesso
            return redirect()->route('sales.index')
                ->with('success', __('messages.sale_completed'));
        } catch (\Exception $e) {
            // Caso ocorra um erro, fazer rollback da transação
            DB::rollBack();
            return back()->with('error', __('messages.error_occurred') . ': ' . $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        // Carregar os itens da venda junto com os produtos e cotação associada
        $sale->load(['saleItems.product', 'quotation']);
        return view('sales.show', compact('sale'));
    }

    public function receipt($saleId)
    {
        // Carregar a venda com seus itens e produtos relacionados
        $sale = Sale::with(['saleItems.product', 'quotation.items'])->findOrFail($saleId);
        return view('sales.receipt', compact('sale'));
    }

    /**
     * Emitir / Descarregar Factura A4, Factura-Recibo ou Recibo Oficial em PDF (Estilo ZBIZ Plus)
     */
    public function invoicePdf(Sale $sale, Request $request)
    {
        $sale->load(['saleItems.product', 'user', 'quotation.items']);

        if ($request->filled('type') && in_array($request->type, ['receipt', 'cash_invoice', 'invoice', 'proforma'])) {
            if ($sale->invoice_type !== $request->type) {
                $tenantId = $sale->tenant_id ?? current_tenant_id();
                $sale->invoice_type = $request->type;
                $sale->invoice_number = Sale::generateNextInvoiceNumber($tenantId, $request->type);
                $sale->save();
            }
        }

        $pdf = Pdf::loadView('documents.invoice_pdf', compact('sale'))
            ->setPaper('a4', 'portrait');

        $cleanNumber = str_replace(['/', '\\'], '_', $sale->invoice_number ?? ('VD_' . str_pad($sale->id, 5, '0', STR_PAD_LEFT)));
        return $pdf->download("Documento_{$cleanNumber}.pdf");
    }

    /**
     * Atualizar Dados Fiscais / Tipo de Documento (Recibo, Factura-Recibo, Factura Comercial) e NUIT do Cliente
     */
    public function updateInvoiceDetails(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'invoice_type'     => 'required|in:receipt,cash_invoice,invoice,proforma',
            'customer_name'    => 'required|string|max:255',
            'customer_nuit'    => 'nullable|string|max:30',
            'customer_phone'   => 'nullable|string|max:40',
            'customer_address' => 'nullable|string|max:255',
            'tax_regime'       => 'nullable|in:normal,exempt,simplified',
            'due_date'         => 'nullable|date',
        ]);

        $tenantId = $sale->tenant_id ?? current_tenant_id();
        $newType = $validated['invoice_type'];

        if ($sale->invoice_type !== $newType || empty($sale->invoice_number)) {
            $sale->invoice_number = Sale::generateNextInvoiceNumber($tenantId, $newType);
        }

        $taxRegime = $validated['tax_regime'] ?? ($sale->tax_regime ?: 'normal');
        $taxRate = $taxRegime === 'normal' ? (float) ($sale->tax_rate ?: 16.0) : 0.0;
        $total = (float) $sale->total_amount;
        $taxAmount = 0.0;

        if ($taxRegime === 'normal' && $taxRate > 0 && $total > 0) {
            $base = $total / (1 + ($taxRate / 100));
            $taxAmount = round($total - $base, 2);
        }

        $sale->update([
            'invoice_type'     => $newType,
            'invoice_number'   => $sale->invoice_number,
            'customer_name'    => $validated['customer_name'],
            'customer_nuit'    => $validated['customer_nuit'] ?? null,
            'customer_phone'   => $validated['customer_phone'] ?? null,
            'customer_address' => $validated['customer_address'] ?? null,
            'tax_regime'       => $taxRegime,
            'tax_rate'         => $taxRate,
            'tax_amount'       => $taxAmount,
            'due_date'         => $validated['due_date'] ?? $sale->due_date,
        ]);

        return back()->with('success', "Documento atualizado para {$sale->official_invoice_title} ({$sale->invoice_number}) com sucesso!");
    }

    public function process_sale(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'amount_paid' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'order_id' => 'nullable|integer',
            'customer_name' => 'nullable|string|max:255',
            // Validate breakdown
            'cash_amount' => 'nullable|numeric|min:0',
            'card_amount' => 'nullable|numeric|min:0',
            'mpesa_amount' => 'nullable|numeric|min:0',
            'emola_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $amountPaid = $validated['amount_paid'];
            $totalAmount = $validated['total_amount'];

            // Use provided breakdown or fallback to logic (though frontend should send it now)
            $cash = $request->input('cash_amount', 0);
            $card = $request->input('card_amount', 0);
            $mpesa = $request->input('mpesa_amount', 0);
            $emola = $request->input('emola_amount', 0);

            // If no breakdown provided but method is single, assign amount_paid to that method
            if ($cash == 0 && $card == 0 && $mpesa == 0 && $emola == 0) {
                switch ($validated['payment_method']) {
                    case 'cash':
                        $cash = $amountPaid;
                        break;
                    case 'card':
                        $card = $amountPaid;
                        break;
                    case 'mpesa':
                        $mpesa = $amountPaid;
                        break;
                    case 'emola':
                        $emola = $amountPaid;
                        break;
                }
            }

            // Localizar turno de caixa aberto para o operador atual (ou da casa)
            $activeShift = CashShift::where('user_id', auth()->id())
                ->where('status', 'open')
                ->latest('opened_at')
                ->first();

            if (!$activeShift) {
                $activeShift = CashShift::where('status', 'open')
                    ->latest('opened_at')
                    ->first();
            }

            $invoiceType = $request->input('invoice_type', !empty($request->customer_nuit) ? 'cash_invoice' : 'receipt');

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'cash_shift_id' => $activeShift?->id,
                'sale_date' => now(),
                'customer_name' => $validated['customer_name'] ?? 'Cliente Geral',
                'customer_nuit' => $request->input('customer_nuit'),
                'customer_phone' => $request->input('customer_phone'),
                'customer_address' => $request->input('customer_address'),
                'invoice_type' => $invoiceType,
                'subtotal' => $totalAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
                'cash_amount' => $cash,
                'card_amount' => $card,
                'mpesa_amount' => $mpesa,
                'emola_amount' => $emola,
                'order_id' => $request->order_id,
            ]);

            // Atualizar os acumulados do turno aberto
            if ($activeShift) {
                $activeShift->increment('cash_sales', $cash);
                $activeShift->increment('card_sales', $card);
                $activeShift->increment('mpesa_sales', $mpesa);
                $activeShift->increment('emola_sales', $emola);
                $activeShift->increment('total_sales', $totalAmount);
            }

            // Create Debt if amount paid is less than total
            if ($amountPaid < $totalAmount) {
                if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
                    throw new \Exception("Apenas administradores e gerentes podem registrar vendas com pagamento parcial (dívidas).");
                }

                \App\Models\CustomerDebt::create([
                    'sale_id' => $sale->id,
                    'user_id' => auth()->id(),
                    'customer_name' => $validated['customer_name'] ?? 'Cliente Geral',
                    'total_amount' => $totalAmount,
                    'remaining_amount' => $totalAmount - $amountPaid,
                    'status' => 'pending',
                    'notes' => 'Dívida originada de venda via POS. Valor total: ' . $totalAmount . ', Valor pago: ' . $amountPaid
                ]);
            }

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['id']);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price']
                ]);

                // Update stock
                $product->decrement('stock_quantity', $item['quantity']);

                // Registrar movimentação de estoque
                StockMovement::create([
                    'product_id' => $item['id'],
                    'user_id' => auth()->id(),
                    'quantity' => -$item['quantity'],
                    'type' => 'sale',
                    'reference_type' => 'Sale',
                    'reference_id' => $sale->id,
                    'notes' => 'Venda Processada'
                ]);
            }

            DB::commit();

            // Sincronizar pedido e mesa caso a venda tenha se originado de um pedido guardado / recuperado
            if ($sale->order_id) {
                $order = DB::table('orders')->where('id', $sale->order_id)->first();
                if ($order) {
                    // Marcar pedido como concluído e pago para que saia da lista de pedidos guardados
                    DB::table('orders')->where('id', $sale->order_id)->update([
                        'status' => 'completed',
                        'payment_status' => 'paid',
                        'payment_method' => $validated['payment_method'],
                        'delivered_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($order->table_id) {
                        $table = DB::table('tables')->where('id', $order->table_id)->first();
                        if ($table) {
                            if ($table->is_temporary) {
                                // Desvincular mesa temporária para evitar erro de chave estrangeira e excluir
                                DB::table('orders')->where('table_id', $table->id)->update(['table_id' => null]);
                                DB::table('tables')->where('id', $table->id)->delete();
                            } else {
                                // Se for mesa real, verificar se existem outros pedidos pendentes/ativos nela
                                $hasOtherActive = DB::table('orders')
                                    ->where('table_id', $table->id)
                                    ->where('id', '!=', $sale->order_id)
                                    ->whereIn('status', ['active', 'pending', 'preparing', 'ready'])
                                    ->exists();

                                if (!$hasOtherActive) {
                                    DB::table('tables')->where('id', $table->id)->update([
                                        'status' => 'free',
                                        'updated_at' => now()
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('messages.sale_completed'),
                'sale_id' => $sale->id,
                'receipt_url' => route('sales.receipt', $sale->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('messages.error_processing_sale') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function details($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);
        return view('sales.partials.details', compact('sale'));
    }

    public function exportPDF($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);

        $pdf = Pdf::loadView('sales.export.pdf', compact('sale'));

        return $pdf->download('Venda_' . str_pad($sale->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }

    // New API endpoint to get products for POS
    public function getProducts()
    {
        $products = Product::where('is_active', true)
            ->select('id', 'name', 'price', 'stock_quantity')
            ->get();

        return response()->json($products);
    }
}
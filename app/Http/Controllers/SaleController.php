<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\User;
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
        // Carregar os itens da venda junto com os produtos
        $sale->load('saleItems.product');
        return view('sales.show', compact('sale'));
    }

    public function receipt($saleId)
    {
        // Carregar a venda com seus itens e produtos relacionados
        $sale = Sale::with(['saleItems.product'])->findOrFail($saleId);
        return view('sales.receipt', compact('sale'));
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

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'sale_date' => now(),
                'customer_name' => $validated['customer_name'] ?? 'Cliente Geral',
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
                'cash_amount' => $cash,
                'card_amount' => $card,
                'mpesa_amount' => $mpesa,
                'emola_amount' => $emola,
                'order_id' => $request->order_id,
            ]);

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

            // Clean up temporary table if this sale came from a held order
            if ($sale->order_id) {
                $order = DB::table('orders')->where('id', $sale->order_id)->first();
                if ($order && $order->table_id) {
                    $table = DB::table('tables')->where('id', $order->table_id)->first();
                    if ($table && $table->is_temporary) {
                        // Set table_id to null in orders to avoid FK constraint violation
                        DB::table('orders')->where('table_id', $table->id)->update(['table_id' => null]);
                        // Delete temporary table
                        DB::table('tables')->where('id', $table->id)->delete();
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
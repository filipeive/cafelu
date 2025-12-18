<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

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
            }

            // Commit da transação
            DB::commit();

            // Redirecionar de volta para a lista de vendas com uma mensagem de sucesso
            return redirect()->route('sales.index')
                ->with('success', 'Sale completed successfully');
        } catch (\Exception $e) {
            // Caso ocorra um erro, fazer rollback da transação
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
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
            // Validate breakdown
            'cash_amount' => 'nullable|numeric|min:0',
            'card_amount' => 'nullable|numeric|min:0',
            'mpesa_amount' => 'nullable|numeric|min:0',
            'emola_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Use provided breakdown or fallback to logic (though frontend should send it now)
            $cash = $request->input('cash_amount', 0);
            $card = $request->input('card_amount', 0);
            $mpesa = $request->input('mpesa_amount', 0);
            $emola = $request->input('emola_amount', 0);

            // If no breakdown provided but method is single, assign amount_paid to that method
            // This maintains backward compatibility if frontend didn't send breakdown
            if ($cash == 0 && $card == 0 && $mpesa == 0 && $emola == 0) {
                switch ($validated['payment_method']) {
                    case 'cash':
                        $cash = $validated['amount_paid'];
                        break;
                    case 'card':
                        $card = $validated['amount_paid'];
                        break;
                    case 'mpesa':
                        $mpesa = $validated['amount_paid'];
                        break;
                    case 'emola':
                        $emola = $validated['amount_paid'];
                        break;
                }
            }

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'sale_date' => now(),
                'total_amount' => $validated['total_amount'],
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
                'cash_amount' => $cash,
                'card_amount' => $card,
                'mpesa_amount' => $mpesa,
                'emola_amount' => $emola,
                'order_id' => $request->order_id, // Save order_id linkage
            ]);

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
            }

            DB::commit();

            // Clean up temporary table if this sale came from a held order
            if ($sale->order_id) {
                $order = DB::table('orders')->where('id', $sale->order_id)->first();
                if ($order && $order->table_id) {
                    $table = DB::table('tables')->where('id', $order->table_id)->first();
                    if ($table && $table->is_temporary) {
                        // Delete temporary table
                        DB::table('tables')->where('id', $table->id)->delete();
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Venda realizada com sucesso!',
                'sale_id' => $sale->id,
                'receipt_url' => route('sales.receipt', $sale->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar venda: ' . $e->getMessage()
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

        $pdf = PDF::loadView('sales.export.pdf', compact('sale'));

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
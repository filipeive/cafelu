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

    public function process(Request $request)
    {
        // Validação básica
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'cashPayment' => 'nullable|numeric|min:0',
            'cardPayment' => 'nullable|numeric|min:0',
            'mpesaPayment' => 'nullable|numeric|min:0',
            'emolaPayment' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Determinar método de pagamento principal
            $paymentMethod = 'cash'; // Default
            if ($validated['cardPayment'] > 0 && $validated['cashPayment'] == 0 && $validated['mpesaPayment'] == 0 && $validated['emolaPayment'] == 0) {
                $paymentMethod = 'card';
            } elseif ($validated['mpesaPayment'] > 0 && $validated['cashPayment'] == 0 && $validated['cardPayment'] == 0 && $validated['emolaPayment'] == 0) {
                $paymentMethod = 'mpesa';
            } elseif ($validated['emolaPayment'] > 0 && $validated['cashPayment'] == 0 && $validated['cardPayment'] == 0 && $validated['mpesaPayment'] == 0) {
                $paymentMethod = 'emola';
            } elseif (
                ($validated['cashPayment'] > 0 && ($validated['cardPayment'] > 0 || $validated['mpesaPayment'] > 0 || $validated['emolaPayment'] > 0)) ||
                ($validated['cardPayment'] > 0 && ($validated['mpesaPayment'] > 0 || $validated['emolaPayment'] > 0)) ||
                ($validated['mpesaPayment'] > 0 && $validated['emolaPayment'] > 0)
            ) {
                $paymentMethod = 'mixed';
            }

            // Calcula o total da venda
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $totalAmount += $item['unit_price'] * $item['quantity'];
            }

            // Criar a venda
            $sale = Sale::create([
                'sale_date' => now(),
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'status' => 'completed',
                'cash_amount' => $validated['cashPayment'] ?? 0,
                'card_amount' => $validated['cardPayment'] ?? 0,
                'mpesa_amount' => $validated['mpesaPayment'] ?? 0,
                'emola_amount' => $validated['emolaPayment'] ?? 0,
            ]);

            // Criar os itens da venda e atualizar o estoque
            foreach ($validated['items'] as $item) {
                // Encontrar produto
                $product = Product::findOrFail($item['product_id']);
                
                // Criar item da venda
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price']
                ]);
                
                // Atualizar estoque se necessário
                if ($product->track_inventory) {
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Venda processada com sucesso!', 
                'saleId' => $sale->id,
                'receiptUrl' => route('sales.receipt', $sale->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao processar a venda.', 
                'error' => $e->getMessage()
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
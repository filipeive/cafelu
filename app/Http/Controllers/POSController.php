<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
    {
        public function index(Request $request)
    {
        $categoryFilter = $request->query('category');
        $searchTerm = $request->query('search');
        $perPage = 12;

        $categories = Category::all();

        $query = Product::query();

        if ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        }

        if ($searchTerm) {
            $query->where('name', 'LIKE', "%$searchTerm%");
        }

        // Paginação automática
        $products = $query->paginate($perPage)->withQueryString();

        return view('pos.index', [
            'categories' => $categories,
            'products' => $products,
            'categoryFilter' => $categoryFilter,
            'searchTerm' => $searchTerm
        ]);
    }

    // No POSController.php, vamos modificar a verificação de pagamento para considerar o troco

    public function checkout(Request $request)
    {
        Log::info('Checkout Request:', $request->all());

        try {
            // Decodificar items do JSON (vem como string do form)
            $itemsJson = $request->input('items');
            $items = json_decode($itemsJson, true);
            
            if (!$items || !is_array($items) || count($items) === 0) {
                return redirect()->back()
                    ->with('error', 'Carrinho vazio. Adicione produtos antes de finalizar.')
                    ->withInput();
            }

            // Validar pagamentos
            $validated = $request->validate([
                'cashPayment' => 'nullable|numeric|min:0',
                'cardPayment' => 'nullable|numeric|min:0',
                'mpesaPayment' => 'nullable|numeric|min:0',
                'emolaPayment' => 'nullable|numeric|min:0',
            ]);

                // Validar estrutura de cada item
                foreach ($items as $index => $item) {
                    if (!isset($item['product_id']) || !isset($item['quantity']) || !isset($item['unit_price'])) {
                        return redirect()->back()
                            ->with('error', 'Dados do produto inválidos.')
                            ->withInput();
                    }

                    // Validar se produto existe
                    $productExists = DB::table('products')->where('id', $item['product_id'])->exists();
                    if (!$productExists) {
                        return redirect()->back()
                            ->with('error', 'Produto ID ' . $item['product_id'] . ' não encontrado.')
                            ->withInput();
                    }
                }

                DB::beginTransaction();

                // Cálculo do total
                $totalAmount = collect($items)->sum(function($item) {
                    return $item['unit_price'] * $item['quantity'];
                });

                // Pagamentos recebidos
                $cashPayment = floatval($validated['cashPayment'] ?? 0);
                $cardPayment = floatval($validated['cardPayment'] ?? 0);
                $mpesaPayment = floatval($validated['mpesaPayment'] ?? 0);
                $emolaPayment = floatval($validated['emolaPayment'] ?? 0);
                
                // Pagamentos não em dinheiro
                $nonCashPayments = $cardPayment + $mpesaPayment + $emolaPayment;
                
                // Verificar se pagamentos não em dinheiro ultrapassam o total
                if ($nonCashPayments > $totalAmount) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', sprintf(
                            'Pagamentos sem dinheiro (MZN %s) ultrapassam o valor total (MZN %s)',
                            number_format($nonCashPayments, 2),
                            number_format($totalAmount, 2)
                        ))
                        ->withInput();
                }
                
                // Calcular valor restante que deve ser pago em dinheiro
                $remainingAmount = $totalAmount - $nonCashPayments;
                
                // Verificar se pagamento em dinheiro é suficiente
                if ($cashPayment < $remainingAmount) {
                    DB::rollBack();
                    $missing = $remainingAmount - $cashPayment;
                    return redirect()->back()
                        ->with('error', sprintf(
                            'Pagamento insuficiente. Faltam MZN %s',
                            number_format($missing, 2)
                        ))
                        ->withInput();
                }
                
                // Calcular troco (apenas do pagamento em dinheiro)
                $change = $cashPayment > $remainingAmount ? $cashPayment - $remainingAmount : 0;

                // Criar a venda
                $saleId = DB::table('sales')->insertGetId([
                    'user_id' => auth()->id(),
                    'sale_date' => now(),
                    'total_amount' => $totalAmount,
                    'payment_method' => $this->determinePaymentMethod([
                        'cashPayment' => $cashPayment,
                        'cardPayment' => $cardPayment,
                        'mpesaPayment' => $mpesaPayment,
                        'emolaPayment' => $emolaPayment
                    ]),
                    'status' => 'completed',
                    'cash_amount' => $cashPayment,
                    'card_amount' => $cardPayment,
                    'mpesa_amount' => $mpesaPayment,
                    'emola_amount' => $emolaPayment,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Inserir itens da venda e atualizar estoque
                foreach ($items as $item) {
                    // Inserir item da venda
                    DB::table('sale_items')->insert([
                        'sale_id' => $saleId,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Atualizar estoque
                    DB::table('products')
                        ->where('id', $item['product_id'])
                        ->decrement('stock_quantity', $item['quantity']);
                }

                DB::commit();
            
            Log::info('Venda processada com sucesso', [
                'sale_id' => $saleId,
                'total' => $totalAmount,
                'change' => $change
            ]);

            // Manter os dados da venda na sessão para uso posterior
            session()->put('last_sale_id', $saleId);
            session()->put('last_sale_change', $change);
            session()->put('last_sale_total', $totalAmount);
            
        // Redirecionar com sucesso
        return redirect()
            ->route('pos.index', ['saleId' => $saleId])
            ->with('success', 'Venda concluída com sucesso!')
            ->with('change', $change)
            ->with('totalAmount', $totalAmount);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erro ao processar venda: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Determina o método de pagamento principal
     */
    private function determinePaymentMethod($paymentData)
    {
        $methods = [
            'cash' => $paymentData['cashPayment'] ?? 0,
            'card' => $paymentData['cardPayment'] ?? 0,
            'mpesa' => $paymentData['mpesaPayment'] ?? 0,
            'emola' => $paymentData['emolaPayment'] ?? 0
        ];

        // Filtrar métodos usados (valor > 0)
        $usedMethods = array_filter($methods, function($amount) {
            return $amount > 0;
        });

        // Se houver mais de um método, retorna "multiple"
        if (count($usedMethods) > 1) {
            return 'multiple';
        }

        // Se houver apenas um, retorna o nome do método
        if (count($usedMethods) === 1) {
            return array_key_first($usedMethods);
        }

        // Padrão: cash
        return 'cash';
    }

    public function receipt($saleId)
    {
        // Buscar os dados da venda
        $sale = DB::table('sales')->where('id', $saleId)->first();
        
        if (!$sale) {
            abort(404, 'Venda não encontrada');
        }

        // Buscar os itens da venda
        $items = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sale_id', $saleId)
            ->select('products.name', 'sale_items.quantity', 'sale_items.unit_price')
            ->get();

        return view('pos.receipt', [
            'sale' => $sale,
            'items' => $items
        ]);
    }

}
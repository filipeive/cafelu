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
        $limit = 12;
        $page = $request->query('page', 1);
        $offset = ($page - 1) * $limit;

        $categories = Category::all();

        $query = Product::query();

        if ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        }

        if ($searchTerm) {
            $query->where('name', 'LIKE', "%$searchTerm%");
        }

        $products = $query->offset($offset)->limit($limit)->get();

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
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'cashPayment' => 'nullable|numeric|min:0',
                'cardPayment' => 'nullable|numeric|min:0',
                'mpesaPayment' => 'nullable|numeric|min:0',
                'emolaPayment' => 'nullable|numeric|min:0',
            ]);

            DB::beginTransaction();

            // Cálculo do total
            $totalAmount = collect($validated['items'])->sum(function($item) {
                return $item['unit_price'] * $item['quantity'];
            });

            // Verificação de pagamento com tratamento de troco
            $cashPayment = $validated['cashPayment'] ?? 0;
            $cardPayment = $validated['cardPayment'] ?? 0;
            $mpesaPayment = $validated['mpesaPayment'] ?? 0;
            $emolaPayment = $validated['emolaPayment'] ?? 0;
            
            // Os pagamentos não em dinheiro devem corresponder exatamente ao valor cobrado
            $nonCashPayments = $cardPayment + $mpesaPayment + $emolaPayment;
            
            // Verificar se os pagamentos não em dinheiro já ultrapassam o total
            if ($nonCashPayments > $totalAmount) {
                throw new \Exception("Pagamentos sem dinheiro ($nonCashPayments) ultrapassam o valor total da venda ($totalAmount)");
            }
            
            // Se houver pagamento em dinheiro, deve cobrir pelo menos o restante
            $remainingAmount = $totalAmount - $nonCashPayments;
            if ($cashPayment < $remainingAmount) {
                throw new \Exception("Pagamento insuficiente. Faltam MZN " . number_format($remainingAmount - $cashPayment, 2));
            }
            
            // Calcular o troco (apenas para pagamento em dinheiro)
            $change = $cashPayment > $remainingAmount ? $cashPayment - $remainingAmount : 0;

            // Criação da venda
            $saleId = DB::table('sales')->insertGetId([
                'user_id' => auth()->user()->id,
                'sale_date' => now(),
                'total_amount' => $totalAmount,
                'payment_method' => $this->determinePaymentMethod($validated),
                'status' => 'completed',
                'cash_amount' => $cashPayment,
                'card_amount' => $cardPayment,
                'mpesa_amount' => $mpesaPayment,
                'emola_amount' => $emolaPayment,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Itens da venda
            foreach ($validated['items'] as $item) {
                DB::table('sale_items')->insert([
                    'sale_id' => $saleId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Atualiza estoque
                DB::table('products')
                    ->where('id', $item['product_id'])
                    ->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venda concluída com sucesso',
                'sale_id' => $saleId,
                'change' => $change // Retorna o valor do troco para exibição no front-end
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar venda: ' . $e->getMessage()
            ], 500);
        }
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

        // Se houver mais de um método, indica "multiple"
        $usedMethods = array_filter($methods, function($amount) {
            return $amount > 0;
        });

        if (count($usedMethods) > 1) {
            return 'multiple';
        }

        // Retorna o método usado ou "cash" como padrão
        $method = array_key_first($usedMethods);
        return $method ?: 'cash';
    }
}
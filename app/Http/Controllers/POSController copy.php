<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

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

    public function completeCheckout(Request $request)
    {
        \Log::info('Checkout Request:', $request->all());

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

            // Verificação de pagamento
            $totalPayments = array_sum([
                $validated['cashPayment'] ?? 0,
                $validated['cardPayment'] ?? 0,
                $validated['mpesaPayment'] ?? 0,
                $validated['emolaPayment'] ?? 0
            ]);

            if (abs($totalPayments - $totalAmount) > 0.01) {
                throw new \Exception("Total de pagamentos ($totalPayments) não confere com valor da venda ($totalAmount)");
            }

            // Criação da venda
            $saleId = DB::table('sales')->insertGetId([
                'sale_date' => now(),
                'total_amount' => $totalAmount,
                'payment_method' => $this->determinePaymentMethod($validated),
                'status' => 'completed',
                'cash_amount' => $validated['cashPayment'] ?? 0,
                'card_amount' => $validated['cardPayment'] ?? 0,
                'mpesa_amount' => $validated['mpesaPayment'] ?? 0,
                'emola_amount' => $validated['emolaPayment'] ?? 0,
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
                    ->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venda concluída com sucesso',
                'sale_id' => $saleId
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar venda: ' . $e->getMessage()
            ], 500);
        }
    }

}

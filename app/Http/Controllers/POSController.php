<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use App\Models\StockMovement;
use App\Models\Sale;
use App\Models\User;
use App\Notifications\SaleCompletedNotification;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
{
    public function index(Request $request)
    {
        $categoryFilter = $request->query('category');
        $searchTerm = $request->query('search');

        $categories = Category::all();

        $query = Product::query();

        // REMOVIDO: O filtro de categoria do servidor
        // if ($categoryFilter) {
        //     $query->where('category_id', $categoryFilter);
        // }

        if ($searchTerm) {
            $query->where('name', 'LIKE', "%$searchTerm%");
        }

        // Carregar TODOS os produtos para filtrar no front-end (Otimizado)
        $products = $query->select('id', 'name', 'price', 'category_id', 'stock_quantity', 'image')->get();

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
            $totalAmount = collect($validated['items'])->sum(function ($item) {
                return $item['unit_price'] * $item['quantity'];
            });

            // Verificação de pagamento com tratamento de troco
            $cashPayment = $validated['cashPayment'] ?? 0;
            $cardPayment = $validated['cardPayment'] ?? 0;
            $mpesaPayment = $validated['mpesaPayment'] ?? 0;
            $emolaPayment = $validated['emolaPayment'] ?? 0;

            // Os pagamentos não em dinheiro devem corresponder exatamente ao valor cobrado
            $nonCashPayments = $cardPayment + $mpesaPayment + $emolaPayment;

            // Calcular o total pago
            $totalPaid = $cashPayment + $cardPayment + $mpesaPayment + $emolaPayment;

            // Calcular o troco (apenas para pagamento em dinheiro e se o total pago for maior que o total da venda)
            $change = 0;
            if ($totalPaid > $totalAmount && $cashPayment > 0) {
                $change = $totalPaid - $totalAmount;
            }

            // Criação da venda
            $saleId = DB::table('sales')->insertGetId([
                'user_id' => auth()->user()->id,
                'sale_date' => now(),
                'customer_name' => $validated['customer_name'] ?? 'Cliente Geral',
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

            // Se o valor pago for menor que o total, criar uma dívida (apenas para admin e manager)
            if ($totalPaid < $totalAmount) {
                if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
                    throw new \Exception("Apenas administradores e gerentes podem registrar vendas com pagamento parcial (dívidas).");
                }

                $remainingAmount = $totalAmount - $totalPaid;
                \App\Models\CustomerDebt::create([
                    'sale_id' => $saleId,
                    'user_id' => auth()->user()->id,
                    'customer_name' => $validated['customer_name'] ?? 'Cliente Geral',
                    'total_amount' => $totalAmount,
                    'remaining_amount' => $remainingAmount,
                    'status' => 'pending',
                    'notes' => 'Dívida originada de venda via POS. Valor total: ' . $totalAmount . ', Valor pago: ' . $totalPaid
                ]);
            }

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

                // Registra movimentação de estoque
                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'user_id' => auth()->user()->id,
                    'quantity' => -$item['quantity'], // Quantidade negativa para saída de estoque
                    'type' => 'sale',
                    'reference_type' => 'Sale',
                    'reference_id' => $saleId,
                    'notes' => 'Venda via POS'
                ]);

                // Verificar estoque baixo
                $product = Product::find($item['product_id']);
                if ($product && $product->stock_quantity <= $product->min_stock_level) {
                    $users = User::whereIn('role', ['admin', 'manager', 'chef', 'waiter'])->get();
                    Notification::send($users, new LowStockNotification($product));
                }
            }

            DB::commit();

            // Notificar venda concluída
            $sale = Sale::find($saleId);
            if ($sale) {
                $users = User::whereIn('role', ['admin', 'manager'])->get();
                Notification::send($users, new SaleCompletedNotification($sale));
            }

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
    public function hold(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'customer_name' => 'nullable|string',
                'table_id' => 'nullable|exists:tables,id',
                'total_amount' => 'required|numeric'
            ]);

            DB::beginTransaction();

            $tableId = $validated['table_id'] ?? null;
            $createdTempTable = false;

            // Create temporary table if no table selected
            if (!$tableId) {
                // Find next available temporary table number (starting from 9000)
                $maxTempNumber = DB::table('tables')
                    ->where('is_temporary', true)
                    ->max('number');

                $tempNumber = $maxTempNumber ? $maxTempNumber + 1 : 9000;

                $tableId = DB::table('tables')->insertGetId([
                    'number' => $tempNumber,
                    'capacity' => 1,
                    'status' => 'occupied',
                    'is_temporary' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $createdTempTable = true;
            } else {
                // Mark selected table as occupied
                DB::table('tables')->where('id', $tableId)->update([
                    'status' => 'occupied',
                    'updated_at' => now()
                ]);
            }

            // Create order with table assignment
            $orderId = DB::table('orders')->insertGetId([
                'table_id' => $tableId,
                'user_id' => auth()->id(),
                'customer_name' => $validated['customer_name'] ?? 'Cliente Geral',
                'total_amount' => $validated['total_amount'],
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add items
            foreach ($validated['items'] as $item) {
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pedido guardado com sucesso',
                'order_id' => $orderId,
                'table_id' => $tableId,
                'is_temp_table' => $createdTempTable
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hold Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao guardar pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printReceipt($saleId)
    {
        // Redirect to the sales receipt route which is already set up for printing
        return redirect()->route('sales.receipt', $saleId);
    }

    public function getTables()
    {
        $tables = Table::where('is_temporary', false)
            ->where('status', 'free')
            ->orderBy('number')
            ->select('id', 'number', 'capacity', 'status')
            ->get();

        return response()->json($tables);
    }

    public function getHeldOrders()
    {
        $orders = DB::table('orders')
            ->leftJoin('tables', 'orders.table_id', '=', 'tables.id')
            ->where('orders.status', 'active')
            ->orderBy('orders.created_at', 'desc')
            ->select(
                'orders.id',
                'orders.customer_name',
                'orders.total_amount',
                'orders.created_at',
                'tables.number as table_number',
                'tables.is_temporary'
            )
            ->get();

        return response()->json($orders);
    }

    public function retrieveOrder($orderId)
    {
        $order = DB::table('orders')->where('id', $orderId)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pedido não encontrado'], 404);
        }

        $items = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('order_id', $orderId)
            ->select('products.id', 'products.name', 'products.price', 'products.stock_quantity', 'order_items.quantity')
            ->get();

        // Format items for the cart
        $cartItems = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'stock' => $item->stock_quantity
            ];
        });

        return response()->json([
            'success' => true,
            'cart' => $cartItems,
            'customer_name' => $order->customer_name,
            'order_id' => $order->id
        ]);
    }

    private function determinePaymentMethod($paymentData)
    {
        $methods = [
            'cash' => $paymentData['cashPayment'] ?? 0,
            'card' => $paymentData['cardPayment'] ?? 0,
            'mpesa' => $paymentData['mpesaPayment'] ?? 0,
            'emola' => $paymentData['emolaPayment'] ?? 0
        ];

        // Se houver mais de um método, indica "multiple"
        $usedMethods = array_filter($methods, function ($amount) {
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
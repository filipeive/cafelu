<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Client;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Listagem de todos os pedidos
     */
    public function index()
    {
        $search = request('search');
        
        $orders = Order::with('table')
            ->when($search, function($query, $search) {
                return $query->where('customer_name', 'like', "%{$search}%")
                            ->orWhereHas('table', function($q) use ($search) {
                                $q->where('number', 'like', "%{$search}%");
                            })
                            ->orWhere('id', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6);
            
        $total_orders = Order::count();
        $totalToday = Order::today()->sum('total_amount');
        $totalRevenueToday = Order::today()
            ->whereIn('status', ['completed', 'paid'])
            ->sum('total_amount');
        $tables = Table::get();
        $totalOpen = Order::active()->count();

        return view('orders.index', compact(
            'orders', 'total_orders', 'totalToday', 
            'totalRevenueToday', 'tables', 'totalOpen', 'search'
        ));
    }


    public function orderGetTotalToday()
    {
        return Order::whereDate('created_at', today())->sum('total_amount');
    }

    public function order_get_open_count()
    {
        return Order::where('status', 'active')->count();
    }

    /**
     * Mostrar detalhes de um pedido específico
     */
    public function show(Order $order)
    {
        // 👇 GARANTIR CARREGAMENTO DO PRODUTO
        $order->load('items.product', 'table', 'user');
        return view('orders.show', compact('order'));
    }

    /**
     * Editar um pedido (adicionar/remover itens)
     */
    public function edit(Order $order)
    {
        if ($order->status === 'paid' || $order->status === 'canceled') {
            return redirect()->route('orders.index')
                ->with('error', 'Não é possível editar um pedido que já foi pago ou cancelado.');
        }

        // 👇 GARANTIR CARREGAMENTO DO PRODUTO (com trashed, por segurança)
        $order->load([
            'items.product' => function($query) {
                $query->withTrashed();
            },
            'table'
        ]);

        $categories = Category::with(['products' => function($query) {
            $query->where('is_active', 1);
        }])->get();

        $products = Product::where('is_active', 1)->get();

        return view('orders.edit', compact('order', 'categories', 'products'));
    }

    /**
     * Adicionar item ao pedido
     */
    public function addItem(Request $request, Order $order)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        $existingItem = OrderItem::where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->where('status', 'pending')
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->total_price = $existingItem->quantity * $existingItem->unit_price;
            $existingItem->save();
        } else {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->quantity = $request->quantity;
            $orderItem->unit_price = $product->price;
            $orderItem->total_price = $product->price * $request->quantity;
            $orderItem->notes = $request->notes;
            $orderItem->save();
        }

        $this->updateOrderTotal($order);

        return redirect()->route('orders.edit', $order->id)
            ->with('success', 'Item adicionado ao pedido.');
    }

    /**
     * Remover item do pedido
     */
    public function removeItem(OrderItem $orderItem)
    {
        $order = $orderItem->order;

        if ($order->status === 'paid' || $order->status === 'canceled') {
            return redirect()->route('orders.edit', $order->id)
                ->with('error', 'Não é possível modificar um pedido que já foi pago ou cancelado.');
        }

        $orderItem->delete();
        $this->updateOrderTotal($order);

        return redirect()->route('orders.edit', $order->id)
            ->with('success', 'Item removido do pedido.');
    }

    /**
     * Atualizar status do item do pedido
     */
    public function updateItemStatus(Request $request, OrderItem $orderItem)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,delivered,cancelled',
        ]);

        $orderItem->status = $request->status;
        $orderItem->save();

        return redirect()->back()->with('success', 'Status do item atualizado.');
    }

    /**
     * Finalizar pedido (mudar status para 'completed')
     */
    public function complete(Order $order)
    {
        // Verificar se o pedido tem itens
        if ($order->items->count() === 0) {
            $message = 'Este pedido não pode ser finalizado pois não possui itens.';
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }
            return redirect()->back()->with('error', $message);
        }

        if ($order->status !== 'active') {
            $message = 'Apenas pedidos ativos podem ser finalizados.';
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }
            return redirect()->back()->with('error', $message);
        }

        try {
            $order->status = 'completed';
            $order->save();
            
            $message = 'Pedido finalizado com sucesso';
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            // Criar notificação
            \App\Services\NotificationService::orderCompletedNotification($order);
            
            return redirect()->route('orders.edit', $order->id)
                ->with('success', 'Pedido finalizado com sucesso! Registe o Pagamento para Confirmar a Venda');
        } catch (\Exception $e) {
            $message = 'Erro ao finalizar pedido: ' . $e->getMessage();
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Registrar pagamento do pedido
     */
    public function pay(Request $request, Order $order)
    {
        // Verificar se o pedido tem itens
        if ($order->items->count() === 0) {
            $message = 'Este pedido não pode ser pago pois não possui itens.';           
            return redirect()->back()->with('error', $message);
        }
        
        try {
            DB::beginTransaction();

            $request->validate([
                'payment_method' => 'required|in:cash,card,mpesa,emola,mkesh,outros',
                'notes' => 'nullable|string',
                'amount_paid' => 'required|numeric|min:0',
            ]);

            if ($request->amount_paid < $order->total_amount) {
                throw new \Exception('O valor pago não pode ser menor que o total do pedido.');
            }

            if ($order->status !== 'completed') {
                throw new \Exception('Apenas pedidos finalizados podem ser pagos.');
            }

            $sale = Sale::create([
                'order_id'       => $order->id,
                'user_id'        => auth()->id(),
                'customer_name'  => $order->customer_name,
                'total_amount'   => $order->total_amount,
                'payment_method' => $request->payment_method,
                'amount_paid'    => $request->amount_paid,
                'change_amount'  => $request->amount_paid - $order->total_amount,
                'notes'          => $request->notes ?? $order->notes,
                'status'         => 'completed',
            ]);

            foreach ($order->items as $item) {
                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'notes'      => $item->notes ?? null
                ]);

                $product = $item->product;
                if ($product) {
                    // Atualizar stock
                    $product->decrement('stock_quantity', $item->quantity);

                    // Registrar movimento
                    \App\Models\StockMovement::create([
                        'product_id'    => $product->id,
                        'user_id'       => auth()->id(),
                        'movement_type' => 'out',
                        'quantity'      => $item->quantity,
                        'reason'        => 'Venda do pedido #' . $order->id,
                        'reference_id'  => $sale->id,
                        'movement_date' => now(),
                    ]);
                }
            }

            $order->update([
                'status'         => 'paid',
                'payment_method' => $request->payment_method,
                'notes'          => $request->notes,
                'paid_at'        => now()
            ]);

            if ($order->table) {
                $order->table->update(['status' => 'free']);
            }

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Pagamento registrado e venda finalizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao processar pagamento: ' . $e->getMessage());
        }
    }
    /**
     * Cancelar pedido
     */
    public function cancel(Order $order)
    {
        // Verificar se pedido pode ser cancelado
        if ($order->status === 'canceled') {
            return redirect()->back()->with('error', 'Este pedido já está cancelado.');
        }
        
        if ($order->status === 'paid') {
            return redirect()->back()->with('error', 'Não é possível cancelar um pedido já pago.');
        }

        try {
            $request = request();
            
            $validated = $request->validate([
                'notes' => 'required|string|min:3',
            ], [
                'notes.required' => 'O motivo do cancelamento é obrigatório.',
                'notes.min' => 'O motivo deve ter pelo menos 3 caracteres.'
            ]);

            DB::beginTransaction();

            // Atualizar pedido
            $order->notes = ($order->notes ? $order->notes . "\n\n" : '') . 
                        "CANCELADO: " . $validated['notes'];
            $order->status = 'canceled';
            $order->save();

            // Liberar mesa se houver
            if ($order->table) {
                $order->table->status = 'free';
                $order->table->save();
            }

            DB::commit();

            Log::info('Pedido cancelado', [
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'reason' => $validated['notes']
            ]);

            return redirect()
                ->route('orders.index')
                ->with('success', 'Pedido #' . $order->id . ' cancelado com sucesso.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            $errors = collect($e->errors())->flatten()->implode(' ');
            return redirect()->back()->with('error', 'Erro de validação: ' . $errors);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erro ao cancelar pedido', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Erro ao cancelar pedido: ' . $e->getMessage());
        }
    }
    /**
     * Atualizar informações do pedido
     */
    public function update(Request $request, Order $order)
    {
        try {
            $request->validate([
                'customer_name' => 'nullable|string|max:255',
                'notes' => 'nullable|string'
            ]);

            $order->update([
                'customer_name' => $request->customer_name,
                'notes' => $request->notes
            ]);

            return redirect()->back()->with('success', 'Informações atualizadas com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar informações: ' . $e->getMessage());
        }
    }
    /**
     * Atualizar quantidade do item do pedido
     */
    public function updateItemQuantity(Request $request, OrderItem $orderItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $order = $orderItem->order;

        if ($order->status === 'paid' || $order->status === 'canceled') {
            return redirect()->back()->with('error', 'Não é possível modificar um pedido pago ou cancelado.');
        }

        $orderItem->quantity = $request->quantity;
        $orderItem->total_price = $orderItem->unit_price * $request->quantity;
        $orderItem->save();

        $this->updateOrderTotal($order);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Quantidade atualizada']);
        }

        return redirect()->back()->with('success', 'Quantidade atualizada com sucesso.');
    }
    /**
     * Retornar dados do pedido em JSON (para impressão, API, etc)
     */
   public function getOrderData(Order $order)
    {
        $order->load([
            'items.product' => function($query) {
                $query->withTrashed();
            },
            'table',
            'user'
        ]);

        return response()->json([
            'id' => $order->id,
            'customer_name' => $order->customer_name,
            'created_at' => $order->created_at,
            'total_amount' => $order->total_amount,
            'table' => $order->table ? ['number' => $order->table->number] : null,
            'user' => ['name' => $order->user->name ?? 'Sistema'],
            'items' => $order->items->map(function ($item) {
                return [
                    'product' => [
                        'name' => $item->product ? $item->product->name : '[Produto não encontrado]'
                    ],
                    'quantity' => $item->quantity,
                    'total_price' => $item->total_price,
                ];
            }),
            'logo' => asset('assets/images/logo_zalala.png'),
            'companyName' => 'ZALALA BEACH BAR',
            'address' => 'Bairro de Zalala, ER470',
            'phone' => 'Tel: (+258) 846 885 214',
            'nuit' => '110735901',
            'email' => 'info@zalalabeach.com',
        ]);
    }

    /**
     * Imprimir recibo do pedido
     */
    public function printReceipt(Order $order)
    {
        $order->load('items.product', 'table', 'user');
        return view('orders.receipt', compact('order'));
    }

    // Alias para print
    public function print(Order $order)
    {
        $order->load('items.product', 'table', 'user');
        return view('orders.receipt', compact('order'));
    }

    /**
     * Método para atualizar o valor total do pedido
     */
    private function updateOrderTotal(Order $order)
    {
        $total = OrderItem::where('order_id', $order->id)->sum('total_price');
        $order->total_amount = $total;
        $order->save();
    }
}
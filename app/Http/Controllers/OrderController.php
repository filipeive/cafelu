<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Listagem de todos os pedidos
     */
    public function index()
    {
        $search = request('search');
        $orders = Order::with('table')->orderBy('created_at', 'desc')->paginate(15);
        $total_orders = Order::count();
         // Obtém o total de pedidos feitos hoje
         $totalToday = $this->orderGetTotalToday();
         // Obtém o total de pedidos abertos
         $totalOpen = $this->order_get_open_count();

        return view('orders.index', compact('orders', 'total_orders', 'totalToday', 'totalOpen', 'search'));
    }
    public function orderGetTotalToday()
    {
        // Calcula o total de pedidos feitos hoje
        $total = Order::whereDate('created_at', today())->sum('total_amount'); // Ou qualquer campo que represente o valor total

        return $total;
    }
    public function order_get_open_count()
    {
        // Obtém o total de pedidos abertos
        $total = Order::where('status', 'active')->count();

        return $total;
    }
    /**
     * Mostrar detalhes de um pedido específico
     */
    public function show(Order $order)
    {
        $order->load('items.product', 'table', 'user');
        return view('orders.show', compact('order'));
    }

    /**
     * Editar um pedido (adicionar/remover itens)
     */
    public function edit(Order $order)
    {
        if ($order->status === 'paid' || $order->status === 'canceled') {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Não é possível editar um pedido que já foi pago ou cancelado.');
        }

        $order->load('items.product', 'table');
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
        
        // Verificar se o produto já existe no pedido
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

        // Atualizar valor total do pedido
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
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Não é possível modificar um pedido que já foi pago ou cancelado.');
        }

        $orderItem->delete();
        
        // Atualizar valor total do pedido
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
        if ($order->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Apenas pedidos ativos podem ser finalizados.'
            ], 400);
        }
    
        try {
            // Atualizar o status do pedido para 'completed'
            $order->status = 'completed';
            $order->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Pedido finalizado com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao finalizar pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar pagamento do pedido
     */
    public function pay(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,card,mpesa,emola,mkesh',
            'notes' => 'nullable|string',
        ]);

        if ($order->status !== 'completed') {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Apenas pedidos finalizados podem ser pagos.');
        }

        $order->status = 'paid';
        $order->payment_method = $request->payment_method;
        $order->notes = $request->notes;
        $order->save();

        // Liberar a mesa ou grupo de mesas
        if ($order->table) {
            if ($order->table->group_id) {
                // Se for parte de um grupo, liberar todas as mesas do grupo
                if ($order->table->is_main) {
                    Table::where('group_id', $order->table->group_id)
                        ->update([
                            'status' => 'free',
                            'group_id' => null,
                            'is_main' => 0,
                            'merged_capacity' => null
                        ]);
                }
            } else {
                // Se for mesa individual, apenas liberar esta mesa
                $order->table->status = 'free';
                $order->table->save();
            }
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Pagamento registrado com sucesso!');
    }

    /**
     * Cancelar pedido
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'canceled' || $order->status === 'paid') {
            $message = 'Este pedido não pode ser cancelado.';

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }

            return redirect()->back()->with('error', $message);
        }

        try {
            // Atualizar o status do pedido para 'canceled'
            $order->status = 'canceled';
            $order->save();

            // Se houver mesa associada, liberar a mesa
            if ($order->table) {
                $order->table->status = 'free';
                $order->table->save();
            }

            $message = 'Pedido cancelado com sucesso';

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return redirect()->route('tables.index')->with('success', $message);
        } catch (\Exception $e) {
            $message = 'Erro ao cancelar pedido: ' . $e->getMessage();

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }

            return redirect()->back()->with('error', $message);
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
     * Imprimir recibo do pedido
     */
    public function printReceipt(Order $order)
    {
        $order->load('items.product', 'table', 'user');
        return view('orders.receipt', compact('order'));
    }
    //print
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
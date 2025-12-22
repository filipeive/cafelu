<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Notifications\SaleCompletedNotification;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
//validator
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $search = request('search');
        $filter = request('filter'); // 'online' or 'in-house'

        $query = Order::with('table');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($filter === 'online') {
            $query->whereNull('table_id');
        } elseif ($filter === 'in-house') {
            $query->whereNotNull('table_id');
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        $total_orders = Order::count();

        // Obtém o total de pedidos feitos hoje
        $totalToday = $this->orderGetTotalToday();
        // Obtém o total de pedidos abertos
        $totalOpen = $this->order_get_open_count();

        return view('orders.index', compact('orders', 'total_orders', 'totalToday', 'totalOpen', 'search', 'filter'));
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
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'table_id' => $request->table_id,
                'user_id' => auth()->id(),
                'status' => 'active',
                'customer_name' => $request->customer_name ?? null,
            ]);

            $totalAmount = 0;

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                $totalPrice = $product->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $totalPrice,
                ]);

                $totalAmount += $totalPrice;
            }

            $order->total_amount = $totalAmount;
            $order->save();

            // Update table status if table_id is present
            if ($request->table_id) {
                $table = Table::find($request->table_id);
                $table->status = 'occupied';
                $table->save();
            }

            DB::commit();

            // Notificar novo pedido
            $users = User::all();
            Notification::send($users, new NewOrderNotification($order));

            return redirect()->route('orders.edit', $order->id)
                ->with('success', 'Pedido criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao criar pedido: ' . $e->getMessage());
        }
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
            return redirect()->route('orders.index', $order->id)
                ->with('error', 'Não é possível editar um pedido que já foi pago ou cancelado.');
        }

        $order->load('items.product', 'table');
        $categories = Category::with([
            'products' => function ($query) {
                $query->where('is_active', 1);
            }
        ])->get();
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
            return redirect()->route('orders.edit', $order->id)
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
     * Atualizar status do pedido e registrar timestamps
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:active,preparing,ready,completed,canceled',
        ]);

        $status = $request->status;
        $order->status = $status;

        if ($status === 'preparing') {
            $order->preparing_at = now();
        } elseif ($status === 'ready') {
            $order->ready_at = now();
            // Notificar cliente
            if ($order->user) {
                $order->user->notify(new \App\Notifications\OrderReadyNotification($order));
            }
        } elseif ($status === 'completed') {
            $order->delivered_at = now();
        }

        $order->save();

        return redirect()->back()->with('success', 'Status do pedido atualizado para ' . $status);
    }

    /**
     * Finalizar pedido (mudar status para 'completed')
     */
    public function complete(Order $order)
    {
        if ($order->status === 'paid' || $order->status === 'canceled') {
            $message = 'Não é possível finalizar um pedido já pago ou cancelado.';
            return request()->wantsJson() ? response()->json(['success' => false, 'message' => $message], 400) : redirect()->back()->with('error', $message);
        }

        if ($order->items->isEmpty()) {
            $message = 'Não é possível finalizar um pedido vazio.';
            return request()->wantsJson() ? response()->json(['success' => false, 'message' => $message], 400) : redirect()->back()->with('error', $message);
        }

        try {
            $order->status = 'completed';
            $order->delivered_at = now();
            $order->save();

            $message = 'Pedido finalizado com sucesso!';
            return request()->wantsJson() ? response()->json(['success' => true, 'message' => $message]) : redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            $message = 'Erro ao finalizar pedido: ' . $e->getMessage();
            return request()->wantsJson() ? response()->json(['success' => false, 'message' => $message], 500) : redirect()->back()->with('error', $message);
        }
    }
    /**
     * Registrar pagamento do pedido
     */
    public function pay(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();

            // Garantir total do pedido (calcula a partir dos itens se estiver nulo ou zero)
            $total = $order->total_amount;
            if (is_null($total) || $total == 0) {
                $total = $order->items->sum('total_price');
                $order->total_amount = $total;
                $order->save();
            }

            $request->validate([
                'payment_method' => 'required|in:cash,card,mpesa,emola,mkesh',
                'notes' => 'nullable|string',
                'amount_paid' => 'required|numeric|min:0',
            ]);

            if ($order->status !== 'completed') {
                throw new \Exception('Apenas pedidos finalizados podem ser pagos.');
            }

            // Distribui o valor pago para a coluna correspondente
            $amountPaid = (float) $request->amount_paid;
            $cash = $card = $mpesa = $emola = 0.00;
            switch ($request->payment_method) {
                case 'card':
                    $card = $amountPaid;
                    break;
                case 'mpesa':
                    $mpesa = $amountPaid;
                    break;
                case 'emola':
                    $emola = $amountPaid;
                    break;
                default:
                    $cash = $amountPaid;
                    break; // cash, mkesh ou fallback
            }

            // 1. Registrar a venda preenchendo total_amount e os campos de pagamento existentes na tabela
            $sale = Sale::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'customer_name' => $order->customer_name ?? 'Cliente Geral',
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'cash_amount' => $cash,
                'card_amount' => $card,
                'mpesa_amount' => $mpesa,
                'emola_amount' => $emola,
                'notes' => $request->notes ?? $order->notes,
                'status' => 'completed'
            ]);

            // 2. Registrar os itens da venda
            foreach ($order->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                    'notes' => $item->notes
                ]);

                // 3. Atualizar o estoque do produto
                $product = $item->product;
                $product->stock_quantity -= $item->quantity;
                $product->save();

                // 4. Registrar movimentação de estoque
                StockMovement::create([
                    'product_id' => $item->product_id,
                    'user_id' => auth()->id(),
                    'quantity' => -$item->quantity,
                    'type' => 'sale',
                    'reference_type' => 'Sale',
                    'reference_id' => $sale->id,
                    'notes' => 'Venda via Pedido #' . $order->id
                ]);

                // Verificar estoque baixo
                if ($product->stock_quantity <= $product->min_stock_level) {
                    $users = User::all();
                    Notification::send($users, new LowStockNotification($product));
                }
            }

            // Notificar venda concluída
            $users = User::all();
            Notification::send($users, new SaleCompletedNotification($sale));

            // 5. Atualizar o status do pedido e criar dívida se necessário
            $remainingBalance = $total - $amountPaid;

            if ($remainingBalance > 0) {
                if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
                    throw new \Exception('Apenas administradores e gerentes podem registrar pagamentos parciais (dívidas).');
                }
                $paymentStatus = 'partial';
            } else {
                $paymentStatus = 'paid';
            }

            $order->update([
                'status' => 'paid',
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'paid_at' => now()
            ]);

            if ($remainingBalance > 0) {
                \App\Models\CustomerDebt::create([
                    'sale_id' => $sale->id,
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'customer_name' => $order->customer_name ?? 'Cliente Geral',
                    'total_amount' => $total,
                    'remaining_amount' => $remainingBalance,
                    'status' => 'pending',
                    'notes' => 'Dívida originada do pedido #' . $order->id . '. Valor total: ' . $total . ', Valor pago: ' . $amountPaid
                ]);
            }

            // 5. Liberar mesas (mantém seu código existente)
            if ($order->table) {
                if ($order->table->group_id) {
                    $groupedTables = Table::where('group_id', $order->table->group_id)->get();
                    foreach ($groupedTables as $table) {
                        $table->update([
                            'status' => 'free',
                            'group_id' => null,
                            'is_main' => false,
                            'merged_capacity' => null
                        ]);
                    }
                } else {
                    $order->table->update(['status' => 'free']);
                }
            }

            DB::commit();

            $message = 'Pagamento registrado e venda finalizada com sucesso!';

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->route('orders.show', $order->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            $message = 'Erro ao processar pagamento: ' . $e->getMessage();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return redirect()->back()->with('error', $message);
        }
    }
    /**
     * Cancelar pedido
     */
    public function cancel(Order $order)
    {
        $request = request();

        // Verificar se o pedido já foi cancelado
        if ($order->status === 'canceled') {
            $message = 'Este pedido já está cancelado.';
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->back()->with('error', $message);
        }

        try {
            DB::beginTransaction();

            $request->validate([
                'notes' => 'nullable|string',
            ]);

            // Se o pedido já foi pago, reverter o estoque
            if ($order->status === 'paid') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock_quantity += $item->quantity;
                    $product->save();

                    // Registrar movimentação de estorno
                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'user_id' => auth()->id(),
                        'quantity' => $item->quantity,
                        'type' => 'return',
                        'reference_type' => 'Order',
                        'reference_id' => $order->id,
                        'notes' => 'Estorno por cancelamento de pedido pago #' . $order->id
                    ]);
                }

                // Marcar a venda vinculada como cancelada
                $sale = Sale::where('order_id', $order->id)->first();
                if ($sale) {
                    $sale->update(['status' => 'canceled']);
                }
            }

            // Atualizar o status do pedido para 'canceled'
            $order->notes = $request->notes;
            $order->status = 'canceled';
            $order->save();

            // Se houver mesa associada, liberar a mesa
            if ($order->table) {
                $order->table->status = 'free';
                $order->table->save();
            }

            DB::commit();

            $message = 'Pedido cancelado com sucesso' . ($order->status === 'paid' ? ' e estoque estornado.' : '.');

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return redirect()->route('tables.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function getOrderData(Order $order)
    {
        $order->load('items.product', 'table', 'user');

        return response()->json([
            'id' => $order->id,
            'created_at' => $order->created_at,
            'total_amount' => $order->total_amount,
            'table' => $order->table ? ['number' => $order->table->number] : null,
            'user' => ['name' => $order->user->name ?? 'Sistema'],
            'items' => $order->items->map(function ($item) {
                return [
                    'product' => ['name' => $item->product->name],
                    'quantity' => $item->quantity,
                    'total_price' => $item->total_price,
                ];
            }),
            'logo' => asset('assets/images/logo.png'),
            'companyName' => 'ZALALA BEACH BAR',
            'address' => 'Bairro de Zalala, ER470, Cidade de Quelimane',
            'phone' => 'Tel: (+258) 846 885 214',
            'nuit' => '110735901',
            'email' => 'zalalabeachbar@gmail.com',
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
    //print
    public function print(Order $order)
    {
        $order->load('items.product', 'table', 'user');
        return view('orders.receipt', compact('order'));
    }
    /**
     * Confirmar pagamento enviado pelo cliente
     */
    public function confirmPayment(Order $order)
    {
        if ($order->payment_status !== 'awaiting_confirmation') {
            return redirect()->back()->with('error', 'Este pedido não possui um pagamento aguardando confirmação.');
        }

        try {
            DB::beginTransaction();

            $total = $order->total_amount;
            if (is_null($total) || $total == 0) {
                $total = $order->items->sum('total_price');
                $order->total_amount = $total;
            }

            // Determine payment method amounts
            $cash = $card = $mpesa = $emola = 0.00;
            switch ($order->payment_method) {
                case 'card':
                    $card = $total;
                    break;
                case 'mpesa':
                    $mpesa = $total;
                    break;
                case 'emola':
                    $emola = $total;
                    break;
                default:
                    $cash = $total;
                    break;
            }

            // 1. Registrar a venda
            $sale = Sale::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'customer_name' => $order->customer_name ?? 'Cliente Online',
                'total_amount' => $total,
                'payment_method' => $order->payment_method ?? 'cash',
                'cash_amount' => $cash,
                'card_amount' => $card,
                'mpesa_amount' => $mpesa,
                'emola_amount' => $emola,
                'notes' => 'Pagamento confirmado pelo administrador. ' . $order->notes,
                'status' => 'completed'
            ]);

            // 2. Registrar os itens da venda e atualizar estoque
            foreach ($order->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                    'notes' => $item->notes
                ]);

                $product = $item->product;
                $product->stock_quantity -= $item->quantity;
                $product->save();

                StockMovement::create([
                    'product_id' => $item->product_id,
                    'user_id' => auth()->id(),
                    'quantity' => -$item->quantity,
                    'type' => 'sale',
                    'reference_type' => 'Sale',
                    'reference_id' => $sale->id,
                    'notes' => 'Venda via Pedido Online #' . $order->id
                ]);

                if ($product->stock_quantity <= $product->min_stock_level) {
                    $users = User::all();
                    Notification::send($users, new LowStockNotification($product));
                }
            }

            // 3. Atualizar o status do pedido
            $order->update([
                'status' => 'paid',
                'payment_status' => 'paid',
                'paid_at' => now()
            ]);

            // 4. Notificar cliente se houver usuário associado
            if ($order->user) {
                $order->user->notify(new \App\Notifications\SaleCompletedNotification($sale));
            }

            DB::commit();

            return redirect()->back()->with('success', 'Pagamento confirmado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao confirmar pagamento: ' . $e->getMessage());
        }
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
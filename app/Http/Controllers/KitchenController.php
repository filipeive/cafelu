<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasPermissions;
use App\Services\NotificationService;

class KitchenController extends Controller
{
    use HasPermissions;

    public function __construct()
    {
        // Aplicar middleware de permissão para cozinha
        $this->middleware('permission:manage_kitchen');
    }

    /**
     * Dashboard principal da cozinha
     */
    public function dashboard()
    {
        $this->logActivity('kitchen_access', null, 'Acessou dashboard da cozinha');

        // Pedidos ativos organizados por prioridade (mais antigos primeiro)
        $activeOrders = Order::with(['items.product.category', 'table', 'user'])
                           ->where('status', 'active')
                           ->orderBy('created_at', 'asc')
                           ->get();

        // Estatísticas da cozinha
        $stats = [
            'active_orders' => $activeOrders->count(),
            'pending_items' => OrderItem::whereHas('order', function($query) {
                $query->where('status', 'active');
            })->where('status', 'pending')->count(),
            'preparing_items' => OrderItem::whereHas('order', function($query) {
                $query->where('status', 'active');
            })->where('status', 'preparing')->count(),
            'ready_items' => OrderItem::whereHas('order', function($query) {
                $query->where('status', 'active');
            })->where('status', 'ready')->count(),
            'orders_completed_today' => Order::whereDate('created_at', today())
                                           ->where('status', 'completed')
                                           ->count()
        ];

        // Tempo médio de preparo (últimas 24h)
        $avgPrepTime = $this->calculateAveragePreparationTime();

        // Itens por categoria para facilitar organização
        $itemsByCategory = $this->getActiveItemsByCategory();

        return view('kitchen.dashboard', compact(
            'activeOrders', 
            'stats', 
            'avgPrepTime',
            'itemsByCategory'
        ));
    }

    /**
     * Visualizar detalhes de um pedido específico na cozinha
     */
    public function showOrder(Order $order)
    {
        if ($order->status !== 'active') {
            return redirect()->route('kitchen.dashboard')
                ->with('warning', 'Este pedido não está mais ativo.');
        }

        $order->load(['items.product.category', 'table', 'user']);

        $this->logActivity('kitchen_view_order', $order, "Visualizou pedido #{$order->id} na cozinha");

        return view('kitchen.order-details', compact('order'));
    }

    /**
     * Visualizar pedidos organizados por categoria
     */
    public function byCategory()
    {
        $this->logActivity('kitchen_by_category', null, 'Acessou visualização por categoria');

        $itemsByCategory = $this->getActiveItemsByCategory();
        
        return view('kitchen.by-category', compact('itemsByCategory'));
    }

    /**
     * Histórico de pedidos da cozinha
     */
    public function history(Request $request)
    {
        $query = Order::with(['items.product', 'table', 'user'])
                     ->whereIn('status', ['completed', 'paid'])
                     ->orderBy('completed_at', 'desc');

        // Filtros
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('completed_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('completed_at', '<=', $request->date_to);
        }

        if ($request->has('table_id') && $request->table_id) {
            $query->where('table_id', $request->table_id);
        }

        $orders = $query->paginate(20);
        $tables = Table::all();

        $this->logActivity('kitchen_history', null, 'Acessou histórico da cozinha');

        return view('kitchen.history', compact('orders','tables'));
    }

    /**
     * API: Obter pedidos ativos (para atualizações em tempo real)
     */
    public function getActiveOrders()
    {
        $orders = Order::with(['items' => function($query) {
                $query->whereIn('status', ['pending', 'preparing', 'ready'])
                      ->with('product.category');
            }, 'table'])
            ->where('status', 'active')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'orders' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'table_number' => $order->table?->number ?? 'Balcão',
                    'customer_name' => $order->customer_name,
                    'created_at' => $order->created_at,
                    'elapsed_time' => $order->created_at->diffInMinutes(now()),
                    'items' => $order->items->map(function($item) {
                        return [
                            'id' => $item->id,
                            'product_name' => $item->product->name,
                            'category' => $item->product->category->name ?? 'Sem categoria',
                            'quantity' => $item->quantity,
                            'status' => $item->status,
                            'notes' => $item->notes,
                            'preparation_time' => $this->getEstimatedPrepTime($item->product),
                        ];
                    })
                ];
            }),
            'stats' => [
                'total_orders' => $orders->count(),
                'total_items' => $orders->sum(fn($order) => $order->items->count()),
                'pending_items' => $orders->sum(fn($order) => $order->items->where('status', 'pending')->count()),
                'preparing_items' => $orders->sum(fn($order) => $order->items->where('status', 'preparing')->count()),
                'ready_items' => $orders->sum(fn($order) => $order->items->where('status', 'ready')->count()),
            ]
        ]);
    }

    /**
     * Atualizar status de um item específico
     */
    public function updateItemStatus(Request $request, OrderItem $item)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,delivered'
        ]);

        $oldStatus = $item->status;
        $item->status = $request->status;
        
        // Registrar timestamp para métricas
        switch ($request->status) {
            case 'preparing':
                $item->started_at = now();
                break;
            case 'ready':
                $item->finished_at = now();
                break;
        }

        $item->save();

        $this->logActivity(
            'kitchen_update_item',
            $item,
            "Alterou status do item '{$item->product->name}' de '{$oldStatus}' para '{$request->status}'",
            [
                'order_id' => $item->order_id,
                'product_id' => $item->product_id,
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ]
        );

        // Verificar se todos os itens do pedido estão prontos
        $this->checkOrderCompletion($item->order);

        // --- Resposta condicional ---
        if ($request->expectsJson()) {
            // Para AJAX ou fetch()
            return response()->json([
                'success' => true,
                'message' => "Status atualizado para '{$request->status}'",
                'item' => [
                    'id' => $item->id,
                    'status' => $item->status,
                    'started_at' => $item->started_at,
                    'finished_at' => $item->finished_at
                ]
            ]);
        }

        // Para request normal (via formulário / browser)
        return redirect()
            ->back()
            ->with('success', "Status atualizado para '{$request->status}'");
    }


    /**
     * Iniciar preparo de todos os itens de um pedido
     */
    public function startAllItems(Order $order)
    {
        if ($order->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Este pedido não está ativo'
            ], 400);
        }

        $updatedItems = OrderItem::where('order_id', $order->id)
                                ->where('status', 'pending')
                                ->update([
                                    'status' => 'preparing',
                                    'started_at' => now()
                                ]);

        $this->logActivity('kitchen_start_all', $order, 
            "Iniciou preparo de todos os itens do pedido #{$order->id}",
            ['items_updated' => $updatedItems]
        );

        return response()->json([
            'success' => true,
            'message' => "Preparo iniciado para {$updatedItems} itens",
            'items_updated' => $updatedItems
        ]);
    }

   public function finishAllItems(Order $order)
    {
        if ($order->status !== 'active') {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este pedido não está ativo'
                ], 400);
            }

            return redirect()->back()->with('error', 'Este pedido não está ativo');
        }

        $updatedItems = OrderItem::where('order_id', $order->id)
            ->whereIn('status', ['pending', 'preparing'])
            ->update([
                'status' => 'ready',
                'finished_at' => now()
            ]);

        $this->logActivity('kitchen_finish_all', $order, 
            "Finalizou todos os itens do pedido #{$order->id}",
            ['items_updated' => $updatedItems]
        );

        // dispara notificação para o garçom
        NotificationService::notifyWaiterOrderReady($order);

        $this->checkOrderCompletion($order);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Todos os itens foram finalizados ({$updatedItems} itens)",
                'items_updated' => $updatedItems
            ]);
        }

        return redirect()
            ->route('kitchen.order.show', $order)
            ->with('success', "Todos os itens foram finalizados ({$updatedItems} itens)");
    }

    /**
     * Métodos auxiliares privados
     */

    private function getActiveItemsByCategory()
    {
        return OrderItem::with(['product.category', 'order.table'])
                       ->whereHas('order', function($query) {
                           $query->where('status', 'active');
                       })
                       ->whereIn('status', ['pending', 'preparing', 'ready'])
                       ->get()
                       ->groupBy(function($item) {
                           return $item->product->category->name ?? 'Sem categoria';
                       })
                       ->map(function($items, $categoryName) {
                           return [
                               'category' => $categoryName,
                               'items' => $items->map(function($item) {
                                   return [
                                       'id' => $item->id,
                                       'order_id' => $item->order_id,
                                       'table_number' => $item->order->table?->number ?? 'Balcão',
                                       'product_name' => $item->product->name,
                                       'quantity' => $item->quantity,
                                       'status' => $item->status,
                                       'notes' => $item->notes,
                                       'order_time' => $item->order->created_at,
                                       'elapsed_minutes' => $item->order->created_at->diffInMinutes(now())
                                   ];
                               })
                           ];
                       });
    }

    private function calculateAveragePreparationTime()
    {
        $completedItems = OrderItem::whereNotNull('started_at')
                                  ->whereNotNull('finished_at')
                                  ->where('created_at', '>=', now()->subDay())
                                  ->get();

        if ($completedItems->isEmpty()) {
            return 0;
        }

        $totalMinutes = $completedItems->sum(function($item) {
            return $item->started_at->diffInMinutes($item->finished_at);
        });

        return round($totalMinutes / $completedItems->count(), 1);
    }

    private function getEstimatedPrepTime(Product $product)
    {
        // Tempo estimado baseado na categoria ou configuração do produto
        $categoryTimes = [
            'Bebidas' => 2,
            'Entradas' => 10,
            'Pratos Principais' => 25,
            'Sobremesas' => 8,
            'Lanches' => 15,
        ];

        $categoryName = $product->category->name ?? 'Outros';
        return $categoryTimes[$categoryName] ?? 12; // Padrão 12 minutos
    }

    private function checkOrderCompletion(Order $order)
    {
        $pendingItems = OrderItem::where('order_id', $order->id)
                               ->whereIn('status', ['pending', 'preparing'])
                               ->count();

        if ($pendingItems === 0) {
            // Todos os itens estão prontos, pode notificar o garçom
            $this->notifyWaiterOrderReady($order);
        }
    }

    private function notifyWaiterOrderReady(Order $order)
    {
        // Implementar notificação para o garçom
        // Pode ser WebSocket, notificação push, etc.
        
        $this->logActivity('kitchen_notify_ready', $order, 
            "Pedido #{$order->id} está pronto para entrega",
            ['table_number' => $order->table?->number]
        );
    }

    private function logActivity($action, $model, $description, $extraData = [])
    {
        UserActivity::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : 'Kitchen',
            'model_id' => $model?->id,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'extra_data' => $extraData ? json_encode($extraData) : null,
        ]);
    }
}
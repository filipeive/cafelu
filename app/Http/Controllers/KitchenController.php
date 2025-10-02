<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use App\Models\OrderItem;
use App\Models\UserActivity;
use App\Models\Category;
use App\Models\KitchenMetric;
use App\Models\CategoryPrepTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Traits\HasPermissions;

class KitchenController extends Controller
{
    use HasPermissions;

    public function __construct()
    {
        $this->middleware('permission:manage_kitchen');
    }

    /**
     * Dashboard principal da cozinha
     */
    public function dashboard()
    {
        try {
            $this->logActivity('kitchen_access', null, 'Acessou dashboard da cozinha');

            // Pedidos ativos
            $activeOrders = Order::with(['items.product.category', 'table', 'user'])
                ->where('status', 'active')
                ->orderBy('created_at', 'asc')
                ->get();

            // Estatísticas principais
            $stats = [
                'active_orders' => $activeOrders->count(),
                'pending_items' => OrderItem::whereHas('order', fn($q) => $q->where('status', 'active'))
                    ->where('status', 'pending')->count(),
                'preparing_items' => OrderItem::whereHas('order', fn($q) => $q->where('status', 'active'))
                    ->where('status', 'preparing')->count(),
                'ready_items' => OrderItem::whereHas('order', fn($q) => $q->where('status', 'active'))
                    ->where('status', 'ready')->count(),
                'orders_completed_today' => Order::whereDate('created_at', today())
                    ->where('status', 'completed')
                    ->count()
            ];

            // Tempo médio de preparo (usando KitchenMetric)
            $avgPrepTime = KitchenMetric::getAveragePreparationTime(7) ?? 0;
            $avgPrepTime = round($avgPrepTime, 1);

            // Métricas do dia
            $todayMetrics = KitchenMetric::getTodayMetrics();

            return view('kitchen.dashboard', compact('activeOrders', 'stats', 'avgPrepTime', 'todayMetrics'));
        } catch (\Exception $e) {
            Log::error('Erro no dashboard da cozinha', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Erro ao carregar dashboard da cozinha. Tente novamente.');
        }
    }

    /**
     * Visualizar pedidos organizados por categoria
     */
    public function byCategory()
    {
        try {
            $categories = Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $itemsByCategory = [];

            foreach ($categories as $category) {
                $items = OrderItem::with(['order.table', 'product'])
                    ->whereHas('order', fn($q) => $q->where('status', 'active'))
                    ->whereHas('product', fn($q) => $q->where('category_id', $category->id))
                    ->whereIn('status', ['pending', 'preparing', 'ready'])
                    ->orderBy('created_at')
                    ->get()
                    ->map(function ($item) use ($category) {
                        $elapsedMinutes = $item->created_at->diffInMinutes(now());
                        $estimatedTime = CategoryPrepTime::getEstimatedTime($category->id);
                        
                        return [
                            'id' => $item->id,
                            'product_name' => $item->product->name,
                            'quantity' => $item->quantity,
                            'status' => $item->status,
                            'order_id' => $item->order_id,
                            'table_number' => $item->order->table->number ?? 'Balcão',
                            'notes' => $item->notes,
                            'elapsed_minutes' => $elapsedMinutes,
                            'estimated_minutes' => $estimatedTime,
                            'is_overdue' => $elapsedMinutes > $estimatedTime,
                            'priority' => $this->calculatePriority($item, $estimatedTime)
                        ];
                    });

                if ($items->isNotEmpty()) {
                    $itemsByCategory[] = [
                        'category' => $category->name,
                        'category_id' => $category->id,
                        'items' => $items,
                        'total_items' => $items->count(),
                        'pending_count' => $items->where('status', 'pending')->count(),
                        'preparing_count' => $items->where('status', 'preparing')->count(),
                        'ready_count' => $items->where('status', 'ready')->count(),
                        'estimated_time' => CategoryPrepTime::getEstimatedTime($category->id)
                    ];
                }
            }

            $this->logActivity('kitchen_by_category', null, 'Visualizou pedidos por categoria');

            return view('kitchen.by-category', compact('itemsByCategory'));
        } catch (\Exception $e) {
            Log::error('Erro ao visualizar por categoria', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('kitchen.dashboard')
                ->with('error', 'Erro ao carregar visualização por categoria.');
        }
    }

    /**
     * Atualizar status de um item (JSON response)
     */
    public function updateItemStatus(Request $request, OrderItem $item)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,preparing,ready,delivered'
            ]);

            if ($item->order->status !== 'active') {
                return $this->respond($request, false, 'Este pedido não está mais ativo.', 400);
            }

            if (!$this->isValidStatusTransition($item->status, $validated['status'])) {
                return $this->respond($request, false, "Transição inválida: '{$item->status}' → '{$validated['status']}'", 400);
            }

            $oldStatus = $item->status;
            $item->status = $validated['status'];

            // Registrar timestamps
            if ($validated['status'] === 'preparing' && !$item->started_at) {
                $item->started_at = now();
            } elseif ($validated['status'] === 'ready' && !$item->finished_at) {
                $item->finished_at = now();
            }

            $item->save();

            $this->logActivity(
                'kitchen_update_item',
                $item,
                "Alterou status do item '{$item->product->name}' de '{$oldStatus}' para '{$validated['status']}'",
                [
                    'order_id' => $item->order_id,
                    'product_id' => $item->product_id,
                    'old_status' => $oldStatus,
                    'new_status' => $validated['status']
                ]
            );

            // Verificar se pedido está completo
            $orderCompleted = $this->checkOrderCompletion($item->order);

            DB::commit();

            $messages = [
                'preparing' => 'Preparo iniciado com sucesso',
                'ready' => 'Item marcado como pronto',
                'pending' => 'Item retornou para pendente'
            ];

            return $this->respond($request, true, $messages[$validated['status']] ?? 'Status atualizado', 200, [
                'item' => [
                    'id' => $item->id,
                    'status' => $item->status,
                    'started_at' => $item->started_at?->toIso8601String(),
                    'finished_at' => $item->finished_at?->toIso8601String()
                ],
                'order_completed' => $orderCompleted
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->respond($request, false, 'Dados inválidos', 422, ['errors' => $e->errors()]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar status do item', [
                'item_id' => $item->id,
                'new_status' => $request->status,
                'error' => $e->getMessage(),
            ]);
            return $this->respond($request, false, 'Erro ao atualizar status. Tente novamente.', 500);
        }
    }

    /**
     * Helper para responder JSON ou Redirect dependendo do tipo de request
     */
    private function respond($request, $success, $message, $status = 200, $extra = [])
    {
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(array_merge([
                'success' => $success,
                'message' => $message,
            ], $extra), $status);
        }

        if ($success) {
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', $message);
        }
    }


    /**
     * Visualizar detalhes de um pedido
     */
    public function showOrder(Order $order)
    {
        try {
            if ($order->status !== 'active') {
                return redirect()->route('kitchen.dashboard')
                    ->with('warning', 'Este pedido não está mais ativo.');
            }

            $order->load(['items.product.category', 'table', 'user']);
            $this->logActivity('kitchen_view_order', $order, "Visualizou pedido #{$order->id} na cozinha");

            return view('kitchen.order-details', compact('order'));
        } catch (\Exception $e) {
            Log::error('Erro ao visualizar pedido na cozinha', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('kitchen.dashboard')
                ->with('error', 'Erro ao carregar detalhes do pedido.');
        }
    }
     /**
     * Histórico de pedidos
     */
    public function history(Request $request)
    {
        try {
            $query = Order::with(['items.product', 'table', 'user'])
                ->whereIn('status', ['completed', 'paid'])
                ->orderBy('completed_at', 'desc');

            if ($request->filled('date_from')) {
                $query->whereDate('completed_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('completed_at', '<=', $request->date_to);
            }
            if ($request->filled('table_id')) {
                $query->where('table_id', $request->table_id);
            }

            $orders = $query->paginate(20);
            $tables = Table::all();

            $this->logActivity('kitchen_history', null, 'Acessou histórico da cozinha');

            return view('kitchen.history', compact('orders', 'tables'));
        } catch (\Exception $e) {
            Log::error('Erro no histórico da cozinha', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('kitchen.dashboard')
                ->with('error', 'Erro ao carregar histórico.');
        }
    }
    /**
     * Iniciar preparo de todos os itens de um pedido
     */
    public function startAllItems(Order $order)
    {
        DB::beginTransaction();
        try {
            if ($order->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este pedido não está ativo'
                ], 400);
            }

            $updatedItems = OrderItem::where('order_id', $order->id)
                ->where('status', 'pending')
                ->update(['status' => 'preparing', 'started_at' => now()]);

            if ($updatedItems === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum item pendente para iniciar'
                ], 400);
            }

            $this->logActivity('kitchen_start_all', $order,
                "Iniciou preparo de todos os itens do pedido #{$order->id}",
                ['items_updated' => $updatedItems]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Preparo iniciado para {$updatedItems} " . ($updatedItems === 1 ? 'item' : 'itens'),
                'items_updated' => $updatedItems
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao iniciar todos os itens', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erro ao iniciar preparo'
            ], 500);
        }
    }

    /**
     * Finalizar todos os itens de um pedido
     */
    public function finishAllItems(Order $order)
    {
        DB::beginTransaction();
        try {
            if ($order->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este pedido não está ativo'
                ], 400);
            }

            $itemsToUpdate = OrderItem::where('order_id', $order->id)
                ->whereIn('status', ['pending', 'preparing'])
                ->get();

            if ($itemsToUpdate->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum item para finalizar'
                ], 400);
            }

            // Atualizar itens
            foreach ($itemsToUpdate as $item) {
                if (!$item->started_at) {
                    $item->started_at = now();
                }
                $item->finished_at = now();
                $item->status = 'ready';
                $item->save();
            }

            $this->logActivity('kitchen_finish_all', $order,
                "Finalizou todos os itens do pedido #{$order->id}",
                ['items_updated' => $itemsToUpdate->count()]
            );

            // Verificar conclusão do pedido
            $this->checkOrderCompletion($order);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Todos os itens foram finalizados ({$itemsToUpdate->count()} " . 
                           ($itemsToUpdate->count() === 1 ? 'item' : 'itens') . ")",
                'items_updated' => $itemsToUpdate->count()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao finalizar todos os itens', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erro ao finalizar itens'
            ], 500);
        }
    }

    /* ===================== Métodos Auxiliares ===================== */

    private function isValidStatusTransition($current, $new)
    {
        $transitions = [
            'pending' => ['preparing', 'ready'],
            'preparing' => ['ready', 'pending'],
            'ready' => ['preparing', 'delivered']
        ];
        return in_array($new, $transitions[$current] ?? []);
    }

    private function calculatePriority($item, $estimatedTime = 15)
    {
        $minutes = $item->created_at->diffInMinutes(now());
        
        // Prioridade baseada no tempo estimado
        if ($minutes > $estimatedTime * 1.5) return 'high';
        if ($minutes > $estimatedTime) return 'medium';
        return 'low';
    }

    private function checkOrderCompletion(Order $order)
    {
        $pending = OrderItem::where('order_id', $order->id)
            ->whereIn('status', ['pending', 'preparing'])
            ->count();

        if ($pending === 0) {
            // Registrar métrica do pedido
            KitchenMetric::recordOrderMetric($order);
            
            // Atualizar tempos estimados por categoria
            $this->updateCategoryPrepTimes($order);
            
            return true;
        }
        
        return false;
    }

    private function updateCategoryPrepTimes(Order $order)
    {
        $categories = $order->items->pluck('product.category_id')->unique();
        
        foreach ($categories as $categoryId) {
            if ($categoryId) {
                CategoryPrepTime::updateFromMetrics($categoryId);
            }
        }
    }


    private function logActivity($action, $model, $description, $extra = [])
    {
        try {
            UserActivity::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => $model ? get_class($model) : 'Kitchen',
                'model_id' => $model?->id,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'extra_data' => $extra ? json_encode($extra) : null,
            ]);
        } catch (\Exception $e) {
            Log::warning('Erro ao registrar atividade', ['error' => $e->getMessage()]);
        }
    }
}
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
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Traits\HasPermissions;
use App\Services\NotificationService;

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

            $activeOrders = Order::with(['items.product.category', 'table', 'user'])
                               ->where('status', 'active')
                               ->orderBy('created_at', 'asc')
                               ->get();

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

            $avgPrepTime = $this->calculateAveragePreparationTime();
            $itemsByCategory = $this->getActiveItemsByCategory();

            return view('kitchen.dashboard', compact(
                'activeOrders', 
                'stats', 
                'avgPrepTime',
                'itemsByCategory'
            ));

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
     * Visualizar detalhes de um pedido específico na cozinha
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
     * Visualizar pedidos organizados por categoria
     */
    public function byCategory()
    {
        try {
            $this->logActivity('kitchen_by_category', null, 'Acessou visualização por categoria');
            $itemsByCategory = $this->getActiveItemsByCategory();
            
            return view('kitchen.by-category', compact('itemsByCategory'));

        } catch (\Exception $e) {
            Log::error('Erro na visualização por categoria', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('kitchen.dashboard')
                ->with('error', 'Erro ao carregar categorias.');
        }
    }

    /**
     * Histórico de pedidos da cozinha
     */
    public function history(Request $request)
    {
        try {
            $query = Order::with(['items.product', 'table', 'user'])
                         ->whereIn('status', ['completed', 'paid'])
                         ->orderBy('completed_at', 'desc');

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

        } catch (\Exception $e) {
            Log::error('Erro no histórico da cozinha', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('kitchen.dashboard')
                ->with('error', 'Erro ao carregar histórico.');
        }
    }

    /**
     * Atualizar status de um item específico com validação robusta
     */
    public function updateItemStatus(Request $request, OrderItem $item)
    {
        // IMPORTANTE: Verificar se é requisição AJAX primeiro
        $isAjax = $request->ajax() || $request->wantsJson() || $request->expectsJson();

        DB::beginTransaction();

        try {
            // Validação
            $validated = $request->validate([
                'status' => 'required|in:pending,preparing,ready,delivered'
            ]);

            // Verificar se o pedido está ativo
            if ($item->order->status !== 'active') {
                throw ValidationException::withMessages([
                    'status' => 'Este pedido não está mais ativo.'
                ]);
            }

            // Validar transição de status
            if (!$this->isValidStatusTransition($item->status, $validated['status'])) {
                throw ValidationException::withMessages([
                    'status' => "Não é possível mudar de '{$item->status}' para '{$validated['status']}'."
                ]);
            }

            $oldStatus = $item->status;
            $item->status = $validated['status'];
            
            // Registrar timestamps
            switch ($validated['status']) {
                case 'preparing':
                    $item->started_at = now();
                    break;
                case 'ready':
                    $item->finished_at = now();
                    break;
            }

            $item->save();

            // Log da atividade
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

            // Verificar conclusão do pedido
            $this->checkOrderCompletion($item->order);

            DB::commit();

            // Mensagem personalizada
            $messages = [
                'preparing' => 'Preparo iniciado com sucesso',
                'ready' => 'Item marcado como pronto',
                'pending' => 'Item retornou para pendente'
            ];

            $message = $messages[$validated['status']] ?? 'Status atualizado com sucesso';

            // SEMPRE retornar JSON para requisições AJAX
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'item' => [
                        'id' => $item->id,
                        'status' => $item->status,
                        'started_at' => $item->started_at,
                        'finished_at' => $item->finished_at
                    ]
                ], 200);
            }

            // Resposta para request tradicional
            return redirect()
                ->back()
                ->with('success', $message);

        } catch (ValidationException $e) {
            DB::rollBack();
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->with('error', $e->getMessage());

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao atualizar status do item', [
                'item_id' => $item->id,
                'new_status' => $request->status,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar status. Tente novamente.'
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Erro ao atualizar status. Tente novamente.');
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
                throw new \Exception('Este pedido não está ativo');
            }

            $updatedItems = OrderItem::where('order_id', $order->id)
                                    ->where('status', 'pending')
                                    ->update([
                                        'status' => 'preparing',
                                        'started_at' => now()
                                    ]);

            if ($updatedItems === 0) {
                throw new \Exception('Nenhum item pendente para iniciar');
            }

            $this->logActivity('kitchen_start_all', $order, 
                "Iniciou preparo de todos os itens do pedido #{$order->id}",
                ['items_updated' => $updatedItems]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Preparo iniciado para {$updatedItems} " . 
                            ($updatedItems === 1 ? 'item' : 'itens'),
                'items_updated' => $updatedItems
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao iniciar todos os itens', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
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
                throw new \Exception('Este pedido não está ativo');
            }

            $updatedItems = OrderItem::where('order_id', $order->id)
                ->whereIn('status', ['pending', 'preparing'])
                ->update([
                    'status' => 'ready',
                    'finished_at' => now()
                ]);

            if ($updatedItems === 0) {
                throw new \Exception('Nenhum item para finalizar');
            }

            $this->logActivity('kitchen_finish_all', $order, 
                "Finalizou todos os itens do pedido #{$order->id}",
                ['items_updated' => $updatedItems]
            );

            // Notificar garçom
            NotificationService::notifyWaiterOrderReady($order);

            $this->checkOrderCompletion($order);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Todos os itens foram finalizados ({$updatedItems} " . 
                            ($updatedItems === 1 ? 'item' : 'itens') . ")",
                'items_updated' => $updatedItems
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao finalizar todos os itens', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Métodos auxiliares privados
     */

    private function isValidStatusTransition($currentStatus, $newStatus)
    {
        $validTransitions = [
            'pending' => ['preparing', 'ready'],
            'preparing' => ['ready', 'pending'],
            'ready' => ['preparing', 'delivered']
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }

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
        $categoryTimes = [
            'Bebidas' => 2,
            'Entradas' => 10,
            'Pratos Principais' => 25,
            'Sobremesas' => 8,
            'Lanches' => 15,
        ];

        $categoryName = $product->category->name ?? 'Outros';
        return $categoryTimes[$categoryName] ?? 12;
    }

    private function checkOrderCompletion(Order $order)
    {
        $pendingItems = OrderItem::where('order_id', $order->id)
                               ->whereIn('status', ['pending', 'preparing'])
                               ->count();

        if ($pendingItems === 0) {
            $this->notifyWaiterOrderReady($order);
        }
    }

    private function notifyWaiterOrderReady(Order $order)
    {
        $this->logActivity('kitchen_notify_ready', $order, 
            "Pedido #{$order->id} está pronto para entrega",
            ['table_number' => $order->table?->number]
        );
    }

    private function logActivity($action, $model, $description, $extraData = [])
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
                'extra_data' => $extraData ? json_encode($extraData) : null,
            ]);
        } catch (\Exception $e) {
            Log::warning('Erro ao registrar atividade', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
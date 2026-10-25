<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display Kitchen Display System (KDS) / Monitor de Cozinha
     */
    public function index(Request $request)
    {
        $query = Order::query()
            ->whereIn('status', ['active', 'preparing', 'ready'])
            ->with(['table', 'items.product', 'user'])
            ->orderBy('created_at', 'asc');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        $allActiveOrders = Order::whereIn('status', ['active', 'preparing', 'ready'])->get();

        $stats = [
            'pending'     => $allActiveOrders->where('status', 'active')->count(),
            'preparing'   => $allActiveOrders->where('status', 'preparing')->count(),
            'ready'       => $allActiveOrders->where('status', 'ready')->count(),
            'total'       => $allActiveOrders->count(),
        ];

        return view('kitchen.index', compact('orders', 'stats'));
    }

    /**
     * Return live JSON data for real-time KDS polling
     */
    public function liveData()
    {
        $orders = Order::query()
            ->whereIn('status', ['active', 'preparing', 'ready'])
            ->with(['table', 'items.product', 'user'])
            ->orderBy('created_at', 'asc')
            ->get();

        $stats = [
            'pending'     => $orders->where('status', 'active')->count(),
            'preparing'   => $orders->where('status', 'preparing')->count(),
            'ready'       => $orders->where('status', 'ready')->count(),
            'total'       => $orders->count(),
        ];

        $formattedOrders = $orders->map(function ($order) {
            return [
                'id'            => $order->id,
                'status'        => $order->status,
                'customer_name' => $order->customer_name ?: ($order->user?->name ?? 'Consumo Local'),
                'table_name'    => $order->table ? $order->table->name : 'Balcão / Viagem',
                'notes'         => $order->notes,
                'elapsed_min'   => $order->created_at ? $order->created_at->diffInMinutes(now()) : 0,
                'created_at_fmt'=> $order->created_at ? $order->created_at->format('H:i') : '--:--',
                'items'         => $order->items->map(function ($item) {
                    return [
                        'id'         => $item->id,
                        'name'       => $item->product ? $item->product->name : ($item->notes ?? 'Item'),
                        'quantity'   => $item->quantity,
                        'notes'      => $item->notes,
                        'image_url'  => $item->product?->image_url,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'stats'   => $stats,
            'orders'  => $formattedOrders,
        ]);
    }

    /**
     * Update order status from kitchen display
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,preparing,ready,delivered,completed,canceled']
        ]);

        $status = $validated['status'];
        $order->status = $status;

        if ($status === 'preparing') {
            $order->preparing_at = now();
        } elseif ($status === 'ready') {
            $order->ready_at = now();
            if ($order->user) {
                try {
                    $order->user->notify(new \App\Notifications\OrderReadyNotification($order));
                } catch (\Exception $e) {
                    // Log or ignore if notification not set up
                }
            }
        } elseif (in_array($status, ['delivered', 'completed'])) {
            $order->delivered_at = now();

            // Check if table has other active orders; if not, free the table
            if ($order->table) {
                $hasOtherOrders = $order->table->orders()
                    ->where('id', '!=', $order->id)
                    ->whereIn('status', ['active', 'preparing', 'ready'])
                    ->exists();

                if (!$hasOtherOrders && $order->table->status === 'occupied') {
                    // Keep table or free if completed
                    // $order->table->update(['status' => 'free']);
                }
            }
        }

        $order->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status do pedido #' . $order->id . ' atualizado com sucesso.',
                'order'   => $order
            ]);
        }

        return back()->with('success', 'Status do pedido #' . $order->id . ' atualizado.');
    }
}


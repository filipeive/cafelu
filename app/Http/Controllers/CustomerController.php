<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Stats
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'total_spent' => Order::where('user_id', $user->id)
                ->whereIn('status', ['paid', 'completed'])
                ->sum('total_amount'),
            'active_orders' => Order::where('user_id', $user->id)
                ->whereIn('status', ['active', 'preparing', 'ready'])
                ->count(),
        ];

        // Recommended Products
        $recommendedProducts = Product::where('stock_quantity', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('customer.dashboard', compact('orders', 'stats', 'recommendedProducts'));
    }

    public function orders(Request $request)
    {
        $user = Auth::user();
        $query = Order::where('user_id', $user->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('customer.profile')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'active',
                'customer_name' => Auth::user()->name,
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $item) {
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

            DB::commit();

            // Notify admins/managers
            $admins = User::whereIn('role', ['admin', 'manager'])->get();
            Notification::send($admins, new NewOrderNotification($order));

            return response()->json([
                'success' => true,
                'message' => __('messages.order_placed_successfully'),
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('messages.error_placing_order') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function pay(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => __('messages.unauthorized')], 403);
        }

        if ($order->status === 'paid') {
            return response()->json(['success' => false, 'message' => __('messages.order_already_paid')], 400);
        }

        $request->validate([
            'payment_method' => 'required|string',
            'phone' => 'nullable|string',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Update order with payment details but keep it as 'awaiting_confirmation'
            $order->update([
                'payment_method' => $request->payment_method,
                'payment_status' => 'awaiting_confirmation',
                'payment_proof' => $paymentProofPath,
                'notes' => ($order->notes ? $order->notes . "\n" : "") . "Pagamento via " . strtoupper($request->payment_method) . ($request->phone ? " (Tel: " . $request->phone . ")" : "") . ($paymentProofPath ? " [Comprovativo anexado]" : ""),
            ]);

            return response()->json([
                'success' => true,
                'message' => __('messages.payment_sent_for_confirmation')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.error_processing_payment') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancelOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', __('messages.unauthorized'));
        }

        if ($order->status !== 'active') {
            return back()->with('error', 'Este pedido não pode mais ser cancelado.');
        }

        $order->update(['status' => 'canceled']);

        return back()->with('success', __('messages.order_canceled'));
    }

    public function reorder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', __('messages.unauthorized'));
        }

        try {
            DB::beginTransaction();

            $newOrder = Order::create([
                'user_id' => Auth::id(),
                'status' => 'active',
                'customer_name' => Auth::user()->name,
                'total_amount' => $order->total_amount,
            ]);

            foreach ($order->items as $item) {
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ]);
            }

            DB::commit();

            // Notify admins/managers
            $admins = User::whereIn('role', ['admin', 'manager'])->get();
            Notification::send($admins, new NewOrderNotification($newOrder));

            return redirect()->route('customer.dashboard')->with('success', __('messages.order_reordered'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao repetir pedido: ' . $e->getMessage());
        }
    }
}

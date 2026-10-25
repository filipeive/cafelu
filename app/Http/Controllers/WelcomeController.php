<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $categories = Category::with([
            'products' => function ($query) {
                $query->where('is_active', true);
            }
        ])->get();

        $featuredProducts = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $tables = Table::orderBy('number', 'asc')->get();

        return view('welcome', compact('categories', 'featuredProducts', 'tables'));
    }

    public function storeSelfServiceOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'service_type'  => 'required|in:table,takeaway',
            'table_id'      => 'nullable|exists:tables,id',
            'notes'         => 'nullable|string|max:500',
            'items'         => 'required|array|min:1',
            'items.*.id'    => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $adminId = \App\Models\User::where('role', 'admin')->value('id') ?? 1;
            $userId = auth()->id() ?? $adminId;

            $tableId = ($request->service_type === 'table') ? $request->table_id : null;

            $order = Order::create([
                'user_id'         => $userId,
                'table_id'        => $tableId,
                'customer_name'   => trim($request->customer_name),
                'status'          => 'active',
                'payment_status'  => 'pending',
                'notes'           => $request->notes ? trim($request->notes) : null,
                'total_amount'    => 0,
            ]);

            $total = 0;
            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['id']);
                $qty = (int)$itemData['quantity'];
                $itemTotal = ($product->price ?? $product->selling_price) * $qty;

                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $product->id,
                    'quantity'    => $qty,
                    'unit_price'  => $product->price ?? $product->selling_price,
                    'total_price' => $itemTotal,
                    'notes'       => !empty($itemData['notes']) ? trim($itemData['notes']) : null,
                    'status'      => 'pending'
                ]);

                $total += $itemTotal;
            }

            $order->total_amount = $total;
            $order->save();

            // Mark table occupied if set
            if ($tableId) {
                $table = Table::find($tableId);
                if ($table && $table->status === 'free') {
                    $table->update(['status' => 'occupied']);
                }
            }

            DB::commit();

            // Notify staff
            try {
                $staff = \App\Models\User::whereIn('role', ['admin', 'manager', 'waiter'])->get();
                if ($staff->isNotEmpty()) {
                    \Illuminate\Support\Facades\Notification::send($staff, new \App\Notifications\NewOrderNotification($order));
                }
            } catch (\Exception $e) {
                // Ignore notification error
            }

            return response()->json([
                'success'    => true,
                'message'    => 'Pedido enviado para a cozinha com sucesso!',
                'order_id'   => $order->id,
                'table_name' => $order->table ? $order->table->name : 'Balcão / Viagem',
                'total'      => number_format($total, 2) . ' ' . Setting::get('currency_symbol', 'MT'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar o pedido: ' . $e->getMessage()
            ], 500);
        }
    }
}

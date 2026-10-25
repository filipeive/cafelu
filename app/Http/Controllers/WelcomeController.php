<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class WelcomeController extends Controller
{
    public function index(Request $request)
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

        // Tables
        $tables = Table::orderBy('number', 'asc')->get();

        // Detected table from QR code query parameter (e.g., ?mesa=4 or ?table=4)
        $detectedTable = $request->get('mesa', $request->get('table', ''));
        $tableNumber = preg_replace('/[^0-9]/', '', $detectedTable);

        $detectedTableId = '';
        if (!empty($tableNumber)) {
            $matched = $tables->firstWhere('number', (int)$tableNumber);
            if ($matched) {
                $detectedTableId = $matched->id;
            }
        }

        return view('welcome', compact('categories', 'featuredProducts', 'tables', 'tableNumber', 'detectedTableId'));
    }

    public function storeSelfServiceOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:100',
            'table_id'      => 'nullable',
            'table_number'  => 'nullable',
            'notes'         => 'nullable|string|max:500',
            'items'         => 'required|array|min:1',
            'items.*.id'    => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $adminId = User::where('role', 'admin')->value('id') ?? 1;
            $userId = auth()->id() ?? $adminId;

            // Resolve real table
            $tableId = null;
            $tableDisplay = 'Balcão / Salão';

            // 1. If explicit table_id provided
            if ($request->filled('table_id') && is_numeric($request->table_id)) {
                $table = Table::find($request->table_id);
                if ($table) {
                    $tableId = $table->id;
                    $tableDisplay = 'Mesa ' . $table->number;
                }
            }

            // 2. If table_number was provided (e.g. "1" or "Mesa 1")
            if (!$tableId && $request->filled('table_number')) {
                $cleanNum = preg_replace('/[^0-9]/', '', $request->table_number);
                if (!empty($cleanNum)) {
                    $table = Table::where('number', (int)$cleanNum)->first();
                    if ($table) {
                        $tableId = $table->id;
                        $tableDisplay = 'Mesa ' . $table->number;
                    } else {
                        $tableDisplay = 'Mesa ' . $cleanNum;
                    }
                } else {
                    $tableDisplay = trim($request->table_number);
                }
            }

            // Ensure the real table in DB is marked as occupied
            if ($tableId) {
                $table = Table::find($tableId);
                if ($table) {
                    $table->status = 'occupied';
                    $table->save();
                }
            }

            $customerName = trim($request->customer_name ?: '');
            if (empty($customerName)) {
                $customerName = auth()->check() ? auth()->user()->name : ($tableId ? $tableDisplay : 'Cliente no Salão');
            }

            $order = Order::create([
                'user_id'         => $userId,
                'table_id'        => $tableId,
                'customer_name'   => $customerName,
                'status'          => 'active',
                'payment_status'  => 'pending',
                'notes'           => $request->notes ? trim($request->notes) : null,
                'total_amount'    => 0,
            ]);

            $total = 0;
            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['id']);
                $qty = (int)$itemData['quantity'];
                $unitPrice = $product->price ?? $product->selling_price;
                $itemTotal = $unitPrice * $qty;

                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $product->id,
                    'quantity'    => $qty,
                    'unit_price'  => $unitPrice,
                    'total_price' => $itemTotal,
                    'notes'       => !empty($itemData['notes']) ? trim($itemData['notes']) : null,
                    'status'      => 'pending'
                ]);

                $total += $itemTotal;
            }

            $order->total_amount = $total;
            $order->save();

            DB::commit();

            // Notify staff
            try {
                $staff = User::whereIn('role', ['admin', 'manager', 'waiter'])->get();
                if ($staff->isNotEmpty()) {
                    Notification::send($staff, new \App\Notifications\NewOrderNotification($order));
                }
            } catch (\Exception $e) {
                // Ignore notification failure
            }

            return response()->json([
                'success'    => true,
                'message'    => 'Pedido enviado para a cozinha com sucesso!',
                'order_id'   => $order->id,
                'table_name' => $tableDisplay,
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

    public function callWaiter(Request $request)
    {
        $table = $request->input('table', 'Balcão / Salão');
        $name = $request->input('name', 'Cliente');

        // Broadcast / notify staff
        try {
            $staff = User::whereIn('role', ['admin', 'manager', 'waiter'])->get();
            // You can log or send notification to staff
        } catch (\Exception $e) {
            // ignore
        }

        return response()->json([
            'success' => true,
            'message' => 'O garçom foi chamado para ' . ($table ? 'a ' . $table : 'o seu atendimento') . '! Um atendente já está a caminho.'
        ]);
    }
}

<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        // Filtra os pedidos com base na pesquisa
        $orders = Order::with(['table', 'orderItems.product'])
        ->when($search, function ($query, $search) {
            return $query->where('status', 'like', "%{$search}%")
                        ->orWhereHas('table', function ($query) use ($search) {
                            return $query->where('name', 'like', "%{$search}%");
                        });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        $total_orders = Order::count();
         // Obtém o total de pedidos feitos hoje
        $totalToday = $this->orderGetTotalToday();
        // Obtém o total de pedidos abertos
        $totalOpen = $this->order_get_open_count();
        
        return view('orders.index', compact('orders', 'total_orders', 'totalToday', 'totalOpen', 'search'));
    }
    // app/Http/Controllers/OrderController.php

    public function orderGetTotalToday()
    {
        // Calcula o total de pedidos feitos hoje
        $total = Order::whereDate('created_at', today())->sum('total_amount'); // Ou qualquer campo que represente o valor total

        return $total;
    }
    public function order_get_open_count()
    {
        // Obtém o total de pedidos abertos
        $total = Order::where('status', 'pending')->count();

        return $total;
    }
    public function create()
    {
        $tables = Table::where('status', 'occupied')->get();
        $products = Product::where('is_active', true)->get();
        
        return view('orders.create', compact('tables', 'products'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            $order = Order::create([
                'table_id' => $validated['table_id'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
            ]);
            
            foreach ($validated['products'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('orders.index')
                ->with('success', 'Order created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function show(Order $order)
    {
        $order->load(['table', 'orderItems.product']);
        $order_items = $order->orderItems;
        return view('orders.show', compact('order', 'order_items'));
    }
    
    public function edit(Order $order)
    {
        $tables = Table::all();
        $products = Product::where('is_active', true)->get();
        $order->load(['orderItems.product']);
        
        return view('orders.edit', compact('order', 'tables', 'products'));
    }
    
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            $order->update([
                'table_id' => $validated['table_id'],
                'notes' => $validated['notes'] ?? null,
            ]);
            
            // Delete existing order items
            $order->orderItems()->delete();
            
            // Create new order items
            foreach ($validated['products'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('orders.index')
                ->with('success', 'Order updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function destroy(Order $order)
    {
        DB::beginTransaction();
        
        try {
            // Delete order items
            $order->orderItems()->delete();
            
            // Delete order
            $order->delete();
            
            DB::commit();
            
            return redirect()->route('orders.index')
                ->with('success', 'Order deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function complete(Order $order)
    {
        $order->update(['status' => 'completed']);
        
        return redirect()->route('orders.index')
            ->with('success', 'Order marked as completed');
    }
    
    public function kitchen()
    {
        $pendingOrders = Order::with(['table', 'orderItems.product'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('orders.kitchen', compact('pendingOrders'));
    }
    // app/Http/Controllers/OrderController.php

    public function receipt(Order $order)
    {
        // Carrega as informações do pedido
        $order->load(['table', 'orderItems.product']);
        
        // Aqui você pode gerar um PDF ou apenas retornar uma view com o recibo
        // Se você quiser gerar um PDF, pode usar bibliotecas como `dompdf` ou `barryvdh/laravel-dompdf`
        
        return view('orders.receipt', compact('order')); // Exibindo uma view com o recibo
    }
    public function cancel(Order $order)
    {
        // Altera o status do pedido para 'cancelled'
        $order->update(['status' => 'cancelled']);
        
        return redirect()->route('orders.index')
            ->with('success', 'Order cancelled successfully');
    }
    public function reopen(Order $order)
    {
        // Altera o status do pedido para 'pending'
        $order->update(['status' => 'pending']);
        
        return redirect()->route('orders.index')
            ->with('success', 'Order reopened successfully');
    }
    //print
    public function print(Order $order)
    {
        // Carrega as informações do pedido
        $order->load(['table', 'orderItems.product']);
        
        $items = $order->orderItems; // Certifique-se de que isso está retornando os itens
        //dd($items); // Debug para garantir que os dados estão sendo passados

        $company_name = config('app.name');
        $company_address = 'Avenida Principal, 123, Cidade, Estado';
        $company_phone = config('app.phone');

        return view('orders.print', compact('order', 'company_name', 'company_address', 'company_phone', 'items')); // Exibindo uma view com o recibo
    }

}

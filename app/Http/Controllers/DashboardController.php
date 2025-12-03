<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use App\Models\Client;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
{
    $data = cache()->remember('dashboard_data_' . auth()->id(), now()->addMinutes(5), function () {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfWeek = Carbon::now()->startOfWeek();

        // ✅ Vendas — queries simples e seguras
        $todaySales = Sale::whereDate('created_at', $today)->sum('total_amount') ?? 0;
        $yesterdaySales = Sale::whereDate('created_at', $yesterday)->sum('total_amount') ?? 0;
        $weekSales = Sale::whereBetween('created_at', [$startOfWeek, Carbon::now()])->sum('total_amount') ?? 0;

        // Pedidos — uma query só com `selectRaw`
        $orderStats = Order::selectRaw("
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as open_orders,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
            SUM(CASE WHEN status = 'completed' AND DATE(created_at) = ? THEN 1 ELSE 0 END) as completed_today
        ", [$today->toDateString()])->first();

        $openOrders = $orderStats->open_orders ?? 0;
        $pendingOrders = $orderStats->pending_orders ?? 0;
        $completedOrdersToday = $orderStats->completed_today ?? 0;

        //  Estoque baixo
        $lowStockProducts = Product::with('category')
            ->whereBetween('stock_quantity', [1, 9])
            ->orderBy('stock_quantity')
            ->take(5)
            ->get();

        //  Mesas
        $tables = Table::select('id', 'number', 'capacity', 'status')->get();
        $occupiedTables = $tables->where('status', 'occupied')->count();
        $availableTables = $tables->where('status', 'available')->count();

        //  Clientes
        $newClientsToday = Client::whereDate('created_at', $today)->count();
        
        // Total de produtos (usado na view)
        $totalProducts = Product::count();

        //  Crescimento
        $salesGrowth = $yesterdaySales > 0
            ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1)
            : ($todaySales > 0 ? 100 : 0);

        //  Dados para gráficos e listas
        $hourlySales = $this->getHourlySalesData();
        $dailySales = $this->getDailySalesData();
        $topProducts = $this->getTopProducts();
        $recentOrders = $this->getRecentOrders();

        return compact(
            'todaySales',
            'yesterdaySales',
            'weekSales',
            'openOrders',
            'pendingOrders',
            'completedOrdersToday',
            'lowStockProducts',
            'tables',
            'occupiedTables',
            'availableTables',
            'newClientsToday',
            'totalProducts',
            'salesGrowth',
            'hourlySales',
            'dailySales',
            'topProducts',
            'recentOrders'
        );
    });

    return view('dashboard.index', $data);
}

    // 🔹 Nova rota para refresh via AJAX
    public function stats()
{
    $today = Carbon::today();
    $yesterday = Carbon::yesterday();

    $todaySales = Sale::whereDate('created_at', $today)->sum('total_amount') ?? 0;
    $yesterdaySales = Sale::whereDate('created_at', $yesterday)->sum('total_amount') ?? 0;

    $openOrders = Order::where('status', 'active')->count();
    $lowStockCount = Product::whereBetween('stock_quantity', [1, 9])->count();
    $availableTables = Table::where('status', 'available')->count();
    $totalTables = Table::count();

    $salesGrowth = $yesterdaySales > 0
        ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1)
        : ($todaySales > 0 ? 100 : 0);

    return response()->json([
        'success' => true,
        'todaySales' => (float) $todaySales,
        'openOrders' => $openOrders,
        'lowStockCount' => $lowStockCount,
        'availableTables' => $availableTables,
        'totalTables' => $totalTables,
        'salesGrowth' => $salesGrowth,
        'timestamp' => now()->format('H:i:s'),
    ]);
}

    private function getHourlySalesData()
    {
        $today = Carbon::today();
        $hourly = Sale::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('SUM(total_amount) as sales')
        )
        ->whereDate('created_at', $today)
        ->groupBy('hour')
        ->orderBy('hour')
        ->pluck('sales', 'hour');

        $data = [];
        for ($h = 0; $h < 24; $h++) {
            $label = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $data[] = [
                'hour' => $label,
                'sales' => (float) ($hourly->get($h, 0))
            ];
        }
        return $data;
    }

    private function getDailySalesData()
    {
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = Carbon::now()->subDays($i)->toDateString();
        }

        $sales = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as sales')
        )
        ->whereIn(DB::raw('DATE(created_at)'), $dates)
        ->groupBy('date')
        ->pluck('sales', 'date');

        $data = [];
        foreach ($dates as $date) {
            $d = Carbon::parse($date);
            $dayName = $d->locale('pt')->isoFormat('ddd');
            $data[] = [
                'day' => $dayName,
                'sales' => (float) ($sales->get($date, 0))
            ];
        }
        return $data;
    }

    private function getTopProducts($limit = 5)
    {
        return Product::select('products.id', 'products.name', 'products.stock_quantity')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [Carbon::now()->subDays(30), Carbon::now()])
            ->groupBy('products.id', 'products.name', 'products.stock_quantity')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->with('category:id,name') 
            ->get();
    }

    private function getRecentOrders($limit = 5)
    {
        return Order::select('id', 'customer_name', 'table_id', 'total_amount', 'status', 'created_at')
            ->with('table:id,number') // apenas table
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($order) {
                $order->client_name = $order->customer_name; // usa customer_name quando não há relação client
                $order->status_color = $this->getStatusColor($order->status);
                $order->status_label = $this->getStatusLabel($order->status);
                return $order;
            });
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            'pending' => 'danger',
            'active', 'preparing' => 'primary',
            'ready', 'delivered', 'completed' => 'success',
            'cancelled' => 'warning',
            'paid' => 'success',
            default => 'primary',
        };
    }

    private function getStatusLabel($status)
    {
        return match ($status) {
            'pending' => 'Pendente',
            'active' => 'Ativo',
            'preparing' => 'Preparando',
            'ready' => 'Pronto',
            'delivered' => 'Entregue',
            'completed' => 'Concluído',
            'cancelled' => 'Cancelado',
            default => ucfirst($status),
        };
    }
}
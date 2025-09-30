<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // --- métricas vendas
            $totalSalesToday = Sale::whereDate('sale_date', Carbon::today())->sum('total_amount');
            $yesterdaySales = Sale::whereDate('sale_date', Carbon::yesterday())->sum('total_amount');
            $salesChange = $yesterdaySales > 0 ? (($totalSalesToday - $yesterdaySales) / $yesterdaySales * 100) : 0;
            $isPositive = $salesChange >= 0;

            $totalSalesThisMonth = Sale::whereMonth('sale_date', Carbon::now()->month)
                ->whereYear('sale_date', Carbon::now()->year)
                ->sum('total_amount');

            // --- pedidos
            $openOrders = Order::whereIn('status', ['active', 'pending', 'preparing'])->count();
            $completedOrdersToday = Order::whereDate('created_at', Carbon::today())
                ->whereIn('status', ['completed', 'paid'])
                ->count();

            $yesterdayOrders = Order::whereDate('created_at', Carbon::yesterday())
                ->whereIn('status', ['completed', 'paid'])
                ->count();

            $ordersChange = $yesterdayOrders > 0 ? (($completedOrdersToday - $yesterdayOrders) / $yesterdayOrders * 100) : 0;
            $isOrdersPositive = $ordersChange >= 0;

            // --- despesas
            $totalExpensesToday = 0;
            $totalExpensesMonth = 0;
            
            if (class_exists('\App\Models\Expense')) {
                $totalExpensesToday = \App\Models\Expense::whereDate('expense_date', Carbon::today())->sum('amount');
                $totalExpensesMonth = \App\Models\Expense::whereMonth('expense_date', Carbon::now()->month)
                    ->whereYear('expense_date', Carbon::now()->year)
                    ->sum('amount');
            }

            // --- produtos
            $lowStockProducts = Product::with('category')
                ->whereColumn('stock_quantity', '<=', 'min_stock_level')
                ->where('is_active', true)
                ->whereNull('deleted_at')
                ->get();

            $outOfStockProducts = Product::where('stock_quantity', 0)
                ->where('is_active', true)
                ->whereNull('deleted_at')
                ->count();

            $totalProducts = Product::where('is_active', true)->whereNull('deleted_at')->count();

            // --- mesas
            $tables = Table::with(['orders' => function ($query) {
                $query->whereIn('status', ['active', 'completed'])->latest();
            }])->orderBy('number')->get();

            $occupiedTables = $tables->filter(fn($t) => $t->orders->whereIn('status', ['active', 'completed'])->isNotEmpty())->count();
            $availableTables = $tables->count() - $occupiedTables;

            // --- gráficos
            $hourlySalesData = $this->getHourlySalesData();
            $dailySalesData = $this->getDailySalesData();
            $topProducts = $this->getTopProductsThisMonth();
            $recentOrders = $this->getRecentOrders();

            return view('dashboard.index', compact(
                'totalSalesToday',
                'yesterdaySales',
                'salesChange',
                'isPositive',
                'totalSalesThisMonth',
                'openOrders',
                'completedOrdersToday',
                'yesterdayOrders',
                'ordersChange',
                'isOrdersPositive',
                'totalExpensesToday',
                'totalExpensesMonth',
                'lowStockProducts',
                'outOfStockProducts',
                'totalProducts',
                'tables',
                'occupiedTables',
                'availableTables',
                'hourlySalesData',
                'dailySalesData',
                'topProducts',
                'recentOrders'
            ));
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return view('dashboard.index')->with('error', 'Erro ao carregar dashboard: ' . $e->getMessage());
        }
    }

    public function menu()
    {
        try {
            $categories = Category::with(['products' => function ($query) {
                $query->where('is_active', true)
                    ->whereNull('deleted_at')
                    ->orderBy('name');
            }])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            return view('dashboard.menu', compact('categories'));
        } catch (\Exception $e) {
            \Log::error('Menu error: ' . $e->getMessage());
            return view('dashboard.menu', ['categories' => collect()])->with('error', 'Erro ao carregar menu');
        }
    }

    public function menuCategory($categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            $products = Product::where('category_id', $categoryId)
                ->where('is_active', true)
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get();

            return view('dashboard.menu_category', compact('category', 'products'));
        } catch (\Exception $e) {
            \Log::error('Menu category error: ' . $e->getMessage());
            return redirect()->route('dashboard.menu')->with('error', 'Categoria não encontrada');
        }
    }

    // === helpers ===

    private function getHourlySalesData()
    {
        $data = collect();
        $today = Carbon::today();

        for ($hour = 0; $hour < 24; $hour++) {
            $startTime = $today->copy()->addHours($hour);
            $endTime = $today->copy()->addHours($hour + 1);

            $totalAmount = Sale::whereBetween('sale_date', [$startTime, $endTime])->sum('total_amount');

            $data->push([
                'hour' => sprintf('%02d:00', $hour),
                'value' => (float)$totalAmount
            ]);
        }

        return $data;
    }

    private function getDailySalesData()
    {
        $data = collect();
        $startDate = Carbon::now()->subDays(6);

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $totalAmount = Sale::whereDate('sale_date', $date)->sum('total_amount');

            $data->push([
                'date' => $date->format('d/m'),
                'value' => (float)$totalAmount
            ]);
        }

        return $data;
    }

    private function getTopProductsThisMonth()
    {
        return Product::join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereMonth('sales.sale_date', Carbon::now()->month)
            ->whereYear('sales.sale_date', Carbon::now()->year)
            ->groupBy('products.id', 'products.name', 'products.selling_price')
            ->select(
                'products.id',
                'products.name',
                'products.selling_price',
                DB::raw('SUM(sale_items.quantity) as total_sold'),
                DB::raw('SUM(sale_items.total_price) as total_revenue')
            )
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    }

    private function getRecentOrders()
    {
        return Order::with(['table', 'user'])
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }
}
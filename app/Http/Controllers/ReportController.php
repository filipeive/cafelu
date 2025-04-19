<?php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }
    
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        
        // Daily sales
        $dailySales = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->select(DB::raw('DATE(sale_date) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy(DB::raw('DATE(sale_date)'))
            ->orderBy('date')
            ->get();
            
        // Payment methods breakdown
        $paymentMethods = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();
            
        // Top selling products
        $topProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as quantity'),
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as total')
            )
            ->groupBy('products.name')
            ->orderByDesc('quantity')
            ->limit(10)
            ->get();
            
        // Sales by category
        $salesByCategory = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->select(
            'categories.name',
            DB::raw('SUM(sale_items.quantity) as quantity'),
            DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as total')
            )
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();
        return view('reports.sales', compact('dailySales', 'paymentMethods', 'topProducts', 'salesByCategory', 'startDate', 'endDate'));
    }
    public function inventory(Request $request)
    {
        $lowStockThreshold = $request->input('low_stock_threshold', 10);
        
        $lowStockProducts = Product::where('stock_quantity', '<', $lowStockThreshold)
            ->with('category')
            ->get();
            
        return view('reports.inventory', compact('lowStockProducts', 'lowStockThreshold'));
    }
    public function salesByProduct(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        
        $salesByProduct = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as quantity'),
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as total')
            )
            ->groupBy('products.name')
            ->orderByDesc('total')
            ->get();
            
        return view('reports.sales-by-product', compact('salesByProduct', 'startDate', 'endDate'));
    }
    public function salesByCategory(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        
        $salesByCategory = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->select(
                'categories.name',
                DB::raw('SUM(sale_items.quantity) as quantity'),
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as total')
            )
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();
            
        return view('reports.sales-by-category', compact('salesByCategory', 'startDate', 'endDate'));
    }
    public function salesByPaymentMethod(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        
        $salesByPaymentMethod = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();
            
        return view('reports.sales-by-payment-method', compact('salesByPaymentMethod', 'startDate', 'endDate'));
    }
    public function salesByDate(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        
        $salesByDate = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->select(DB::raw('DATE(sale_date) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy(DB::raw('DATE(sale_date)'))
            ->orderBy('date')
            ->get();
            
        return view('reports.sales-by-date', compact('salesByDate', 'startDate', 'endDate'));
    }
}
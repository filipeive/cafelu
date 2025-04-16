<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Table;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
     /*    $totalSales = Sale::where('status', 'completed')->sum('total_amount');
        $availableTables = Table::where('status', 'free')->count();
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->get();
        
        return view('dashboard.index', compact('totalSales', 'availableTables', 'lowStockProducts')); */
        // abrir o pos
        return redirect()->route('pos.index');
    }
    //crie essas funcoes Route::get('menu', [DashboardController::class, 'menu'])->name('menu');Route::get('menu/{category}', [DashboardController::class, 'menuCategory'])->name('menu.category');
    public function menu()
    {
        $categories = Category::all();
        return view('dashboard.menu', compact('categories'));
    }
    public function menuCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get();
        return view('dashboard.menu_category', compact('products'));
    }
}


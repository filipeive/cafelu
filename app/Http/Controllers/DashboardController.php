<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Mostrar o dashboard principal do sistema.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Vendas de hoje
        $totalSalesToday = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');
        
        // Pedidos abertos
        $openOrders = Order::where('status', 'active')->count();
        
        // Produtos com estoque baixo (menos de 10 unidades)
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->get();
        
        // Todas as mesas
        $tables = Table::all();
        
        // Dados de vendas por hora para o gráfico
        $hourlySalesData = $this->getHourlySalesData();
        
        return view('dashboard.index', compact(
            'totalSalesToday',
            'openOrders',
            'lowStockProducts',
            'tables',
            'hourlySalesData'
        ));
    }
    
    /**
     * Obter os dados de vendas por hora para o gráfico.
     *
     * @return array
     */
    private function getHourlySalesData()
    {
        $data = [];
        $today = Carbon::today();
        
        // Gerar dados para as 24 horas do dia
        for ($hour = 0; $hour < 24; $hour++) {
            $startTime = $today->copy()->addHours($hour);
            $endTime = $today->copy()->addHours($hour + 1);
            
            $totalAmount = Sale::whereBetween('created_at', [$startTime, $endTime])
                ->sum('total_amount');
            
            $data[] = [
                'hour' => $hour . ':00',
                'value' => (float) $totalAmount
            ];
        }
        
        return $data;
    }
    /**
     * Mostrar o menu de produtos.
     *
     * @return \Illuminate\Http\Response
     */
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


<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rota pública de login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Rotas protegidas por autenticação
Route::middleware(['auth', 'verified'])->group(function () {
    
    // ===================================================
    // DASHBOARD
    // ===================================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu', [DashboardController::class, 'menu'])->name('menu.index');
    Route::get('/menu/{category}', [DashboardController::class, 'menuCategory'])->name('menu.category');

    // ===================================================
    // PERFIL DO USUÁRIO
    // ===================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    // ===================================================
    // PRODUTOS
    // ===================================================
    Route::resource('products', ProductController::class);
    Route::patch('products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
    Route::get('products/{product}/stock-history', [ProductController::class, 'stockHistory'])->name('products.stock-history');
    Route::get('products/{product}/sales-data', [ProductController::class, 'salesData'])->name('products.sales-data');

    // ===================================================
    // CATEGORIAS
    // ===================================================
    Route::resource('categories', CategoryController::class);

    // ===================================================
    // MESAS
    // ===================================================
    Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
    Route::patch('/tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.update-status');
    Route::post('/tables/{table}/order', [TableController::class, 'createOrder'])->name('tables.create-order');
    Route::post('/tables/merge', [TableController::class, 'mergeTables'])->name('tables.merge');
    Route::post('/tables/split', [TableController::class, 'splitTables'])->name('tables.split');

    // ===================================================
    // PEDIDOS
    // ===================================================
    Route::resource('orders', OrderController::class)->except(['create', 'store']);
    Route::post('/orders/{order}/items', [OrderController::class, 'addItem'])->name('orders.add-item');
    Route::delete('/order-items/{orderItem}', [OrderController::class, 'removeItem'])->name('order-items.destroy');
    Route::patch('/order-items/{orderItem}/status', [OrderController::class, 'updateItemStatus'])->name('order-items.update-status');
    Route::patch('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::post('/orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/data', [OrderController::class, 'getOrderData'])->name('orders.data');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'printReceipt'])->name('orders.receipt');
    Route::get('/orders/{order}/print', [OrderController::class, 'printReceipt'])->name('orders.print');

    // ===================================================
    // PDV (Point of Sale)
    // ===================================================
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/receipt/{saleId}', [POSController::class, 'receipt'])->name('pos.receipt');

    // ===================================================
    // VENDAS
    // ===================================================
    Route::resource('sales', SaleController::class);
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    Route::post('/sales/process', [SaleController::class, 'process'])->name('sales.process');
    Route::get('/sales/{id}/details', [SaleController::class, 'details'])->name('sales.details');
    Route::get('/sales/{id}/export-pdf', [SaleController::class, 'exportPDF'])->name('sales.export-pdf');
    Route::get('/api/products', [SaleController::class, 'getProducts'])->name('api.products');

    // ===================================================
    // CLIENTES
    // ===================================================
    Route::resource('clients', ClientController::class);

    // ===================================================
    // FUNCIONÁRIOS
    // ===================================================
    Route::resource('employees', EmployeeController::class);

    // ===================================================
    // USUÁRIOS (Admin apenas)
    // ===================================================
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // ===================================================
    // MOVIMENTAÇÃO DE ESTOQUE
    // ===================================================
    Route::resource('stock-movements', StockMovementController::class)->except(['edit', 'update']);
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::get('/stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
    Route::get('/stock-movements/{stockMovement}', [StockMovementController::class, 'show'])->name('stock-movements.show');
    Route::delete('/stock-movements/{stockMovement}', [StockMovementController::class, 'destroy'])->name('stock-movements.destroy');

    // ===================================================
    // RELATÓRIOS (Admin e Manager)
    // ===================================================
    Route::middleware(['manager'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/daily-sales', [ReportController::class, 'dailySales'])->name('reports.daily-sales');
        Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('/reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');
        Route::get('/reports/monthly-sales', [ReportController::class, 'monthlySales'])->name('reports.monthly-sales');
        Route::get('/reports/sales-by-product', [ReportController::class, 'salesByProduct'])->name('reports.sales-by-product');
        Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
        Route::get('/reports/cash-flow', [ReportController::class, 'cashFlow'])->name('reports.cash-flow');
        Route::get('/reports/customer-profitability', [ReportController::class, 'customerProfitability'])->name('reports.customer-profitability');
        Route::get('/reports/abc-analysis', [ReportController::class, 'abcAnalysis'])->name('reports.abc-analysis');
        Route::get('/reports/period-comparison', [ReportController::class, 'periodComparison'])->name('reports.period-comparison');
        Route::get('/reports/business-insights', [ReportController::class, 'businessInsights'])->name('reports.business-insights');
        Route::get('/reports/sales-specialized', [ReportController::class, 'salesReport'])->name('reports.sales-specialized');
        Route::get('/reports/expenses-specialized', [ReportController::class, 'expensesReport'])->name('reports.expenses-specialized');
        Route::get('/reports/comparison-specialized', [ReportController::class, 'comparisonReport'])->name('reports.comparison-specialized');
        Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::post('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
    });

    // ===================================================
    // BUSCA GLOBAL
    // ===================================================
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/api/search', [SearchController::class, 'api'])->name('search.api');

    // ===================================================
    // ROTAS AJAX/API INTERNAS
    // ===================================================
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/products/search', function(Request $request) {
            $query = $request->get('q');
            $products = \App\Models\Product::where('name', 'like', "%{$query}%")
                                          ->where('is_active', true)
                                          ->whereNull('deleted_at')
                                          ->limit(10)
                                          ->get(['id', 'name', 'selling_price', 'stock_quantity']);
            return response()->json($products);
        })->name('products.search');
        
        Route::get('/categories/{category}/products', function($categoryId) {
            $products = \App\Models\Product::where('category_id', $categoryId)
                                          ->where('is_active', true)
                                          ->whereNull('deleted_at')
                                          ->get(['id', 'name', 'selling_price', 'stock_quantity']);
            return response()->json($products);
        })->name('categories.products');

        Route::get('/tables/status', function() {
            $tables = \App\Models\Table::with(['orders' => function($query) {
                $query->whereIn('status', ['active', 'completed'])->latest();
            }])->get();
            
            return response()->json($tables->map(function($table) {
                $hasActiveOrder = $table->orders->whereIn('status', ['active', 'completed'])->isNotEmpty();
                return [
                    'id' => $table->id,
                    'number' => $table->number,
                    'status' => $hasActiveOrder ? 'occupied' : 'free',
                    'capacity' => $table->capacity,
                    'order_id' => $hasActiveOrder ? $table->orders->first()->id : null
                ];
            }));
        })->name('tables.status');

        Route::get('/dashboard/stats', function() {
            return response()->json([
                'sales_today' => \App\Models\Sale::whereDate('sale_date', today())->sum('total_amount'),
                'orders_active' => \App\Models\Order::whereIn('status', ['active', 'pending', 'preparing'])->count(),
                'products_low_stock' => \App\Models\Product::whereColumn('stock_quantity', '<=', 'min_stock_level')
                                                          ->where('is_active', true)
                                                          ->whereNull('deleted_at')
                                                          ->count(),
                'tables_occupied' => \App\Models\Table::whereHas('orders', function($query) {
                    $query->whereIn('status', ['active', 'completed']);
                })->count()
            ]);
        })->name('dashboard.stats');
    });
});

// ===================================================
// MIDDLEWARE CUSTOMIZADOS
// ===================================================

// Middleware para Admin
/* Route::middleware(['auth'])->group(function () {
    Route::middleware(function ($request, $next) {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
        }
        return $next($request);
    })->group(function () {
        // Rotas específicas para admin já definidas acima
    });
}); */
/* 
// Middleware para Manager (Admin + Manager)
Route::middleware(['auth'])->group(function () {
    Route::middleware(function ($request, $next) {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Acesso negado. Apenas administradores e gerentes podem acessar esta área.');
        }
        return $next($request);
    })->group(function () {
        // Rotas específicas para manager já definidas acima
    });
}); */

// ===================================================
// ROTAS DE AUTENTICAÇÃO (Laravel Breeze)
// ===================================================
Auth::routes();
// ===================================================
// ROTAS ADICIONAIS PARA FUNCIONALIDADES ESPECIAIS
// ===================================================

Route::middleware(['auth'])->group(function () {
    
    // Rotas para impressão sem layout
    Route::get('/print/order/{order}', function(\App\Models\Order $order) {
        $order->load(['items.product', 'table', 'user']);
        return view('orders.print', compact('order'));
    })->name('print.order');
    
    Route::get('/print/sale/{sale}', function(\App\Models\Sale $sale) {
        $sale->load(['items.product', 'user']);
        return view('sales.print', compact('sale'));
    })->name('print.sale');

    // Rotas para exportação
    Route::get('/export/products', function() {
        $products = \App\Models\Product::with('category')
                                      ->where('is_active', true)
                                      ->whereNull('deleted_at')
                                      ->get();
        
        $csv = "ID,Nome,Categoria,Preço de Compra,Preço de Venda,Estoque,Estoque Mínimo,Status\n";
        
        foreach($products as $product) {
            $csv .= implode(',', [
                $product->id,
                '"' . $product->name . '"',
                '"' . ($product->category->name ?? 'Sem categoria') . '"',
                $product->purchase_price,
                $product->selling_price,
                $product->stock_quantity,
                $product->min_stock_level,
                $product->is_active ? 'Ativo' : 'Inativo'
            ]) . "\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="produtos_' . date('Y-m-d') . '.csv"');
    })->name('export.products');

    Route::get('/export/sales', function(Request $request) {
        $query = \App\Models\Sale::with(['user', 'items.product']);
        
        if ($request->date_from) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }
        
        $sales = $query->get();
        
        $csv = "ID,Data,Cliente,Total,Método de Pagamento,Usuário,Status\n";
        
        foreach($sales as $sale) {
            $csv .= implode(',', [
                $sale->id,
                $sale->sale_date->format('d/m/Y H:i'),
                '"' . ($sale->customer_name ?? 'N/A') . '"',
                $sale->total_amount,
                '"' . $sale->payment_method . '"',
                '"' . ($sale->user->name ?? 'N/A') . '"',
                '"' . $sale->status . '"'
            ]) . "\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="vendas_' . date('Y-m-d') . '.csv"');
    })->name('export.sales');

    // Rotas para relatórios rápidos
    Route::get('/quick-stats', function() {
        $today = today();
        $thisMonth = now()->startOfMonth();
        
        return response()->json([
            'today' => [
                'sales' => \App\Models\Sale::whereDate('sale_date', $today)->count(),
                'revenue' => \App\Models\Sale::whereDate('sale_date', $today)->sum('total_amount'),
                'orders' => \App\Models\Order::whereDate('created_at', $today)->count(),
            ],
            'month' => [
                'sales' => \App\Models\Sale::where('sale_date', '>=', $thisMonth)->count(),
                'revenue' => \App\Models\Sale::where('sale_date', '>=', $thisMonth)->sum('total_amount'),
                'orders' => \App\Models\Order::where('created_at', '>=', $thisMonth)->count(),
            ],
            'products' => [
                'total' => \App\Models\Product::where('is_active', true)->whereNull('deleted_at')->count(),
                'low_stock' => \App\Models\Product::whereColumn('stock_quantity', '<=', 'min_stock_level')
                                                 ->where('is_active', true)
                                                 ->whereNull('deleted_at')
                                                 ->count(),
                'out_of_stock' => \App\Models\Product::where('stock_quantity', 0)
                                                    ->where('is_active', true)
                                                    ->whereNull('deleted_at')
                                                    ->count(),
            ]
        ]);
    })->name('quick-stats');

    // Rota para backup básico (apenas admin)
    Route::get('/backup', function() {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            storage_path('app/backups/' . $filename)
        );
        
        // Criar diretório se não existir
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }
        
        exec($command, $output, $return_var);
        
        if ($return_var === 0) {
            return response()->download(storage_path('app/backups/' . $filename));
        } else {
            return redirect()->back()->with('error', 'Erro ao gerar backup');
        }
    })->name('backup');
});

// ===================================================
// ROTAS DE FALLBACK E REDIRECIONAMENTOS
// ===================================================

// Redirecionamento para dashboard após login
Route::redirect('/home', '/dashboard');

// Rota de fallback para 404 personalizado
Route::fallback(function () {
    return view('errors.404');
});

// Rotas para testes em desenvolvimento (remover em produção)
if (app()->environment('local')) {
    Route::get('/test-db', function() {
        try {
            $users = \App\Models\User::count();
            $products = \App\Models\Product::count();
            $categories = \App\Models\Category::count();
            
            return response()->json([
                'database' => 'Connected successfully',
                'users' => $users,
                'products' => $products,
                'categories' => $categories,
                'timestamp' => now()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Database connection failed',
                'message' => $e->getMessage()
            ], 500);
        }
    });
    
    Route::get('/populate-test-data', function() {
        // Criar dados de teste (apenas em desenvolvimento)
        \Artisan::call('db:seed');
        return 'Dados de teste criados com sucesso!';
    });
}
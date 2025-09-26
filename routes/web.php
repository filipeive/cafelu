<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Auth::routes();

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas (Base)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - Todos os usuários autenticados
    Route::get('dashboard/', [DashboardController::class, 'index'])->name('dashboard');
    //Route::get('/dashboard', [DashboardController::class, 'menu'])->name('dashboard.menu');

    // ===== PERFIL DO USUÁRIO =====
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::get('/stats', [ProfileController::class, 'stats'])->name('stats');
        Route::get('/performance', [ProfileController::class, 'performance'])->name('performance');
        Route::get('/show', [ProfileController::class, 'show'])->name('show');
        //update-photo
        Route::patch('/photo', [ProfileController::class, 'updatePhoto'])->name('update-photo');

    });

    /*
    |--------------------------------------------------------------------------
    | OPERAÇÕES BÁSICAS - Maioria dos usuários
    |--------------------------------------------------------------------------
    */
    
    // ===== PONTO DE VENDA (PDV) =====
    Route::middleware(['permission:view_products,create_sales'])->group(function () {
        Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
        Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.completeCheckout');
        Route::get('/pos/receipt/{saleId}', [POSController::class, 'receipt'])->name('pos.receipt');
        Route::get('/pos/receipt/{saleId}/print', [POSController::class, 'printReceipt'])->name('pos.receipt.print');
    });

    // ===== PEDIDOS =====
    Route::middleware(['permission:view_orders'])->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/data/{order}', [OrderController::class, 'getOrderData'])->name('orders.data');
        Route::get('/orders/kitchen', [OrderController::class, 'kitchen'])
            ->middleware('permission:manage_kitchen')->name('orders.kitchen');
    });

    Route::middleware(['permission:create_orders'])->group(function () {
        Route::post('/orders/{order}/add-item', [OrderController::class, 'addItem'])->name('orders.add-item');
    });

    Route::middleware(['permission:edit_orders'])->group(function () {
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::post('/orders/items/{orderItem}/remove', [OrderController::class, 'removeItem'])->name('orders.remove-item');
        Route::post('/orders/items/{orderItem}/update-status', [OrderController::class, 'updateItemStatus'])->name('orders.update-item-status');
        Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    });

    Route::middleware(['permission:cancel_orders'])->group(function () {
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });

    Route::middleware(['permission:manage_payments'])->group(function () {
        Route::post('/orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
        Route::get('/orders/{order}/print-receipt', [OrderController::class, 'printReceipt'])->name('orders.print-receipt');
        Route::get('/orders/print/{order}', [OrderController::class, 'print'])->name('orders.print');
    });
        
        // ===== COZINHA - Sistema dedicado =====
        Route::prefix('kitchen')->name('kitchen.')->group(function () {
            // Dashboard principal da cozinha
            Route::get('/', [KitchenController::class, 'dashboard'])->name('dashboard');
            
            // Visualizações da cozinha
            Route::get('/orders/{order}', [KitchenController::class, 'showOrder'])->name('order.show');
            Route::get('/by-category', [KitchenController::class, 'byCategory'])->name('by-category');
            Route::get('/history', [KitchenController::class, 'history'])->name('history');
            
            // API Routes para atualizações em tempo real
            Route::get('/active-orders', [KitchenController::class, 'getActiveOrders'])->name('active-orders');
            Route::post('/items/{item}/status', [KitchenController::class, 'updateItemStatus'])->name('update-item-status');
            Route::post('/orders/{order}/start-all', [KitchenController::class, 'startAllItems'])->name('start-all');
            Route::post('/orders/{order}/finish-all', [KitchenController::class, 'finishAllItems'])->name('finish-all');
        });

    // ===== MESAS =====
    Route::middleware(['permission:view_orders,manage_tables'])->group(function () {
        Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
        Route::post('/tables/{table}/update-status', [TableController::class, 'updateStatus'])->name('tables.update-status');
        Route::post('/tables/{table}/create-order', [TableController::class, 'createOrder'])->name('tables.create-order');
    });

    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/tables/create', [TableController::class, 'create'])->name('tables.create');
        Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
        Route::get('/tables/{table}/edit', [TableController::class, 'edit'])->name('tables.edit');
        Route::put('/tables/{table}', [TableController::class, 'update'])->name('tables.update');
        Route::delete('/tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
        Route::post('/tables/merge', [TableController::class, 'mergeTables'])->name('tables.merge');
        Route::post('/tables/split', [TableController::class, 'splitTables'])->name('tables.split');
    });

    /*
    |--------------------------------------------------------------------------
    | VENDAS E FINANCEIRO
    |--------------------------------------------------------------------------
    */
    
    // ===== VENDAS =====
    Route::middleware(['permission:view_sales'])->group(function () {
        Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
        Route::get('sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
        Route::post('sales/{sale}/print', [SaleController::class, 'receipt'])->name('sales.print');
    });

    Route::middleware(['permission:create_sales'])->group(function () {
        Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
        Route::post('/pos/process-sale', [SaleController::class, 'process_sale'])->name('sales.process');
    });

    Route::middleware(['permission:edit_sales'])->group(function () {
        Route::get('sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');
        Route::put('sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
    });

    Route::middleware(['permission:delete_sales'])->group(function () {
        Route::delete('sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
    });

    // ===== RELATÓRIOS =====
    Route::middleware(['permission:view_reports'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/dashboard', [ReportController::class, 'dashboard'])->name('reports.dashboard');
        Route::get('/reports/daily-sales', [ReportController::class, 'dailySales'])->name('reports.daily-sales');
        Route::get('/reports/monthly-sales', [ReportController::class, 'monthlySales'])->name('reports.monthly-sales');
        Route::get('/reports/sales-by-product', [ReportController::class, 'salesByProduct'])->name('reports.sales-by-product');
        Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('/reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');
    });

    Route::middleware(['permission:financial_reports'])->group(function () {
        Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
        Route::get('/reports/cash-flow', [ReportController::class, 'cashFlow'])->name('reports.cash-flow');
    });

    Route::middleware(['permission:advanced_analytics'])->group(function () {
        Route::get('/sales-specialized', [ReportController::class, 'salesReport'])->name('reports.sales-specialized');
        Route::get('/expenses-specialized', [ReportController::class, 'expensesReport'])->name('reports.expenses-specialized');
        Route::get('/comparison-specialized', [ReportController::class, 'comparisonReport'])->name('reports.comparison-specialized');
        Route::get('/reports/customer-profitability', [ReportController::class, 'customerProfitability'])->name('reports.customer-profitability');
        Route::get('/reports/abc-analysis', [ReportController::class, 'abcAnalysis'])->name('reports.abc-analysis');
        Route::get('/reports/period-comparison', [ReportController::class, 'periodComparison'])->name('reports.period-comparison');
        Route::get('/reports/business-insights', [ReportController::class, 'businessInsights'])->name('reports.business-insights');
    });

    Route::middleware(['permission:export_reports'])->group(function () {
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPDF'])->name('reports.export.pdf');
        Route::get('/reports/export-csv', [ReportController::class, 'exportCSV'])->name('reports.export.csv');
    });

    /*
    |--------------------------------------------------------------------------
    | GESTÃO DE PRODUTOS E ESTOQUE
    |--------------------------------------------------------------------------
    */
    
    /* // ===== PRODUTOS =====
    Route::prefix('products')->name('products.')->group(function () {
        // Visualizar produtos
        Route::middleware(['permission:view_products'])->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/{product}', [ProductController::class, 'show'])->name('show');
            Route::get('/search', [ProductController::class, 'search'])->name('search');
            Route::get('products/api/list', [ProductController::class, 'apiList'])->name('api.list');
        });

        // Criar produtos
        Route::middleware(['permission:create_products'])->group(function () {
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::post('/{product}/duplicate', [ProductController::class, 'duplicate'])->name('duplicate');
        });

        // Editar produtos
        Route::middleware(['permission:edit_products'])->group(function () {
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::post('/bulk-toggle', [ProductController::class, 'bulkToggle'])->name('bulk-toggle');
        });

        // Gestão de estoque
        Route::middleware(['permission:manage_stock'])->group(function () {
            Route::post('/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('adjust-stock');
        });

        // Relatórios de produtos
        Route::middleware(['permission:view_reports'])->group(function () {
            Route::get('/report', [ProductController::class, 'report'])->name('report');
            Route::get('/export/{format}', [ProductController::class, 'exportProducts'])->name('export');
        });

        // Deletar produtos
        Route::middleware(['permission:delete_products'])->group(function () {
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });
    }); */
    // ===== PRODUTOS - Permissões ajustadas =====
    Route::prefix('products')->name('products.')->group(function () {
        // Relatório e exportação - view_products permission
            Route::get('/search', [ProductController::class, 'search'])->name('search');
            Route::get('products/api/list', [ProductController::class, 'apiList'])->name('api.list');
        // Criar produtos - create_products permission
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');

        // Editar produtos - edit_products permission
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::post('/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('adjust-stock');
            Route::post('/{product}/duplicate', [ProductController::class, 'duplicate'])->name('duplicate');
            Route::post('/bulk-toggle', [ProductController::class, 'bulkToggle'])->name('bulk-toggle');
            Route::middleware('permission:view_products')->group(function () {
            Route::get('/report', [ProductController::class, 'report'])->name('report');
            Route::get('/export/{format}', [ProductController::class, 'exportProducts'])->name('export');
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        });

        // Deletar produtos - delete_products permission
        Route::middleware('permission:delete_products')->group(function () {
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });
    });
    // ===== CATEGORIAS =====
    Route::prefix('categories')->name('categories.')->middleware(['permission:manage_categories'])->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ===== MOVIMENTAÇÕES DE ESTOQUE =====
    Route::prefix('stock-movements')->name('stock-movements.')->group(function () {
        Route::middleware(['permission:view_stock_movements'])->group(function () {
            Route::get('/', [StockMovementController::class, 'index'])->name('index');
            Route::get('/{stockMovement}', [StockMovementController::class, 'show'])->name('show');
        });

        Route::middleware(['permission:create_stock_movements'])->group(function () {
            Route::get('/create', [StockMovementController::class, 'create'])->name('create');
            Route::post('/', [StockMovementController::class, 'store'])->name('store');
        });

        Route::middleware(['permission:edit_stock_movements'])->group(function () {
            Route::get('/{stockMovement}/edit', [StockMovementController::class, 'edit'])->name('edit');
            Route::put('/{stockMovement}', [StockMovementController::class, 'update'])->name('update');
        });

        Route::middleware(['role:admin'])->group(function () {
            Route::delete('/{stockMovement}', [StockMovementController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | GESTÃO FINANCEIRA
    |--------------------------------------------------------------------------
    */
    
    // ===== DESPESAS =====
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::middleware(['permission:view_expenses'])->group(function () {
            Route::get('/', [ExpenseController::class, 'index'])->name('index');
            Route::get('/{expense}', [ExpenseController::class, 'show'])->name('show');
            Route::get('/{expense}/details', [ExpenseController::class, 'showData'])->name('details');
            Route::get('/{expense}/download-receipt', [ExpenseController::class, 'downloadReceipt'])->name('download-receipt');
        });

        Route::middleware(['permission:create_expenses'])->group(function () {
            Route::get('/create', [ExpenseController::class, 'create'])->name('create');
            Route::post('/', [ExpenseController::class, 'store'])->name('store');
        });

        Route::middleware(['permission:edit_expenses'])->group(function () {
            Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('edit');
            Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update');
        });

        Route::middleware(['permission:delete_expenses'])->group(function () {
            Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
        });
    });

    // Categorias de despesas - apenas admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('expense-categories', ExpenseCategoryController::class)->only(['store']);
    });

    /*
    |--------------------------------------------------------------------------
    | GESTÃO DE CLIENTES E FUNCIONÁRIOS
    |--------------------------------------------------------------------------
    */
    
    // ===== CLIENTES =====
    Route::prefix('client')->name('client.')->group(function () {
        Route::middleware(['permission:view_clients'])->group(function () {
            Route::get('/', [ClientController::class, 'index'])->name('index');
            Route::get('/{client}/show', [ClientController::class, 'show'])->name('show');
            Route::get('/{client}/orders', [ClientController::class, 'orders'])->name('orders');
            Route::get('/search', [ClientController::class, 'search'])->name('search');
        });

        Route::middleware(['permission:create_clients'])->group(function () {
            Route::get('/create', [ClientController::class, 'create'])->name('create');
            Route::post('/store', [ClientController::class, 'store'])->name('store');
        });

        Route::middleware(['permission:edit_clients'])->group(function () {
            Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
            Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        });

        Route::middleware(['permission:delete_clients'])->group(function () {
            Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
        });
    });

    // ===== FUNCIONÁRIOS =====
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::middleware(['permission:view_employees'])->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('index');
            Route::get('/{employee}/show', [EmployeeController::class, 'show'])->name('show');
            Route::get('/search', [EmployeeController::class, 'search'])->name('search');
        });

        Route::middleware(['permission:create_employees'])->group(function () {
            Route::get('/create', [EmployeeController::class, 'create'])->name('create');
            Route::post('/store', [EmployeeController::class, 'store'])->name('store');
        });

        Route::middleware(['permission:edit_employees'])->group(function () {
            Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
            Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
        });

        Route::middleware(['permission:delete_employees'])->group(function () {
            Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | ADMINISTRAÇÃO - APENAS ADMIN
    |--------------------------------------------------------------------------
    */
    
    // ===== USUÁRIOS DO SISTEMA =====
    Route::middleware(['role:admin'])->group(function () {
        //Route::resource('users', UserController::class);
    });
    Route::prefix('users')->name('users.')->middleware(['role:admin'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        
        // Rotas de atividade
        Route::get('/{user}/activity', [UserController::class, 'activity'])->name('activity');
        
        // Rotas de ação
        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        
        // Rotas de senhas temporárias
        Route::get('/{user}/temporary-passwords', [UserController::class, 'temporaryPasswords'])->name('temporary-passwords');
        Route::post('/{user}/invalidate-temporary-passwords', [UserController::class, 'invalidateTemporaryPasswords'])->name('invalidate-temporary-passwords');
    });

    // ===== MENU DIGITAL =====
    Route::middleware(['permission:view_products'])->group(function () {
        Route::get('menu', [DashboardController::class, 'menu'])->name('menu.index');
        Route::get('menu/{category}', [DashboardController::class, 'menuCategory'])->name('menu.category');
    });

    /*
    |--------------------------------------------------------------------------
    | SISTEMA DE BUSCA E NOTIFICAÇÕES
    |--------------------------------------------------------------------------
    */
    
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/api', [SearchController::class, 'api'])->name('search.api');

    // ===== NOTIFICAÇÕES =====
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::get('/api/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unreadCount');
        Route::get('/api/list', [NotificationController::class, 'getNotifications'])->name('list');
        Route::get('/stream', [NotificationController::class, 'stream'])->name('stream');
        Route::get('/check-new', [NotificationController::class, 'checkNew'])->name('checkNew');
    });
});
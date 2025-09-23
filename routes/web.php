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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});
//home
Route::get('/home', [HomeController::class, 'index'])->name('home');
Auth::routes();

// Dashboard
Route::get('dashboard/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Resource Routes
Route::middleware(['auth'])->group(function () {
    // Users Management (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // POS
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.completeCheckout');
    Route::get('/pos/receipt/{saleId}', [POSController::class, 'receipt'])->name('pos.receipt');
    Route::get('/pos/receipt/{saleId}/print', [POSController::class, 'printReceipt'])->name('pos.receipt.print');

    //menu management
    Route::get('menu', [DashboardController::class, 'menu'])->name('menu.index');
    Route::get('menu/{category}', [DashboardController::class, 'menuCategory'])->name('menu.category');
     // Rotas para gerenciamento de mesas
     Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
     Route::get('/tables/create', [TableController::class, 'create'])->name('tables.create');
     Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
     Route::get('/tables/{table}/edit', [TableController::class, 'edit'])->name('tables.edit');
     Route::put('/tables/{table}', [TableController::class, 'update'])->name('tables.update');
     Route::delete('/tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
     
     // Rotas das mesas
    Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
    Route::post('/tables/{table}/update-status', [TableController::class, 'updateStatus'])->name('tables.update-status');
    Route::post('/tables/{table}/create-order', [TableController::class, 'createOrder'])->name('tables.create-order');
    Route::post('/tables/merge', [TableController::class, 'mergeTables'])->name('tables.merge');
    Route::post('/tables/split', [TableController::class, 'splitTables'])->name('tables.split');

    // Rotas dos pedidos
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::post('/orders/{order}/add-item', [OrderController::class, 'addItem'])->name('orders.add-item');
    Route::post('/orders/items/{orderItem}/remove', [OrderController::class, 'removeItem'])->name('orders.remove-item');
    Route::post('/orders/items/{orderItem}/update-status', [OrderController::class, 'updateItemStatus'])->name('orders.update-item-status');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::post('/orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/print-receipt', [OrderController::class, 'printReceipt'])->name('orders.print-receipt');
    Route::post('/orders/complete/{order}', [OrderController::class, 'complete'])->name('orders.complete');
    Route::get('/orders/data/{order}', [OrderController::class, 'getOrderData'])->name('orders.data');
    Route::post('/orders/cancel/{order}', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/print/{order}', [OrderController::class, 'print'])->name('orders.print');
    Route::get('/orders/kitchen', [OrderController::class, 'kitchen'])->name('orders.kitchen');

    // Products & Categories
    Route::resource('products', ProductController::class);
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/{product}/stock-history', [ProductController::class, 'stockHistory'])->name('products.stock-history');
    Route::get('/{product}/sales-data', [ProductController::class, 'salesData'])->name('products.sales-data');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::put('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
    Route::post('products/{product}/stock', [ProductController::class, 'updateStock'])
    ->name('products.stock.update');
    //product export
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::resource('categories', CategoryController::class);
    
    // Sales Management
    Route::resource('sales', SaleController::class);
    Route::get('sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    //post
    Route::post('sales/{sale}/print', [SaleController::class, 'receipt'])->name('sales.print');
    //products.api.list
    Route::get('products/api/list', [ProductController::class, 'apiList'])->name('products.api.list');

    // Rota personalizada para processar venda (chamada pelo JavaScript)
    Route::post('/pos/process-sale', [SaleController::class, 'process_sale'])->name('sales.process');
    // Employees Management
    Route::resource('employees', EmployeeController::class);
    
    // Clients Management
    //Route::resource('clients', ClientController::class);
    Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
    //create
    Route::get('clients/create', [ClientController::class, 'create'])->name('client.create');
    Route::get('clients/{client}/orders', [ClientController::class, 'orders'])->name('clients.orders');
    //clpent search
    Route::get('clients/search', [ClientController::class, 'search'])->name('client.search');
    //store
    Route::post('clients/store', [ClientController::class, 'store'])->name('client.store');
    //edit
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('client.edit');
    //update
    Route::put('clients/{client}', [ClientController::class, 'update'])->name('client.update');
    //destroy
    Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('client.destroy');

    //employes
    Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('employees/store', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::get('employees/{employee}/show', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('employees/search', [EmployeeController::class, 'search'])->name('employees.search');


    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'save'])->name('save');

    //reports
   /*  Route::prefix('reports')->name('reports.')->controller(ReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/sales', 'sales')->name('sales');
        Route::get('/inventory', 'inventory')->name('inventory');
        Route::get('/sales-by-product', 'salesByProduct')->name('salesByProduct');
        Route::get('/sales-by-category', 'salesByCategory')->name('salesByCategory');
        Route::get('/sales-by-payment-method', 'salesByPaymentMethod')->name('salesByPaymentMethod');
        Route::get('/sales-by-date', 'salesByDate')->name('salesByDate');
    }); */
    //expense category
    Route::resource('expense-categories', ExpenseCategoryController::class)->only(['store']);
    // ===== DESPESAS =====
    Route::prefix('expenses')->name('expenses.')->group(function () {
        // Visualizar despesas - todos podem
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::get('/{expense}', [ExpenseController::class, 'show'])->name('show');
        Route::get('/{expense}/details', [ExpenseController::class, 'showData'])->name('details');
        
        // Criar despesas - create_expenses permission
        Route::middleware('permissions:create_expenses')->group(function () {
            Route::get('/create', [ExpenseController::class, 'create'])->name('create');
            Route::post('/', [ExpenseController::class, 'store'])->name('store');
        });
        
        // Editar despesas - edit_expenses permission
        Route::middleware('permissions:edit_expenses')->group(function () {
            Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('edit');
            Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update');
        });
        
        // Deletar despesas - delete_expenses permission
        Route::middleware('permissions:delete_expenses')->group(function () {
            Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
        });
    });
    
    // ===== MOVIMENTAÇÕES DE ESTOQUE =====
    Route::prefix('stock-movements')->name('stock-movements.')->group(function () {
        // Visualizar movimentações - view_stock_movements permission
        Route::middleware('permissions:view_stock_movements')->group(function () {
            Route::get('/', [StockMovementController::class, 'index'])->name('index');
            Route::get('/{stockMovement}', [StockMovementController::class, 'show'])->name('show');
        });
        
        // Criar movimentações - create_stock_movements permission
        Route::middleware('permissions:create_stock_movements')->group(function () {
            Route::get('/create', [StockMovementController::class, 'create'])->name('create');
            Route::post('/', [StockMovementController::class, 'store'])->name('store');
        });
        
        // Gerenciar estoque - manage_stock permission (admin only)
        Route::middleware('permissions:manage_stock')->group(function () {
             // Rota para listagem (index)
        Route::get('/stock-movements', [StockMovementController::class, 'index'])
            ->name('stock-movements.index');
        
        // Rota para mostrar formulário de criação
        Route::get('/stock-movements', [StockMovementController::class, 'create'])
            ->name('create');
        
            Route::get('/stock-movements/{stockMovement}/edit', [StockMovementController::class, 'edit'])
            ->name('edit');
        
        // Rota para armazenar novo movimento
        Route::post('/stock-movements', [StockMovementController::class, 'store'])
            ->name('store');
        // Edit
        
        // Rota para mostrar detalhes de um movimento específico
        Route::get('/stock-movements/{stockMovement}', [StockMovementController::class, 'show'])
            ->name('show');
        
        // Rota para atualizar movimento
        Route::put('/stock-movements/{stockMovement}', [StockMovementController::class, 'update'])
            ->name('update');
        
        // Rota para excluir movimento
        Route::delete('/stock-movements/{stockMovement}', [StockMovementController::class, 'destroy'])
            ->name('destroy');
            });
        });
        
    // ===== RELATÓRIOS =====
        Route::prefix('reports')->name('reports.')->group(function () {
        // ===== DASHBOARD PRINCIPAL =====
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');

        // ===== RELATÓRIOS BÁSICOS =====
        Route::get('/daily-sales', [ReportController::class, 'dailySales'])->name('daily-sales');
        Route::get('/monthly-sales', [ReportController::class, 'monthlySales'])->name('monthly-sales');
        Route::get('/sales-by-product', [ReportController::class, 'salesByProduct'])->name('sales-by-product');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/low-stock', [ReportController::class, 'lowStock'])->name('low-stock');

        // ===== RELATÓRIOS FINANCEIROS =====
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
        Route::get('/cash-flow', [ReportController::class, 'cashFlow'])->name('cash-flow');

        // ===== ANÁLISES AVANÇADAS =====
        Route::get('/customer-profitability', [ReportController::class, 'customerProfitability'])->name('customer-profitability');
        Route::get('/abc-analysis', [ReportController::class, 'abcAnalysis'])->name('abc-analysis');
        Route::get('/period-comparison', [ReportController::class, 'periodComparison'])->name('period-comparison');
        Route::get('/business-insights', [ReportController::class, 'businessInsights'])->name('business-insights');

        // ===== RELATÓRIOS ESPECIALIZADOS =====
        Route::get('/sales-specialized', [ReportController::class, 'salesReport'])->name('sales-specialized');
        Route::get('/expenses-specialized', [ReportController::class, 'expensesReport'])->name('expenses-specialized');
        Route::get('/comparison-specialized', [ReportController::class, 'comparisonReport'])->name('comparison-specialized');

        // ===== EXPORTAÇÕES =====
        Route::get('/export', [ReportController::class, 'export'])->name('export');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export-pdf', [ReportController::class, 'exportPDF'])->name('export.pdf');
        Route::get('/export-csv', [ReportController::class, 'exportCSV'])->name('export.csv');
    });
    // Rotas de busca
        Route::middleware(['auth'])->group(function () {
            
            // Busca completa (página de resultados)
            Route::get('/search', [SearchController::class, 'index'])->name('search.index');
            
            // API de busca rápida (para autocomplete/dropdown)
            Route::get('/search/api', [SearchController::class, 'api'])->name('search.api');
        });

});
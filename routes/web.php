<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

// Global Search
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Notifications
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Language Switcher
Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
//home
Route::get('/home', [HomeController::class, 'index'])->name('home');
// Authentication routes (provided by Laravel)
Auth::routes(['register' => true]); // Disable public registration

// Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
});

// Resource Routes
Route::middleware(['auth'])->group(function () {
    // Users Management (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit_logs.index');
        Route::get('audit-logs/{audit_log}', [AuditLogController::class, 'show'])->name('audit_logs.show');
    });

    // POS
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    Route::post('/pos/hold', [POSController::class, 'hold'])->name('pos.hold'); // Nova rota
    Route::get('/pos/receipt/{sale}', [POSController::class, 'receipt'])->name('pos.receipt');
    Route::get('/pos/receipt/{saleId}/print', [POSController::class, 'printReceipt'])->name('pos.receipt.print');
    Route::get('/pos/held-orders', [POSController::class, 'getHeldOrders'])->name('pos.held-orders');
    Route::get('/pos/tables', [POSController::class, 'getTables'])->name('pos.tables');
    Route::get('/pos/retrieve-order/{order}', [POSController::class, 'retrieveOrder'])->name('pos.retrieve-order');

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
    // Rota GET para mostrar o formulário de criação de pedido a partir da mesa
    Route::post('/tables/{table}/create-order', [TableController::class, 'createOrder'])->name('tables.create-order');
    // Rota POST que cria o pedido (mantém o comportamento existente) — renomeada
    Route::post('/tables/status/{table}', [TableController::class, 'updateStatus'])->name('tables.status');
    Route::post('/tables/store', [TableController::class, 'store'])->name('tables.store');
    Route::delete('/tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
    Route::post('/tables/merge', [TableController::class, 'mergeTables'])->name('tables.merge');
    Route::post('/tables/split', [TableController::class, 'splitTables'])->name('tables.split');

    // Rotas dos pedidos
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
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
    Route::post('/orders/complete/{order}', [OrderController::class, 'complete'])->name('orders.complete.alt');
    Route::get('/orders/data/{order}', [OrderController::class, 'getOrderData'])->name('orders.data');
    Route::post('/orders/cancel/{order}', [OrderController::class, 'cancel'])->name('orders.cancel.alt');
    Route::get('/orders/print/{order}', [OrderController::class, 'print'])->name('orders.print');
    Route::get('/orders/{order}/print', [OrderController::class, 'print'])->name('orders.print.alt');
    Route::get('/orders/kitchen', [OrderController::class, 'kitchen'])->name('orders.kitchen');

    // Products & Categories
    Route::resource('products', ProductController::class);
    // Rotas principais de produtos (CRUD)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Rotas especiais de produtos
    Route::post('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.stock.update');
    Route::get('/products/{product}/stock-history', [ProductController::class, 'stockHistory'])->name('products.stock-history');
    Route::get('/products/{product}/sales-data', [ProductController::class, 'salesData'])->name('products.sales-data');
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');

    // Categorias
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
    // Expenses
    Route::resource('expenses', ExpenseController::class);

    // Employees Management
    Route::get('/employees/payroll', [EmployeeController::class, 'payroll'])->name('employees.payroll');
    Route::post('/employees/{employee}/pay-salary', [EmployeeController::class, 'paySalary'])->name('employees.pay-salary');
    Route::get('employees/search', [EmployeeController::class, 'search'])->name('employees.search');
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




    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.save');

    //reports
    Route::prefix('reports')->name('reports.')->controller(ReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/sales', 'sales')->name('sales');
        Route::get('/inventory', 'inventory')->name('inventory');
        Route::get('/sales-by-product', 'salesByProduct')->name('salesByProduct');
        Route::get('/sales-by-category', 'salesByCategory')->name('salesByCategory');
        Route::get('/sales-by-payment-method', 'salesByPaymentMethod')->name('salesByPaymentMethod');
        Route::get('/sales-by-date', 'salesByDate')->name('salesByDate');
    });

    // Stock Management
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('stock/history', [StockController::class, 'history'])->name('stock.history');
    Route::get('stock/{product}/adjust', [StockController::class, 'adjust'])->name('stock.adjust');
    Route::post('stock/{product}/adjust', [StockController::class, 'storeAdjustment'])->name('stock.store-adjustment');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});
// Restricted Routes with Throttling
// Route::middleware(['auth:web', 'throttle:6,1'])->group(function () {
//     Route::get('/restricted-page', 'RestrictedController@show')->name('restricted-page');
// });
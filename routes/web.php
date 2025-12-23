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
use App\Http\Controllers\DebtController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Customer Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/customer/order/create', [CustomerController::class, 'createOrder'])->name('customer.order.create');
    Route::post('/customer/order', [CustomerController::class, 'storeOrder'])->name('customer.order.store');
    Route::get('/customer/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::put('/customer/profile', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::post('/customer/order/{order}/pay', [CustomerController::class, 'pay'])->name('customer.order.pay');
    Route::post('/customer/order/{order}/cancel', [CustomerController::class, 'cancelOrder'])->name('customer.order.cancel');
    Route::post('/customer/order/{order}/reorder', [CustomerController::class, 'reorder'])->name('customer.order.reorder');
});

// Global Search
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Notifications
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Language Switcher
Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
// Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
});

// Authentication routes (provided by Laravel)
Auth::routes(['register' => true]); // Disable public registration

// Resource Routes
Route::middleware(['auth'])->group(function () {
    // Users Management (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit_logs.index');
        Route::get('audit-logs/{audit_log}', [AuditLogController::class, 'show'])->name('audit_logs.show');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('reports/daily-sales', [ReportController::class, 'dailySales'])->name('reports.dailySales');
        Route::get('reports/monthly-sales', [ReportController::class, 'monthlySales'])->name('reports.monthlySales');
        Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.lowStock');
        Route::get('reports/sales-by-product', [ReportController::class, 'salesByProduct'])->name('reports.salesByProduct');
        Route::get('reports/sales-by-category', [ReportController::class, 'salesByCategory'])->name('reports.salesByCategory');
        Route::get('reports/sales-by-payment-method', [ReportController::class, 'salesByPaymentMethod'])->name('reports.salesByPaymentMethod');
        Route::get('reports/sales-by-date', [ReportController::class, 'salesByDate'])->name('reports.salesByDate');

        // New Advanced Reports
        Route::get('reports/cash-flow', [ReportController::class, 'cashFlow'])->name('reports.cashFlow');
        Route::get('reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profitLoss');
        Route::get('reports/customer-profitability', [ReportController::class, 'customerProfitability'])->name('reports.customerProfitability');
        Route::get('reports/abc-analysis', [ReportController::class, 'abcAnalysis'])->name('reports.abcAnalysis');
        Route::get('reports/period-comparison', [ReportController::class, 'periodComparison'])->name('reports.periodComparison');
        Route::get('reports/business-insights', [ReportController::class, 'businessInsights'])->name('reports.businessInsights');
        Route::get('reports/expenses', [ReportController::class, 'expensesReport'])->name('reports.expensesReport');
        Route::get('reports/comparison', [ReportController::class, 'comparisonReport'])->name('reports.comparisonReport');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');

        // Debts
        Route::resource('debts', DebtController::class);
        Route::post('debts/{debt}/pay', [DebtController::class, 'pay'])->name('debts.pay');
        Route::post('debts/register', [DebtController::class, 'registerDebt'])->name('debts.register');
    });

    // Staff Routes (Admin, Manager, Waiter)
    Route::middleware(['role:admin,manager,waiter'])->group(function () {
        // Home (Staff only)
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        // POS
        Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
        Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
        Route::post('/pos/hold', [POSController::class, 'hold'])->name('pos.hold');
        Route::get('/pos/receipt/{sale}', [POSController::class, 'receipt'])->name('pos.receipt');
        Route::get('/pos/receipt/{saleId}/print', [POSController::class, 'printReceipt'])->name('pos.receipt.print');
        Route::get('/pos/held-orders', [POSController::class, 'getHeldOrders'])->name('pos.held-orders');
        Route::get('/pos/tables', [POSController::class, 'getTables'])->name('pos.tables');
        Route::get('/pos/retrieve-order/{order}', [POSController::class, 'retrieveOrder'])->name('pos.retrieve-order');
        Route::post('/pos/process-sale', [SaleController::class, 'process_sale'])->name('sales.process');

        // Menu management
        Route::get('menu', [DashboardController::class, 'menu'])->name('menu.index');
        Route::get('menu/{category}', [DashboardController::class, 'menuCategory'])->name('menu.category');

        // Tables
        Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
        Route::get('/tables/create', [TableController::class, 'create'])->name('tables.create');
        Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
        Route::get('/tables/{table}/edit', [TableController::class, 'edit'])->name('tables.edit');
        Route::put('/tables/{table}', [TableController::class, 'update'])->name('tables.update');
        Route::delete('/tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
        Route::post('/tables/{table}/update-status', [TableController::class, 'updateStatus'])->name('tables.update-status');
        Route::post('/tables/{table}/create-order', [TableController::class, 'createOrder'])->name('tables.create-order');
        Route::post('/tables/status/{table}', [TableController::class, 'updateStatus'])->name('tables.status');
        Route::post('/tables/store', [TableController::class, 'store'])->name('tables.store');
        Route::post('/tables/merge', [TableController::class, 'mergeTables'])->name('tables.merge');
        Route::post('/tables/split', [TableController::class, 'splitTables'])->name('tables.split');

        // Orders
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::post('/orders/{order}/add-item', [OrderController::class, 'addItem'])->name('orders.add-item');
        Route::post('/orders/items/{orderItem}/remove', [OrderController::class, 'removeItem'])->name('orders.remove-item');
        Route::post('/orders/items/{orderItem}/update-status', [OrderController::class, 'updateItemStatus'])->name('orders.update-item-status');
        Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
        Route::post('/orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
        Route::post('/orders/{order}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/{order}/print-receipt', [OrderController::class, 'printReceipt'])->name('orders.print-receipt');
        Route::post('/orders/complete/{order}', [OrderController::class, 'complete'])->name('orders.complete.alt');
        Route::get('/orders/data/{order}', [OrderController::class, 'getOrderData'])->name('orders.data');
        Route::post('/orders/cancel/{order}', [OrderController::class, 'cancel'])->name('orders.cancel.alt');
        Route::get('/orders/print/{order}', [OrderController::class, 'print'])->name('orders.print');
        Route::get('/orders/{order}/print', [OrderController::class, 'print'])->name('orders.print.alt');
        Route::get('/orders/kitchen', [OrderController::class, 'kitchen'])->name('orders.kitchen');
        Route::post('/orders/{order}/approve-cancellation', [OrderController::class, 'approveCancellation'])->name('orders.approve-cancellation');
        Route::post('/orders/{order}/reject-cancellation', [OrderController::class, 'rejectCancellation'])->name('orders.reject-cancellation');

        // Products & Categories
        Route::resource('products', ProductController::class);
        Route::post('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.stock.update');
        Route::get('/products/{product}/stock-history', [ProductController::class, 'stockHistory'])->name('products.stock-history');
        Route::get('/products/{product}/sales-data', [ProductController::class, 'salesData'])->name('products.sales-data');
        Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
        Route::resource('categories', CategoryController::class);

        // Sales
        Route::resource('sales', SaleController::class);
        Route::get('sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
        Route::post('sales/{sale}/print', [SaleController::class, 'receipt'])->name('sales.print');

        // Expenses
        Route::resource('expenses', ExpenseController::class);

        // Employees
        Route::get('/employees/payroll', [EmployeeController::class, 'payroll'])->name('employees.payroll');
        Route::post('/employees/{employee}/pay-salary', [EmployeeController::class, 'paySalary'])->name('employees.pay-salary');
        Route::get('employees/search', [EmployeeController::class, 'search'])->name('employees.search');
        Route::resource('employees', EmployeeController::class);

        // Clients
        Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('clients/create', [ClientController::class, 'create'])->name('client.create');
        Route::get('clients/{client}/orders', [ClientController::class, 'orders'])->name('clients.orders');
        Route::get('clients/search', [ClientController::class, 'search'])->name('client.search');
        Route::post('clients/store', [ClientController::class, 'store'])->name('client.store');
        Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('client.edit');
        Route::put('clients/{client}', [ClientController::class, 'update'])->name('client.update');
        Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('client.destroy');

        // Stock
        Route::get('stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('stock/history', [StockController::class, 'history'])->name('stock.history');
        Route::get('stock/{product}/adjust', [StockController::class, 'adjust'])->name('stock.adjust');
        Route::post('stock/{product}/adjust', [StockController::class, 'storeAdjustment'])->name('stock.store-adjustment');

        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Profile Management (All authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.save');
});
// Restricted Routes with Throttling
// Route::middleware(['auth:web', 'throttle:6,1'])->group(function () {
//     Route::get('/restricted-page', 'RestrictedController@show')->name('restricted-page');
// });
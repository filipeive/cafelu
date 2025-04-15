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
// Authentication routes (provided by Laravel)
Auth::routes(['register' => true]); // Disable public registration

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
    Route::post('/orders/cancel/{order}', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/print/{order}', [OrderController::class, 'print'])->name('orders.print');
    Route::get('/orders/kitchen', [OrderController::class, 'kitchen'])->name('orders.kitchen');
    // Products & Categories
    Route::resource('products', ProductController::class);
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::put('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
    Route::resource('categories', CategoryController::class);
    
    // Sales Management
    Route::resource('sales', SaleController::class);
    Route::get('sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    //post
    Route::post('sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');
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

    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    //reports
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('reports/stock/low', [ReportController::class, 'lowStock'])->name('reports.stock.low');
    Route::get('reports/stock/expired', [ReportController::class, 'expiredStock'])->name('reports.stock.expired');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    Route::resource('stock', StockController::class)->except(['show']);

    // Stock Management Pages
    Route::get('/stock/create', [StockController::class, 'create'])->name('stock.create');
    Route::get('/stock/generate-qrcode', [StockController::class, 'generateBarcode'])->name('stock.generate-qrcode');
    Route::get('/stock/{id}/batches/view', [StockController::class, 'viewBatches'])->name('stock.batches.view');
    Route::get('/stock/batch/{id}/edit', [StockController::class, 'editBatch'])->name('stock.batch.edit');
    Route::get('/stock/batch/{id}/barcode', [StockController::class, 'viewBarcode'])->name('stock.batch.barcode');

    // Stock Actions
    Route::post('/stock/find-barcode', [StockController::class, 'findByBarcode'])->name('stock.find-barcode');
    Route::get('/stock/{id}/batches', [StockController::class, 'getBatches'])->name('stock.batches');
    Route::put('/stock/batch/{id}', [StockController::class, 'updateBatch'])->name('stock.batch.update');
    Route::delete('/stock/batch/{id}', [StockController::class, 'destroyBatch'])->name('stock.batch.destroy');
    
    // Sales
    Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [App\Http\Controllers\SalesController::class, 'create'])->name('sales.create');
    Route::post('/sales', [App\Http\Controllers\SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [App\Http\Controllers\SalesController::class, 'show'])->name('sales.show');
    
    // Reports
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/inventory', [App\Http\Controllers\ReportController::class, 'inventory'])->name('reports.inventory');
});

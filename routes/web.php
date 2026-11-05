<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/about', function () {
    return 'Ini page about POS Barokah Mart';
});

Route::get('/login', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('login.create');

    Route::post('/login', [LoginController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchases', PurchaseController::class)->except(['edit', 'update']);
    Route::resource('users', UserController::class);
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('report.sales');
    Route::get('/reports/sales/export', [ReportController::class, 'export'])->name('report.export');
});
 
Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/{transaction}/struk', [PosController::class, 'struk'])->name('pos.struk');
    Route::get('/pos/history', [PosController::class, 'history'])->name('pos.history');
});


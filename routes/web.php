<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PurchasesController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Inventories Routes
    Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');

    // Sales Routes
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');

    // Purchases Routes
    Route::get('/purchases', [PurchasesController::class, 'index'])->name('purchases.index');
});



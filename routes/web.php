<?php

use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\Dashboard\DashboardController;
use App\Http\Controllers\Web\DataMaster\Inventory\InventoryController;
use App\Http\Controllers\Web\Transaction\PurchasesController;
use App\Http\Controllers\Web\Transaction\SalesController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('data-master')->group(function () {
        Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
        Route::get('/purchases', [PurchasesController::class, 'index'])->name('purchases.index');
    });
});

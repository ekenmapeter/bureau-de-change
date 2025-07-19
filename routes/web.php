<?php

use App\Http\Controllers\Admin\CashDepositController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Cashier\DashboardController as CashierDashboardController;
use App\Http\Controllers\Cashier\CashDepositController as CashierCashDepositController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\OpeningBalanceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->middleware('auth')->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/api/wallets', [WalletController::class, 'api'])->name('api.wallets');
    Route::resource('opening-balances', OpeningBalanceController::class)->only(['create', 'store', 'index', 'edit', 'update', 'destroy']);
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/wallets', [WalletController::class, 'index'])->name('wallets.index');
    Route::get('/wallets/download', [WalletController::class, 'downloadHistory'])->name('wallets.download-history');
    Route::get('/rates', [RateController::class, 'index'])->name('rates.index');
    Route::post('/rates', [RateController::class, 'update'])->name('rates.update');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/clear-logs', [SettingsController::class, 'clearLogs'])->name('settings.clear-logs');
    Route::resource('users', UserController::class);
    Route::resource('cash-deposits', CashDepositController::class)->only(['index', 'store']);
    Route::resource('transactions', TransactionController::class)->middleware(\App\Http\Middleware\CheckOpeningBalance::class);
});

// Manager Routes
Route::middleware(['auth'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/wallets', [WalletController::class, 'index'])->name('wallets.index');
    Route::get('/rates', [RateController::class, 'index'])->name('rates.index');
    Route::post('/rates', [RateController::class, 'update'])->name('rates.update');
    Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::resource('transactions', TransactionController::class)->middleware(\App\Http\Middleware\CheckOpeningBalance::class);
});

// Cashier Routes
Route::middleware(['auth'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/dashboard', [CashierDashboardController::class, 'index'])->name('dashboard');
    Route::get('/wallets', [WalletController::class, 'index'])->name('wallets.index');
    Route::resource('transactions', TransactionController::class)->middleware(\App\Http\Middleware\CheckOpeningBalance::class);
    Route::resource('cash-deposits', CashierCashDepositController::class)->only(['index', 'store']);
});

require __DIR__ . '/auth.php';

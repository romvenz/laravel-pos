<?php

use App\Http\Controllers\SaleConfirmationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('sales.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::resource('products', ProductController::class)->except('show');
    Route::resource('customers', CustomerController::class)->except('show');
    Route::resource('users', StaffController::class)->except('show');

    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
});

Route::get('/sales/{sale}/confirm', [SaleConfirmationController::class, 'show'])
    ->middleware('signed')
    ->name('sales.confirm.show');

Route::post('/sales/{sale}/confirm', [SaleConfirmationController::class, 'store'])
    ->middleware('signed')
    ->name('sales.confirm.store');
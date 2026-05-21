<?php
// routes/client.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\InvoiceController;

Route::middleware(['auth', 'verified', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);

    // Invoices
    Route::get('invoices',              [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}',    [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('invoices/{invoice}/pay',[InvoiceController::class, 'pay'])->name('invoices.pay');
});
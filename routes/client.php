<?php
// routes/client.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\InvoiceController;
use App\Http\Controllers\Client\DeliverableController;


Route::middleware(['auth', 'verified', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);

   Route::get('projects',                [DeliverableController::class, 'index'])->name('projects.index');
     Route::get('projects/{project}/result',[DeliverableController::class, 'show'])->name('projects.result');

    // Invoices
    Route::get('invoices',              [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}',    [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('invoices/{invoice}/pay',[InvoiceController::class, 'pay'])->name('invoices.pay');
});
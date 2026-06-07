<?php
// routes/admin.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\DeliverableController;

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
    Route::patch('orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    Route::patch('orders/{order}/reject',  [OrderController::class, 'reject'])->name('orders.reject');

    Route::resource('projects', ProjectController::class);
    Route::post('projects/{project}/members',         [ProjectController::class, 'addMember'])->name('projects.members.add');
    Route::delete('projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.members.remove');
    Route::patch('projects/{project}/progress',       [ProjectController::class, 'updateProgress'])->name('projects.progress');

    Route::resource('projects.tasks', TaskController::class)->shallow();
    Route::patch('projects/{project}/finalize', [DeliverableController::class, 'finalize'])->name('projects.finalize');

    // Invoices
    Route::resource('invoices', InvoiceController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::post('projects/{project}/invoices',        [InvoiceController::class, 'store'])->name('projects.invoices.store');

    Route::get('orders/{order}/invoice/create', [OrderController::class, 'createInvoice'])->name('orders.invoice');
    Route::post('orders/{order}/invoice',       [OrderController::class, 'storeInvoice'])->name('orders.invoice.store');

    // Payments
    Route::get('payments',                            [PaymentController::class, 'index'])->name('payments.index');
    Route::patch('payments/{payment}/confirm',        [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::patch('payments/{payment}/reject',         [PaymentController::class, 'reject'])->name('payments.reject');
});

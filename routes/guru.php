<?php
// routes/guru.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\DashboardController;
use App\Http\Controllers\Guru\ProjectController;
use App\Http\Controllers\Guru\ReviewController;

Route::middleware(['auth', 'verified', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects (read only)
    Route::get('projects',      [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

    // Task management oleh guru
    Route::resource('projects.tasks', \App\Http\Controllers\Guru\TaskController::class)->shallow();

    // Reviews
    Route::get('reviews',                          [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('progresses/{progress}/review',    [ReviewController::class, 'store'])->name('reviews.store');
});
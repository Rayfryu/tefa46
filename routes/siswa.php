<?php
// routes/siswa.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\ProjectController;
use App\Http\Controllers\Siswa\TaskController;

Route::middleware(['auth', 'verified', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects
    Route::get('projects',           [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

    // Tasks
    Route::get('tasks',              [TaskController::class, 'index'])->name('tasks.index');
    Route::get('tasks/{task}',       [TaskController::class, 'show'])->name('tasks.show');
    Route::patch('tasks/{task}/start',[TaskController::class, 'start'])->name('tasks.start');

    // Progress upload
    Route::post('tasks/{task}/progress', [TaskController::class, 'uploadProgress'])->name('tasks.progress.store');
});
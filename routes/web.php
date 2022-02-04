<?php

use App\Http\Controllers\AudioController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('/audios')->group(function () {
        Route::get('', [AudioController::class, 'index'])->name('audios');
        Route::get('create', [AudioController::class, 'create']);
        Route::get('{audio}/edit', [AudioController::class, 'edit']);
        Route::put('{audio}', [AudioController::class, 'update']);
        Route::delete('{audio}', [AudioController::class, 'destroy']);
    });

    Route::prefix('/topics')->group(function () {
        Route::get('', [TopicController::class, 'index'])->name('topics');
        Route::delete('{topic}', [TopicController::class, 'destroy']);
    });
});

require __DIR__ . '/auth.php';

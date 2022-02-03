<?php

use App\Http\Controllers\AudioController;
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
        Route::get('', [AudioController::class, 'index']);
        Route::get('create', [AudioController::class, 'create']);
        Route::get('{audio}/edit', [AudioController::class, 'edit']);
    });
});

require __DIR__ . '/auth.php';

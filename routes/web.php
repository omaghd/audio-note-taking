<?php

use App\Http\Controllers\AudioController;
use App\Http\Controllers\AudioTopicController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Models\Topic;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('audios');
})->name('root');

Route::middleware(['auth'])->group(function () {
    Route::prefix('/audios')->group(function () {
        Route::get('', [AudioController::class, 'index'])->name('audios');
        Route::middleware(['can:is-admin'])->group(function () {
            Route::get('create', [AudioController::class, 'create'])->name('audios.create');
            Route::post('', [AudioController::class, 'store']);
            Route::get('{audio}/edit', [AudioController::class, 'edit'])->name('audios.edit');
            Route::put('{audio}', [AudioController::class, 'update'])->name('audios.update');
            Route::delete('{audio}', [AudioController::class, 'destroy'])->name('audios.destroy');
        });

        Route::get('{audio}/topics', [AudioTopicController::class, 'index'])->name('audios.topics');
        Route::post('{audio}/topics', [AudioTopicController::class, 'store'])->name('audios.topics.create');
    });

    Route::prefix('/topics')->group(function () {
        Route::get('', [TopicController::class, 'index'])->name('topics');

        Route::delete('{topic}', [TopicController::class, 'destroy'])
            ->name('topics.destroy')
            ->can('delete-topic', 'topic');
    });

    Route::group(['prefix' => '/users', 'middleware' => ['can:is-admin']], function () {
        Route::get('', [UserController::class, 'index'])->name('users');
        Route::get('create', [UserController::class, 'create'])->name('users.create');
        Route::post('', [UserController::class, 'store']);
        Route::get('{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

require __DIR__ . '/auth.php';

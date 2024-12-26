<?php

use App\Http\Controllers\V1\AppController;
use App\Http\Controllers\V1\AuthController;
use Illuminate\Support\Facades\Route;

// Chráněno jen App tokenem: (zde např. registrace uživatele)
Route::middleware(['app.token'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['app.token'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});


Route::prefix('/apps')->middleware(['app.token', 'app.master'])->group(function () {
    Route::post('/', [AppController::class, 'store']);
    Route::get('/', [AppController::class, 'index']);
    Route::get('/{id}', [AppController::class, 'show']);
    Route::delete('/{id}', [AppController::class, 'destroy']);
    Route::patch('/{id}', [AppController::class, 'update']);
    Route::post('/{id}/refresh', [AppController::class, 'refresh']);
});

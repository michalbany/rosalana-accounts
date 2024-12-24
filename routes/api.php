<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Chráněno jen App tokenem: (zde např. registrace uživatele)
Route::middleware(['app.token'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['app.token', 'jwt.auth'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/refresh', [AuthController::class, 'refresh']);

Route::prefix('/apps')->middleware(['app.master'])->group(function () {
    Route::post('/register', [AppController::class, 'store']);
    Route::get('/', [AppController::class, 'index']);
    Route::get('/{token}', [AppController::class, 'show']);
    Route::delete('/{token}', [AppController::class, 'destroy']);
});

<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::any('/', function () {
    return ['Rosalana Accounts' => config('app.version')];
});

Route::prefix('v1')->group(function () {
    require __DIR__.'/api_v1.php';
});

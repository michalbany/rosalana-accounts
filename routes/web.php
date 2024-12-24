<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Rosalana Accounts' => config('app.version')];
});

// require __DIR__.'/auth.php';

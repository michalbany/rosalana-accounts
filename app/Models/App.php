<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $fillable = [
        'name',
        'token',
        'url',
    ];

    protected $hidden = [
        'token',
    ];
}

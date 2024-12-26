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

    protected $appends = [
        'master',
    ];

    public function getMasterAttribute(): bool
    {
        return $this->token === env('ROSALANA_MASTER_TOKEN', 'master-token');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'market',
        'value',
        'date',
    ];

    protected $dates = [
        'date',
    ];
}

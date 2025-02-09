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

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_purchases')->withPivot('quantity', 'price', 'total');
    }
}

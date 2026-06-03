<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'price',
        'price_raw',
        'image',
        'category',
        'badge',
        'description',
        'features',
        'sizes',
    ];

    protected $casts = [
        'features' => 'array',
        'sizes' => 'array',
    ];
}

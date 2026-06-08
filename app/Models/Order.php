<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number',
        'customer_name',
        'customer_whatsapp',
        'customer_address',
        'product_id',
        'quantity',
        'size',
        'shipping_cost',
        'total_price',
        'status',
        'payment_method',
        'courier',
        'notes',
    ];

    /**
     * Get the product that was ordered.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

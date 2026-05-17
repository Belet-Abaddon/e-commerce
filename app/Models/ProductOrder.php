<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductOrder extends Pivot
{
    use HasFactory;
    protected $table = 'product_orders';

    protected $fillable = [
        'order_id',
        'product_id'
    ];
}

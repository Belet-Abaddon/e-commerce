<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductOrder extends Pivot
{
    protected $table = 'product_order';

    protected $fillable = [
        'order_id',
        'product_id'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PromotionProduct extends Pivot
{
    protected $table = 'promotion_product';

    protected $fillable = [
        'promotion_id',
        'product_id',
        'percentage',
        'description'
    ];
}
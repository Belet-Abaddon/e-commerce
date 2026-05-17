<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PromotionProduct extends Pivot
{
    use HasFactory;
    protected $table = 'promotion_products';

    protected $fillable = [
        'promotion_id',
        'product_id',
        'percentage',
        'description'
    ];
}
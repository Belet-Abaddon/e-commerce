<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promotion extends Model
{
    protected $fillable = ['promotion_name', 'start_date', 'end_date'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promotion_product')
                    ->withPivot('id', 'percentage', 'description')
                    ->withTimestamps();
    }
}
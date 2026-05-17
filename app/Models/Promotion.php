<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Promotion extends Model
{
    use HasFactory;
    protected $fillable = ['promotion_name', 'start_date', 'end_date'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promotion_products')
                    ->withPivot('id', 'percentage', 'description')
                    ->withTimestamps();
    }
}
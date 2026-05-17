<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    // Updated to include 'status'
    protected $fillable = ['name', 'description', 'price', 'product_type_id', 'brand_id', 'status'];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'product_orders');
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'promotion_products')
                    ->withPivot('percentage', 'description');
    }
}
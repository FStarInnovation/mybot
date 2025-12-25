<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'source_id',
        'brand',
        'price',
        'original_price',
        'discount',
        'pharmacy',
        'url',
        'description',
        'category_id',
        'regular_price',
        'discount_pct',
        'promo_unit_price',
        'dosage_form',
        'dosage_strength',
        'units_per_pack',
        'unit_label',
        'source_site',
        'promotion_type',
        'promotion_details',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'regular_price' => 'decimal:2',
        'discount_pct' => 'decimal:2',
        'promo_unit_price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}

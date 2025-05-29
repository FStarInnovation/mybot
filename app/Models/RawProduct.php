<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Attributes\Casts\Attribute;
use Illuminate\Database\Eloquent\Casts\AsArrayObject as CastsAsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawProduct extends Model
{
    use HasFactory;

    protected $table = 'raw_products';

    protected $fillable = [
        'data',
        'metadata',
        'gtin',
        'hashtag',
        'price',
        'price_num',
        'timestamp',
        'title',
        'url',
        'embedding',
        'processed_at',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
        'metadata' => 'array',
        'price_num' => 'decimal:2',
        'embedding' => 'array', // pgvector returns string; adapt later if using custom cast
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

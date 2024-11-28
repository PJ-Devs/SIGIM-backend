<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'stock',
        'supplier_price',
        'sale_price',
        'thumbnail',
        'barcode',
        'minimal_safe_stock',
        'discount',
        'is_favorite',
        'enterprise_id',
        'category_id',
        'supplier_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_products', 'product_id', 'user_id')->withPivot('quantity', 'created_at', 'updated_at')->withTimestamps();
    }
}

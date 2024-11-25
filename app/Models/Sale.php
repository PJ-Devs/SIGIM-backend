<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'price',
        'discount',
        'total_price',
        'invoice_id',
        'client_id',
        'product_id'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

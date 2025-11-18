<?php

namespace App\Modules\POS\Models;

use App\Modules\Core\Traits\HasUuid;
use App\Modules\Inventory\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosOrderItem extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'pos_order_id',
        'product_id',
        'product_name',
        'product_code',
        'quantity',
        'unit_price',
        'discount_type', // fixed, percentage
        'discount_value',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'subtotal',
        'total',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(PosOrder::class, 'pos_order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

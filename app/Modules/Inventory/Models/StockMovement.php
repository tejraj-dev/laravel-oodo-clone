<?php

namespace App\Modules\Inventory\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory, HasUuid, HasCompany;

    protected $fillable = [
        'company_id',
        'product_id',
        'warehouse_id',
        'type',
        'quantity',
        'reference_type',
        'reference_id',
        'movement_date',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'movement_date' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

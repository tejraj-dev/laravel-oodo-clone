<?php

namespace App\Modules\Manufacturing\Models;

use App\Modules\Inventory\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'product_id',
        'quantity_required',
        'quantity_consumed',
        'unit_cost',
        'total_cost',
    ];

    protected $casts = [
        'quantity_required' => 'decimal:2',
        'quantity_consumed' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

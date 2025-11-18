<?php

namespace App\Modules\Manufacturing\Models;

use App\Modules\Inventory\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BomItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bom_id',
        'product_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'scrap_percent',
        'sequence',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'scrap_percent' => 'decimal:2',
        'sequence' => 'integer',
    ];

    public function bom(): BelongsTo
    {
        return $this->belongsTo(BillOfMaterials::class, 'bom_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

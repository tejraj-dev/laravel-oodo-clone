<?php

namespace App\Modules\Manufacturing\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasStatus;
use App\Modules\Core\Traits\HasUuid;
use App\Modules\Inventory\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillOfMaterials extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes;

    protected $table = 'bill_of_materials';

    protected $fillable = [
        'company_id',
        'product_id',
        'bom_number',
        'name',
        'version',
        'quantity',
        'unit_cost',
        'total_cost',
        'is_active',
        'status',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BomItem::class, 'bom_id');
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'bom_id');
    }
}

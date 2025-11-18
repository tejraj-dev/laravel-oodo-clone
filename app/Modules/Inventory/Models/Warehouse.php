<?php

namespace App\Modules\Inventory\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasStatus;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes;

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'type',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'email',
        'manager_id',
        'is_active',
        'status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function manager(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }

    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class);
    }

    public function stockTransfers(): HasMany
    {
        return $this->hasMany(StockTransfer::class, 'from_warehouse_id');
    }
}

<?php

namespace App\Modules\Inventory\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasStatus;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model implements HasMedia
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, InteractsWithMedia, HasSlug;

    protected $fillable = [
        'company_id',
        'category_id',
        'sku',
        'barcode',
        'name',
        'slug',
        'description',
        'type',
        'unit_of_measure',
        'cost_price',
        'selling_price',
        'tax_percent',
        'is_active',
        'track_inventory',
        'min_stock_level',
        'max_stock_level',
        'reorder_level',
        'reorder_quantity',
        'weight',
        'dimensions',
        'status',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'is_active' => 'boolean',
        'track_inventory' => 'boolean',
        'min_stock_level' => 'decimal:2',
        'max_stock_level' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'reorder_quantity' => 'decimal:2',
        'weight' => 'decimal:2',
        'dimensions' => 'array',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}

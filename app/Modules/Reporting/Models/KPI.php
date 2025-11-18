<?php

namespace App\Modules\Reporting\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class KPI extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $table = 'kpis';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'category', // sales, finance, operations, hr, customer
        'metric_type', // count, sum, average, percentage, ratio
        'data_source',
        'calculation_formula', // JSON formula configuration
        'target_value',
        'current_value',
        'unit', // currency, percentage, number, etc.
        'trend', // up, down, stable
        'comparison_period', // daily, weekly, monthly, quarterly, yearly
        'color_rules', // JSON color coding rules (red/yellow/green)
        'is_active',
    ];

    protected $casts = [
        'calculation_formula' => 'array',
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'color_rules' => 'array',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'category', 'current_value', 'target_value'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function widgets(): HasMany
    {
        return $this->hasMany(Widget::class);
    }
}

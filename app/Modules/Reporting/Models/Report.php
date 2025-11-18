<?php

namespace App\Modules\Reporting\Models;

use App\Models\User;
use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Report extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'user_id',
        'report_template_id',
        'name',
        'description',
        'report_type', // tabular, chart, pivot, summary, custom
        'data_source', // sales, purchases, inventory, crm, hr, accounting, etc.
        'query_config', // JSON configuration for query building
        'filters', // JSON filters configuration
        'columns', // JSON columns configuration
        'grouping', // JSON grouping configuration
        'sorting', // JSON sorting configuration
        'chart_type', // line, bar, pie, doughnut, area, scatter
        'chart_config', // JSON chart configuration
        'is_public', // Public or private report
        'is_favorite',
        'is_active',
    ];

    protected $casts = [
        'query_config' => 'array',
        'filters' => 'array',
        'columns' => 'array',
        'grouping' => 'array',
        'sorting' => 'array',
        'chart_config' => 'array',
        'is_public' => 'boolean',
        'is_favorite' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'report_type', 'data_source', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }

    public function scheduledReports(): HasMany
    {
        return $this->hasMany(ScheduledReport::class);
    }
}

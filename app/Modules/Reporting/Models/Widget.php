<?php

namespace App\Modules\Reporting\Models;

use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Widget extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'dashboard_id',
        'report_id',
        'kpi_id',
        'name',
        'widget_type', // chart, metric, table, kpi, custom
        'position_x',
        'position_y',
        'width',
        'height',
        'config', // JSON widget configuration
        'refresh_interval',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public function dashboard(): BelongsTo
    {
        return $this->belongsTo(Dashboard::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function kpi(): BelongsTo
    {
        return $this->belongsTo(KPI::class);
    }
}

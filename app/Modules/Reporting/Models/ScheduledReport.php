<?php

namespace App\Modules\Reporting\Models;

use App\Models\User;
use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ScheduledReport extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'report_id',
        'user_id',
        'name',
        'schedule_type', // daily, weekly, monthly, quarterly, yearly, custom
        'schedule_config', // JSON schedule configuration (cron expression)
        'recipients', // JSON array of email addresses
        'export_format', // pdf, excel, csv, json
        'last_run_at',
        'next_run_at',
        'is_active',
    ];

    protected $casts = [
        'schedule_config' => 'array',
        'recipients' => 'array',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'schedule_type', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

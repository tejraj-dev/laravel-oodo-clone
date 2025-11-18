<?php

namespace App\Modules\Projects\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasStatus;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timesheet extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes;

    protected $fillable = [
        'company_id',
        'project_id',
        'task_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'hours',
        'description',
        'billable',
        'hourly_rate',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'hours' => 'decimal:2',
        'billable' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

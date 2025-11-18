<?php

namespace App\Modules\Approvals\Models;

use App\Models\Traits\HasCompany;
use App\Models\Traits\HasStatus;
use App\Models\Traits\HasUuid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ApprovalDelegate extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'delegator_id',
        'delegate_id',
        'approval_workflow_id',
        'reason',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['delegator_id', 'delegate_id', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function delegator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegator_id');
    }

    public function delegate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegate_id');
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'approval_workflow_id');
    }

    public function isActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now()->toDateString();

        if ($this->start_date && $now < $this->start_date->toDateString()) {
            return false;
        }

        if ($this->end_date && $now > $this->end_date->toDateString()) {
            return false;
        }

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }
}

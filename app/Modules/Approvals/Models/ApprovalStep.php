<?php

namespace App\Modules\Approvals\Models;

use App\Models\Traits\HasCompany;
use App\Models\Traits\HasUuid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ApprovalStep extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'approval_workflow_id',
        'name',
        'description',
        'step_order',
        'approver_type',
        'approver_id',
        'role_id',
        'required_approvals',
        'allow_delegation',
        'sla_hours',
        'is_active',
    ];

    protected $casts = [
        'allow_delegation' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'step_order', 'approver_type'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'approval_workflow_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(ApprovalAction::class);
    }
}

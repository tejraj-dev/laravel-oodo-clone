<?php

namespace App\Modules\Approvals\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasStatus;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ApprovalWorkflow extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'entity_type',
        'workflow_type',
        'is_sequential',
        'require_all_approvers',
        'auto_approve_threshold',
        'auto_reject_threshold',
        'escalation_enabled',
        'escalation_hours',
        'notification_enabled',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'is_sequential' => 'boolean',
        'require_all_approvers' => 'boolean',
        'escalation_enabled' => 'boolean',
        'notification_enabled' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'entity_type', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function steps(): HasMany
    {
        return $this->hasMany(ApprovalStep::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(ApprovalRule::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class);
    }

    public function scopeByEntityType($query, string $entityType)
    {
        return $query->where('entity_type', $entityType);
    }
}

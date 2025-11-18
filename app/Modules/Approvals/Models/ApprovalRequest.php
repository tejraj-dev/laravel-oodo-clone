<?php

namespace App\Modules\Approvals\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ApprovalRequest extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'approval_workflow_id',
        'requester_id',
        'request_number',
        'subject',
        'description',
        'entity_type',
        'entity_id',
        'current_step_id',
        'priority',
        'status',
        'submitted_at',
        'completed_at',
        'approved_at',
        'rejected_at',
        'cancelled_at',
        'total_steps',
        'completed_steps',
        'rejected_by',
        'rejection_reason',
        'metadata',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['request_number', 'status', 'current_step_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'approval_workflow_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(ApprovalStep::class, 'current_step_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(ApprovalAction::class);
    }

    public function entity()
    {
        return $this->morphTo('entity');
    }

    public function approve(User $user, string $comments = null): void
    {
        $this->actions()->create([
            'company_id' => $this->company_id,
            'approval_step_id' => $this->current_step_id,
            'user_id' => $user->id,
            'action' => 'approved',
            'comments' => $comments,
            'action_at' => now(),
        ]);

        $this->moveToNextStep();
    }

    public function reject(User $user, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => $user->id,
            'rejection_reason' => $reason,
        ]);

        $this->actions()->create([
            'company_id' => $this->company_id,
            'approval_step_id' => $this->current_step_id,
            'user_id' => $user->id,
            'action' => 'rejected',
            'comments' => $reason,
            'action_at' => now(),
        ]);
    }

    protected function moveToNextStep(): void
    {
        $this->increment('completed_steps');

        if ($this->completed_steps >= $this->total_steps) {
            $this->update([
                'status' => 'approved',
                'approved_at' => now(),
                'completed_at' => now(),
            ]);
        } else {
            $nextStep = $this->workflow->steps()
                ->where('step_order', '>', $this->currentStep->step_order)
                ->orderBy('step_order')
                ->first();

            if ($nextStep) {
                $this->update([
                    'current_step_id' => $nextStep->id,
                    'status' => 'pending',
                ]);
            }
        }
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}

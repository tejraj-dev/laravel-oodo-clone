<?php

namespace App\Modules\Approvals\Models;

use App\Models\Traits\HasCompany;
use App\Models\Traits\HasStatus;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ApprovalRule extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'approval_workflow_id',
        'name',
        'description',
        'condition_field',
        'condition_operator',
        'condition_value',
        'rule_type',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'condition_field', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'approval_workflow_id');
    }

    public function evaluate(array $data): bool
    {
        $fieldValue = data_get($data, $this->condition_field);

        return match ($this->condition_operator) {
            'equals' => $fieldValue == $this->condition_value,
            'not_equals' => $fieldValue != $this->condition_value,
            'greater_than' => $fieldValue > $this->condition_value,
            'less_than' => $fieldValue < $this->condition_value,
            'greater_than_or_equal' => $fieldValue >= $this->condition_value,
            'less_than_or_equal' => $fieldValue <= $this->condition_value,
            'contains' => str_contains($fieldValue, $this->condition_value),
            'starts_with' => str_starts_with($fieldValue, $this->condition_value),
            'ends_with' => str_ends_with($fieldValue, $this->condition_value),
            default => false,
        };
    }
}

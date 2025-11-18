<?php

namespace App\Modules\Email\Models;

use App\Models\Traits\HasCompany;
use App\Models\Traits\HasStatus;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AutoResponder extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'email_template_id',
        'name',
        'description',
        'trigger_type',
        'trigger_conditions',
        'delay_minutes',
        'subject',
        'body_html',
        'body_text',
        'from_name',
        'from_email',
        'reply_to',
        'max_sends_per_recipient',
        'is_active',
        'sent_count',
        'last_sent_at',
    ];

    protected $casts = [
        'trigger_conditions' => 'array',
        'is_active' => 'boolean',
        'last_sent_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'trigger_type', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    public function shouldTrigger(array $context): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Simple condition matching - can be enhanced
        foreach ($this->trigger_conditions as $key => $value) {
            if (!isset($context[$key]) || $context[$key] != $value) {
                return false;
            }
        }

        return true;
    }

    public function incrementSentCount(): void
    {
        $this->increment('sent_count');
        $this->update(['last_sent_at' => now()]);
    }

    public function scopeByTrigger($query, string $triggerType)
    {
        return $query->where('trigger_type', $triggerType);
    }
}

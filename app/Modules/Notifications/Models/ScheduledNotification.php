<?php

namespace App\Modules\Notifications\Models;

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

class ScheduledNotification extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'user_id',
        'notification_template_id',
        'name',
        'type',
        'channels',
        'title',
        'message',
        'data',
        'action_url',
        'action_text',
        'icon',
        'priority',
        'schedule_type',
        'scheduled_at',
        'recurrence_rule',
        'recipients',
        'recipient_type',
        'last_sent_at',
        'next_send_at',
        'send_count',
        'max_send_count',
        'is_active',
        'status',
    ];

    protected $casts = [
        'channels' => 'array',
        'data' => 'array',
        'recipients' => 'array',
        'scheduled_at' => 'datetime',
        'last_sent_at' => 'datetime',
        'next_send_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'schedule_type', 'scheduled_at', 'status', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class, 'notification_template_id');
    }

    public function markAsSent(): void
    {
        $this->update([
            'last_sent_at' => now(),
            'send_count' => $this->send_count + 1,
            'next_send_at' => $this->calculateNextSendTime(),
        ]);
    }

    public function calculateNextSendTime(): ?string
    {
        if ($this->schedule_type === 'once') {
            return null;
        }

        if ($this->max_send_count && $this->send_count >= $this->max_send_count) {
            return null;
        }

        // Simple recurrence logic - can be enhanced with cron expressions
        return match ($this->schedule_type) {
            'daily' => now()->addDay()->toDateTimeString(),
            'weekly' => now()->addWeek()->toDateTimeString(),
            'monthly' => now()->addMonth()->toDateTimeString(),
            'yearly' => now()->addYear()->toDateTimeString(),
            default => null,
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending')
            ->where('is_active', true)
            ->where('next_send_at', '<=', now());
    }

    public function scopeByScheduleType($query, string $type)
    {
        return $query->where('schedule_type', $type);
    }
}

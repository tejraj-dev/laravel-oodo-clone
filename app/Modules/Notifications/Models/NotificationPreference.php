<?php

namespace App\Modules\Notifications\Models;

use App\Models\Traits\HasCompany;
use App\Models\Traits\HasUuid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NotificationPreference extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'user_id',
        'notification_type',
        'channel_database',
        'channel_email',
        'channel_sms',
        'channel_push',
        'channel_slack',
        'frequency',
        'quiet_hours_start',
        'quiet_hours_end',
        'is_enabled',
    ];

    protected $casts = [
        'channel_database' => 'boolean',
        'channel_email' => 'boolean',
        'channel_sms' => 'boolean',
        'channel_push' => 'boolean',
        'channel_slack' => 'boolean',
        'is_enabled' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['notification_type', 'channel_email', 'channel_sms', 'is_enabled'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isChannelEnabled(string $channel): bool
    {
        if (!$this->is_enabled) {
            return false;
        }

        return match ($channel) {
            'database' => $this->channel_database,
            'email' => $this->channel_email,
            'sms' => $this->channel_sms,
            'push' => $this->channel_push,
            'slack' => $this->channel_slack,
            default => false,
        };
    }

    public function isInQuietHours(): bool
    {
        if (!$this->quiet_hours_start || !$this->quiet_hours_end) {
            return false;
        }

        $now = now()->format('H:i');

        return $now >= $this->quiet_hours_start && $now <= $this->quiet_hours_end;
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('notification_type', $type);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }
}

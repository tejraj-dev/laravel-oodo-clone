<?php

namespace App\Modules\Email\Models;

use App\Models\Traits\HasCompany;
use App\Models\Traits\HasStatus;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SmtpConfiguration extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'name',
        'driver',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_name',
        'from_email',
        'is_default',
        'is_active',
        'daily_limit',
        'hourly_limit',
        'emails_sent_today',
        'emails_sent_this_hour',
        'last_used_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'host', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value ? encrypt($value) : null;
    }

    public function getPasswordAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }

    public function canSend(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->daily_limit && $this->emails_sent_today >= $this->daily_limit) {
            return false;
        }

        if ($this->hourly_limit && $this->emails_sent_this_hour >= $this->hourly_limit) {
            return false;
        }

        return true;
    }

    public function incrementSentCount(): void
    {
        $this->increment('emails_sent_today');
        $this->increment('emails_sent_this_hour');
        $this->update(['last_used_at' => now()]);
    }

    public function resetDailyCount(): void
    {
        $this->update(['emails_sent_today' => 0]);
    }

    public function resetHourlyCount(): void
    {
        $this->update(['emails_sent_this_hour' => 0]);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeCanSend($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('daily_limit')
                    ->orWhereColumn('emails_sent_today', '<', 'daily_limit');
            })
            ->where(function ($q) {
                $q->whereNull('hourly_limit')
                    ->orWhereColumn('emails_sent_this_hour', '<', 'hourly_limit');
            });
    }
}

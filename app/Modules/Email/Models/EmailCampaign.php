<?php

namespace App\Modules\Email\Models;

use App\Models\Traits\HasCompany;
use App\Models\Traits\HasStatus;
use App\Models\Traits\HasUuid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EmailCampaign extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'user_id',
        'email_template_id',
        'name',
        'description',
        'campaign_type',
        'subject',
        'body_html',
        'body_text',
        'from_name',
        'from_email',
        'reply_to',
        'recipient_list_type',
        'recipient_list',
        'recipient_count',
        'scheduled_at',
        'sent_at',
        'completed_at',
        'total_sent',
        'total_delivered',
        'total_opened',
        'total_clicked',
        'total_bounced',
        'total_unsubscribed',
        'total_failed',
        'status',
        'is_active',
    ];

    protected $casts = [
        'recipient_list' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'campaign_type', 'status', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(EmailMessage::class);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(EmailRecipient::class);
    }

    public function getOpenRate(): float
    {
        if ($this->total_sent == 0) {
            return 0;
        }

        return round(($this->total_opened / $this->total_sent) * 100, 2);
    }

    public function getClickRate(): float
    {
        if ($this->total_sent == 0) {
            return 0;
        }

        return round(($this->total_clicked / $this->total_sent) * 100, 2);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending')
            ->where('scheduled_at', '<=', now());
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('campaign_type', $type);
    }
}

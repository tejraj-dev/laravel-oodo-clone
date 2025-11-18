<?php

namespace App\Modules\Email\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EmailRecipient extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'email_campaign_id',
        'email_message_id',
        'email',
        'name',
        'status',
        'sent_at',
        'delivered_at',
        'opened_at',
        'clicked_at',
        'bounced_at',
        'unsubscribed_at',
        'failed_at',
        'open_count',
        'click_count',
        'user_agent',
        'ip_address',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'bounced_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['email', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(EmailCampaign::class, 'email_campaign_id');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(EmailMessage::class, 'email_message_id');
    }

    public function trackOpen(string $userAgent = null, string $ipAddress = null): void
    {
        $this->update([
            'opened_at' => $this->opened_at ?? now(),
            'open_count' => $this->open_count + 1,
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress,
        ]);
    }

    public function trackClick(string $userAgent = null, string $ipAddress = null): void
    {
        $this->update([
            'clicked_at' => $this->clicked_at ?? now(),
            'click_count' => $this->click_count + 1,
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress,
        ]);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOpened($query)
    {
        return $query->whereNotNull('opened_at');
    }

    public function scopeClicked($query)
    {
        return $query->whereNotNull('clicked_at');
    }
}

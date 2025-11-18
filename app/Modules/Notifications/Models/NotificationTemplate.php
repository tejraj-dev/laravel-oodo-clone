<?php

namespace App\Modules\Notifications\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasStatus;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NotificationTemplate extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'description',
        'type',
        'channels',
        'subject',
        'message_template',
        'email_template',
        'sms_template',
        'variables',
        'icon',
        'priority',
        'action_url_template',
        'action_text',
        'is_system_template',
        'is_active',
    ];

    protected $casts = [
        'channels' => 'array',
        'variables' => 'array',
        'is_system_template' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'type', 'channels', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function renderMessage(array $data = []): string
    {
        $message = $this->message_template;

        foreach ($data as $key => $value) {
            $message = str_replace("{{" . $key . "}}", $value, $message);
        }

        return $message;
    }

    public function renderEmail(array $data = []): string
    {
        $email = $this->email_template;

        foreach ($data as $key => $value) {
            $email = str_replace("{{" . $key . "}}", $value, $email);
        }

        return $email;
    }

    public function renderSms(array $data = []): string
    {
        $sms = $this->sms_template;

        foreach ($data as $key => $value) {
            $sms = str_replace("{{" . $key . "}}", $value, $sms);
        }

        return $sms;
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeSystemTemplates($query)
    {
        return $query->where('is_system_template', true);
    }

    public function scopeCustomTemplates($query)
    {
        return $query->where('is_system_template', false);
    }
}

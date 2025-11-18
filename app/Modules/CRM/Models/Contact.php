<?php

namespace App\Modules\CRM\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasStatus;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogsActivity;
use Spatie\Activitylog\Traits\LogsActivity as LogsActivityTrait;

class Contact extends Model implements LogsActivity
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, LogsActivityTrait;

    protected $fillable = [
        'company_id',
        'account_id',
        'title',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'job_title',
        'department',
        'reports_to',
        'assistant',
        'assistant_phone',
        'birth_date',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'description',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function reportsTo(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'reports_to');
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}

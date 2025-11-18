<?php

namespace App\Modules\Reporting\Models;

use App\Models\User;
use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Dashboard extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'description',
        'layout_config', // JSON layout configuration (grid positions)
        'refresh_interval', // Auto-refresh interval in seconds
        'is_default',
        'is_public',
        'is_active',
    ];

    protected $casts = [
        'layout_config' => 'array',
        'is_default' => 'boolean',
        'is_public' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'is_default', 'is_public', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function widgets(): HasMany
    {
        return $this->hasMany(Widget::class);
    }
}

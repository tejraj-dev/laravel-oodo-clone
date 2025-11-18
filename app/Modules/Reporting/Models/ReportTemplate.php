<?php

namespace App\Modules\Reporting\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ReportTemplate extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'category', // sales, financial, inventory, hr, crm
        'report_type',
        'data_source',
        'default_config', // JSON default configuration
        'required_parameters', // JSON required parameters
        'is_system_template', // Built-in system templates
        'is_active',
    ];

    protected $casts = [
        'default_config' => 'array',
        'required_parameters' => 'array',
        'is_system_template' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'category', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}

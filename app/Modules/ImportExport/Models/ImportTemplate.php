<?php

namespace App\Modules\ImportExport\Models;

use App\Models\Traits\HasCompany;
use App\Models\Traits\HasStatus;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ImportTemplate extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'entity_type',
        'mapping',
        'validation_rules',
        'default_values',
        'sample_file_path',
        'is_system_template',
        'is_active',
        'usage_count',
    ];

    protected $casts = [
        'mapping' => 'array',
        'validation_rules' => 'array',
        'default_values' => 'array',
        'is_system_template' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'entity_type', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function importJobs(): HasMany
    {
        return $this->hasMany(ImportJob::class);
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    public function scopeByEntityType($query, string $entityType)
    {
        return $query->where('entity_type', $entityType);
    }

    public function scopeSystemTemplates($query)
    {
        return $query->where('is_system_template', true);
    }
}

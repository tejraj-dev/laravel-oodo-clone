<?php

namespace App\Modules\ImportExport\Models;

use App\Models\Traits\HasCompany;
use App\Models\Traits\HasUuid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ExportJob extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'entity_type',
        'format',
        'filters',
        'columns',
        'file_name',
        'file_path',
        'file_size',
        'status',
        'total_rows',
        'is_scheduled',
        'schedule_frequency',
        'next_run_at',
        'last_run_at',
        'started_at',
        'completed_at',
        'failed_at',
        'error_message',
    ];

    protected $casts = [
        'filters' => 'array',
        'columns' => 'array',
        'is_scheduled' => 'boolean',
        'next_run_at' => 'datetime',
        'last_run_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'entity_type', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsProcessing(): void
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    public function markAsCompleted(string $filePath, int $fileSize, int $totalRows): void
    {
        $this->update([
            'status' => 'completed',
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'total_rows' => $totalRows,
            'completed_at' => now(),
            'last_run_at' => now(),
        ]);

        if ($this->is_scheduled) {
            $this->updateNextRun();
        }
    }

    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'error_message' => $error,
        ]);
    }

    protected function updateNextRun(): void
    {
        $nextRun = match ($this->schedule_frequency) {
            'daily' => now()->addDay(),
            'weekly' => now()->addWeek(),
            'monthly' => now()->addMonth(),
            'yearly' => now()->addYear(),
            default => null,
        };

        if ($nextRun) {
            $this->update(['next_run_at' => $nextRun]);
        }
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeScheduled($query)
    {
        return $query->where('is_scheduled', true)
            ->where('next_run_at', '<=', now());
    }
}

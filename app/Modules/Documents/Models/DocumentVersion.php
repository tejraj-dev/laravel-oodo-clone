<?php

namespace App\Modules\Documents\Models;

use App\Models\Traits\HasCompany;
use App\Models\Traits\HasUuid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DocumentVersion extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'document_id',
        'user_id',
        'version_number',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'change_summary',
        'is_current',
        'checksum',
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['version_number', 'file_name', 'is_current'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function makeCurrent(): void
    {
        // Mark all other versions as not current
        DocumentVersion::where('document_id', $this->document_id)
            ->update(['is_current' => false]);

        // Mark this version as current
        $this->update(['is_current' => true]);

        // Update document's current version
        $this->document->update([
            'current_version_id' => $this->id,
            'version_number' => $this->version_number,
        ]);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }
}

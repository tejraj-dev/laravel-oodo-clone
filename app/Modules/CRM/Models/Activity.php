<?php

namespace App\Modules\CRM\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes;

    protected $fillable = [
        'company_id',
        'subject',
        'type',
        'status',
        'priority',
        'due_date',
        'due_time',
        'duration',
        'location',
        'description',
        'assigned_to',
        'related_to_type',
        'related_to_id',
        'lead_id',
        'contact_id',
        'opportunity_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'due_time' => 'datetime',
    ];

    public function relatedTo(): MorphTo
    {
        return $this->morphTo();
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }
}

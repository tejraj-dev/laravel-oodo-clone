<?php

namespace App\Modules\HR\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasFactory, HasUuid, HasCompany;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'days_per_year',
        'is_paid',
        'is_active',
    ];

    protected $casts = [
        'days_per_year' => 'integer',
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }
}

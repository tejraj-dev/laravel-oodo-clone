<?php

namespace App\Modules\POS\Models;

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

class PosSession extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'cash_register_id',
        'user_id',
        'name',
        'session_number',
        'start_time',
        'end_time',
        'opening_balance',
        'closing_balance',
        'expected_closing_balance',
        'cash_in',
        'cash_out',
        'total_sales',
        'total_refunds',
        'status', // draft, open, closed
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'expected_closing_balance' => 'decimal:2',
        'cash_in' => 'decimal:2',
        'cash_out' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_refunds' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'session_number', 'status', 'opening_balance', 'closing_balance'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(PosOrder::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PosPayment::class);
    }
}

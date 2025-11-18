<?php

namespace App\Modules\POS\Models;

use App\Models\User;
use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use App\Modules\Sales\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PosOrder extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'pos_session_id',
        'customer_id',
        'user_id',
        'order_number',
        'order_date',
        'subtotal',
        'discount_type', // fixed, percentage
        'discount_value',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'amount_paid',
        'amount_due',
        'change_amount',
        'status', // draft, paid, partially_paid, refunded, cancelled
        'payment_status', // pending, paid, partially_paid, refunded
        'notes',
        'customer_notes',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_due' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['order_number', 'status', 'payment_status', 'total_amount'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(PosSession::class, 'pos_session_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PosOrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PosPayment::class);
    }
}

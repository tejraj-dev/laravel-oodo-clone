<?php

namespace App\Modules\Sales\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasStatus;
use App\Modules\Core\Traits\HasUuid;
use App\Modules\CRM\Models\Contact;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogsActivity;
use Spatie\Activitylog\Traits\LogsActivity as LogsActivityTrait;

class SalesOrder extends Model implements LogsActivity
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes, LogsActivityTrait;

    protected $fillable = [
        'company_id',
        'order_number',
        'quote_id',
        'customer_id',
        'contact_id',
        'order_date',
        'expected_delivery_date',
        'delivery_date',
        'status',
        'payment_status',
        'payment_terms',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'terms_conditions',
        'notes',
        'assigned_to',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
    ];

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }
}

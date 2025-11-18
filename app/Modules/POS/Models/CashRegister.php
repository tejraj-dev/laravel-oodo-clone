<?php

namespace App\Modules\POS\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CashRegister extends Model
{
    use HasFactory, HasUuid, HasCompany, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'name',
        'location',
        'is_active',
        'auto_close_session',
        'receipt_header',
        'receipt_footer',
        'printer_ip',
        'printer_port',
        'allow_discount',
        'max_discount_percent',
        'require_customer',
        'allow_credit_sale',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_close_session' => 'boolean',
        'allow_discount' => 'boolean',
        'require_customer' => 'boolean',
        'allow_credit_sale' => 'boolean',
        'max_discount_percent' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'location', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(PosSession::class);
    }
}

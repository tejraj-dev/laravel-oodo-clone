<?php

namespace App\Modules\HR\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Core\Traits\HasStatus;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, HasUuid, HasCompany, HasStatus, SoftDeletes;

    protected $fillable = [
        'company_id',
        'employee_id',
        'period_start',
        'period_end',
        'payment_date',
        'basic_salary',
        'allowances',
        'overtime_pay',
        'bonuses',
        'gross_salary',
        'tax_deduction',
        'insurance_deduction',
        'other_deductions',
        'total_deductions',
        'net_salary',
        'status',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'bonuses' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'insurance_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

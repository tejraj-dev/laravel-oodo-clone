<?php

namespace App\Modules\Core\Traits;

use App\Modules\Core\Models\Company;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasCompany
{
    public static function bootHasCompany(): void
    {
        static::creating(function ($model) {
            if (empty($model->company_id) && auth()->check()) {
                $model->company_id = auth()->user()->company_id ?? session('company_id');
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeForCompany($query, $companyId = null)
    {
        $companyId = $companyId ?? (auth()->user()->company_id ?? session('company_id'));

        if ($companyId) {
            return $query->where('company_id', $companyId);
        }

        return $query;
    }
}

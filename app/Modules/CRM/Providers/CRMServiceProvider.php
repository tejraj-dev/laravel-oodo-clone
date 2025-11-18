<?php

namespace App\Modules\CRM\Providers;

use App\Modules\BaseModuleServiceProvider;

class CRMServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'CRM';

    /**
     * Register services.
     */
    public function register(): void
    {
        parent::register();

        // Register module-specific services here
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();

        // Boot module-specific services here
    }
}

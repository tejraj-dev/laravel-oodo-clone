<?php

namespace App\Modules\Reporting\Providers;

use App\Modules\BaseModuleServiceProvider;

class ReportingServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'Reporting';

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

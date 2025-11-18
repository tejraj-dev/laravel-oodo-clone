<?php

namespace App\Modules\Sales\Providers;

use App\Modules\BaseModuleServiceProvider;

class SalesServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'Sales';

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

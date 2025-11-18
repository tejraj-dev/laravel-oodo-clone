<?php

namespace App\Modules\Manufacturing\Providers;

use App\Modules\BaseModuleServiceProvider;

class ManufacturingServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'Manufacturing';

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

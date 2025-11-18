<?php

namespace App\Modules\Inventory\Providers;

use App\Modules\BaseModuleServiceProvider;

class InventoryServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'Inventory';

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

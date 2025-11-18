<?php

namespace App\Modules\Documents\Providers;

use App\Modules\BaseModuleServiceProvider;

class DocumentsServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'Documents';

    /**
     * Register services.
     */
    public function register(): void
    {
        parent::register();

        // Register module specific services here
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();

        // Boot module specific services here
    }
}

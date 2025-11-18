<?php

namespace App\Modules\ImportExport\Providers;

use App\Modules\BaseModuleServiceProvider;

class ImportExportServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'ImportExport';

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

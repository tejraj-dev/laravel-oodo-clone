<?php

namespace App\Modules\Approvals\Providers;

use App\Modules\BaseModuleServiceProvider;

class ApprovalsServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'Approvals';

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

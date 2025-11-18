<?php

namespace App\Modules\Email\Providers;

use App\Modules\BaseModuleServiceProvider;

class EmailServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'Email';

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

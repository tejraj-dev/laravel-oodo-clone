<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;

abstract class BaseModuleServiceProvider extends ServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName;

    /**
     * Module path
     */
    protected string $modulePath;

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->modulePath = app_path("Modules/{$this->moduleName}");

        // Load module config
        $this->mergeConfigFrom(
            "{$this->modulePath}/config.php",
            strtolower($this->moduleName)
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load migrations
        if (is_dir("{$this->modulePath}/Database/Migrations")) {
            $this->loadMigrationsFrom("{$this->modulePath}/Database/Migrations");
        }

        // Load routes
        if (file_exists("{$this->modulePath}/routes/api.php")) {
            $this->loadRoutesFrom("{$this->modulePath}/routes/api.php");
        }

        if (file_exists("{$this->modulePath}/routes/web.php")) {
            $this->loadRoutesFrom("{$this->modulePath}/routes/web.php");
        }

        // Load views
        if (is_dir("{$this->modulePath}/resources/views")) {
            $this->loadViewsFrom("{$this->modulePath}/resources/views", strtolower($this->moduleName));
        }

        // Load translations
        if (is_dir("{$this->modulePath}/resources/lang")) {
            $this->loadTranslationsFrom("{$this->modulePath}/resources/lang", strtolower($this->moduleName));
        }

        // Publish config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                "{$this->modulePath}/config.php" => config_path(strtolower($this->moduleName) . '.php'),
            ], "{$this->moduleName}-config");

            // Publish migrations
            if (is_dir("{$this->modulePath}/Database/Migrations")) {
                $this->publishes([
                    "{$this->modulePath}/Database/Migrations" => database_path('migrations'),
                ], "{$this->moduleName}-migrations");
            }
        }
    }

    /**
     * Get module name
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * Get module path
     */
    public function getModulePath(): string
    {
        return $this->modulePath;
    }
}

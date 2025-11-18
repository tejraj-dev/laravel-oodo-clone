<?php

namespace App\Modules;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Auto-discover and register all modules
        $this->registerModules();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Auto-load module routes
        $this->loadModuleRoutes();

        // Auto-load module migrations
        $this->loadModuleMigrations();
    }

    /**
     * Register all modules
     */
    protected function registerModules(): void
    {
        $modules = $this->getAvailableModules();

        foreach ($modules as $module) {
            $providerClass = "App\\Modules\\{$module}\\Providers\\{$module}ServiceProvider";

            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }

    /**
     * Load routes for all modules
     */
    protected function loadModuleRoutes(): void
    {
        $modules = $this->getAvailableModules();

        foreach ($modules as $module) {
            $apiRoutesPath = app_path("Modules/{$module}/routes/api.php");
            $webRoutesPath = app_path("Modules/{$module}/routes/web.php");

            if (file_exists($apiRoutesPath)) {
                $this->loadRoutesFrom($apiRoutesPath);
            }

            if (file_exists($webRoutesPath)) {
                $this->loadRoutesFrom($webRoutesPath);
            }
        }
    }

    /**
     * Load migrations for all modules
     */
    protected function loadModuleMigrations(): void
    {
        $modules = $this->getAvailableModules();

        foreach ($modules as $module) {
            $migrationsPath = app_path("Modules/{$module}/Database/Migrations");

            if (is_dir($migrationsPath)) {
                $this->loadMigrationsFrom($migrationsPath);
            }
        }
    }

    /**
     * Get all available modules
     */
    protected function getAvailableModules(): array
    {
        $modulesPath = app_path('Modules');

        if (!is_dir($modulesPath)) {
            return [];
        }

        $modules = [];
        $directories = File::directories($modulesPath);

        foreach ($directories as $directory) {
            $moduleName = basename($directory);

            // Check if module has a config file to determine if it's enabled
            $configPath = "{$directory}/config.php";

            if (file_exists($configPath)) {
                $config = require $configPath;
                if ($config['enabled'] ?? true) {
                    $modules[] = $moduleName;
                }
            } else {
                // If no config, assume enabled
                $modules[] = $moduleName;
            }
        }

        return $modules;
    }
}

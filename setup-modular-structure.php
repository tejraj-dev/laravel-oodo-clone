<?php

/**
 * Script to set up modular structure for all modules
 * This makes each module self-contained and installable/uninstallable
 */

$modules = [
    'Notifications' => [
        'migration' => '2025_11_18_100000_create_notifications_tables.php',
        'icon' => 'bell',
        'description' => 'Real-time notifications and alerts system',
    ],
    'Email' => [
        'migration' => '2025_11_18_110000_create_email_tables.php',
        'icon' => 'envelope',
        'description' => 'Email marketing and communication module',
    ],
    'Documents' => [
        'migration' => '2025_11_18_120000_create_documents_tables.php',
        'icon' => 'document',
        'description' => 'Document management system with versioning',
    ],
    'Approvals' => [
        'migration' => '2025_11_18_130000_create_approvals_tables.php',
        'icon' => 'clipboard-document-check',
        'description' => 'Approval workflows and processes',
    ],
    'ImportExport' => [
        'migration' => '2025_11_18_140000_create_import_export_tables.php',
        'icon' => 'arrow-down-tray',
        'description' => 'Import and export functionality',
    ],
];

foreach ($modules as $moduleName => $moduleData) {
    echo "Setting up {$moduleName} module...\n";

    $modulePath = "app/Modules/{$moduleName}";

    // Create necessary directories
    $directories = [
        "{$modulePath}/Database/Migrations",
        "{$modulePath}/Providers",
        "{$modulePath}/resources/views",
        "{$modulePath}/resources/lang",
    ];

    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo "  Created directory: {$dir}\n";
        }
    }

    // Move migration if it exists
    $oldMigrationPath = "database/migrations/{$moduleData['migration']}";
    $newMigrationPath = "{$modulePath}/Database/Migrations/{$moduleData['migration']}";

    if (file_exists($oldMigrationPath) && !file_exists($newMigrationPath)) {
        rename($oldMigrationPath, $newMigrationPath);
        echo "  Moved migration: {$moduleData['migration']}\n";
    }

    // Create config.php
    $configPath = "{$modulePath}/config.php";
    if (!file_exists($configPath)) {
        $configContent = <<<PHP
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Module Enabled
    |--------------------------------------------------------------------------
    |
    | This option controls whether the module is enabled or disabled.
    |
    */
    'enabled' => env('{$moduleName}_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Module Name
    |--------------------------------------------------------------------------
    */
    'name' => '{$moduleName}',

    /*
    |--------------------------------------------------------------------------
    | Module Description
    |--------------------------------------------------------------------------
    */
    'description' => '{$moduleData['description']}',

    /*
    |--------------------------------------------------------------------------
    | Module Icon
    |--------------------------------------------------------------------------
    */
    'icon' => 'heroicon-o-{$moduleData['icon']}',

    /*
    |--------------------------------------------------------------------------
    | Navigation Group
    |--------------------------------------------------------------------------
    */
    'navigation_group' => '{$moduleName}',

    /*
    |--------------------------------------------------------------------------
    | Navigation Sort
    |--------------------------------------------------------------------------
    */
    'navigation_sort' => 100,
];

PHP;
        file_put_contents($configPath, $configContent);
        echo "  Created config.php\n";
    }

    // Create Service Provider
    $providerPath = "{$modulePath}/Providers/{$moduleName}ServiceProvider.php";
    if (!file_exists($providerPath)) {
        $providerContent = <<<PHP
<?php

namespace App\Modules\\{$moduleName}\Providers;

use App\Modules\BaseModuleServiceProvider;

class {$moduleName}ServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Module name
     */
    protected string \$moduleName = '{$moduleName}';

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

PHP;
        file_put_contents($providerPath, $providerContent);
        echo "  Created {$moduleName}ServiceProvider.php\n";
    }

    echo "  ✓ {$moduleName} module setup complete\n\n";
}

echo "All modules have been set up with modular structure!\n";

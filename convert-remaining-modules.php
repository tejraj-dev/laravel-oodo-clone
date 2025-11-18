<?php

/**
 * Script to convert remaining modules to modular structure
 * This will create config.php and service providers for all original modules
 */

$modules = [
    [
        'name' => 'Core',
        'description' => 'Core system functionality and shared components',
        'icon' => 'heroicon-o-cog-6-tooth',
        'navigation_group' => 'System',
        'navigation_sort' => 10,
    ],
    [
        'name' => 'CRM',
        'description' => 'Customer Relationship Management',
        'icon' => 'heroicon-o-users',
        'navigation_group' => 'CRM',
        'navigation_sort' => 20,
    ],
    [
        'name' => 'Sales',
        'description' => 'Sales Order Management',
        'icon' => 'heroicon-o-shopping-cart',
        'navigation_group' => 'Sales',
        'navigation_sort' => 30,
    ],
    [
        'name' => 'Purchase',
        'description' => 'Purchase Order Management',
        'icon' => 'heroicon-o-shopping-bag',
        'navigation_group' => 'Purchase',
        'navigation_sort' => 40,
    ],
    [
        'name' => 'Inventory',
        'description' => 'Inventory and Warehouse Management',
        'icon' => 'heroicon-o-cube',
        'navigation_group' => 'Inventory',
        'navigation_sort' => 50,
    ],
    [
        'name' => 'HR',
        'description' => 'Human Resources Management',
        'icon' => 'heroicon-o-user-group',
        'navigation_group' => 'HR',
        'navigation_sort' => 60,
    ],
    [
        'name' => 'Projects',
        'description' => 'Project Management',
        'icon' => 'heroicon-o-briefcase',
        'navigation_group' => 'Projects',
        'navigation_sort' => 70,
    ],
    [
        'name' => 'Manufacturing',
        'description' => 'Manufacturing and Production',
        'icon' => 'heroicon-o-wrench',
        'navigation_group' => 'Manufacturing',
        'navigation_sort' => 80,
    ],
    [
        'name' => 'Accounting',
        'description' => 'Accounting and Financial Management',
        'icon' => 'heroicon-o-calculator',
        'navigation_group' => 'Accounting',
        'navigation_sort' => 90,
    ],
    [
        'name' => 'POS',
        'description' => 'Point of Sale',
        'icon' => 'heroicon-o-banknotes',
        'navigation_group' => 'POS',
        'navigation_sort' => 100,
    ],
    [
        'name' => 'Reporting',
        'description' => 'Reports and Analytics',
        'icon' => 'heroicon-o-chart-bar',
        'navigation_group' => 'Reporting',
        'navigation_sort' => 110,
    ],
];

foreach ($modules as $module) {
    $moduleName = $module['name'];
    $modulePath = __DIR__ . "/app/Modules/{$moduleName}";

    echo "Processing module: {$moduleName}\n";

    // Create Providers directory if it doesn't exist
    $providersPath = "{$modulePath}/Providers";
    if (!is_dir($providersPath)) {
        mkdir($providersPath, 0755, true);
        echo "  ✓ Created Providers directory\n";
    }

    // Create Database/Migrations directory if it doesn't exist
    $migrationsPath = "{$modulePath}/Database/Migrations";
    if (!is_dir($migrationsPath)) {
        mkdir($migrationsPath, 0755, true);
        echo "  ✓ Created Database/Migrations directory\n";
    }

    // Create config.php
    $configPath = "{$modulePath}/config.php";
    if (!file_exists($configPath)) {
        $envKey = strtoupper($moduleName);
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
    'enabled' => env('{$envKey}_ENABLED', true),

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
    'description' => '{$module['description']}',

    /*
    |--------------------------------------------------------------------------
    | Module Icon
    |--------------------------------------------------------------------------
    */
    'icon' => '{$module['icon']}',

    /*
    |--------------------------------------------------------------------------
    | Navigation Group
    |--------------------------------------------------------------------------
    */
    'navigation_group' => '{$module['navigation_group']}',

    /*
    |--------------------------------------------------------------------------
    | Navigation Sort
    |--------------------------------------------------------------------------
    */
    'navigation_sort' => {$module['navigation_sort']},
];

PHP;
        file_put_contents($configPath, $configContent);
        echo "  ✓ Created config.php\n";
    } else {
        echo "  - config.php already exists\n";
    }

    // Create Service Provider
    $providerPath = "{$providersPath}/{$moduleName}ServiceProvider.php";
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

PHP;
        file_put_contents($providerPath, $providerContent);
        echo "  ✓ Created {$moduleName}ServiceProvider.php\n";
    } else {
        echo "  - Service provider already exists\n";
    }

    echo "\n";
}

echo "✅ All modules converted to modular structure!\n";
echo "\nNext steps:\n";
echo "1. Move module-specific migrations from database/migrations/ to app/Modules/{Module}/Database/Migrations/\n";
echo "2. Run: git add . && git commit -m 'Convert all modules to modular structure'\n";


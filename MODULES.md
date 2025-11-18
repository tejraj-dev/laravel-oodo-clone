# Laravel ERP - Modular Architecture

## Overview

This Laravel ERP system uses a fully modular architecture where each module is self-contained and can be independently installed, configured, and uninstalled.

## Module Structure

Each module follows this standardized structure:

```
app/Modules/{ModuleName}/
├── config.php                          # Module configuration
├── Providers/
│   └── {ModuleName}ServiceProvider.php # Module service provider
├── Models/                             # Eloquent models
├── Database/
│   └── Migrations/                     # Module-specific migrations
├── Filament/
│   └── Resources/                      # Admin panel resources
├── Http/
│   ├── Controllers/
│   │   └── Api/                        # API controllers
│   └── Resources/                      # API resources
├── GraphQL/
│   └── schema.graphql                  # GraphQL schema
├── routes/
│   ├── api.php                         # API routes
│   └── web.php                         # Web routes (optional)
└── resources/
    ├── views/                          # Blade templates (optional)
    └── lang/                           # Translations (optional)
```

## Auto-Discovery

The system automatically discovers and registers:

- ✅ **Migrations**: From `Database/Migrations/`
- ✅ **Routes**: From `routes/api.php` and `routes/web.php`
- ✅ **Filament Resources**: From `Filament/Resources/`
- ✅ **Navigation Groups**: From module `config.php`
- ✅ **Service Providers**: From `Providers/`

## Module Configuration

Each module has a `config.php` file with the following options:

```php
<?php

return [
    // Enable/disable the module
    'enabled' => env('MODULE_NAME_ENABLED', true),

    // Module metadata
    'name' => 'ModuleName',
    'description' => 'Module description',
    'icon' => 'heroicon-o-icon-name',

    // Navigation settings
    'navigation_group' => 'Module Group',
    'navigation_sort' => 100,
];
```

## Enabling/Disabling Modules

### Method 1: Environment Variable

Add to your `.env` file:

```env
NOTIFICATIONS_ENABLED=false
EMAIL_ENABLED=false
DOCUMENTS_ENABLED=true
```

### Method 2: Module Config File

Edit `app/Modules/{ModuleName}/config.php`:

```php
'enabled' => false,
```

### Method 3: Artisan Command (Coming Soon)

```bash
php artisan module:disable Notifications
php artisan module:enable Notifications
```

## Available Modules

### Core Business Modules (11)
1. **Core** - Companies, users, currencies, taxes
2. **CRM** - Customer relationship management
3. **Sales** - Sales orders, quotations, invoicing
4. **Purchase** - Purchase orders, suppliers, receiving
5. **Inventory** - Stock management, warehouses, transfers
6. **HR** - Employees, departments, attendance, payroll
7. **Projects** - Project management, tasks, time tracking
8. **Manufacturing** - BOMs, work orders, production
9. **Accounting** - General ledger, journal entries, financial reports
10. **POS** - Point of sale operations
11. **Reporting** - Reports, dashboards, KPIs, analytics

### Phase 1 Modules (5)
12. **Notifications** - Multi-channel notifications and alerts
13. **Email** - Email marketing and campaigns
14. **Documents** - Document management with versioning
15. **Approvals** - Approval workflows and processes
16. **ImportExport** - Data import/export functionality

## Creating a New Module

### 1. Create Module Structure

```bash
mkdir -p app/Modules/MyModule/{Models,Database/Migrations,Filament/Resources,Http/Controllers/Api,Http/Resources,GraphQL,routes,Providers}
```

### 2. Create Module Config

Create `app/Modules/MyModule/config.php`:

```php
<?php

return [
    'enabled' => env('MYMODULE_ENABLED', true),
    'name' => 'MyModule',
    'description' => 'My custom module',
    'icon' => 'heroicon-o-puzzle-piece',
    'navigation_group' => 'MyModule',
    'navigation_sort' => 200,
];
```

### 3. Create Service Provider

Create `app/Modules/MyModule/Providers/MyModuleServiceProvider.php`:

```php
<?php

namespace App\Modules\MyModule\Providers;

use App\Modules\BaseModuleServiceProvider;

class MyModuleServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'MyModule';

    public function register(): void
    {
        parent::register();
        // Register module services
    }

    public function boot(): void
    {
        parent::boot();
        // Boot module services
    }
}
```

### 4. Create Models, Migrations, etc.

Follow the standard Laravel conventions for creating:
- Models in `Models/`
- Migrations in `Database/Migrations/`
- Controllers in `Http/Controllers/Api/`
- Resources in `Http/Resources/`
- Routes in `routes/api.php`

### 5. Module Will Auto-Register

The module will automatically be discovered and registered on the next request.

## Module Dependencies

If your module depends on other modules, document it in the config:

```php
'dependencies' => [
    'Core',
    'CRM',
],
```

## Best Practices

1. **Keep modules independent**: Minimize dependencies between modules
2. **Use events**: Communicate between modules using Laravel events
3. **Namespace properly**: Always use proper namespacing (`App\Modules\{ModuleName}`)
4. **Follow conventions**: Use the standard module structure
5. **Document**: Add README.md to your module directory
6. **Test**: Write tests for your module functionality
7. **Version migrations**: Use timestamps in migration filenames

## Module Isolation

Each module is isolated with:

- ✅ Own migrations directory
- ✅ Own routes file
- ✅ Own service provider
- ✅ Own configuration
- ✅ Can be enabled/disabled independently
- ✅ Auto-discovered by the system

## Database Migrations

Module migrations are automatically loaded from:
```
app/Modules/{ModuleName}/Database/Migrations/
```

Run migrations normally:
```bash
php artisan migrate
```

To run migrations for a specific module:
```bash
php artisan migrate --path=app/Modules/MyModule/Database/Migrations
```

## Publishing Module Assets

If your module has publishable assets, use the service provider:

```php
public function boot(): void
{
    parent::boot();

    if ($this->app->runningInConsole()) {
        $this->publishes([
            "{$this->modulePath}/config.php" => config_path('mymodule.php'),
        ], 'mymodule-config');
    }
}
```

Then publish:
```bash
php artisan vendor:publish --tag=mymodule-config
```

## Module Distribution

To distribute a module:

1. Copy the entire module directory
2. Include `config.php`, service provider, and all code
3. Document dependencies and installation steps
4. Users drop it into `app/Modules/` and it auto-registers

## Troubleshooting

### Module not appearing in admin panel

1. Check module is enabled in config
2. Clear cache: `php artisan config:clear`
3. Check Filament resources exist in correct directory
4. Verify service provider is properly configured

### Routes not working

1. Verify routes file exists at `routes/api.php` or `routes/web.php`
2. Check route file syntax
3. Clear route cache: `php artisan route:clear`

### Migrations not running

1. Ensure migrations are in `Database/Migrations/`
2. Check migration file naming follows Laravel conventions
3. Run: `php artisan migrate:status`

## Future Enhancements

- [ ] Artisan commands for module management
- [ ] Module marketplace/repository
- [ ] Module version management
- [ ] Module dependency resolution
- [ ] Module testing framework
- [ ] Module generator command

## Support

For issues or questions about the modular architecture, please check:
- ROADMAP.md for planned features
- API_DOCUMENTATION.md for API details
- GitHub issues for community support

<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\File;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panelConfig = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources');

        // Auto-discover module resources
        $panelConfig = $this->discoverModuleResources($panelConfig);

        return $panelConfig
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->navigationGroups($this->getNavigationGroups())
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->brandName('Laravel ERP')
            ->favicon(asset('favicon.ico'));
    }

    /**
     * Auto-discover and register module resources
     */
    protected function discoverModuleResources(Panel $panel): Panel
    {
        $modulesPath = app_path('Modules');

        if (!is_dir($modulesPath)) {
            return $panel;
        }

        $modules = File::directories($modulesPath);

        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);
            $resourcesPath = "{$modulePath}/Filament/Resources";

            // Check if module is enabled
            $configPath = "{$modulePath}/config.php";
            if (file_exists($configPath)) {
                $config = require $configPath;
                if (!($config['enabled'] ?? true)) {
                    continue;
                }
            }

            // Discover resources if directory exists
            if (is_dir($resourcesPath)) {
                $panel->discoverResources(
                    in: $resourcesPath,
                    for: "App\\Modules\\{$moduleName}\\Filament\\Resources"
                );
            }
        }

        return $panel;
    }

    /**
     * Get navigation groups from enabled modules
     */
    protected function getNavigationGroups(): array
    {
        $groups = ['Dashboard'];
        $modulesPath = app_path('Modules');

        if (!is_dir($modulesPath)) {
            return array_merge($groups, ['Settings']);
        }

        $modules = File::directories($modulesPath);
        $moduleGroups = [];

        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);
            $configPath = "{$modulePath}/config.php";

            if (file_exists($configPath)) {
                $config = require $configPath;

                if ($config['enabled'] ?? true) {
                    $navigationGroup = $config['navigation_group'] ?? $moduleName;
                    $sortOrder = $config['navigation_sort'] ?? 100;

                    $moduleGroups[] = [
                        'name' => $navigationGroup,
                        'sort' => $sortOrder,
                    ];
                }
            }
        }

        // Sort by sort order
        usort($moduleGroups, fn($a, $b) => $a['sort'] <=> $b['sort']);

        // Extract names
        $groups = array_merge(
            $groups,
            array_map(fn($group) => $group['name'], $moduleGroups),
            ['Settings']
        );

        return $groups;
    }
}

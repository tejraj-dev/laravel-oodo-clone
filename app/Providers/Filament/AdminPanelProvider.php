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
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/Core/Filament/Resources'), for: 'App\\Modules\\Core\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/CRM/Filament/Resources'), for: 'App\\Modules\\CRM\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/Sales/Filament/Resources'), for: 'App\\Modules\\Sales\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/Purchase/Filament/Resources'), for: 'App\\Modules\\Purchase\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/Inventory/Filament/Resources'), for: 'App\\Modules\\Inventory\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/HR/Filament/Resources'), for: 'App\\Modules\\HR\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/Projects/Filament/Resources'), for: 'App\\Modules\\Projects\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/Manufacturing/Filament/Resources'), for: 'App\\Modules\\Manufacturing\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/Accounting/Filament/Resources'), for: 'App\\Modules\\Accounting\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/POS/Filament/Resources'), for: 'App\\Modules\\POS\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/Reporting/Filament/Resources'), for: 'App\\Modules\\Reporting\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/Notifications/Filament/Resources'), for: 'App\\Modules\\Notifications\\Filament\\Resources')
            ->discoverResources(in: app_path('Modules/Email/Filament/Resources'), for: 'App\\Modules\\Email\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->navigationGroups([
                'Dashboard',
                'POS',
                'CRM',
                'Sales',
                'Purchase',
                'Inventory',
                'Manufacturing',
                'HR',
                'Projects',
                'Accounting',
                'Reporting',
                'Notifications',
                'Email',
                'Settings',
            ])
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
}

<?php

namespace App\Providers\Filament;

use App\Filament\Resources\ResolveLogResource\Widgets\DnsRequestsByStatus;
use App\Filament\Resources\ResolveLogResource\Widgets\DnsRequestsOverTimeChart;
use App\Filament\Resources\ResolveLogResource\Widgets\FailedDomainsChart;
use App\Filament\Resources\ResolveLogResource\Widgets\FilterStatusDistributionChart;
use App\Filament\Resources\ResolveLogResource\Widgets\ResolvedDomainsLocationChart;
use App\Filament\Resources\ResolveLogResource\Widgets\StatsOverview;
use App\Filament\Resources\ResolveLogResource\Widgets\TopClientsRequestsChart;
use App\Filament\Resources\ResolveLogResource\Widgets\TopDomainsByRequestsChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
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
            ->brandName('Secure Proxy')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->databaseNotifications()
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                StatsOverview::class,
                DnsRequestsOverTimeChart::class,
                ResolvedDomainsLocationChart::class,
                TopDomainsByRequestsChart::class,
                TopClientsRequestsChart::class,
                DnsRequestsByStatus::class,
                FilterStatusDistributionChart::class,
                FailedDomainsChart::class,
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
            ]);
    }
}

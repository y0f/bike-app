<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\LoanBikeResource;
use App\Filament\Resources\ScheduleResource;
use Filament\SpatieLaravelTranslatablePlugin;
use App\Filament\Resources\AppointmentResource;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Resources\CustomerBikeResource;
use App\Filament\Resources\ServicePointResource;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('administratie_portaal')
            ->brandLogo(asset('images/filament-logo.png'))
            ->favicon(asset('images/logo.png'))
            ->login()
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {

                return $builder->groups([
                    NavigationGroup::make('') // <- This makes the navigation item not have a group. Useful for pages.
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->icon('heroicon-o-home')
                               ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                ->url(fn (): string => Dashboard::getUrl()),
                        ]),
                    NavigationGroup::make('Planning')
                        ->items([
                            ...AppointmentResource::getNavigationItems(),
                            ...ScheduleResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Servicebeheer')
                        ->items([
                            ...ServicePointResource::getNavigationItems(),
                            ...CustomerBikeResource::getNavigationItems(),
                            ...LoanBikeResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Gebruikersbeheer')
                        ->items([
                            ...UserResource::getNavigationItems(),
                            ...ActivityResource::getNavigationItems(),
                        ]),
                ]);
            })
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->colors([
                'primary' => Color::Orange,
                'danger'  => Color::Red,
                'gray'    => Color::Zinc,
                'info'    => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Yellow,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
            ->plugin(
                SpatieLaravelTranslatablePlugin::make()
                    ->defaultLocales(['en', 'nl']),
            )
            ->plugins([

            ]);
    }
}

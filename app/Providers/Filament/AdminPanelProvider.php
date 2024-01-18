<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Filament\Pages\Faq;
use App\Filament\Pages\Settings;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Navigation\MenuItem;
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
            ->brandLogo(asset('images/logo.svg'))
            ->favicon(asset('images/logo.svg'))
            ->login()
            ->profile()
            ->databaseNotifications()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {

                return $builder->groups([
                    NavigationGroup::make('')
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
            ->userMenuItems([
                'faq' => MenuItem::make()
                    ->label('FAQ')
                    ->icon('heroicon-o-question-mark-circle')
                    ->url(fn (): string => Faq::getUrl()),
            ])
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->colors([
                'primary' => Color::Purple,
                'danger'  => Color::Red,
                'gray'    => Color::Zinc,
                'info'    => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Yellow,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            // ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
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
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                SpatieLaravelTranslatablePlugin::make()
                    ->defaultLocales(['en', 'nl']),
                \Hasnayeen\Themes\ThemesPlugin::make(),
            ]);
    }
}

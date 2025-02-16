<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Illuminate\View\View;
use Filament\PanelProvider;
use App\Filament\Admin\Pages\Faq;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Hasnayeen\Themes\ThemesPlugin;
use Filament\View\PanelsRenderHook;
use App\Filament\Admin\Pages\Dashboard;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Admin\Widgets\UsersAccountWidget;
use Filament\SpatieLaravelTranslatablePlugin;
use JibayMcs\FilamentTour\FilamentTourPlugin;
use App\Filament\Admin\Resources\UserResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Admin\Resources\ActivityResource;
use App\Filament\Admin\Resources\LoanBikeResource;
use App\Filament\Admin\Resources\ScheduleResource;
use App\Filament\Admin\Resources\AppointmentResource;
use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Filament\Admin\Resources\CustomerBikeResource;
use App\Filament\Admin\Resources\ServicePointResource;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Admin\Resources\InventoryItemResource;
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
                    NavigationGroup::make(__('admin-panel.schedule-resource-group'))
                        ->items([
                            ...AppointmentResource::getNavigationItems(),
                            ...ScheduleResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make(__('admin-panel.service-resource-group'))
                        ->items([
                            ...ServicePointResource::getNavigationItems(),
                            // Has no usage currently...InventoryItemResource::getNavigationItems(),
                            ...CustomerBikeResource::getNavigationItems(),
                            ...LoanBikeResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make(__('admin-panel.user-resource-group'))
                        ->items([
                            ...UserResource::getNavigationItems(),
                            ...ActivityResource::getNavigationItems(),
                        ]),
                ]);
            })
            // Needs livewire component and view
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, fn (): View => view('filament.hooks.test'))
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
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                UsersAccountWidget::class,
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
                ThemesPlugin::make(),
                FilamentTourPlugin::make()->enableCssSelector(),
            ]);
    }
}

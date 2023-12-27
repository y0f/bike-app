<?php

namespace App\Providers;

use App\Models\Appointment;
use Filament\Support\Assets\Css;
use Filament\Forms\Components\Field;
use App\Observers\AppointmentObserver;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Filament\Forms\Components\Actions\Action;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('theme', __DIR__ . '/../../resources/css/filament/admin/theme.css'),
        ]);
    }
}

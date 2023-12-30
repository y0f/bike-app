<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\LoanBike;
use App\Models\CustomerBike;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class ApplyTenantScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        CustomerBike::addGlobalScope(function (Builder $query) {
            $servicePoint = Filament::getTenant();
            $query->whereHas('servicePoints', function (Builder $query) use ($servicePoint) {
                $query->where('service_point_id', optional($servicePoint)->id);
            });
        });

        // Filament::getTenant() is how to get the service_point_id.
        LoanBike::addGlobalScope(function (Builder $query) {
            $query->whereBelongsTo(Filament::getTenant());
        });

        return $next($request);
    }
}

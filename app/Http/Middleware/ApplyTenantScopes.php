<?php

namespace App\Http\Middleware;

use App\Models\CustomerBike;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApplyTenantScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * @var \App\Models\Clinic $clinic The current clinic
         */
        $servicePoints = Filament::getTenant();
        CustomerBike::addGlobalScope(
            fn (Builder $query) =>
                $query->whereHas('servicePoints', fn (Builder $query) =>
                    $query->where('service_point_id', $servicePoints->id))
        );

        return $next($request);
    }
}
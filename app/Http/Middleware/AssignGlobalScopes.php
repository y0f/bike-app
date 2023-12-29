<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\LoanBike;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;

class AssignGlobalScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Schedule::addGlobalScope(function (Builder $query) {
            $query->whereBelongsTo(Filament::auth()->user(), 'owner');
        });

        Appointment::addGlobalScope(function (Builder $query) {
            $query->whereBelongsTo(Filament::auth()->user(), 'mechanic');
        });

        // Filament::getTenant() is how to get the service_point_id.
        LoanBike::addGlobalScope(function (Builder $query) {
            $query->whereBelongsTo(Filament::getTenant());
        });

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Schedule;
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

        return $next($request);
    }
}

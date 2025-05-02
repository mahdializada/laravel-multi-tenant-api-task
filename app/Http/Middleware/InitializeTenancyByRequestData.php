<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyByRequestData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('X-Tenant')) {
            return response()->json(['error' => 'Tenant not specified'], 400);
        }

        $tenant = Tenant::find($request->header('X-Tenant'));

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        tenancy()->initialize($tenant);

        return $next($request);
    }
}

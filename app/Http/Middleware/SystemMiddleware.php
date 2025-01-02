<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SystemService;
use Symfony\Component\HttpFoundation\Response;

class SystemMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (SystemService::isInstalled()) {
            // Block access to /install* if already installed
            if ($request->is('install*')) {
                return redirect(route('dashboard'));
            }
        } else {
            // Redirect to /install if not installed and not on install page
            if (!$request->is('install*')) {
                return redirect(route('install'));
            }
        }

        return $next($request);
    }
}

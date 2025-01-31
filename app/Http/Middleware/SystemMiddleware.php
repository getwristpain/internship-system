<?php

namespace App\Http\Middleware;

use App\Helpers\Logger;
use App\Services\SystemService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SystemMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Redirect based on system installation status
            $redirectRoute = $this->getRedirectRoute($request);
            if ($redirectRoute) {
                return redirect($redirectRoute);
            }

            return $next($request);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Logger::handle('error', __('system.error.message', ['context' => 'Server'])),
            ], 500);
        }
    }

    /**
     * Determine the appropriate redirect route based on system installation status.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function getRedirectRoute(Request $request): ?string
    {
        if (SystemService::isInstalled()) {
            return $request->is('install*') ? route('dashboard') : null;
        }

        return !$request->is('install*') ? route('install') : null;
    }
}

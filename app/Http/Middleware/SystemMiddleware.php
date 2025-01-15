<?php

namespace App\Http\Middleware;

use App\Helpers\Exception;
use Closure;
use Illuminate\Http\Request;
use App\Services\SystemService;
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
            // Check if the system is installed
            if (SystemService::isInstalled()) {
                // If installed, block access to '/install*' routes
                if ($request->is('install*')) {
                    return redirect(route('dashboard'));
                }
            } else {
                // If not installed, redirect to '/install' route
                if (!$request->is('install*')) {
                    return redirect(route('install'));
                }
            }

            // Proceed to the next middleware or request handler
            return $next($request);
        } catch (\Throwable $th) {
            // Handle the exception
            $message = Exception::handle(__('system.error.message', ['context' => 'Server']));

            // Return a JSON response with the error message
            return response()->json([
                'message' => $message,
            ], 500); // Optional: add HTTP status code, e.g., 500 for internal server error
        }
    }
}

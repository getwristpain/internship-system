<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Define the allowed roles for the author
        $allowedRolesForAuthor = ['owner', 'admin', 'department-staff'];

        // Check if the required role is 'author'
        if (in_array('author', $roles)) {
            // Ensure the user has one of the allowed roles for author
            if (in_array($user->roles->pluck('slug')->first(), $allowedRolesForAuthor)) {
                return $next($request);
            }
        } else {
            // Check if the user has any of the specified roles
            if (in_array($user->roles->pluck('slug')->first(), $roles)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized');
    }
}

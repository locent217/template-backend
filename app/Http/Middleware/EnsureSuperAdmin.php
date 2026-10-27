<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (!$user || !$user->active) {
            abort(403, 'Access denied.');
        }

        $user->loadMissing('userType');

        if ($user->userType?->code !== 'SUPER_ADMIN') {
            abort(403, 'Only Super Admin can manage user permissions.');
        }

        return $next($request);
    }
}

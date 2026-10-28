<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFirstUseCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->first_use && !$request->is('api/me', 'api/logout', 'api/account/credentials')) {
            return response()->json(['message' => 'You must update your initial credentials before continuing.', 'code' => 'FIRST_USE_REQUIRED'], 403);
        }
        return $next($request);
    }
}

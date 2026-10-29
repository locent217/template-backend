<?php

namespace App\Http\Middleware;

use App\Support\Maintenance\MaintenanceStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        // Status and maintenance management endpoints must stay reachable.
        if ($request->is('api/system/maintenance', 'api/system/maintenance/status')) {
            return $next($request);
        }

        $status = MaintenanceStatus::current();
        if (!$status['enabled']) {
            return $next($request);
        }

        // Super Admin bypass only. Authentication is still enforced by protected routes.
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->active) {
            $user->loadMissing('userType');
            if ($user->userType?->code === 'SUPER_ADMIN') {
                return $next($request);
            }
        }

        return response()->json([
            'code' => 'SYSTEM_MAINTENANCE',
            'message' => $status['message'],
        ], 503)->header('Retry-After', '60');
    }
}

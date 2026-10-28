<?php

namespace App\Http\Middleware;

use App\Modules\Permission\Service\PermissionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function __construct(
        protected PermissionService $permissionService
    ) {}

    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {
        $user = $request->user();

        if (
            !$user ||
            !$this->permissionService->hasPermission(
                $user,
                $permission
            )
        ) {
            app(\App\Support\Audit\AuditRecorder::class)->record('auth.access_denied', null, [], [], ['permission' => $permission]);
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}

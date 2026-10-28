<?php
namespace App\Http\Middleware;

use App\Support\Audit\AuditRecorder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeAuditLogAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || !$user->active) abort(403, 'Access denied.');
        $user->loadMissing(['userType', 'department']);
        $allowed = strtoupper((string) $user->userType?->code) === 'SUPER_ADMIN'
            || strtoupper((string) $user->department?->code) === 'INFORMATION_TECHNOLOGY';
        if (!$allowed) {
            app(AuditRecorder::class)->record('auth.access_denied', null, [], [], ['resource' => 'audit_logs']);
            abort(403, 'Audit log access denied.');
        }
        return $next($request);
    }
}

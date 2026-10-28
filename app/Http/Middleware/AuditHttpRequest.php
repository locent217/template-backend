<?php
namespace App\Http\Middleware;

use App\Support\Audit\Sanitizer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuditHttpRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $id = (string) Str::uuid(); // server-generated; never trust a client-provided ID
        $request->attributes->set('audit_request_id', $id);
        $start = hrtime(true);
        try {
            $response = $next($request);
            $response->headers->set('X-Request-ID', $id);
            return $response;
        } finally {
            $route = $request->route();
            $status = isset($response) ? $response->getStatusCode() : 500;
            $method = $request->method();
            if (config('audit.enabled') && ($method !== 'GET' || $status >= 400 || config('audit.log_reads'))) {
                $action = $route?->getActionName();
                $class = str_contains((string) $action, '@') ? explode('@', $action, 2)[0] : null;
                $function = $route?->getActionMethod();
                $path = null;
                if ($class && $function && method_exists($class, $function)) {
                    try { $path = str_replace(base_path() . DIRECTORY_SEPARATOR, '', (new \ReflectionMethod($class, $function))->getFileName() ?: ''); } catch (\ReflectionException) {}
                }
                Log::channel('audit_diagnostics')->info('api_request', [
                    'request_id' => $id, 'actor_id' => $request->user()?->getAuthIdentifier(),
                    'ip' => $request->ip(), 'method' => $method,
                    'route' => $route?->uri(), 'path' => $request->path(),
                    'controller' => $class, 'controller_method' => $function, 'controller_file' => $path,
                    'status' => $status, 'duration_ms' => round((hrtime(true) - $start) / 1e6, 2),
                    'payload' => $method === 'GET' ? [] : Sanitizer::bounded($request->except(['_token'])),
                ]);
            }
        }
    }
}

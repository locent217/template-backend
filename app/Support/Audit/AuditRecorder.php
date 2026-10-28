<?php
namespace App\Support\Audit;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AuditRecorder
{
    public function record(string $event, ?Model $subject = null, array $old = [], array $new = [], array $context = []): void
    {
        if (!config('audit.enabled')) return;
        try {
            $request = app()->bound('request') ? request() : null;
            $actor = $request?->user();
            $actorName = $actor ? trim(implode(' ', array_filter([$actor->first_name ?? null, $actor->middle_name ?? null, $actor->last_name ?? null]))) : null;
            AuditLog::query()->create([
                'request_id' => $request?->attributes->get('audit_request_id'),
                'actor_id' => $actor?->getAuthIdentifier(),
                'actor_name' => $actorName ?: ($actor->username ?? null),
                'actor_username' => $actor->username ?? null,
                'event' => $event,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject ? (string) $subject->getKey() : null,
                'old_values' => Sanitizer::bounded($old),
                'new_values' => Sanitizer::bounded($new),
                'context' => Sanitizer::bounded($context),
                'ip_address' => $request?->ip(),
            ]);
        } catch (Throwable $e) {
            // Audit storage failures must not leak private details into HTTP responses.
            Log::channel('audit_diagnostics')->error('audit_storage_failure', ['exception' => get_class($e)]);
        }
    }
}

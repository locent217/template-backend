<?php
namespace App\Http\Controllers;

use App\Modules\AuditLog\Service\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct(private AuditLogService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'request_id' => ['nullable', 'uuid'],
            'actor_id' => ['nullable', 'uuid'],
            'actor_search' => ['nullable', 'string', 'max:100'],
            'event' => ['nullable', 'string', 'max:80'],
            'ip_address' => ['nullable', 'ip'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);
        return response()->json($this->service->list($filters));
    }

    public function actors(Request $request): JsonResponse
    {
        $filters = $request->validate(['search' => ['nullable', 'string', 'max:100']]);
        return response()->json(['data' => $this->service->actors($filters['search'] ?? '')]);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json(['data' => $this->service->get($id)]);
    }
}

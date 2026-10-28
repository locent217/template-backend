<?php
namespace App\Http\Controllers;
use App\Modules\Notification\Service\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class NotificationController extends Controller
{
    public function __construct(private NotificationService $service) {}
    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->listFor($request->user(), (int) $request->integer('per_page', 15)));
    }
    public function markRead(Request $request, string $id): JsonResponse
    {
        abort_unless($this->service->mark($request->user(), $id, true), 404);
        return response()->json(['message' => 'Marked as read.']);
    }
    public function markUnread(Request $request, string $id): JsonResponse
    {
        abort_unless($this->service->mark($request->user(), $id, false), 404);
        return response()->json(['message' => 'Marked as unread.']);
    }
}

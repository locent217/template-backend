<?php

namespace App\Http\Controllers;

use App\Support\Maintenance\MaintenanceStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemMaintenanceController extends Controller
{
    public function status(): JsonResponse
    {
        return response()->json(['data' => MaintenanceStatus::current()]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'enabled' => ['required', 'boolean'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        DB::table('system_maintenance')->updateOrInsert(
            ['id' => 1],
            [
                'enabled' => $data['enabled'],
                'message' => $data['message'] ?? null,
                'updated_by' => $request->user()->id,
                'updated_at' => now(),
            ],
        );

        return $this->status();
    }
}

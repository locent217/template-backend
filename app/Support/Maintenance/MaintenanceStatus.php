<?php

namespace App\Support\Maintenance;

use Illuminate\Support\Facades\DB;

class MaintenanceStatus
{
    public static function current(): array
    {
        $record = DB::table('system_maintenance')->where('id', 1)->first();

        return [
            'enabled' => (bool) ($record?->enabled ?? false),
            'message' => $record?->message ?: 'The system is temporarily unavailable for maintenance.',
            'updated_at' => $record?->updated_at,
        ];
    }
}

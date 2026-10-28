<?php
return [
    // New models in these directories are automatically observed.
    'model_directories' => ['Models', 'Modules'],
    // Never audit the audit table itself. Add other sensitive/internal models here.
    'excluded_models' => [\App\Models\AuditLog::class],
    'enabled' => env('AUDIT_ENABLED', true),
    'log_reads' => env('AUDIT_LOG_READS', false),
    'max_payload_bytes' => (int) env('AUDIT_MAX_PAYLOAD_BYTES', 8192),
    'retention_days' => (int) env('AUDIT_RETENTION_DAYS', 180),
];

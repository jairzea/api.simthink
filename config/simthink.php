<?php

return [
    'storage' => [
        'driver' => env('SIMTHINK_STORAGE_DRIVER', 'local'),
        'disk'   => env('SIMTHINK_STORAGE_RAG_DISK', 'rag_local'),
    ],
    'limits' => [
        'max_total_storage_mb' => (int) env('SIMTHINK_MAX_TOTAL_STORAGE_MB', 5120),
        'max_file_mb' => 100, // por archivo
    ],
    'gateway' => [
        'url' => env('SIMTHINK_GATEWAY_URL', 'http://localhost:8000'),
    ],
];
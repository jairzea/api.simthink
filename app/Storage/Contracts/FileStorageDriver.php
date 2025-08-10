<?php

namespace App\Storage\Contracts;

use Illuminate\Http\UploadedFile;

interface FileStorageDriver {
    /** @return array{path: string, disk: string, size_kb: int, mime: string} */
    public function store(UploadedFile $file, string $userId, ?string $subfolder = null): array;

    public function delete(string $path): void;

    /** URL firmada o null si es privado */
    public function temporaryUrl(string $path, int $ttlSeconds = 3600): ?string;
}
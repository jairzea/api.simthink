<?php

namespace App\Storage\Drivers;

use App\Storage\Contracts\FileStorageDriver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LocalStorageDriver implements FileStorageDriver {
    public function __construct(private readonly string $disk) {}

    public function store(UploadedFile $file, string $userId, ?string $subfolder = null): array {
        $folder = trim("users/{$userId}/" . ($subfolder ?? ''), '/');
        $path = $file->store($folder, $this->disk);

        return [
            'path'    => $path,
            'disk'    => $this->disk,
            'size_kb' => (int) ceil($file->getSize() / 1024),
            'mime'    => $file->getClientMimeType(),
        ];
    }

    public function delete(string $path): void {
        Storage::disk($this->disk)->delete($path);
    }

    public function temporaryUrl(string $path, int $ttlSeconds = 3600): ?string {
        // Para local privado, normalmente retornas null o un endpoint proxy.
        return null;
    }
}
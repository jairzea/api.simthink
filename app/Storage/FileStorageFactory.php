<?php

// app/Storage/FileStorageFactory.php
namespace App\Storage;

use App\Storage\Contracts\FileStorageDriver;
use App\Storage\Drivers\LocalStorageDriver;
// use App\Storage\Drivers\S3StorageDriver;
use InvalidArgumentException;

class FileStorageFactory {
    public static function make(): FileStorageDriver {
        $driver = config('simthink.storage.driver', env('SIMTHINK_STORAGE_DRIVER', 'local'));
        $disk   = config('simthink.storage.disk', env('SIMTHINK_STORAGE_RAG_DISK', 'rag_local'));

        return match ($driver) {
            'local' => new LocalStorageDriver($disk),
            // 's3'    => new S3StorageDriver('rag_s3'),
            default => throw new InvalidArgumentException("Driver no soportado: {$driver}"),
        };
    }
}
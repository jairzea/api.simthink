<?php

namespace App\Services;

use App\Http\Resources\RagUploadResource;
use App\Models\RagUpload;
use App\Repositories\RagUploadRepository;
use App\Storage\Contracts\FileStorageDriver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class RagUploadService {
    public function __construct(
        private readonly FileStorageDriver $storage,
        private readonly RagUploadRepository $repo
    ) {}

    /** @return RagUpload[] */
    public function uploadFiles(string $userId, array $files, ?string $investigationId = null): array
    {
        $this->assertUserHasQuota($userId, $files);

        $saved = [];
        DB::transaction(function () use ($userId, $files, $investigationId, &$saved) {
            foreach ($files as $file) {
                /** @var UploadedFile $file */
                $stored = $this->storage->store($file, $userId, 'rag');

                $upload = $this->repo->create([
                    'user_id'         => $userId,
                    'investigation_id'=> $investigationId,
                    'filename'        => $file->getClientOriginalName(),
                    'path'            => $stored['path'],
                    'disk'            => $stored['disk'],
                    'mime_type'       => $stored['mime'],
                    'size_kb'         => $stored['size_kb'],
                    'file_type'       => $this->inferType($stored['mime']),
                    'status'          => 'uploaded',
                    'meta'            => ['hash' => sha1_file($file->getRealPath())],
                ]);

                $saved[] = $upload;

                // Evento/Job para post-proceso (OCR/parse/indexación vectorial)
                // event(new RagUploadStored($upload));
                // dispatch(new ProcessRagUploadJob($upload->id));
            }
        });

        return $saved;
    }

    private function inferType(?string $mime): ?string {
        if (!$mime) return null;
        return match(true) {
            str_contains($mime, 'pdf')   => 'pdf',
            str_contains($mime, 'word')  => 'docx',
            str_contains($mime, 'excel'),
            str_contains($mime, 'spreadsheet') => 'xlsx',
            str_contains($mime, 'text')  => 'txt',
            str_contains($mime, 'image') => 'image',
            default => null
        };
    }

    private function assertUserHasQuota(string $userId, array $files): void {
        $usedKb = $this->repo->sumUserStorageKb($userId);
        $incomingKb = array_reduce($files, fn($c, UploadedFile $f) => $c + (int)ceil($f->getSize()/1024), 0);
        $limitKb = config('simthink.limits.max_total_storage_mb') * 1024;

        if (($usedKb + $incomingKb) > $limitKb) {
            abort(413, 'Se supera el límite de almacenamiento asignado a tu cuenta.');
        }
    }

    public function delete(RagUpload $upload): void {
        // Primero borra del storage, luego DB
        $this->storage->delete($upload->path);
        $this->repo->delete($upload);
    }
}
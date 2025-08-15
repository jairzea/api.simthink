<?php

namespace App\Repositories;

use App\Models\RagUpload;

class RagUploadRepository {
    public function create(array $data): RagUpload {
        return RagUpload::create($data);
    }

    public function sumUserStorageKb(string $userId): int {
        return (int) RagUpload::where('user_id', $userId)->sum('size_kb');
    }

    public function delete(RagUpload $upload): void {
        $upload->delete();
    }
}
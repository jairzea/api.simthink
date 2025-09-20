<?php

namespace App\Repositories\Contracts;

use App\Models\Investigation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface InvestigationRepositoryInterface
{
    public function create(array $data): Investigation;
    public function find(string $id): ?Investigation;
    public function findByIdempotency(?string $key): ?Investigation;
    public function markProcessing(Investigation $inv): void;
    public function markCompleted(Investigation $inv, array $data): void;
    public function markPending(Investigation $inv, array|object $data): void;
    public function markFailed(Investigation $inv, string $msg): void;
    public function findByUser(int $perPage): ?LengthAwarePaginator;
    public function all(): ?Collection;
    public function claimForRun(Investigation $inv, array $data): bool;
}
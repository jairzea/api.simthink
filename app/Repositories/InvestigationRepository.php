<?php 

namespace App\Repositories;

use App\Models\Investigation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class InvestigationRepository
{
    public function all(): Collection
    {
        return Investigation::latest()->get();
    }

    public function find(string $id): ?Investigation
    {
        return Investigation::where('id', $id)->first();
    }

    public function findByUser(int $perPage = 10): ?LengthAwarePaginator
    {
        return auth()->user()->investigations()->latest()->paginate($perPage);
    }
}
<?php

namespace App\Services;

use App\Models\Investigation;
use App\Repositories\InvestigationRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class InvestigationService
{

    public function __construct(
        protected InvestigationRepository $repository
    ) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function findByUser(int $perPage)
    {
        return $this->repository->findByUser( $perPage);
    }

    public function store(array $data): Investigation
    {
        return Investigation::create([
            'id'              => Str::uuid(),
            'user_id'         => Auth::id(),
            'name'            => $data['name'],
            'type'            => $data['type'],
            'sample_size'     => $data['sample_size'],
            'use_rag'         => $data['use_rag'] ?? false,
            'cost_credits'    => $data['cost_credits'],
            'status'          => $data['status'],
            'context_info'    => $data['context_info'],
            'target_persona'  => $data['target_persona'],
            'research_goal'   => $data['research_goal'],
            'product_info'    => $data['product_info'],
        ]);
    }

    public function update(Investigation $investigation, array $data): Investigation
    {
        $investigation->update($data);
        return $investigation;
    }

    public function delete(Investigation $investigation): void
    {
        $investigation->delete();
    }
}
<?php

namespace App\Services;

use App\Enums\InvestigationStatus;
use App\Jobs\PrepareJob;
use App\Jobs\RunJob;
use App\Models\Investigation;
use App\Repositories\InvestigationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Log;

class InvestigationService
{

    public function __construct(
        protected InvestigationRepository $repository,
        protected AgentGatewayService $gatewayService,
    ) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function findById($id)
    {
        return $this->repository->find($id);
    }

    public function findByUser(int $perPage)
    {
        return $this->repository->findByUser( $perPage);
    }

    public function store(array $data): JsonResponse
    {
        $inv = $this->repository->create($data);

        PrepareJob::dispatch($inv->id)->onQueue('investigations');

        return response()->json(['investigation_id'=>$inv->id,'status'=>$inv->status], 202);
    }

    public function update(Investigation $investigation, array $data): Investigation
    {
        $investigation->update($data);
    
        return $investigation;
    }

    public function confirm(Investigation $investigation, array $data): JsonResponse
    {

        if ($investigation->status !== InvestigationStatus::PendingConfirmation->value) {
            return response()->json(['message'=>'Invalid status'], 409);
        }

        $claimed = $this->repository->claimForRun($investigation, $data);
        
        if (! $claimed) return response()->json(['message'=>'Already claimed'], 409);
        
        $user = Auth::user();
        RunJob::dispatch($investigation->id, $user)->onQueue('investigations');
        
        return response()->json([
            'investigation_id' => $investigation->id, 
            'status' => InvestigationStatus::Processing->value
        ], 202);
    }

    public function delete(Investigation $investigation): void
    {
        $investigation->delete();
    }
}
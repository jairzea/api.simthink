<?php

namespace App\Services;

use App\Enums\InvestigationStatus;
use App\Models\Investigation;
use App\Repositories\InvestigationRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Log;

class InvestigationService
{

    public function __construct(
        protected InvestigationRepository $repository,
        protected AgentGatewayService $gatewayService
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

    public function store(array $data): array
    {
        $investigation = Investigation::create([
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

        $this->update($investigation, [
            'status' => InvestigationStatus::PendingConfirmation,
        ]);

        // Llamar al prepare de FastAPI justo después de guardar
        $response = $this->gatewayService->prepare([
            'organization_info' => $investigation->context_info,
            'research_goal'     => $investigation->research_goal,
            'product_info'      => $investigation->product_info,
            'persona'           => $investigation->target_persona,
            'sample_size'       => $investigation->sample_size,
            'user_id'           => 123,//TODO: $investigation->user_id,
            'investigation_id'  => $investigation->id,
            'use_rag'           => $investigation->use_rag,
            'rag_ids'           => [], // podrías rellenarlo luego
        ]);

        Log::info("Response:", $response);

        return ["investigation_id" => $investigation->id, ...$response];
    }

    public function update(Investigation $investigation, array $data): Investigation
    {
        $investigation->update($data);
    
        return $investigation;
    }

    public function confirm(Investigation $investigation, array $data): array
    {
        $investigation->update($data);

        $result = $this->gatewayService->run(
            $data['temp_id'],
            $investigation->name
        );

    
        return $result;
    }

    public function delete(Investigation $investigation): void
    {
        $investigation->delete();
    }
}
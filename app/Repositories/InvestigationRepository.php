<?php 

namespace App\Repositories;

use App\Enums\InvestigationStatus;
use App\Models\Investigation;
use App\Repositories\Contracts\InvestigationRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Log;

class InvestigationRepository implements InvestigationRepositoryInterface
{

    protected Investigation $model;

    public function __construct(Investigation $model)
    {
        $this->model = $model;
    }
    public function all(): Collection
    {
        return $this->model::latest()->get();
    }

    public function find(string $id): ?Investigation
    {
        return $this->model::where('id', $id)->first();
    }

    public function findByUser(int $perPage = 10): ?LengthAwarePaginator
    {
        return auth()->user()->investigations()->latest()->paginate($perPage);
    }

     public function create(array $data): Investigation
    {
        return $this->model::create([
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

    public function markProcessing(Investigation $inv): void
    {
        $inv->update(['status' => 'processing', 'started_at' => now()]);
    }

    public function markCompleted(Investigation $inv, $data): void
    {

        $agent10 = $data['agent10_final_report'] ?? '';
        
        $refinedHypotheses = $data['refined_hypotheses'] ?? [];

        $hypothesesTexto = collect($refinedHypotheses)
                ->map(fn($item) => '• ' . $item)
                ->implode("\n");
                
        $inv->update([
            'status'        => InvestigationStatus::Completed->value,
            'context_info'    => $data['agent5_market_plan'],
            'target_persona'  => $data['refined_customer_profile'],
            'research_goal'   => $hypothesesTexto,
            'product_info'    => $data['product_analysis_summary'],
            'result_summary'  => $agent10,
            'cost_credits'    => 10
        ]);
    }

    public function markPending(Investigation $inv, $prepareResult): void
    {
        
        $inv->update([
            'status' => InvestigationStatus::PendingConfirmation->value,
            'context_info' => $prepareResult['companyRawDescription'] ?? null,
            'target_persona' => $prepareResult['customerProfile'] ?? null,
            'research_goal' => $prepareResult['hypotheses'] ?? [],
            'product_info' => $prepareResult['productSummary'] ?? null,
            'temp_id' => $prepareResult['tempId'] ?? null,
        ]);
    }

        public function markFailed(Investigation $inv, $msg): void
    {
        $inv->update([
            'status' => InvestigationStatus::Failed->value,
            'error_message' => $msg
        ]);
    }

    public function claimForRun(Investigation $inv, array $data): bool
    {

        Log::info("name", $data);
        
        return $this->model::where('id', $inv->id)
            ->where('status', InvestigationStatus::PendingConfirmation->value)
            ->update([
                'status' => InvestigationStatus::Processing->value,
                'name' => $data['name']
                ]) === 1;
    }

    public function findByIdempotency(?string $key): ?Investigation
    {
        return $key ? $this->model::where('idempotency_key', $key)->first() : null;
    }

}
<?php
namespace App\Services\Credits;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InsufficientCreditsException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\CreditTransactionRepositoryInterface;

class CreditDeductionService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly CreditTransactionRepositoryInterface $creditTx
    ) {}

    /**
     * Descuenta créditos del usuario de forma atómica y registra la transacción.
     */
    public function deduct(User $user, int $credits, array $meta = []): void
    {
        if ($credits <= 0) {
            throw new \InvalidArgumentException('La cantidad de créditos a descontar debe ser mayor a 0.');
        }

        DB::transaction(function () use ($user, $credits, $meta) {
            // Paso 1: deducción atómica (sin condiciones de carrera)
            $ok = $this->users->deductCreditsAtomic((string) $user->id, $credits);

            if (!$ok) {
                throw new InsufficientCreditsException();
            }

            // Paso 2: registrar transacción (auditoría)
            $this->creditTx->createForUser($user->fresh(), [
                'amount_usd'      => 0,                // no es compra
                'credits_added'   => -$credits,        // negativo = deducción
                'payment_method'  => 'deduction',      // o 'system'
                'status'          => 'completed',
                'invoice_number'  => '---',
                'metadata'            => $meta,            // JSON opcional (motivo, referencia)
            ]);
        });
    }
}
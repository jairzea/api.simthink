<?php
namespace App\Repositories\Contracts;

use App\Models\CreditTransaction;
use App\Models\User;

interface CreditTransactionRepositoryInterface
{
    /**
     * Registra una transacción (puede ser negativa para deducciones).
     */
    public function createForUser(User $user, array $data): CreditTransaction;
}
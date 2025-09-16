<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\CreditTransaction;
use App\Repositories\Contracts\CreditTransactionRepositoryInterface;

class CreditTransactionRepository implements CreditTransactionRepositoryInterface
{
    public function createForUser(User $user, array $data): CreditTransaction
    {
        // Asume que User tiene ->creditTransactions() ya definido.
        return $user->creditTransactions()->create($data);
    }
}
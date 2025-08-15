<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditTransactionStoreRequest;
use App\Http\Requests\CreditTransactionUpdateRequest;
use App\Http\Resources\CreditTransactionResource;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CreditTransactionController extends Controller
{
    public function index(Request $request): Response
    {
        $creditTransactions = CreditTransaction::all();
    }

    public function store(CreditTransactionStoreRequest $request): Response
    {
        $creditTransaction = CreditTransaction::create($request->validated());
    }

    public function show(Request $request, CreditTransaction $creditTransaction): CreditTransactionResource
    {
        return new CreditTransactionResource($credit_transaction);
    }

    public function update(CreditTransactionUpdateRequest $request, CreditTransaction $creditTransaction): Response
    {
        $creditTransaction->update($request->validated());
    }

    public function destroy(Request $request, CreditTransaction $creditTransaction): Response
    {
        $creditTransaction->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Add a new transaction (income or expense) to a wallet.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validate required fields, positive amount, and valid transaction type
        $validated = $request->validate([
            'wallet_id'   => 'required|exists:wallets,id',
            'type'        => 'required|in:income,expense',
            'amount'      => 'required|numeric|gt:0',
            'description' => 'nullable|string|max:255',
        ]);

        // Create the transaction
        $transaction = Transaction::create($validated);

        return response()->json([
            'message' => 'Transaction created successfully.',
            'data'    => $transaction,
        ], 201);
    }
}

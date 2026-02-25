<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Create a new wallet for a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validate required fields
        $validated = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        // Create the wallet
        $wallet = Wallet::create($validated);

        return response()->json([
            'message' => 'Wallet created successfully.',
            'data'    => $wallet,
        ], 201);
    }

    /**
     * Display a specific wallet with its balance and all transactions.
     *
     * @param Wallet $wallet
     * @return JsonResponse
     */
    public function show(Wallet $wallet): JsonResponse
    {
        // Eager-load transactions for this wallet
        $wallet->load('transactions');

        return response()->json([
            'data' => [
                'id'           => $wallet->id,
                'user_id'      => $wallet->user_id,
                'name'         => $wallet->name,
                'description'  => $wallet->description,
                'balance'      => $wallet->balance,
                'transactions' => $wallet->transactions,
                'created_at'   => $wallet->created_at,
                'updated_at'   => $wallet->updated_at,
            ],
        ]);
    }
}

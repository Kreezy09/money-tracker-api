<?php

namespace App\Http\Controllers;

use App\Http\Resources\WalletResource;
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

        return (new WalletResource($wallet))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display a specific wallet with its balance and all transactions.
     *
     * @param Wallet $wallet
     * @return WalletResource
     */
    public function show(Wallet $wallet): WalletResource
    {
        // Eager-load transactions for this wallet
        $wallet->load('transactions');

        return new WalletResource($wallet);
    }
}

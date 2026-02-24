<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new user account.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validate required fields
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        // Create the user
        $user = User::create($validated);

        return response()->json([
            'message' => 'User created successfully.',
            'data'    => $user,
        ], 201);
    }

    /**
     * Display a user's profile with all wallets, each wallet's balance,
     * and the total balance across all wallets.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        // Eager-load wallets
        $user->load('wallets');

        // Build the response with wallet balances and total balance
        $wallets = $user->wallets->map(function ($wallet) {
            return [
                'id'          => $wallet->id,
                'name'        => $wallet->name,
                'description' => $wallet->description,
                'balance'     => $wallet->balance,
                'created_at'  => $wallet->created_at,
                'updated_at'  => $wallet->updated_at,
            ];
        });

        return response()->json([
            'data' => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'wallets'       => $wallets,
                'total_balance' => $user->total_balance,
                'created_at'    => $user->created_at,
                'updated_at'    => $user->updated_at,
            ],
        ]);
    }
}

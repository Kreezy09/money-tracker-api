<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
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

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display a user's profile with all wallets, each wallet's balance,
     * and the total balance across all wallets.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        // Eager-load wallets to include balances and totals
        $user->load('wallets');

        return new UserResource($user);
    }
}

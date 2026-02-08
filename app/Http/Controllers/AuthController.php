<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Create token with or without expiration based on remember_me
        $tokenName = 'chat-token';
        
        if ($request->remember_me) {
            // No expiration - token lasts indefinitely
            $token = $user->createToken($tokenName)->plainTextToken;
        } else {
            // Token expires in 24 hours
            $token = $user->createToken(
                $tokenName,
                ['*'],
                now()->addHours(24)
            )->plainTextToken;
        }

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
        ], 'User registered successfully', 201);
    }

    /**
     * Login user and create token
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create token with or without expiration based on remember_me
        $tokenName = 'chat-token';
        
        if ($request->remember_me) {
            // No expiration - token lasts indefinitely
            $token = $user->createToken($tokenName)->plainTextToken;
        } else {
            // Token expires in 24 hours
            $token = $user->createToken(
                $tokenName,
                ['*'],
                now()->addHours(24)
            )->plainTextToken;
        }

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
        ], 'Login successful');
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        // Delete current access token
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout successful');
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        $user = $request->user()->load(['team', 'chats']);

        return $this->successResponse($user, 'User retrieved successfully');
    }
}

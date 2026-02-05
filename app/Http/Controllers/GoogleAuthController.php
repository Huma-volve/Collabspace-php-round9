<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    use ApiResponse;

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Get user info from Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Check if user exists by google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            if(!$user)
            {
                // Check if email already exists (user registered normally)
                $user = User::where('email', $googleUser->getEmail())->first();
                
                // Create new user
                if($user) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'email_verified_at' => now(),
                    ]);
                }
                else {
                    $user = User::create([
                        'full_name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password' => null, 
                        'email_verified_at' => now(),
                    ]);
                }
            }

            // Create authentication token
            $token = $user->createToken('google-auth-token')->plainTextToken;

            return $this->successResponse([
                'user' => $user,
                'token' => $token,
            ], 'Successfully authenticated with Google');

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to authenticate with Google: ' . $e->getMessage(),
                500
            );
        }
    }
}

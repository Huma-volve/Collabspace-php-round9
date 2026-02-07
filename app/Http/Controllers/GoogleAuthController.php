<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;


class GoogleAuthController extends Controller
{

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

            // Redirect to chat-test page with token and user info
            return redirect('/chat-test?' . http_build_query([
                'token' => $token,
                'user_id' => $user->id,
                'user_name' => $user->full_name
            ]));

        } catch (\Exception $e) {
            return redirect('/google-test?error=' . urlencode('Failed to authenticate with Google'));
        }
    }
}

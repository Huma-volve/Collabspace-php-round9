<?php

namespace App\Traits;

use App\Models\User;

/**
 * MockAuth Trait
 * 
 * Use this trait for testing APIs without setting up authentication.
 * It provides a mock authenticated user for development/testing purposes.
 * 
 * WARNING: Remove this from production code!
 */
trait MockAuth
{
    /**
     * Get the authenticated user (mocked for testing)
     * 
     * @return User|null
     */
    protected function getAuthUser(): ?User
    {
        // In production, this should be: auth()->user()
        // For testing, we'll return the first user or create a mock one
        
        // Option 1: Return first user from database
        return User::find(2);
        
        // Option 2: Return specific user by ID
        // return User::find(1);
        
        // Option 3: Create a mock user on the fly (not saved to DB)
        // return new User([
        //     'id' => 1,
        //     'name' => 'Test User',
        //     'email' => 'test@example.com'
        // ]);
    }
    
    /**
     * Get the authenticated user's ID
     * 
     * @return int|null
     */
    protected function getAuthUserId(): ?int
    {
        return $this->getAuthUser()?->id;
    }
    
    /**
     * Check if user is authenticated (always true for testing)
     * 
     * @return bool
     */
    protected function isAuthenticated(): bool
    {
        return $this->getAuthUser() !== null;
    }
}

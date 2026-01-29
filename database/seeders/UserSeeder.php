<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 users for testing (without team_id first)
        
        // User 1: Ahmed (will be Backend Team leader)
        $ahmed = User::create([
            'full_name' => 'Ahmed Mohamed',
            'email' => 'ahmed@example.com',
            'password' => Hash::make('password123'),
            'phone' => '01012345678',
            'job_title' => 'Backend Developer',
            'role' => 'admin',
            'status' => 1,
            'availability' => 1,
            'about' => 'PHP & Laravel Developer with 5 years of experience',
            'experience' => 'senior',
            'experience_year' => '5 years',
            //'team_id' => Team::where('name', 'Backend Team')->first()->id,
        ]);

        // User 2: Sara (will be Frontend Team leader)
        $sara = User::create([
            'full_name' => 'Sara Ali',
            'email' => 'sara@example.com',
            'password' => Hash::make('password123'),
            'phone' => '01098765432',
            'job_title' => 'Frontend Developer',
            'role' => 'employee',
            'status' => 1,
            'availability' => 1,
            'about' => 'React & Vue.js specialist',
            'experience' => 'mid',
            'experience_year' => '3 years',
            //'team_id' => Team::where('name', 'Frontend Team')->first()->id,
        ]);

        // User 3: Mohamed Hassan (will join Frontend Team)
        $mohamed = User::create([
            'full_name' => 'Mohamed Hassan',
            'email' => 'mohamed@example.com',
            'password' => Hash::make('password123'),
            'phone' => '01123456789',
            'job_title' => 'UI/UX Designer',
            'role' => 'employee',
            'status' => 1,
            'availability' => 1,
            'about' => 'Creative designer focused on user experience',
            'experience' => 'mid',
            'experience_year' => '4 years',
            //'team_id' => Team::where('name', 'Frontend Team')->first()->id,
        ]);
        
        // Note: team_id will be assigned after TeamSeeder runs
        // This is handled in DatabaseSeeder by calling assignUsersToTeams()
    }

}

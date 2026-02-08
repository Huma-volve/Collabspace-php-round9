<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create 3 users for testing (without team_id first)
        
        // User 1: Ahmed (will be Backend Team leader)
        $ahmed = User::create([
            'full_name' => 'Ahmed Mohamed',
            'email' => 't79861325@gmail.com',
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
            'email' => 't18472116@gmail.com',
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
        User::insert([
            [
                'full_name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'phone' => '01000000001',
                'job_title' => 'System Administrator',
                'role' => 'admin',
                'status' => 1,
                'availability' => 1,
                'about' => 'Main system administrator',
                'experience_year' => 10,
                'experience' => 'senior',
                'team_id' => 1, // تأكد إن team_id = 1 موجود
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'Ahmed Hassan',
                'email' => 'ahmed@example.com',
                'password' => Hash::make('password'),
                'phone' => '01000000002',
                'job_title' => 'Frontend Developer',
                'role' => 'employee',
                'status' => 1,
                'availability' => 1,
                'about' => 'Frontend specialist',
                'experience_year' => 3,
                'experience' => 'mid',
                'team_id' => 1,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'Sara Mohamed',
                'email' => 'sara@example.com',
                'password' => Hash::make('password'),
                'phone' => '01000000003',
                'job_title' => 'Backend Developer',
                'role' => 'employee',
                'status' => 1,
                'availability' => 0,
                'about' => 'Backend APIs developer',
                'experience_year' => 1,
                'experience' => 'junior',
                'team_id' => 2, // تأكد إن team_id = 2 موجود
                'email_verified_at' => now(),
            ],
        ]);
    }

}

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
        User::insert([
            // [
            //     'full_name' => 'Mohamed',
            //     'email' => 'Mohamedd@example.com',
            //     'password' => Hash::make('password'),
            //     'phone' => '01000000001',
            //     'job_title' => 'System Administrator',
            //     'role' => 'admin',
            //     'status' => 1,
            //     'availability' => 1,
            //     'about' => 'Main system administrator',
            //     'experience_year' => 10,
            //     'experience' => 'senior',
            //     'team_id' => 1, // تأكد إن team_id = 1 موجود
            //     'email_verified_at' => now(),
            // ],
            // [
            //     'full_name' => 'Ahmed Hassan',
            //     'email' => 'nasserd@example.com',
            //     'password' => Hash::make('password'),
            //     'phone' => '01000000002',
            //     'job_title' => 'Frontend Developer',
            //     'role' => 'employee',
            //     'status' => 1,
            //     'availability' => 1,
            //     'about' => 'Frontend specialist',
            //     'experience_year' => 3,
            //     'experience' => 'mid',
            //     'team_id' => 1,
            //     'email_verified_at' => now(),
            // ],
            // [
            //     'full_name' => 'Sara Mohamed',
            //     'email' => 'saraahmadd@example.com',
            //     'password' => Hash::make('password'),
            //     'phone' => '01000000003',
            //     'job_title' => 'Backend Developer',
            //     'role' => 'employee',
            //     'status' => 1,
            //     'availability' => 0,
            //     'about' => 'Backend APIs developer',
            //     'experience_year' => 1,
            //     'experience' => 'junior',
            //     'team_id' => 2, // تأكد إن team_id = 2 موجود
            //     'email_verified_at' => now(),
            // ],
                [
                'full_name' => 'yasser Mohamed',
                'email' => 'yasser@example.com',
                'password' => Hash::make('123456789'),
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

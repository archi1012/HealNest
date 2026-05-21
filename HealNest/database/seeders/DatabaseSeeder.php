<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Admin HealNest',
                'email'    => 'admin@healnest.com',
                'password' => Hash::make('admin1234'),
                'age'      => 30,
                'role'     => 'admin',
            ],
            [
                'name'     => 'Dr. Sarah Counselor',
                'email'    => 'counselor@healnest.com',
                'password' => Hash::make('counselor1234'),
                'age'      => 28,
                'role'     => 'counselor',
            ],
            [
                'name'     => 'Test User',
                'email'    => 'user@healnest.com',
                'password' => Hash::make('user1234'),
                'age'      => 20,
                'role'     => 'user',
            ],
        ];

        foreach ($users as $data) {
            if (!User::where('email', $data['email'])->exists()) {
                User::create($data);
            }
        }
    }
}

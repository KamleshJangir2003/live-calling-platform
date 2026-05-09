<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@livecall.com',
            'phone' => '9876543210',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'wallet_balance' => 0,
            'phone_verified' => true,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Test Model',
            'email' => 'model@livecall.com',
            'phone' => '9876543211',
            'password' => Hash::make('model123'),
            'role' => 'model',
            'wallet_balance' => 500,
            'phone_verified' => true,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'user@livecall.com',
            'phone' => '9876543212',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'wallet_balance' => 1000,
            'phone_verified' => true,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}

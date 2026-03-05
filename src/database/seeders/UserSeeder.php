<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->delete();

        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'COACHTECHビル 101',
        ]);

        User::create([
            'name' => 'user1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市1-2-3',
            'building' => 'テストビル 202',
        ]);
    }
}

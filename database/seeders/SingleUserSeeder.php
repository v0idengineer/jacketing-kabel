<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SingleUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'riyadi256789@gmail.com'],
            [
                'name' => 'operator',
                'password' => Hash::make('InitPass!234'), // password awal
                'next_password_rotate_at' => now()->startOfMonth()->addMonth()->setTime(0, 5),
                'is_single_account' => true,
            ]
        );
    }
}

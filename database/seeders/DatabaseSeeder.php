<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('POS_ADMIN_PASSWORD');

        if (! is_string($password) || strlen($password) < 12) {
            throw new RuntimeException(
                'Set POS_ADMIN_PASSWORD in .env to at least 12 characters before seeding.'
            );
        }

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'full_name' => 'Administrator',
                'email' => env('POS_ADMIN_EMAIL', 'admin@example.test'),
                'password' => Hash::make($password),
            ]
        );
    }
}
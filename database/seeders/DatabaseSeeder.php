<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ADMIN (akun tetap)
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'alamat' => 'Kantor',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // USER CONTOH (optional)
        User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User',
                'username' => 'user',
                'alamat' => 'Rumah',
                'role' => 'user',
                'password' => Hash::make('user12345'),
            ]
        );
    }
}

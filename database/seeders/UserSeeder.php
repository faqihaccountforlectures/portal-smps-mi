<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Akun Admin Default dari file .env (dengan fallback aman)
        User::create([
            'email' => env('ADMIN_DEFAULT_EMAIL', 'admin@smpsmi.com'),
            'password' => Hash::make(env('ADMIN_DEFAULT_PASSWORD', 'password123')),
            'role' => 'admin'
        ]);
    }
}

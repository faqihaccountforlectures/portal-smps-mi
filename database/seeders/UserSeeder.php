<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'email' => 'admin@smpsmi.com',
            'password' => Hash::make('password123'), // Password percobaan
            'role' => 'admin'
        ]);

        // Buat Akun Guru & Profilnya
        $guru = User::create([
            'email' => 'guru@smpsmi.com',
            'password' => Hash::make('password123'),
            'role' => 'guru',
        ]);

        TeacherProfile::create([
            'user_id' => $guru->id,
            'nip' => '198001012005011001',
            'full_name' => 'Budi Santoso, S.Pd.',
            'position' => 'guru',
            'gender' => 'laki-laki',
            'phone_number' => '081234567890',
        ]);

        // Buat Akun Siswa & Profilnya
        $siswa = User::create([
            'email' => 'siswa@smpsmi.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
        ]);

        StudentProfile::create([
            'user_id' => $siswa->id,
            'nisn' => '0012345678',
            'full_name' => 'Andi Pratama',
            'gender' => 'laki-laki',
            'phone_number' => '089876543210',
            'parent_phone' => '081122334455',
        ]);
    }
}

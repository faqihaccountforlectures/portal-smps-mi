<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Kolom-kolom di database yang boleh diisi secara massal (misal pake metode create atau update)
    protected $fillable = [
        'email',
        'password',
        'google_id', // Buat nyimpen ID dari Google SSO
        'role', // Buat nentuin dia itu admin, guru, atau siswa
    ];

    // Kolom yang disembunyiin pas datanya diambil (biar password gak bocor di respon API/JSON)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Otomatis ngubah (casting) data password jadi bentuk hash (enkripsi) sebelum disimpen ke database
    protected $casts = [
        'password' => 'hashed',
    ];

    // Relasi One-to-One: Nentuin kalo satu Akun User punya satu Profil Siswa.
    // Jadi nanti gampang manggilnya, misal: $user->studentProfile
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    // Relasi One-to-One: Nentuin kalo satu Akun User punya satu Profil Guru.
    // Cara kerjanya sama persis kayak profil siswa di atas
    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class);
    }
}
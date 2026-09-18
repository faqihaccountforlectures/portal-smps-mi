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
        'name',         // Nama Lengkap Pengguna (Admin/Pengguna Sistem)
        'email',        // Email / Username untuk Login
        'phone_number', // Nomor Telepon / WhatsApp
        'password',
        'google_id',    // Buat nyimpen ID dari Google SSO
        'role',         // Buat nentuin dia itu admin, guru, atau siswa
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

    /**
     * Relasi ke penugasan mengajar guru (TeacherAssignment).
     */
    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'teacher_id');
    }

    /**
     * FUNGSI KODE: Accessor untuk mendapatkan nama lengkap tampilan pengguna.
     * Secara cerdas memeriksa profil pengguna sesuai peran:
     * - Jika Guru: mengambil nama dari teacherProfile->full_name.
     * - Jika Siswa: mengambil nama dari studentProfile->full_name.
     * - Jika Admin atau profil belum ada: menggunakan kolom 'name' atau potongan awal email.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->role === 'guru' && $this->relationLoaded('teacherProfile') ? $this->teacherProfile : $this->teacherProfile()->first()) {
            $profile = $this->teacherProfile;
            if (!empty($profile->full_name)) {
                return $profile->full_name;
            }
        }

        if ($this->role === 'siswa' && $this->relationLoaded('studentProfile') ? $this->studentProfile : $this->studentProfile()->first()) {
            $profile = $this->studentProfile;
            if (!empty($profile->full_name)) {
                return $profile->full_name;
            }
        }

        return !empty($this->name) ? $this->name : explode('@', $this->email)[0];
    }

    /**
     * FUNGSI KODE: Accessor untuk mendapatkan 1 karakter inisial nama depan pengguna
     * untuk dirender sebagai foto profil lingkaran (avatar placeholder) di topbar.
     */
    public function getInitialAttribute(): string
    {
        $name = trim($this->display_name);
        return strtoupper(mb_substr($name, 0, 1, 'UTF-8')) ?: 'U';
    }
}
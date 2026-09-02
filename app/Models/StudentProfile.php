<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    // Kolom-kolom yang boleh diisi sekalian (mass assignment)
    protected $fillable = [
        'user_id',
        'nisn',
        'full_name',
        'gender',
        'phone_number',
        'parent_phone',
        'status',
    ];

    // Relasi balik (Inverse) ke tabel users
    // Biar kalo kita nge-load profil siswa, kita gampang nyari ini profil punya akun login siapa
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
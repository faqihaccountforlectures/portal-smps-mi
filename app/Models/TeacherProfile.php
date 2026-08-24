<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    use HasFactory;

    // Daftar kolom yang bisa diisi sekalian (mass assignment)
    protected $fillable = [
        'user_id',
        'nip',
        'full_name',
        'position',
        'gender',
        'phone_number',
    ];

    // Relasi balik (Inverse) ke tabel users
    // Biar kalo kita manggil profil guru, kita bisa tau ini profil punya akun (email) siapa
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

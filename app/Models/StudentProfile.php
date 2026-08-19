<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'nisn',
        'full_name',
        'gender',
        'phone_number',
        'parent_phone',
    ];

    /**
     * Relasi balik ke tabel User (One-to-One Inverse).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
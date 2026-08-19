<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'nip',
        'full_name',
        'position',
        'gender',
        'phone_number',
    ];

    /**
     * Relasi balik ke tabel User (One-to-One Inverse).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

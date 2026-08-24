<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    // Kolom-kolom yang diizinkan untuk diisi secara massal via form
    protected $fillable = [
        'name',
        'grade_level',
        'homeroom_teacher_id'
    ];

    // Relasi ke tabel users (khusus yang rolenya Guru) yang jadi wali kelas
    // Jadi dari kelas kita bisa langsung nge-load data akun wali kelasnya
    public function homeroomTeacher()
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    // Relasi ke tabel class_enrollments.
    // Ibaratnya, ini tuh buat nyari "Siapa aja sih anak-anak yang terdaftar dan duduk di kelas ini?"
    // Lewat sini kita bisa ngambil semua histori pendaftaran kelas (enrollments) yang nyangkut ke ID kelas ini.
    public function enrollments()
    {
        return $this->hasMany(ClassEnrollment::class, 'class_room_id');
    }
}

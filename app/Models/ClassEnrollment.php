<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_room_id',
        'academic_year_id',
        'status'
    ];

    // Relasi balik ke Siswa
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relasi balik ke Kelas
    public function classRoom()
    {
        return $this->belongsTo(User::class, 'class_room_id');
    }

    // Relasi balik ke Tahun Ajaran
    public function academicYear()
    {
        return $this->belongsTo(User::class, 'academic_year_id');
    }
}
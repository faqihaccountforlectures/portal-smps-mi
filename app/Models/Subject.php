<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'grade_level',
        'kkm',
        'category',
    ];

    /**
     * FUNGSI KODE: Relasi ke tabel penugasan guru (teacher_assignments).
     * Satu mata pelajaran dapat ditugaskan ke beberapa guru dan kelas.
     */
    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'subject_id');
    }
}

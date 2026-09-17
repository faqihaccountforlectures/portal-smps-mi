<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'class_room_id',
        'academic_year_id'
    ];

    // Relasi balik ke Guru
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    // Relasi balik ke Mata Pelajaran
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Relasi balik ke Kelas
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_id');
    }

    /**
     * Jadwal pelajaran dari penugasan ini
     */
    public function lessonSchedules()
    {
        return $this->hasMany(LessonSchedule::class);
    }

    // Relasi balik ke Tahun Ajaran
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    /**
     * FUNGSI KODE: Relasi one-to-many ke model Materi Pelajaran (LearningMaterial).
     * Satu penugasan guru (alokasi mapel di suatu kelas) dapat memiliki banyak berkas/materi pelajaran.
     */
    public function learningMaterials()
    {
        return $this->hasMany(LearningMaterial::class, 'teacher_assignment_id');
    }
}

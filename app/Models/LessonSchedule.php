<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_assignment_id',
        'day_of_week',
        'start_time',
        'end_time'
    ];

    /**
     * Relasi ke TeacherAssignment (Penugasan Guru)
     */
    public function teacherAssignment()
    {
        return $this->belongsTo(TeacherAssignment::class);
    }
}

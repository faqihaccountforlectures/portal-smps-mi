<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    // Nentuin kolom mana aja yang boleh diisi lewat form (Mass Assignment)
    protected $fillable = [
        'year_name',
        'semester',
        'is_active',
    ];

    /**
     * Relasi ke penugasan guru pada tahun ajaran ini.
     */
    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'academic_year_id');
    }
}

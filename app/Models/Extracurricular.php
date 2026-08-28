<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'schedule',
        'teacher_id',
        'fee'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function registrations()
    {
        return $this->hasMany(ExtracurricularRegistration::class, 'extracurricular_id');
    }
}

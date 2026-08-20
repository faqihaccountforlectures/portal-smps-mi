<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtracurricularRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'extracurricular_id',
        'payment_id',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class, 'extracurricular_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}

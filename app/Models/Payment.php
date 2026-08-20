<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'total_amount',
        'payment_method',
        'proof_of_payment',
        'payment_status',
        'verified_by'
    ];

   public function student()
   {
    return $this->belongsTo(User::class, 'student_id');
   } 

   public function verifier()
   {
    return $this->belongsTo(User::class, 'verified_by');
   }
}

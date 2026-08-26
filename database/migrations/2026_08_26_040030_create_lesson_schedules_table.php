<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lesson_schedules', function (Blueprint $table) {
            $table->id();
            // Relasi ke teacher_assignments (guru + mapel + kelas + tahun ajaran)
            $table->foreignId('teacher_assignment_id')->constrained('teacher_assignments')->onDelete('cascade');
            
            // Hari (Senin, Selasa, dst)
            $table->string('day_of_week');
            
            // Jam mulai dan jam selesai
            $table->time('start_time');
            $table->time('end_time');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_schedules');
    }
};

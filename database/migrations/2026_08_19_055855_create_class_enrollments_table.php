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
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id();

            // Relasi ke Siswa (users)
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');

            // Relasi ke Kelas (class_rooms)
            $table->foreignId('class_room_id')->constrained('class_rooms')->onDelete('cascade');

            // Relasi ke Tahun Ajaran (academic_years)
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');

            $table->enum('status', ['aktif', 'lulus', 'pindah'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_enrollments');
    }
};

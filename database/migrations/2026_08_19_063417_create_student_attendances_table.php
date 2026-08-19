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
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel terkait
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recorded_by_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('class_room_id')->constrained('class_rooms')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');

            // Data Absensi Geolocation
            $table->date('date');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa']);
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};

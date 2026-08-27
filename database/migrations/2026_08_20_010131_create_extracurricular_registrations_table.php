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
        Schema::create('extracurricular_registrations', function (Blueprint $table) {
            $table->id();

            // Relasi ke Siswa, Eskul, dan Pembayaran
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('extracurricular_id')->constrained('extracurriculars')->onDelete('cascade');

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracurricular_registrations');
    }
};

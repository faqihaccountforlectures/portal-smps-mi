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
        Schema::create('teacher_profiles', function (Blueprint $table) {
             $table->id(); // Primary Key
            // Relasi One-to-One dengan tabel users
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nip', 30)->unique(); // NIP atau NUPTK guru
            $table->string('full_name', 100); // Nama lengkap beserta gelar
            $table->enum('position', ['guru', 'kepala_sekolah', 'wakil_kepala_sekolah'])->default('guru'); // Jabatan guru
            $table->enum('gender', ['laki-laki', 'perempuan']); // Jenis kelamin guru
            $table->string('phone_number', 20)->nullable(); // Nomor telepon guru
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};

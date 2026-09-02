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
        Schema::create('student_profiles', function (Blueprint $table) {
           $table->id(); // Primary Key
            // Relasi One-to-One dengan tabel users (jika user dihapus, profil ikut terhapus)
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nisn', 20)->unique(); // Nomor Induk Siswa Nasional
            $table->string('full_name', 100); // Nama lengkap siswa
            $table->enum('gender', ['laki-laki', 'perempuan']); // Jenis kelamin siswa
            $table->string('phone_number', 20)->nullable(); // Nomor telepon siswa
            $table->string('parent_phone', 20)->nullable(); // Nomor telepon orang tua siswa
            $table->enum('status', ['aktif', 'lulus', 'keluar'])->default('aktif'); // Status siswa
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};

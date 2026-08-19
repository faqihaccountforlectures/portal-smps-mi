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
        Schema::create('users', function (Blueprint $table) {
           $table->id(); // Primary Key: id (BIGINT UNSIGNED)
            $table->string('email')->unique(); // Email untuk login / notifikasi
            $table->string('password')->nullable(); // Nullable jika login lewat Google OAuth
            $table->string('google_id')->nullable(); // ID akun google dari Socialite
            $table->enum('role', ['admin', 'guru', 'siswa', 'kepsek'])->default('siswa'); // Hak akses role
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

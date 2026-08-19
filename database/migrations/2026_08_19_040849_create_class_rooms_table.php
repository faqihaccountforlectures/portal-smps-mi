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
        Schema::create('class_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20); // Contoh: "7A"
            $table->integer('grade_level'); // Tingkat: 7, 8, atau 9

            // FK ke tabel users (Wali Kelas). Pakai nullOnDelete agar kalau guru resign, kelas tidak ikut terhapus.
            $table->foreignid('homeroom_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};

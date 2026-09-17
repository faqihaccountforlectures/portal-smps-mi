<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * FUNGSI KODE: Menjalankan proses migrasi untuk membuat tabel 'learning_materials'.
     * Tabel ini menyimpan data modul, bahan ajar, berkas PDF/PPT, serta link eksternal
     * yang diunggah oleh guru untuk kelas dan mata pelajaran tertentu.
     */
    public function up(): void
    {
        Schema::create('learning_materials', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel penugasan guru (teacher_assignments).
            // Dari relasi ini kita otomatis mengetahui: Guru pengunggah, Mata Pelajaran, Kelas sasaran, dan Tahun Ajaran.
            $table->foreignId('teacher_assignment_id')->constrained('teacher_assignments')->onDelete('cascade');

            // Judul materi atau topik pokok bahasan (contoh: "Bab 1: Struktur Sel dan Jaringan")
            $table->string('title');

            // Petunjuk pembelajaran atau deskripsi singkat dari guru (opsional)
            $table->text('description')->nullable();

            // Lokasi penyimpanan berkas di disk storage public (contoh: "materials/abc123xyz.pdf")
            $table->string('file_path')->nullable();

            // Nama asli berkas saat diunggah untuk keperluan tampilan unduhan (contoh: "Modul_IPA_Bab1.pdf")
            $table->string('file_name')->nullable();

            // Ukuran berkas yang sudah diformat agar mudah dibaca pengguna (contoh: "2.5 MB")
            $table->string('file_size')->nullable();

            // Tautan eksternal opsional seperti Google Drive, YouTube, Google Classroom, atau Canva
            $table->string('link_url')->nullable();

            // Waktu pembuatan dan pembaruan data
            $table->timestamps();
        });
    }

    /**
     * FUNGSI KODE: Membatalkan migrasi dengan menghapus tabel 'learning_materials' jika di-rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_materials');
    }
};

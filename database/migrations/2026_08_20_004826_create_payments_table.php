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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Siswa yang membayar
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            
            // Ekskul yang dibayar
            $table->foreignId('extracurricular_id')->constrained('extracurriculars')->onDelete('cascade');
            
            // Bulan dan Tahun Pembayaran
            $table->string('month', 20);
            $table->integer('year');

            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['transfer', 'cash']);
            $table->string('proof_of_payment', 255)->nullable();
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])->default('pending');

            // Admin/Guru yang memverifikasi (Nullable karena awal bayar belum diverifikasi)
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

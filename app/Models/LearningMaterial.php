<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    use HasFactory;

    /**
     * FUNGSI KODE: Mendefinisikan kolom-kolom tabel yang dapat diisi secara massal (mass assignment).
     */
    protected $fillable = [
        'teacher_assignment_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'link_url',
    ];

    /**
     * FUNGSI KODE: Relasi balik ke model Penugasan Guru (TeacherAssignment).
     * Dari relasi ini kita dapat mengakses informasi:
     * - Guru pengunggah: $material->teacherAssignment->teacher
     * - Mata Pelajaran: $material->teacherAssignment->subject
     * - Kelas: $material->teacherAssignment->classRoom
     * - Tahun Ajaran: $material->teacherAssignment->academicYear
     */
    public function teacherAssignment()
    {
        return $this->belongsTo(TeacherAssignment::class, 'teacher_assignment_id');
    }

    /**
     * FUNGSI KODE: Accessor untuk mendapatkan ekstensi berkas (contoh: 'pdf', 'docx', 'pptx').
     * Jika tidak ada berkas yang diunggah, mengembalikan nilai null.
     */
    public function getFileExtensionAttribute()
    {
        if (!$this->file_name) {
            return null;
        }
        return strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
    }

    /**
     * FUNGSI KODE: Accessor untuk mengidentifikasi warna tema dan ikon berkas berdasarkan ekstensinya,
     * sehingga tampilan badge berkas di halaman web lebih menarik dan informatif.
     */
    public function getBadgeColorAttribute()
    {
        $ext = $this->file_extension;
        return match ($ext) {
            'pdf' => 'rose',
            'doc', 'docx' => 'blue',
            'ppt', 'pptx' => 'amber',
            'xls', 'xlsx' => 'emerald',
            'zip', 'rar' => 'purple',
            default => 'navy-base',
        };
    }
}

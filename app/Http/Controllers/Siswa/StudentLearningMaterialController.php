<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use App\Models\LearningMaterial;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentLearningMaterialController extends Controller
{
    /**
     * FUNGSI KODE: Menampilkan galeri materi pelajaran yang dapat diakses oleh siswa.
     * Sistem secara otomatis memfilter materi agar hanya menampilkan bahan ajar
     * yang ditujukan untuk kelas tempat siswa tersebut terdaftar di tahun ajaran aktif.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // 1. Memeriksa pendaftaran kelas siswa
        $enrollment = ClassEnrollment::with('classRoom')
            ->where('student_id', $userId)
            ->latest()
            ->first();

        // Jika siswa belum dialokasikan ke kelas mana pun
        if (!$enrollment || !$enrollment->classRoom) {
            return view('siswa.materials.index', [
                'hasClass' => false,
                'message'  => 'Anda belum terdaftar di dalam kelas aktif apa pun. Silakan hubungi wali kelas atau bagian kurikulum sekolah.'
            ]);
        }

        $classRoom = $enrollment->classRoom;
        $classId   = $classRoom->id;

        // 2. Mengambil daftar mata pelajaran yang diajarkan pada kelas siswa ini untuk filter tab
        $subjects = Subject::whereHas('teacherAssignments', function ($query) use ($classId) {
            $query->where('class_room_id', $classId);
        })->orderBy('name')->get();

        // 3. Menangani filter mata pelajaran jika siswa memilih tab/kategori mapel tertentu
        $selectedSubjectId = $request->query('subject_id');

        // 4. Mengambil materi pelajaran yang diunggah guru untuk kelas siswa
        $materialsQuery = LearningMaterial::with(['teacherAssignment.subject', 'teacherAssignment.teacher.teacherProfile'])
            ->whereHas('teacherAssignment', function ($query) use ($classId, $selectedSubjectId) {
                $query->where('class_room_id', $classId);
                if ($selectedSubjectId) {
                    $query->where('subject_id', $selectedSubjectId);
                }
            })
            ->latest();

        $materials = $materialsQuery->paginate(9)->withQueryString();

        // Menghitung total seluruh materi di kelas ini
        $totalClassMaterials = LearningMaterial::whereHas('teacherAssignment', function ($q) use ($classId) {
            $q->where('class_room_id', $classId);
        })->count();

        return view('siswa.materials.index', [
            'hasClass'             => true,
            'classRoom'            => $classRoom,
            'subjects'             => $subjects,
            'selectedSubjectId'    => $selectedSubjectId,
            'materials'            => $materials,
            'totalClassMaterials'  => $totalClassMaterials,
        ]);
    }

    /**
     * FUNGSI KODE: Mengunduh berkas materi pelajaran bagi siswa.
     * Mengamankan akses dengan memvalidasi bahwa siswa memang terdaftar pada kelas yang berhak menerima materi tersebut.
     */
    public function download($id)
    {
        $userId = Auth::id();

        // Cari data materi beserta penugasan kelasnya
        $material = LearningMaterial::with('teacherAssignment')->findOrFail($id);

        $targetClassId = $material->teacherAssignment->class_room_id;

        // Validasi: periksa apakah siswa terdaftar di kelas target materi ini
        $isEnrolled = ClassEnrollment::where('student_id', $userId)
            ->where('class_room_id', $targetClassId)
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'Akses ditolak. Materi ini hanya diperuntukkan bagi siswa di kelas yang bersangkutan.');
        }

        // Periksa keberadaan berkas di penyimpanan server
        if (!$material->file_path || !Storage::disk('public')->exists($material->file_path)) {
            return back()->with('error', 'Maaf, berkas materi tidak ditemukan atau telah dihapus dari server.');
        }

        return Storage::disk('public')->download($material->file_path, $material->file_name);
    }
}

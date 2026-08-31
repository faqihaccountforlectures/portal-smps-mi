<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\TeacherAssignment;

class MyClassController extends Controller
{
    /**
     * Menampilkan daftar kelas yang terkait dengan guru yang sedang login.
     * Kelas yang ditampilkan adalah kelas di mana guru tersebut menjadi wali kelas,
     * ditambah dengan kelas-kelas tempat ia ditugaskan mengajar pada tahun ajaran aktif.
     */
    public function index()
    {
        $guruId = Auth::user()->id;

        // 1. Cari Tahun Ajaran yang sedang aktif
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        if (!$activeAcademicYear) {
            // Jika tidak ada tahun ajaran aktif, kita kirimkan data kosong dengan pesan peringatan
            return view('guru.classes.index', [
                'homeroomClasses' => collect(),
                'teachingClasses' => collect(),
                'error' => 'Tidak ada Tahun Ajaran yang aktif saat ini. Harap hubungi administrator.'
            ]);
        }

        // 2. Ambil kelas di mana guru ini menjabat sebagai Wali Kelas
        $homeroomClasses = ClassRoom::where('homeroom_teacher_id', $guruId)->get();

        // 3. Ambil kelas-kelas tempat guru ini mengajar berdasarkan Penugasan Guru (TeacherAssignment)
        // Kita hanya mengambil penugasan untuk tahun ajaran yang sedang aktif
        $assignments = TeacherAssignment::with('classRoom')
            ->where('teacher_id', $guruId)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->get();

        // Ekstrak data kelas dari hasil penugasan (classRoom) dan pastikan tidak ada duplikat.
        // Duplikat bisa terjadi jika satu guru mengajar 2 mata pelajaran di kelas yang sama.
        $teachingClasses = $assignments->pluck('classRoom')->unique('id');

        return view('guru.classes.index', compact('homeroomClasses', 'teachingClasses', 'activeAcademicYear'));
    }

    /**
     * Menampilkan detail sebuah kelas beserta daftar siswa di dalamnya.
     */
    public function show($id)
    {
        $guruId = Auth::user()->id;

        // Cari data kelas berdasarkan ID.
        // Kita gunakan with('enrollments.student.studentProfile') untuk langsung memuat data pendaftaran 
        // beserta akun siswa dan profil siswanya sekaligus (Eager Loading) agar query lebih optimal.
        $classRoom = ClassRoom::with(['enrollments.student.studentProfile'])->findOrFail($id);

        // Cari Tahun Ajaran aktif untuk pengecekan wewenang
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        // PROTEKSI AKSES: Kita harus memastikan bahwa guru yang mengakses halaman kelas ini
        // benar-benar memiliki hak akses (baik sebagai Wali Kelas maupun Guru Mapel di kelas tersebut).
        
        $isHomeroomTeacher = ($classRoom->homeroom_teacher_id === $guruId);
        
        $isTeachingInThisClass = false;
        if ($activeAcademicYear) {
            $isTeachingInThisClass = TeacherAssignment::where('teacher_id', $guruId)
                ->where('class_room_id', $id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->exists();
        }

        // Jika dia bukan wali kelas dan juga tidak mengajar di kelas ini, tolak aksesnya!
        if (!$isHomeroomTeacher && !$isTeachingInThisClass) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat data kelas ini.');
        }

        // Jika lolos proteksi, tampilkan halaman detail kelas
        return view('guru.classes.show', compact('classRoom', 'isHomeroomTeacher'));
    }
}

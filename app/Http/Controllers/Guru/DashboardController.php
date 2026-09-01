<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LessonSchedule;
use App\Models\TeacherAssignment;
use App\Models\ClassEnrollment;
use App\Models\Extracurricular;
use App\Models\AcademicYear;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk guru.
     */
    public function index()
    {
        // Mendapatkan ID user guru yang sedang login
        $teacherId = Auth::id();
        $user = Auth::user();
        
        // 1. Menentukan nama hari ini dalam Bahasa Indonesia
        // Menggunakan isoFormat('dddd') dengan locale 'id' akan menghasilkan 'Senin', 'Selasa', dst.
        Carbon::setLocale('id');
        $todayName = Carbon::now()->isoFormat('dddd'); 
        
        // 2. Mengambil Jadwal Mengajar Hari Ini
        // Mengambil jadwal pelajaran yang terhubung dengan penugasan guru ini, yang jadwalnya adalah HARI INI.
        // Diurutkan berdasarkan jam mulai (start_time).
        $todaySchedules = LessonSchedule::whereHas('teacherAssignment', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
        ->where('day_of_week', $todayName)
        ->with(['teacherAssignment.subject', 'teacherAssignment.classRoom'])
        ->orderBy('start_time')
        ->get();
        
        // 3. Menghitung Total Kelas yang Diajar
        // Mencari daftar ID ruang kelas unik (distinct) yang diajar oleh guru ini
        $classRoomIds = TeacherAssignment::where('teacher_id', $teacherId)
        ->distinct('class_room_id')
        ->pluck('class_room_id');
        
        $totalClasses = $classRoomIds->count();
        
        // 4. Menghitung Total Siswa yang Diajar
        // Pertama, kita pastikan tahun ajaran mana yang sedang aktif
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        
        $totalStudents = 0;
        if ($activeAcademicYear && $totalClasses > 0) {
            // Menghitung siswa yang terdaftar pada kelas-kelas yang diajar tersebut pada tahun ajaran aktif
            $totalStudents = ClassEnrollment::where('academic_year_id', $activeAcademicYear->id)
                ->whereIn('class_room_id', $classRoomIds)
                ->distinct('student_id')
                ->count('student_id');
        }
        
        // 5. Menghitung Total Ekstrakurikuler Binaan
        $totalExtracurriculars = Extracurricular::where('teacher_id', $teacherId)->count();
        
        // Melempar semua variabel di atas ke file blade view
        return view('guru.dashboard', compact(
            'user',
            'todayName',
            'todaySchedules',
            'totalClasses',
            'totalStudents',
            'totalExtracurriculars'
        ));
    }
}

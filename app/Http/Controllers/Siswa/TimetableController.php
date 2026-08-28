<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use App\Models\LessonSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimetableController extends Controller
{
    /**
     * Menampilkan jadwal pelajaran mingguan khusus untuk siswa yang sedang login.
     */
    public function index()
    {
        // 1. Dapatkan ID user (siswa) yang saat ini sedang login di sistem
        $userId = Auth::id();

        // 2. Cari data pendaftaran kelas siswa tersebut.
        // Query ini akan mencari histori siswa dimasukkan ke kelas mana.
        // Kita sertakan relasi 'classRoom' agar nanti nama kelasnya bisa langsung dipanggil.
        $enrollment = ClassEnrollment::with('classRoom')
            ->where('student_id', $userId)
            ->first(); // Mengambil data kelas yang paling pertama ditemukan

        // 3. Cek kondisi: apakah siswa tersebut benar-benar sudah punya kelas?
        if (!$enrollment || !$enrollment->classRoom) {
            // Jika belum masuk kelas mana pun, kirim status 'hasClass' false ke view
            // agar view menampilkan pesan error yang rapi, bukan malah error sistem (crash).
            return view('siswa.timetables.index', [
                'hasClass' => false,
                'message' => 'Anda belum dimasukkan ke dalam kelas apa pun. Silakan hubungi Administrator sekolah.'
            ]);
        }

        // 4. Jika lolos pengecekan (siswa punya kelas), simpan ID dan Nama Kelasnya
        $classId = $enrollment->classRoom->id;
        $className = $enrollment->classRoom->name;

        // 5. Ambil semua data jadwal pelajaran (LessonSchedule) dari database khusus untuk kelas tersebut.
        // Kita hubungkan ke relasi TeacherAssignment untuk memastikan jadwal tersebut ditugaskan ke kelas ini.
        $timetables = LessonSchedule::with(['teacherAssignment.subject', 'teacherAssignment.teacher.teacherProfile'])
            ->whereHas('teacherAssignment', function ($q) use ($classId) {
                $q->where('class_room_id', $classId);
            })
            ->orderBy('start_time', 'asc') // Urutkan jadwal dari jam masuk paling pagi
            ->get();

        // 6. Kelompokkan seluruh jadwal tadi berdasarkan hari (day_of_week).
        $groupedTimetables = $timetables->groupBy('day_of_week');

        // 7. Terakhir, lempar semua data yang sudah diracik tadi ke file view (tampilan HTML)
        return view('siswa.timetables.index', [
            'hasClass' => true,
            'className' => $className,
            'groupedTimetables' => $groupedTimetables
        ]);
    }
}

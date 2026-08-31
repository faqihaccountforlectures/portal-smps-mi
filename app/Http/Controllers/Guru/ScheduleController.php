<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicYear;
use App\Models\TeacherAssignment;

class ScheduleController extends Controller
{
    /**
     * Menampilkan jadwal mengajar mingguan untuk guru yang sedang login.
     * Jadwal diambil dari Penugasan Guru (TeacherAssignment) pada tahun ajaran aktif,
     * kemudian memuat data Jadwal Pelajaran (LessonSchedule) terkait.
     */
    public function index()
    {
        $guruId = Auth::user()->id;

        // 1. Cari Tahun Ajaran yang sedang aktif
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        if (!$activeAcademicYear) {
            // Jika tidak ada tahun ajaran aktif, tampilkan pesan peringatan
            return view('guru.schedules.index', [
                'schedulesByDay' => [],
                'activeAcademicYear' => null,
                'error' => 'Tidak ada Tahun Ajaran yang aktif saat ini. Harap hubungi administrator.'
            ]);
        }

        // 2. Ambil penugasan mengajar untuk guru ini HANYA di tahun ajaran aktif.
        // Kita menggunakan "Eager Loading" (with) agar data Mata Pelajaran, Ruang Kelas, 
        // dan detail Jadwal Waktu (LessonSchedules) langsung ditarik dari database dalam satu tarikan query.
        $assignments = TeacherAssignment::with(['subject', 'classRoom', 'lessonSchedules'])
            ->where('teacher_id', $guruId)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->get();

        // 3. Kita perlu menyusun ulang datanya agar mudah ditampilkan dalam bentuk jadwal mingguan.
        // Kita buat wadah (array) kosong berdasarkan hari-hari efektif sekolah (Senin - Jumat).
        $daysOfWeek = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $schedulesByDay = array_fill_keys($daysOfWeek, []);

        // 4. Proses pengelompokan jadwal
        // Kita telusuri satu per satu penugasan guru...
        foreach ($assignments as $assignment) {
            // Lalu kita telusuri setiap waktu mengajar dari penugasan tersebut...
            foreach ($assignment->lessonSchedules as $schedule) {
                // Pastikan format harinya huruf kecil agar cocok dengan wadah (array) kita
                $day = strtolower($schedule->day_of_week);
                
                // Jika harinya valid, masukkan ke dalam kelompok hari tersebut
                if (array_key_exists($day, $schedulesByDay)) {
                    $schedulesByDay[$day][] = [
                        'subject_name' => $assignment->subject->name,
                        'class_name' => $assignment->classRoom->name,
                        'grade_level' => $assignment->classRoom->grade_level,
                        'start_time' => \Carbon\Carbon::parse($schedule->start_time)->format('H:i'),
                        'end_time' => \Carbon\Carbon::parse($schedule->end_time)->format('H:i'),
                    ];
                }
            }
        }

        // 5. Mengurutkan jadwal di setiap harinya berdasarkan Jam Mulai (start_time)
        // supaya urutannya rapi dari pagi sampai siang/sore.
        foreach ($schedulesByDay as $day => &$schedules) {
            usort($schedules, function ($a, $b) {
                return strcmp($a['start_time'], $b['start_time']);
            });
        }
        unset($schedules); // Melepas referensi memori untuk keamanan

        // 6. Mengirim data jadwal yang sudah rapi ke tampilan (view)
        return view('guru.schedules.index', compact('schedulesByDay', 'activeAcademicYear'));
    }
}

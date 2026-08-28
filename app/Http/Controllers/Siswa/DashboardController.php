<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use App\Models\ExtracurricularRegistration;
use App\Models\LessonSchedule;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama khusus untuk siswa.
     */
    public function index()
    {
        // 1. Ambil data user yang sedang login beserta profil siswanya
        $user = Auth::user()->load('studentProfile');

        // 2. Ambil data kelas siswa saat ini (jika ada)
        $enrollment = ClassEnrollment::with('classRoom')
            ->where('student_id', $user->id)
            ->first();

        $className = $enrollment && $enrollment->classRoom ? $enrollment->classRoom->name : 'Belum Ada Kelas';
        $classId = $enrollment && $enrollment->classRoom ? $enrollment->classRoom->id : null;

        // 3. Ambil jadwal pelajaran khusus untuk HARI INI
        // Translasi nama hari dari bahasa Inggris (format date('l')) ke bahasa Indonesia (sesuai database)
        $daysMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ];
        $todayIndo = $daysMap[date('l')];

        $todaySchedules = collect(); // Koleksi kosong jika tidak ada jadwal
        if ($classId) {
            // Kita cari jadwal di database yang harinya sama dengan hari ini, dan ditugaskan ke kelas si siswa
            $todaySchedules = LessonSchedule::with(['teacherAssignment.subject', 'teacherAssignment.teacher.teacherProfile'])
                ->where('day_of_week', $todayIndo)
                ->whereHas('teacherAssignment', function ($q) use ($classId) {
                    $q->where('class_room_id', $classId);
                })
                ->orderBy('start_time', 'asc')
                ->get();
        }

        // 4. Ambil daftar ekstrakurikuler yang AKTIF diikuti oleh siswa
        $myExtracurriculars = ExtracurricularRegistration::with('extracurricular')
            ->where('student_id', $user->id)
            ->where('status', 'approved') // Hanya ambil yang sudah disetujui admin
            ->get();

        // 5. Cek apakah ada tagihan bulanan ekskul yang tertunggak (Belum dibayar)
        $hasUnpaidBills = false;
        
        // Ambil riwayat pembayaran yang sudah ada
        $payments = Payment::where('student_id', $user->id)->get();
        
        $monthsName = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentDate = now()->startOfMonth();

        foreach ($myExtracurriculars as $reg) {
            if ($reg->extracurricular->fee <= 0) continue; // Skip ekskul gratis

            $startDate = $reg->created_at->startOfMonth();
            $iteratorDate = $startDate->copy();
            
            // Loop dari bulan pendaftaran ekskul sampai bulan saat ini
            while ($iteratorDate->lte($currentDate)) {
                $monthStr = $monthsName[$iteratorDate->month];
                $year = $iteratorDate->year;
                
                // Cari apakah ada bukti pembayaran yang pending atau verified di bulan tersebut
                $hasPaid = $payments->where('extracurricular_id', $reg->extracurricular_id)
                                    ->where('month', $monthStr)
                                    ->where('year', $year)
                                    ->whereIn('payment_status', ['pending', 'verified'])
                                    ->first();

                // Jika belum bayar, berarti ada tunggakan! Set flag ke true dan hentikan loop pencarian tagihan.
                if (!$hasPaid) {
                    $hasUnpaidBills = true;
                    break 2; // Keluar dari kedua loop (while dan foreach)
                }
                $iteratorDate->addMonth();
            }
        }

        // 6. Lempar semua data ini ke view dashboard siswa
        return view('siswa.dashboard', compact(
            'user', 
            'className', 
            'todayIndo', 
            'todaySchedules', 
            'myExtracurriculars', 
            'hasUnpaidBills'
        ));
    }
}

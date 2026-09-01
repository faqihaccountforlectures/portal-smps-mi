<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\ClassEnrollment;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ClassEnrollmentController extends Controller
{
    // Fungsi ini dipakai buat nampilin halaman utama menu Pembagian Kelas.
    // Tujuannya buat ngambil semua data kelas yang ada, terus dihitung otomatis jumlah siswanya.
    // Tapi karena pembagian kelas itu tiap tahun beda-beda, kita harus nyari tau dulu Tahun Ajaran yang lagi 'aktif'.
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        if (!$activeYear) {
            return redirect('/dashboard')->with('error', 'Belum ada Tahun Ajaran yang aktif. Silakan seting dulu di menu Tahun Ajaran.');
        }

        $classes = ClassRoom::with('homeroomTeacher.teacherProfile')
            ->withCount(['enrollments' => function($query) use ($activeYear) {
                $query->where('academic_year_id', $activeYear->id);
            }])
            ->get();

        return view('admin.class-enrollments.index', compact('classes', 'activeYear'));
    }

    // Fungsi ini buat ngeliat "Dalemnya kelas ini ada siapa aja sih?".
    // Pas kita klik salah satu kelas, fungsi ini jalan buat nyari daftar siswa yang udah kecatet di kelas tersebut.
    // Pastinya, dicari yang sesuai sama tahun ajaran yang lagi jalan (aktif) sekarang.
    public function show($classId)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $classRoom = ClassRoom::findOrFail($classId);
        
        $enrollments = ClassEnrollment::with('student.studentProfile')
            ->where('class_room_id', $classId)
            ->where('academic_year_id', $activeYear->id)
            ->get();

        return view('admin.class-enrollments.show', compact('classRoom', 'enrollments', 'activeYear'));
    }

    // Kalau kita mau masukin siswa baru ke dalam kelas, kita bakal dilempar ke fungsi ini.
    // Tugas utamanya adalah nyari siswa-siswa mana aja yang *BELUM* dapet kelas di tahun ajaran ini.
    // Logikanya: Cari id siswa yang udah terdaftar, trus keluarin semua siswa yang id-nya TIDAK ADA di daftar tadi.
    public function addStudents($classId)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $classRoom = ClassRoom::findOrFail($classId);

        // Cari siswa (role='siswa') yang BELUM masuk di class_enrollments untuk tahun ajaran aktif ini
        $enrolledStudentIds = ClassEnrollment::where('academic_year_id', $activeYear->id)
            ->pluck('student_id')
            ->toArray();

        $availableStudents = User::with('studentProfile')
            ->where('role', 'siswa')
            ->whereNotIn('id', $enrolledStudentIds)
            ->get();

        return view('admin.class-enrollments.add-students', compact('classRoom', 'availableStudents', 'activeYear'));
    }

    // Nah, ini bagian paling krusial. Pas admin udah nyentang-nyentang nama siswa dan klik Simpan.
    // Kita tangkap array/kumpulan ID siswa yang dicentang, trus kita masukin satu-satu ke tabel class_enrollments.
    // Kita juga pakai fitur "Transaction" (DB::beginTransaction), jadi kalau tiba-tiba mati lampu atau error pas lagi nyimpen di tengah jalan, datanya di-rollback (dibatalkan) biar database nggak berantakan setengah jadi.
    public function storeStudents(Request $request, $classId)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id'
        ], [
            'student_ids.required' => 'Pilih minimal satu siswa untuk dimasukkan ke kelas.',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        
        DB::beginTransaction();
        try {
            foreach ($request->student_ids as $studentId) {
                // Pastikan belum terdaftar di kelas lain tahun ini (dobel proteksi)
                $exists = ClassEnrollment::where('student_id', $studentId)
                    ->where('academic_year_id', $activeYear->id)
                    ->exists();
                    
                if (!$exists) {
                    ClassEnrollment::create([
                        'student_id' => $studentId,
                        'class_room_id' => $classId,
                        'academic_year_id' => $activeYear->id,
                        'status' => 'aktif'
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('class-enrollments.show', $classId)->with('success', 'Siswa berhasil dimasukkan ke kelas!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses data: ' . $e->getMessage());
        }
    }

    // Fungsi ini dipanggil pas admin ngeklik tombol hapus/tong sampah di sebelah nama siswa di dalam kelas.
    // Tujuannya murni buat ngeluarin si siswa dari rombongan belajar itu.
    // Setelah sukses dihapus, halaman bakal otomatis nge-refresh kembali ke halaman detail kelas asalnya.
    public function destroy($enrollmentId)
    {
        $enrollment = ClassEnrollment::findOrFail($enrollmentId);
        $classId = $enrollment->class_room_id;
        $enrollment->delete();

        return redirect()->route('class-enrollments.show', $classId)->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
    }
}

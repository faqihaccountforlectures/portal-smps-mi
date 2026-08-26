<?php

namespace App\Http\Controllers;

use App\Models\TeacherAssignment;
use App\Models\User;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{
    /**
     * Menampilkan daftar penugasan guru (berdasarkan tahun ajaran aktif)
     */
    public function index()
    {
        // Cari tahun ajaran yang statusnya 'active'
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Kalau belum ada tahun ajaran yang aktif, kita kasih tau admin buat aktifin dulu
        if (!$activeYear) {
            return redirect()->route('academic-years.index')->with('error', 'Tahun ajaran aktif tidak ditemukan. Silakan aktifkan tahun ajaran terlebih dahulu.');
        }

        // Ambil semua data penugasan khusus untuk tahun ajaran yang lagi aktif
        $rawAssignments = TeacherAssignment::with(['teacher.teacherProfile', 'subject', 'classRoom'])
            ->where('academic_year_id', $activeYear->id)
            ->oldest('teacher_id')
            ->get();

        // Kelompokkan berdasarkan guru dan mapel
        $assignments = [];
        foreach ($rawAssignments as $assignment) {
            $key = $assignment->teacher_id . '-' . $assignment->subject_id;
            if (!isset($assignments[$key])) {
                $assignments[$key] = (object)[
                    'teacher_id' => $assignment->teacher_id,
                    'subject_id' => $assignment->subject_id,
                    'teacher' => $assignment->teacher,
                    'subject' => $assignment->subject,
                    'classRooms' => collect([]),
                ];
            }
            $assignments[$key]->classRooms->push($assignment->classRoom);
        }

        // Ubah jadi collection supaya gampang di-loop di blade
        $assignments = collect($assignments)->values();

        // Lempar datanya ke view index
        return view('admin.teacher-assignments.index', compact('assignments', 'activeYear'));
    }

    /**
     * Nampilin form untuk nambah penugasan baru
     */
    public function create()
    {
        // Pastikan ada tahun ajaran aktif dulu
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->route('academic-years.index')->with('error', 'Harap aktifkan tahun ajaran terlebih dahulu sebelum membuat penugasan.');
        }

        // Ambil data-data master untuk dijadiin pilihan (dropdown) di form nanti
        // Ambil user yang rolenya 'guru' beserta profilnya biar bisa dapet namanya
        $teachers = User::where('role', 'guru')->with('teacherProfile')->get()->sortBy(function($user) {
            return $user->teacherProfile ? $user->teacherProfile->full_name : '';
        });
        // Ambil semua mapel
        $subjects = Subject::orderBy('name')->get();
        // Ambil semua kelas
        $classRooms = ClassRoom::orderBy('name')->get();

        // Kirim semua data master itu ke view form create
        return view('admin.teacher-assignments.create', compact('teachers', 'subjects', 'classRooms', 'activeYear'));
    }

    /**
     * Proses nyimpen data penugasan dari form ke database
     */
    public function store(Request $request)
    {
        // Pastiin lagi tahun ajaran aktifnya ada
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Gagal menyimpan, tahun ajaran aktif tidak ditemukan.');
        }

        // Validasi inputan dari form
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_room_ids' => 'required|array|min:1',
            'class_room_ids.*' => 'exists:class_rooms,id',
        ], [
            'teacher_id.required' => 'Guru harus dipilih.',
            'subject_id.required' => 'Mata pelajaran harus dipilih.',
            'class_room_ids.required' => 'Minimal harus centang satu kelas.',
        ]);

        // Kita siapin variabel buat ngitung berapa jadwal yang sukses disimpan, dan berapa yang di-skip karena duplikat
        $createdCount = 0;
        $skippedCount = 0;

        // Looping (perulangan) sebanyak kelas yang dicentang sama admin
        foreach ($request->class_room_ids as $class_id) {
            // Cek dulu, jangan sampe guru yang sama ditugaskan di kelas & mapel yang sama (biar datanya nggak dobel)
            $isDuplicate = TeacherAssignment::where('teacher_id', $request->teacher_id)
                ->where('subject_id', $request->subject_id)
                ->where('class_room_id', $class_id)
                ->where('academic_year_id', $activeYear->id)
                ->exists();

            if (!$isDuplicate) {
                // Kalau aman dan belum ada, baru deh kita simpan ke database
                TeacherAssignment::create([
                    'teacher_id' => $request->teacher_id,
                    'subject_id' => $request->subject_id,
                    'class_room_id' => $class_id,
                    // Otomatis pake tahun ajaran yang aktif, jadi admin nggak usah input lagi
                    'academic_year_id' => $activeYear->id, 
                ]);
                $createdCount++;
            } else {
                // Kalo udah pernah di-assign, kita hitung sebagai di-skip
                $skippedCount++;
            }
        }

        // Siapin pesan suksesnya
        $message = "Berhasil menyimpan $createdCount penugasan kelas baru.";
        if ($skippedCount > 0) {
            $message .= " (Membatalkan $skippedCount kelas karena sudah pernah ditugaskan sebelumnya).";
        }

        // Balikin ke halaman daftar penugasan bawa pesan tadi
        return redirect()->route('teacher-assignments.index')->with('success', $message);
    }

    /**
     * Nampilin form edit penugasan (berdasarkan guru & mapel)
     */
    public function edit($teacher_id, $subject_id)
    {
        // Cek tahun ajaran aktif
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->route('academic-years.index')->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        // Ambil data guru dan mapel spesifik
        $teacher = User::with('teacherProfile')->findOrFail($teacher_id);
        $subject = Subject::findOrFail($subject_id);

        // Cari kelas apa aja yang udah dicentang (diajar) sama dia
        $assignedClassIds = TeacherAssignment::where('teacher_id', $teacher_id)
            ->where('subject_id', $subject_id)
            ->where('academic_year_id', $activeYear->id)
            ->pluck('class_room_id')
            ->toArray();

        // Ambil semua kelas untuk pilihan checkbox
        $classRooms = ClassRoom::orderBy('name')->get();

        return view('admin.teacher-assignments.edit', compact('teacher', 'subject', 'assignedClassIds', 'classRooms', 'activeYear'));
    }

    /**
     * Proses nyimpen update penugasan (sinkronisasi ulang kelas)
     */
    public function update(Request $request, $teacher_id, $subject_id)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Gagal memperbarui, tahun ajaran aktif tidak ditemukan.');
        }

        $request->validate([
            'class_room_ids' => 'required|array|min:1',
            'class_room_ids.*' => 'exists:class_rooms,id',
        ], [
            'class_room_ids.required' => 'Minimal harus centang satu kelas.',
        ]);

        // Karena ini disatukan, cara paling gampang update adalah HAPUS yang lama, lalu BIKIN BARU sesuai centang
        TeacherAssignment::where('teacher_id', $teacher_id)
            ->where('subject_id', $subject_id)
            ->where('academic_year_id', $activeYear->id)
            ->delete();

        foreach ($request->class_room_ids as $class_id) {
            TeacherAssignment::create([
                'teacher_id' => $teacher_id,
                'subject_id' => $subject_id,
                'class_room_id' => $class_id,
                'academic_year_id' => $activeYear->id,
            ]);
        }

        return redirect()->route('teacher-assignments.index')->with('success', 'Manajemen penugasan guru berhasil diperbarui.');
    }

    /**
     * Proses ngehapus seluruh data penugasan untuk kombinasi Guru & Mapel tersebut
     */
    public function destroy($teacher_id, $subject_id)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        // Hapus semua kelas yang terhubung dengan Guru & Mapel ini di tahun ajaran aktif
        TeacherAssignment::where('teacher_id', $teacher_id)
            ->where('subject_id', $subject_id)
            ->where('academic_year_id', $activeYear->id)
            ->delete();

        return redirect()->route('teacher-assignments.index')->with('success', 'Seluruh penugasan untuk mata pelajaran tersebut berhasil dihapus.');
    }
}

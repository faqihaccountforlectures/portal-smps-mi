<?php

namespace App\Http\Controllers;

use App\Models\LessonSchedule;
use App\Models\TeacherAssignment;
use App\Models\ClassRoom;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class LessonScheduleController extends Controller
{
    /**
     * Menampilkan daftar jadwal pelajaran (dikelompokkan per kelas)
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->route('academic-years.index')->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        // Ambil semua kelas untuk filter
        $classRooms = ClassRoom::orderBy('name')->get();
        $selectedClassId = $request->query('class_room_id');

        // Ambil jadwal sesuai kelas yang dipilih (atau kosong kalau belum milih)
        $schedules = collect();
        $selectedClass = null;
        
        if ($selectedClassId) {
            $selectedClass = ClassRoom::find($selectedClassId);
            if ($selectedClass) {
                // Ambil jadwal untuk kelas tersebut di tahun ajaran aktif
                $schedules = LessonSchedule::with(['teacherAssignment.teacher.teacherProfile', 'teacherAssignment.subject'])
                    ->whereHas('teacherAssignment', function ($query) use ($activeYear, $selectedClassId) {
                        $query->where('academic_year_id', $activeYear->id)
                              ->where('class_room_id', $selectedClassId);
                    })
                    ->get()
                    // Kelompokkan per hari dan urutkan berdasarkan jam mulai
                    ->sortBy('start_time')
                    ->groupBy('day_of_week');
            }
        }

        // Urutan hari untuk tampilan
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.lesson-schedules.index', compact('activeYear', 'classRooms', 'selectedClassId', 'selectedClass', 'schedules', 'days'));
    }

    /**
     * Menampilkan form tambah jadwal (Step 1: Pilih Kelas, Step 2: Input Jadwal)
     */
    public function create(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->route('academic-years.index')->with('error', 'Harap aktifkan tahun ajaran terlebih dahulu.');
        }

        $classRooms = ClassRoom::orderBy('name')->get();
        $selectedClassId = $request->query('class_room_id');
        
        $assignments = collect();
        $selectedClass = null;

        if ($selectedClassId) {
            $selectedClass = ClassRoom::find($selectedClassId);
            // Cari guru & mapel apa saja yang ditugaskan ke kelas ini
            $assignments = TeacherAssignment::with(['teacher.teacherProfile', 'subject'])
                ->where('academic_year_id', $activeYear->id)
                ->where('class_room_id', $selectedClassId)
                ->get();
        }

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.lesson-schedules.create', compact('activeYear', 'classRooms', 'selectedClassId', 'selectedClass', 'assignments', 'days'));
    }

    /**
     * Menyimpan banyak jadwal sekaligus (Bulk Insert) beserta validasinya
     */
    public function store(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Gagal menyimpan, tahun ajaran aktif tidak ditemukan.');
        }

        $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'teacher_assignment_id' => 'required|array|min:1',
            'teacher_assignment_id.*' => 'required|exists:teacher_assignments,id',
            'day_of_week' => 'required|array|min:1',
            'day_of_week.*' => 'required|string',
            'start_time' => 'required|array|min:1',
            'start_time.*' => 'required|date_format:H:i',
            'end_time' => 'required|array|min:1',
            'end_time.*' => 'required|date_format:H:i',
        ], [
            'teacher_assignment_id.required' => 'Minimal harus menambahkan satu jadwal.',
        ]);

        $classRoomId = $request->class_room_id;
        $count = count($request->teacher_assignment_id);
        $errors = [];
        $createdCount = 0;

        for ($i = 0; $i < $count; $i++) {
            $taId = $request->teacher_assignment_id[$i];
            $day = $request->day_of_week[$i];
            
            // Tambahkan detik agar sesuai format database jika cuma dapet jam:menit
            $startInput = $request->start_time[$i];
            $endInput = $request->end_time[$i];
            $start = strlen($startInput) == 5 ? $startInput . ':00' : $startInput;
            $end = strlen($endInput) == 5 ? $endInput . ':00' : $endInput;

            // 1. Validasi Jam Terbalik (Mulai > Selesai)
            if (strtotime($start) >= strtotime($end)) {
                $errors[] = "Baris " . ($i + 1) . ": Jam selesai harus lebih besar dari jam mulai.";
                continue;
            }

            // Ambil info penugasan (untuk tau gurunya siapa)
            $assignment = TeacherAssignment::find($taId);
            $teacherId = $assignment->teacher_id;
            
            // 2. Validasi Bentrok Kelas (Di kelas ini, di hari & jam yang tumpang tindih)
            $classOverlap = LessonSchedule::whereHas('teacherAssignment', function($q) use ($classRoomId, $activeYear) {
                    $q->where('class_room_id', $classRoomId)
                      ->where('academic_year_id', $activeYear->id);
                })
                ->where('day_of_week', $day)
                ->where(function($q) use ($start, $end) {
                    $q->where(function($q2) use ($start, $end) {
                        $q2->where('start_time', '<', $end)->where('end_time', '>', $start);
                    });
                })->exists();

            if ($classOverlap) {
                $errors[] = "Baris " . ($i + 1) . ": Kelas sudah memiliki jadwal pada rentang waktu tersebut.";
                continue;
            }

            // 3. Validasi Bentrok Guru (Guru ini ngajar di kelas MANAPUN di hari & jam yang tumpang tindih)
            $teacherOverlap = LessonSchedule::whereHas('teacherAssignment', function($q) use ($teacherId, $activeYear) {
                    $q->where('teacher_id', $teacherId)
                      ->where('academic_year_id', $activeYear->id);
                })
                ->where('day_of_week', $day)
                ->where(function($q) use ($start, $end) {
                    $q->where(function($q2) use ($start, $end) {
                        $q2->where('start_time', '<', $end)->where('end_time', '>', $start);
                    });
                })->exists();

            if ($teacherOverlap) {
                $teacherName = $assignment->teacher->teacherProfile->full_name ?? 'Guru';
                $errors[] = "Baris " . ($i + 1) . ": {$teacherName} memiliki jadwal mengajar di kelas lain pada waktu tersebut.";
                continue;
            }

            // Validasi sukses, simpan jadwal
            LessonSchedule::create([
                'teacher_assignment_id' => $taId,
                'day_of_week' => $day,
                'start_time' => $start,
                'end_time' => $end,
            ]);
            $createdCount++;
        }

        if (count($errors) > 0) {
            $msg = ($createdCount > 0 ? "Sebagian jadwal gagal disimpan. " : "Gagal menyimpan jadwal. ") . implode(" | ", $errors);
            return redirect()->back()->with('error', $msg);
        }

        return redirect()->route('lesson-schedules.index', ['class_room_id' => $classRoomId])->with('success', "Seluruh jadwal berhasil disimpan ($createdCount jadwal).");
    }

    /**
     * Edit satu jadwal spesifik
     */
    public function edit($id)
    {
        $schedule = LessonSchedule::with('teacherAssignment.subject', 'teacherAssignment.teacher.teacherProfile')->findOrFail($id);
        
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->route('academic-years.index')->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $classRoomId = $schedule->teacherAssignment->class_room_id;
        
        $assignments = TeacherAssignment::with(['teacher.teacherProfile', 'subject'])
            ->where('academic_year_id', $activeYear->id)
            ->where('class_room_id', $classRoomId)
            ->get();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.lesson-schedules.edit', compact('schedule', 'assignments', 'days', 'activeYear'));
    }

    /**
     * Update satu jadwal spesifik
     */
    public function update(Request $request, $id)
    {
        $schedule = LessonSchedule::findOrFail($id);
        
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $request->validate([
            'teacher_assignment_id' => 'required|exists:teacher_assignments,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $taId = $request->teacher_assignment_id;
        $day = $request->day_of_week;
        $startInput = $request->start_time;
        $endInput = $request->end_time;
        
        $start = strlen($startInput) == 5 ? $startInput . ':00' : $startInput;
        $end = strlen($endInput) == 5 ? $endInput . ':00' : $endInput;
        
        $assignment = TeacherAssignment::find($taId);
        $classRoomId = $assignment->class_room_id;
        $teacherId = $assignment->teacher_id;

        if (strtotime($start) >= strtotime($end)) {
            return redirect()->back()->with('error', 'Jam selesai harus lebih besar dari jam mulai.')->withInput();
        }

        // Cek Bentrok Kelas (kecuali id ini sendiri)
        $classOverlap = LessonSchedule::where('id', '!=', $id)
            ->whereHas('teacherAssignment', function($q) use ($classRoomId, $activeYear) {
                $q->where('class_room_id', $classRoomId)->where('academic_year_id', $activeYear->id);
            })
            ->where('day_of_week', $day)
            ->where(function($q) use ($start, $end) {
                $q->where('start_time', '<', $end)->where('end_time', '>', $start);
            })->exists();

        if ($classOverlap) {
            return redirect()->back()->with('error', 'Kelas sudah memiliki jadwal pelajaran lain pada rentang waktu tersebut.')->withInput();
        }

        // Cek Bentrok Guru (kecuali id ini sendiri)
        $teacherOverlap = LessonSchedule::where('id', '!=', $id)
            ->whereHas('teacherAssignment', function($q) use ($teacherId, $activeYear) {
                $q->where('teacher_id', $teacherId)->where('academic_year_id', $activeYear->id);
            })
            ->where('day_of_week', $day)
            ->where(function($q) use ($start, $end) {
                $q->where('start_time', '<', $end)->where('end_time', '>', $start);
            })->exists();

        if ($teacherOverlap) {
            return redirect()->back()->with('error', 'Guru sudah memiliki jadwal mengajar di kelas lain pada waktu tersebut.')->withInput();
        }

        $schedule->update([
            'teacher_assignment_id' => $taId,
            'day_of_week' => $day,
            'start_time' => $start,
            'end_time' => $end,
        ]);

        return redirect()->route('lesson-schedules.index', ['class_room_id' => $classRoomId])->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Hapus jadwal
     */
    public function destroy($id)
    {
        $schedule = LessonSchedule::findOrFail($id);
        $classRoomId = $schedule->teacherAssignment->class_room_id;
        $schedule->delete();

        return redirect()->route('lesson-schedules.index', ['class_room_id' => $classRoomId])->with('success', 'Jadwal berhasil dihapus.');
    }
}

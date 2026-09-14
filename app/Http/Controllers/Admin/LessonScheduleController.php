<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\LessonSchedule;
use App\Models\TeacherAssignment;
use App\Models\ClassRoom;
use App\Models\AcademicYear;
use App\Models\Subject;
use App\Models\User;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $selectedDay = $request->query('day_filter');

        // Ambil jadwal sesuai kelas yang dipilih (atau kosong kalau belum milih)
        $schedules = collect();
        $selectedClass = null;
        
        if ($selectedClassId) {
            $selectedClass = ClassRoom::find($selectedClassId);
            if ($selectedClass) {
                $query = LessonSchedule::with(['teacherAssignment.teacher.teacherProfile', 'teacherAssignment.subject'])
                    ->whereHas('teacherAssignment', function ($q) use ($activeYear, $selectedClassId) {
                        $q->where('academic_year_id', $activeYear->id)
                          ->where('class_room_id', $selectedClassId);
                    });

                if ($selectedDay) {
                    $query->where('day_of_week', $selectedDay);
                }

                $schedules = $query->get()
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

            // 3. Validasi Bentrok Guru (Guru ini ngajar di kelas MANAPUN di hari & jam yang tumpang tindih, kecuali mapel Pembiasaan & Kokurikuler yang serentak)
            $isSharedSubject = in_array($assignment->subject->code ?? '', ['S', 'U']);
            if (!$isSharedSubject) {
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

    /**
     * FUNGSI KODE: Mengunduh berkas template matriks CSV resmi untuk Jadwal Pelajaran.
     * Format matriks ini 100% identik dengan susunan lembar jadwal fisik sekolah (Senin - Jumat).
     */
    public function downloadTemplate()
    {
        $fileName = 'template_impor_jadwal_matriks.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            // Sisipkan BOM UTF-8 untuk kompatibilitas Excel
            fputs($handle, "\xEF\xBB\xBF");

            // Header kolom matriks jadwal sesuai ruang kelas resmi
            fputcsv($handle, [
                'HARI',
                'JAM_KE',
                'WAKTU_MULAI',
                'WAKTU_SELESAI',
                'VII A',
                'VII B',
                'VII C',
                'VIII A',
                'VIII B',
                'IX A',
                'IX B',
            ]);

            // Data Contoh: SENIN (Sesuai dokumen cetak resmi sekolah)
            fputcsv($handle, ['SENIN', '0', '06:30', '07:30', 'U20', 'U20', 'U20', 'U20', 'U20', 'U20', 'U20']);
            fputcsv($handle, ['SENIN', '1', '07:30', '08:05', 'F2', 'B8', 'M11', 'L9', 'C15', 'N10', 'E12']);
            fputcsv($handle, ['SENIN', '2', '08:05', '08:40', 'F2', 'B8', 'M11', 'L9', 'C15', 'N10', 'E12']);
            fputcsv($handle, ['SENIN', 'ISTIRAHAT', '08:40', '09:00', '', '', '', '', '', '', '']);
            fputcsv($handle, ['SENIN', '3', '09:00', '09:35', 'N10', 'F2', 'L9', 'R14', 'G8', 'E12', 'D3']);
            fputcsv($handle, ['SENIN', '4', '09:35', '10:10', 'N10', 'F2', 'L9', 'R14', 'G8', 'E12', 'D3']);
            fputcsv($handle, ['SENIN', '5', '10:10', '10:45', 'D3', 'L9', 'A1', 'F2', 'N10', 'E12', 'M11']);
            fputcsv($handle, ['SENIN', '6', '10:45', '11:20', 'D3', 'L9', 'A1', 'F2', 'N10', 'G8', 'M11']);
            fputcsv($handle, ['SENIN', 'ISTIRAHAT', '11:20', '12:20', '', '', '', '', '', '', '']);
            fputcsv($handle, ['SENIN', '7', '12:20', '12:55', 'B8', 'D3', 'F2', 'C15', 'R14', 'L9', 'N10']);
            fputcsv($handle, ['SENIN', '8', '12:55', '13:30', 'B8', 'D3', 'F2', 'C15', 'R14', 'L9', 'N10']);
            fputcsv($handle, ['SENIN', '9', '13:30', '14:05', 'A1', 'M11', 'N10', 'B8', 'L9', 'D3', 'F2']);
            fputcsv($handle, ['SENIN', '10', '14:05', '14:40', 'A1', 'M11', 'N10', 'B8', 'L9', 'D3', 'F2']);

            // Data Contoh: SELASA (Cuplikan jadwal Selasa)
            fputcsv($handle, ['SELASA', '0', '06:30', '07:30', 'U20', 'U20', 'U20', 'U20', 'U20', 'U20', 'U20']);
            fputcsv($handle, ['SELASA', '1', '07:30', '08:05', 'C16', 'E4', 'B8', 'O13', 'A1', 'F2', 'D3']);
            fputcsv($handle, ['SELASA', '2', '08:05', '08:40', 'C16', 'E4', 'B8', 'O13', 'A1', 'F2', 'D3']);

            // Data Contoh: JUMAT (Semua Kokurikuler - S19)
            fputcsv($handle, ['JUMAT', '0', '06:30', '07:30', 'U20', 'U20', 'U20', 'U20', 'U20', 'U20', 'U20']);
            fputcsv($handle, ['JUMAT', '1', '07:30', '08:05', 'S19', 'S19', 'S19', 'S19', 'S19', 'S19', 'S19']);
            fputcsv($handle, ['JUMAT', '2', '08:05', '08:40', 'S19', 'S19', 'S19', 'S19', 'S19', 'S19', 'S19']);

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * FUNGSI KODE: Memproses impor massal matriks Jadwal Pelajaran mingguan dari CSV/Excel.
     * Fitur Pintar (*Smart Auto-Assignment*):
     * 1. Mengurai kode sel kombinasi Mapel + Guru (contoh: F2, B8, M11, O13, U20, S19).
     * 2. Menghubungkan kode mapel ke jenjang kelas (F -> F-7 untuk Kelas VII, F-8 untuk Kelas VIII, dst).
     * 3. Jika penugasan guru belum ada di database, sistem OTOMATIS membuatnya di tabel teacher_assignments.
     * 4. Menyimpan jam pelajaran ke tabel lesson_schedules tanpa bentrok dan otomatis mengabaikan baris ISTIRAHAT.
     */
    public function import(Request $request)
    {
        // 1. Validasi Tahun Ajaran aktif
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan. Harap aktifkan tahun ajaran terlebih dahulu.');
        }

        // 2. Validasi berkas CSV unggahan
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'file.required' => 'Silakan pilih berkas CSV terlebih dahulu.',
            'file.mimes' => 'Format berkas harus berupa .csv.',
            'file.max' => 'Ukuran berkas CSV maksimal adalah 5MB.',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        // 3. Deteksi delimiter secara otomatis
        $firstLine = file_get_contents($filePath, false, null, 0, 1024);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return redirect()->back()->with('error', 'Gagal membuka berkas CSV yang diunggah.');
        }

        // 4. Baca dan petakan kolom Header
        $headerRow = fgetcsv($handle, 0, $delimiter);
        if (!$headerRow) {
            fclose($handle);
            return redirect()->back()->with('error', 'Berkas CSV kosong atau tidak memiliki baris judul kolom.');
        }

        $colHariIndex = null;
        $colStartIdx = null;
        $colEndIdx = null;
        $colSingleTimeIdx = null;
        $classColMap = []; // [colIndex => ClassRoom]

        // Telusuri setiap kolom header
        foreach ($headerRow as $idx => $colName) {
            $cleanName = strtoupper(trim(str_replace(["\xEF\xBB\xBF", '"', "'"], '', $colName)));
            $normalizedName = str_replace([' ', '_'], '', $cleanName);

            if ($normalizedName === 'HARI') {
                $colHariIndex = $idx;
            } elseif (in_array($normalizedName, ['WAKTUMULAI', 'JAMMULAI', 'STARTTIME', 'MULAI'])) {
                $colStartIdx = $idx;
            } elseif (in_array($normalizedName, ['WAKTUSELESAI', 'JAMSELESAI', 'ENDTIME', 'SELESAI'])) {
                $colEndIdx = $idx;
            } elseif (in_array($normalizedName, ['WAKTU', 'JAM'])) {
                $colSingleTimeIdx = $idx;
            } else {
                // Periksa apakah kolom ini merujuk ke salah satu ruang kelas yang ada di database
                $classRoom = ClassRoom::whereRaw('REPLACE(LOWER(name), " ", "") = ?', [strtolower(str_replace(' ', '', $cleanName))])
                    ->first();
                if ($classRoom) {
                    $classColMap[$idx] = $classRoom;
                }
            }
        }

        if ($colHariIndex === null || ($colStartIdx === null && $colSingleTimeIdx === null)) {
            fclose($handle);
            return redirect()->back()->with('error', 'Format kolom CSV tidak sesuai. Pastikan terdapat kolom HARI dan WAKTU_MULAI / WAKTU_SELESAI.');
        }

        if (empty($classColMap)) {
            fclose($handle);
            return redirect()->back()->with('error', 'Tidak ditemukan kolom kelas yang valid (misal: VII A, VII B, dst) pada baris judul CSV.');
        }

        // Muat seluruh referensi data ke memori untuk efisiensi query
        $allSubjects = Subject::all();
        $allTeacherProfiles = TeacherProfile::with('user')->get();
        $allUsersGuru = User::where('role', 'guru')->get();

        $rowNumber = 1;
        $createdScheduleCount = 0;
        $autoAssignmentCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rowNumber++;

                // Abaikan baris kosong
                if (empty($row) || (count($row) === 1 && trim($row[0]) === '')) {
                    continue;
                }

                $hariRaw = isset($row[$colHariIndex]) ? trim($row[$colHariIndex]) : '';
                if ($hariRaw === '') {
                    continue;
                }

                // Normalisasi nama hari ke format baku Title Case (Senin, Selasa, dst)
                $hari = ucfirst(strtolower($hariRaw));

                // Ekstraksi jam mulai dan jam selesai
                $startTime = '';
                $endTime = '';

                if ($colStartIdx !== null && isset($row[$colStartIdx])) {
                    $startTime = trim($row[$colStartIdx]);
                    $endTime = ($colEndIdx !== null && isset($row[$colEndIdx])) ? trim($row[$colEndIdx]) : '';
                } elseif ($colSingleTimeIdx !== null && isset($row[$colSingleTimeIdx])) {
                    // Penanganan jika waktu ditulis sekaligus, contoh: "07.30 - 08.05"
                    $parts = explode('-', $row[$colSingleTimeIdx]);
                    if (count($parts) >= 2) {
                        $startTime = trim($parts[0]);
                        $endTime = trim($parts[1]);
                    }
                }

                // Normalisasi titik menjadi titik dua (07.30 -> 07:30)
                $startTime = str_replace('.', ':', $startTime);
                $endTime = str_replace('.', ':', $endTime);

                // Otomatis lewati jika baris istirahat atau waktu tidak valid
                if (stripos($hariRaw, 'ISTIRAHAT') !== false || stripos($startTime, 'ISTIRAHAT') !== false || $startTime === '' || $endTime === '') {
                    continue;
                }

                // Pastikan format jam memiliki detik (:00) untuk database
                $startTimeFormatted = (strlen($startTime) === 5) ? $startTime . ':00' : $startTime;
                $endTimeFormatted = (strlen($endTime) === 5) ? $endTime . ':00' : $endTime;

                // 5. Telusuri setiap sel kelas pada baris ini
                foreach ($classColMap as $colIdx => $classRoom) {
                    $cellValue = isset($row[$colIdx]) ? trim($row[$colIdx]) : '';

                    // Lewati jika sel kosong, tanda strip, atau bertuliskan istirahat
                    if ($cellValue === '' || $cellValue === '-' || stripos($cellValue, 'ISTIRAHAT') !== false || stripos($cellValue, 'ISHOMA') !== false) {
                        continue;
                    }

                    // 6. Parsing kode sel kombinasi (contoh: "F2", "B8", "M11", "U20", "S19")
                    // Menggunakan Regex untuk memisahkan karakter huruf (Mapel) dan digit angka (Guru)
                    if (!preg_match('/^([A-Za-z]+)[\s\-_:\/]*([0-9]+)$/', $cellValue, $matches)) {
                        // Jika tidak cocok dengan pola standar, catat peringatan
                        $errors[] = "Baris {$rowNumber}, Kolom {$classRoom->name}: Format kode sel '{$cellValue}' tidak dikenali (gunakan format contoh: F2, B8).";
                        continue;
                    }

                    $mapelCodeChar = strtoupper($matches[1]);
                    $guruCodeNumber = $matches[2];

                    // 7. Resolusi Mata Pelajaran Sesuai Tingkat Kelas
                    $subject = null;

                    // Jika mapel Kokurikuler (S) atau Pembiasaan (U)
                    if ($mapelCodeChar === 'S') {
                        $subject = $allSubjects->firstWhere('code', 'S');
                    } elseif ($mapelCodeChar === 'U') {
                        $subject = $allSubjects->firstWhere('code', 'U');
                    } else {
                        // Pola standar: Kode Mapel + Jenjang (contoh: F-7 untuk kelas VII, F-8 untuk kelas VIII)
                        $expectedCode = $mapelCodeChar . '-' . $classRoom->grade_level;
                        $subject = $allSubjects->firstWhere('code', $expectedCode);

                        // Fallback pencarian alternatif jika kode tidak memakai tanda strip
                        if (!$subject) {
                            $subject = $allSubjects->where('grade_level', $classRoom->grade_level)
                                ->first(function ($s) use ($mapelCodeChar) {
                                    return strtoupper(substr($s->code, 0, 1)) === $mapelCodeChar;
                                });
                        }

                        // Fallback jika jenjang spesifik belum ada
                        if (!$subject) {
                            $subject = $allSubjects->first(function ($s) use ($mapelCodeChar) {
                                return strtoupper(substr($s->code, 0, 1)) === $mapelCodeChar;
                            });
                        }
                    }

                    if (!$subject) {
                        $errors[] = "Baris {$rowNumber}, {$classRoom->name}: Mapel kode '{$mapelCodeChar}' tidak ditemukan untuk tingkat kelas {$classRoom->grade_level}.";
                        continue;
                    }

                    // 8. Resolusi Penugasan Guru (TeacherAssignment)
                    $assignment = null;

                    // FUNGSI KODE: Untuk Kokurikuler (S) dan Pembiasaan (U) yang diajar serentak oleh Semua Guru
                    if ($mapelCodeChar === 'S' || $mapelCodeChar === 'U') {
                        // Cari penugasan yang sudah ada di kelas dan mapel ini
                        $assignment = TeacherAssignment::where('subject_id', $subject->id)
                            ->where('class_room_id', $classRoom->id)
                            ->where('academic_year_id', $activeYear->id)
                            ->first();

                        // Jika belum ada penugasan, kaitkan dengan Kepala Sekolah (Guru Kode 19) sebagai penanggung jawab tanpa membuat akun tiruan
                        if (!$assignment) {
                            $repProfile = $allTeacherProfiles->firstWhere('teacher_code', '19') ?? $allTeacherProfiles->first();
                            if ($repProfile && $repProfile->user) {
                                $assignment = TeacherAssignment::firstOrCreate([
                                    'teacher_id' => $repProfile->user->id,
                                    'subject_id' => $subject->id,
                                    'class_room_id' => $classRoom->id,
                                    'academic_year_id' => $activeYear->id,
                                ]);
                                if ($assignment->wasRecentlyCreated) {
                                    $autoAssignmentCount++;
                                }
                            }
                        }
                    } else {
                        // FUNGSI KODE: Pencarian Guru Mata Pelajaran Reguler berdasarkan kode guru 1-19
                        $teacherProfile = $allTeacherProfiles->firstWhere('teacher_code', $guruCodeNumber);
                        $teacherUser = $teacherProfile ? $teacherProfile->user : null;

                        if (!$teacherUser) {
                            $teacherUser = $allUsersGuru->where('name', $guruCodeNumber)->first();
                        }

                        if (!$teacherUser) {
                            $errors[] = "Baris {$rowNumber}, {$classRoom->name}: Guru kode '{$guruCodeNumber}' pada '{$cellValue}' tidak terdaftar di sistem.";
                            continue;
                        }

                        // Hubungkan ke Penugasan Guru
                        $assignment = TeacherAssignment::firstOrCreate([
                            'teacher_id' => $teacherUser->id,
                            'subject_id' => $subject->id,
                            'class_room_id' => $classRoom->id,
                            'academic_year_id' => $activeYear->id,
                        ]);

                        if ($assignment->wasRecentlyCreated) {
                            $autoAssignmentCount++;
                        }
                    }

                    if (!$assignment) {
                        $errors[] = "Baris {$rowNumber}, {$classRoom->name}: Gagal menghubungkan penugasan guru untuk sel '{$cellValue}'.";
                        continue;
                    }

                    // 10. Simpan Slot Jadwal Pelajaran (Hindari duplikasi persis)
                    $existingSchedule = LessonSchedule::where('teacher_assignment_id', $assignment->id)
                        ->where('day_of_week', $hari)
                        ->where('start_time', $startTimeFormatted)
                        ->where('end_time', $endTimeFormatted)
                        ->first();

                    if (!$existingSchedule) {
                        LessonSchedule::create([
                            'teacher_assignment_id' => $assignment->id,
                            'day_of_week' => $hari,
                            'start_time' => $startTimeFormatted,
                            'end_time' => $endTimeFormatted,
                        ]);
                        $createdScheduleCount++;
                    }
                }
            }

            fclose($handle);
            DB::commit();

            if ($createdScheduleCount === 0 && count($errors) > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak ada jadwal pelajaran yang berhasil diimpor. Silakan periksa kesalahan berikut.')
                    ->with('import_errors', $errors);
            }

            $successMsg = "Berhasil mengimpor {$createdScheduleCount} slot jadwal pelajaran!";
            if ($autoAssignmentCount > 0) {
                $successMsg .= " (Sistem juga otomatis membuat {$autoAssignmentCount} data penugasan guru terkait).";
            }

            if (count($errors) > 0) {
                return redirect()->back()
                    ->with('success', $successMsg)
                    ->with('error', 'Beberapa sel data dilewati karena format tidak sesuai.')
                    ->with('import_errors', $errors);
            }

            return redirect()->route('lesson-schedules.index')->with('success', $successMsg);

        } catch (\Exception $e) {
            DB::rollBack();
            if (is_resource($handle)) {
                fclose($handle);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses berkas CSV jadwal: ' . $e->getMessage());
        }
    }
}

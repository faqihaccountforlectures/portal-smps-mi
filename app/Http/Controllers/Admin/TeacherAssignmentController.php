<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\TeacherAssignment;
use App\Models\User;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\AcademicYear;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherAssignmentController extends Controller
{
    /**
     * Menampilkan daftar penugasan guru (berdasarkan tahun ajaran aktif)
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

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

        // Ubah jadi collection
        $assignments = collect($assignments)->values();

        // Filter berdasarkan kata kunci pencarian (nama guru atau nama/kode mata pelajaran)
        if ($search) {
            $searchLower = strtolower($search);
            $assignments = $assignments->filter(function($item) use ($searchLower) {
                $teacherName = strtolower($item->teacher->teacherProfile->full_name ?? $item->teacher->email ?? '');
                $subjectName = strtolower($item->subject->name ?? '');
                $subjectCode = strtolower($item->subject->code ?? '');
                return str_contains($teacherName, $searchLower) || str_contains($subjectName, $searchLower) || str_contains($subjectCode, $searchLower);
            })->values();
        }

        // Paginasi manual (5 per halaman) untuk collection
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $currentPageItems = $assignments->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $assignments = new \Illuminate\Pagination\LengthAwarePaginator($currentPageItems, count($assignments), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        // Lempar datanya ke view index
        return view('admin.teacher-assignments.index', compact('assignments', 'activeYear', 'search'));
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
        // FUNGSI KODE: Menyimpan data penugasan guru ke kelas (Mendukung satu guru maupun banyak guru sekaligus / Centang Semua Guru)
        // Pastiin lagi tahun ajaran aktifnya ada
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Gagal menyimpan, tahun ajaran aktif tidak ditemukan.');
        }

        // Validasi inputan dari form
        $request->validate([
            'teacher_id' => 'nullable|exists:users,id',
            'teacher_ids' => 'nullable|array|min:1',
            'teacher_ids.*' => 'exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_room_ids' => 'required|array|min:1',
            'class_room_ids.*' => 'exists:class_rooms,id',
        ], [
            'subject_id.required' => 'Mata pelajaran harus dipilih.',
            'class_room_ids.required' => 'Minimal harus centang satu kelas.',
        ]);

        // FUNGSI KODE: Ambil kumpulan ID guru yang dipilih (dari teacher_ids checkbox atau teacher_id single select)
        $selectedTeacherIds = [];
        if ($request->has('teacher_ids') && is_array($request->teacher_ids)) {
            $selectedTeacherIds = $request->teacher_ids;
        } elseif ($request->filled('teacher_id')) {
            $selectedTeacherIds = [$request->teacher_id];
        }

        if (empty($selectedTeacherIds)) {
            return redirect()->back()->withInput()->with('error', 'Minimal harus memilih atau mencentang satu guru.');
        }

        // Kita siapin variabel buat ngitung berapa penugasan yang sukses disimpan dan yang di-skip karena duplikat
        $createdCount = 0;
        $skippedCount = 0;

        // FUNGSI KODE: Looping untuk setiap guru yang dipilih dan setiap kelas yang dicentang
        foreach ($selectedTeacherIds as $teacherId) {
            foreach ($request->class_room_ids as $class_id) {
                // Cek dulu, jangan sampai kombinasi guru, mapel, dan kelas yang sama dibuat dobel
                $isDuplicate = TeacherAssignment::where('teacher_id', $teacherId)
                    ->where('subject_id', $request->subject_id)
                    ->where('class_room_id', $class_id)
                    ->where('academic_year_id', $activeYear->id)
                    ->exists();

                if (!$isDuplicate) {
                    // Simpan penugasan baru
                    TeacherAssignment::create([
                        'teacher_id' => $teacherId,
                        'subject_id' => $request->subject_id,
                        'class_room_id' => $class_id,
                        'academic_year_id' => $activeYear->id, 
                    ]);
                    $createdCount++;
                } else {
                    $skippedCount++;
                }
            }
        }

        // Siapin pesan suksesnya
        $message = "Berhasil menyimpan $createdCount penugasan kelas baru.";
        if ($skippedCount > 0) {
            $message .= " (Membatalkan $skippedCount penugasan karena sudah pernah ditugaskan sebelumnya).";
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

    /**
     * FUNGSI KODE: Mengunduh berkas template resmi CSV untuk impor Penugasan Guru.
     * Menggunakan format BOM UTF-8 agar langsung terbaca rapi di Microsoft Excel.
     */
    public function downloadTemplate()
    {
        $fileName = 'template_impor_penugasan_guru.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            // Menyisipkan BOM UTF-8 agar kompatibel dengan Microsoft Excel di Windows
            fputs($handle, "\xEF\xBB\xBF");

            // Header kolom CSV
            fputcsv($handle, [
                'Kode Guru',
                'Kode Mata Pelajaran',
                'Kelas',
            ]);

            // Baris contoh pengisian data (sesuai dokumen resmi sekolah)
            fputcsv($handle, ['2', 'F-7', 'VII A']);   // Guru 2 (Novi Indah) - B. Inggris Kelas 7 - VII A
            fputcsv($handle, ['8', 'B-7', 'VII A']);   // Guru 8 (Ade Refiyanti) - PPKN Kelas 7 - VII A
            fputcsv($handle, ['11', 'M-7', 'VII A']);  // Guru 11 (Shania Nada) - Akidah Akhlak Kelas 7 - VII A
            fputcsv($handle, ['3', 'D-8', 'VIII A']);  // Guru 3 (Lia Kurnia) - Matematika Kelas 8 - VIII A
            fputcsv($handle, ['12', 'E-9', 'IX A']);   // Guru 12 (Dini Nurdiniati) - IPA Kelas 9 - IX A
            fputcsv($handle, ['19', 'S', 'VII A']);    // Guru 19 (Kokurikuler - Semua Guru) - Kelas VII A
            fputcsv($handle, ['20', 'U', 'VII A']);    // Guru 20 (Pembiasaan - Semua Guru) - Kelas VII A

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * FUNGSI KODE: Memproses impor massal data Penugasan Guru dari berkas CSV/Excel.
     * Menerapkan validasi cerdas untuk mencocokkan kode guru, kode mapel, dan ruang kelas,
     * serta otomatis mendukung entitas bersama seperti Kokurikuler & Pembiasaan.
     */
    public function import(Request $request)
    {
        // 1. Validasi keberadaan Tahun Ajaran yang sedang aktif
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan. Harap aktifkan tahun ajaran terlebih dahulu.');
        }

        // 2. Validasi berkas CSV yang diunggah
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'file.required' => 'Silakan pilih berkas CSV terlebih dahulu.',
            'file.mimes' => 'Format berkas harus berupa berkas .csv.',
            'file.max' => 'Ukuran berkas CSV maksimal adalah 5MB.',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        // 3. Deteksi delimiter (koma atau titik koma) secara otomatis
        $firstLine = file_get_contents($filePath, false, null, 0, 1024);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return redirect()->back()->with('error', 'Gagal membuka berkas CSV yang diunggah.');
        }

        // Lewati baris header
        $header = fgetcsv($handle, 0, $delimiter);

        $rowNumber = 1;
        $successCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rowNumber++;

                // Abaikan baris kosong
                if (empty($row) || (count($row) === 1 && trim($row[0]) === '')) {
                    continue;
                }

                $teacherCodeInput = isset($row[0]) ? trim($row[0]) : '';
                $subjectCodeInput = isset($row[1]) ? trim($row[1]) : '';
                $classNameInput = isset($row[2]) ? trim($row[2]) : '';

                if ($teacherCodeInput === '' || $subjectCodeInput === '' || $classNameInput === '') {
                    $errors[] = "Baris {$rowNumber}: Data tidak lengkap (Kode Guru, Kode Mapel, dan Kelas wajib diisi).";
                    continue;
                }

                // 4. Pencarian Ruang Kelas (Normalisasi spasi misal: VIIA -> VII A)
                $classRoom = ClassRoom::whereRaw('REPLACE(LOWER(name), " ", "") = ?', [strtolower(str_replace(' ', '', $classNameInput))])
                    ->first();

                if (!$classRoom) {
                    $errors[] = "Baris {$rowNumber}: Ruang kelas '{$classNameInput}' tidak terdaftar di sistem.";
                    continue;
                }

                // 5. Pencarian Mata Pelajaran
                // Cek kode mapel langsung (misal: F-7, B-8, S, U)
                $subject = Subject::where('code', $subjectCodeInput)
                    ->orWhereRaw('LOWER(code) = ?', [strtolower($subjectCodeInput)])
                    ->first();

                // Jika kode hanya berupa 1 huruf (misal: F), cocokkan dengan jenjang kelas (misal: F + '-' + 7 = F-7)
                if (!$subject && strlen($subjectCodeInput) <= 2) {
                    $combinedCode = strtoupper($subjectCodeInput) . '-' . $classRoom->grade_level;
                    $subject = Subject::where('code', $combinedCode)->first();
                }

                // Jika masih belum ketemu, coba cari berdasarkan nama mapel
                if (!$subject) {
                    $subject = Subject::whereRaw('LOWER(name) = ?', [strtolower($subjectCodeInput)])
                        ->where(function ($q) use ($classRoom) {
                            $q->where('grade_level', $classRoom->grade_level)
                              ->orWhereNull('grade_level');
                        })->first();
                }

                // Fallback pencarian fleksibel berdasarkan huruf awal kode jika jenjang spesifik belum ada
                if (!$subject && strlen($subjectCodeInput) <= 3) {
                    $prefix = strtoupper(substr($subjectCodeInput, 0, 1));
                    $subject = Subject::where('code', 'like', $prefix . '%')->first();
                }

                if (!$subject) {
                    $errors[] = "Baris {$rowNumber}: Mata pelajaran dengan kode '{$subjectCodeInput}' untuk tingkat kelas {$classRoom->grade_level} tidak ditemukan.";
                    continue;
                }

                // 6. Penanganan Penugasan Guru
                // FUNGSI KODE: Jika diisi 'all', 'semua', atau kode 19/20 untuk Pembiasaan (U) & Kokurikuler (S), tugaskan seluruh 19 guru asli sekolah tanpa membuat akun dummy
                $isSharedSubject = in_array(strtoupper(substr($subject->code, 0, 1)), ['S', 'U']);
                if (in_array(strtolower($teacherCodeInput), ['all', 'semua', 'semuaguru', 'semua guru']) || ($isSharedSubject && in_array($teacherCodeInput, ['19', '20']))) {
                    $allTeachers = User::where('role', 'guru')->has('teacherProfile')->get();
                    foreach ($allTeachers as $t) {
                        TeacherAssignment::firstOrCreate([
                            'teacher_id' => $t->id,
                            'subject_id' => $subject->id,
                            'class_room_id' => $classRoom->id,
                            'academic_year_id' => $activeYear->id,
                        ]);
                    }
                    $successCount++;
                    continue;
                }

                // FUNGSI KODE: Pencarian Guru Asli (Berdasarkan teacher_code 1-19, NIP, email, atau nama) TANPA membuat akun tiruan/dummy
                $teacherProfile = TeacherProfile::where('teacher_code', $teacherCodeInput)
                    ->orWhere('nip', $teacherCodeInput)
                    ->first();

                $teacherUser = null;
                if ($teacherProfile) {
                    $teacherUser = User::find($teacherProfile->user_id);
                } else {
                    $teacherUser = User::where('role', 'guru')
                        ->where(function ($q) use ($teacherCodeInput) {
                            $q->where('email', $teacherCodeInput)
                              ->orWhere('name', $teacherCodeInput);
                        })->first();
                }

                if (!$teacherUser) {
                    $errors[] = "Baris {$rowNumber}: Guru dengan kode/NIP '{$teacherCodeInput}' tidak ditemukan di sistem.";
                    continue;
                }

                // 7. Simpan Penugasan Guru (firstOrCreate untuk mencegah duplikasi)
                TeacherAssignment::firstOrCreate([
                    'teacher_id' => $teacherUser->id,
                    'subject_id' => $subject->id,
                    'class_room_id' => $classRoom->id,
                    'academic_year_id' => $activeYear->id,
                ]);

                $successCount++;
            }

            fclose($handle);

            // Jika ada baris sukses, simpan transaksi
            DB::commit();

            if ($successCount === 0 && count($errors) > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak ada data penugasan yang berhasil diimpor. Silakan periksa kesalahan berikut.')
                    ->with('import_errors', $errors);
            }

            $message = "Berhasil mengimpor {$successCount} data penugasan guru!";
            if (count($errors) > 0) {
                return redirect()->back()
                    ->with('success', $message)
                    ->with('error', 'Beberapa baris data dilewati karena kendala format.')
                    ->with('import_errors', $errors);
            }

            return redirect()->route('teacher-assignments.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            if (is_resource($handle)) {
                fclose($handle);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses berkas CSV: ' . $e->getMessage());
        }
    }
}

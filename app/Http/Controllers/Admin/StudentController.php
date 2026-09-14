<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\ClassRoom;
use App\Models\ClassEnrollment;
use App\Models\AcademicYear;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // Nampilin daftar siswa di tabel utama dengan pencarian & paginasi
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::where('role', 'siswa')
            ->with('studentProfile');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhereHas('studentProfile', function($sp) use ($search) {
                      $sp->where('full_name', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                        ->orWhere('parent_phone', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->latest()->paginate(10)->appends($request->all());

        return view('admin.students.index', compact('students', 'search'));
    }

    // Nampilin form tambah data
    public function create()
    {
        return view('admin.students.create');
    }

    // Proses simpan data ke 2 tabel pakai transaction
    public function store(StoreStudentRequest $request)
    {
        // 1. Data sudah tervalidasi oleh StoreStudentRequest
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData) {
            // Bikin akun login (password kosong karena pakai SSO Google Belajar.id)
            $user = User::create([
                'email' => $validatedData['email'],
                'role' => 'siswa',
            ]);

            // Bikin profil siswanya
            StudentProfile::create([
                'user_id' => $user->id,
                'full_name' => $validatedData['full_name'],
                'nisn' => $validatedData['nisn'],
                'gender' => $validatedData['gender'],
                'phone_number' => $validatedData['phone_number'],
                'parent_phone' => $validatedData['parent_phone'],
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Sip! Data siswa baru berhasil ditambahkan.');
    }

    // Nampilin form edit
    public function edit($id)
    {
        $student = User::with('studentProfile')->findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    // Proses update data
    public function update(UpdateStudentRequest $request, $id)
    {
        $student = User::findOrFail($id);

        // 1. Ambil data yang sudah lolos validasi
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData, $student) {
            // Update email loginnya
            $student->update([
                'email' => $validatedData['email'],
            ]);

            // Update profil siswanya
            if ($student->studentProfile) {
                $student->studentProfile->update([
                    'full_name' => $validatedData['full_name'],
                    'nisn' => $validatedData['nisn'],
                    'gender' => $validatedData['gender'],
                    'phone_number' => $validatedData['phone_number'],
                    'parent_phone' => $validatedData['parent_phone'],
                ]);
            } else {
                StudentProfile::create([
                    'user_id' => $student->id,
                    'full_name' => $validatedData['full_name'],
                    'nisn' => $validatedData['nisn'],
                    'gender' => $validatedData['gender'],
                    'phone_number' => $validatedData['phone_number'],
                    'parent_phone' => $validatedData['parent_phone'],
                ]);
            }
        });

        return redirect()->route('students.index')->with('success', 'Oke mantap! Perubahan data siswa sudah tersimpan.');
    }

    // Proses hapus data
    public function destroy($id)
    {
        $student = User::findOrFail($id);
        
        // Hapus profilnya dulu biar bersih, baru hapus akunnya
        if ($student->studentProfile) {
            $student->studentProfile->delete();
        }
        
        $student->delete();

        return redirect()->back()->with('success', 'Data siswa tersebut berhasil dihapus permanen.');
    }

    /**
     * Mengunduh berkas template CSV resmi untuk mempermudah admin
     * dalam menyiapkan data siswa yang akan diimpor secara massal.
     * Menggunakan tanda BOM UTF-8 agar berkas langsung terbaca rapi saat dibuka di Microsoft Excel.
     */
    public function downloadTemplate()
    {
        $fileName = 'template_data_siswa.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            // Menyisipkan BOM UTF-8 agar Microsoft Excel mengenali karakter khusus dan format teks dengan benar
            fputs($handle, "\xEF\xBB\xBF");

            // Baris header kolom template (dilengkapi kolom Kelas opsional)
            fputcsv($handle, [
                'Nama Lengkap',
                'NISN',
                'Email Akun Belajar',
                'Jenis Kelamin',
                'Nomor HP Siswa',
                'Nomor HP Orang Tua',
                'Kelas (Opsional)',
            ]);

            // Baris contoh data pengisian ke-1 (Gunakan ="..." agar Excel tidak mengubah angka menjadi format eksponen ilmiah)
            fputcsv($handle, [
                'Ahmad Fauzi',
                '="0081234567"',
                'ahmad.fauzi@smp.belajar.id',
                'Laki-laki',
                '="081234567890"',
                '="081298765432"',
                '7A',
            ]);

            // Baris contoh data pengisian ke-2
            fputcsv($handle, [
                'Siti Aisyah',
                '="0087654321"',
                'siti.aisyah@smp.belajar.id',
                'Perempuan',
                '="082134567891"',
                '="082198765433"',
                '7B',
            ]);

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Memproses impor massal data siswa dari berkas CSV/Excel.
     * Sistem memvalidasi setiap baris data, membuat akun user, profil siswa,
     * serta langsung mendaftarkan siswa ke kelas rombel jika kolom Kelas diisi.
     */
    public function import(Request $request)
    {
        // 1. Validasi berkas unggahan: harus berupa berkas teks/CSV dengan ukuran maksimal 5MB
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'file.required' => 'Silakan pilih berkas CSV terlebih dahulu.',
            'file.mimes' => 'Format berkas harus berupa .csv.',
            'file.max' => 'Ukuran berkas maksimal adalah 5MB.',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        // 2. Membaca baris pertama untuk mendeteksi pembatas (delimiter koma atau titik koma)
        $firstLine = file_get_contents($filePath, false, null, 0, 1024);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return redirect()->back()->with('error', 'Gagal membuka berkas CSV yang diunggah.');
        }

        // Lewati baris header pertama
        $header = fgetcsv($handle, 1000, $delimiter);

        // Ambil tahun ajaran yang sedang aktif dan buat peta pencarian kelas cerdas
        $activeYear = AcademicYear::where('is_active', true)->first();
        $allClasses = ClassRoom::all();

        // Peta kelas: Mendukung pencocokan format fleksibel (7A, VII A, 8B, VIII B, 9A, IX A, dsb.)
        $classMap = [];
        foreach ($allClasses as $cls) {
            $raw = strtolower(trim($cls->name));
            $clean = preg_replace('/\s+|-/', '', $raw);

            $classMap[$raw] = $cls->id;
            $classMap[$clean] = $cls->id;

            // Pemetaan variasi Romawi ke Angka Arab
            $toArabic = str_replace(['viii', 'vii', 'ix', 'vi', 'x'], ['8', '7', '9', '6', '10'], $clean);
            $classMap[$toArabic] = $cls->id;

            // Pemetaan variasi Angka Arab ke Romawi
            $toRoman = str_replace(['8', '7', '9', '6', '10'], ['viii', 'vii', 'ix', 'vi', 'x'], $clean);
            $classMap[$toRoman] = $cls->id;
        }

        $rowNumber = 1;
        $errors = [];
        $studentsToInsert = [];
        $batchEmails = [];
        $batchNisns = [];

        // 3. Membaca dan memvalidasi setiap baris data dari berkas CSV
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $rowNumber++;

            // Abaikan baris kosong
            if (empty(array_filter($row))) {
                continue;
            }

            // Pastikan baris memiliki minimal 4 kolom utama
            if (count($row) < 4) {
                $errors[] = "Baris ke-{$rowNumber}: Jumlah kolom tidak lengkap. Pastikan mengisi Nama, NISN, Email, dan Jenis Kelamin.";
                continue;
            }

            // Bersihkan data dari karakter ekstra tanda kutip / formula Excel (contoh: ="0812..." atau '0812...)
            $fullName    = trim($row[0] ?? '');
            $nisn        = trim(trim($row[1] ?? ''), "=\"' \t\n\r\0\x0B");
            $email       = trim($row[2] ?? '');
            $genderRaw   = strtolower(trim($row[3] ?? ''));
            $phoneNumber = trim(trim($row[4] ?? ''), "=\"' \t\n\r\0\x0B");
            $parentPhone = trim(trim($row[5] ?? ''), "=\"' \t\n\r\0\x0B");
            $className   = trim(trim($row[6] ?? ''), "=\"' \t\n\r\0\x0B");

            // Validasi Nama Lengkap
            if (empty($fullName) || strlen($fullName) > 100) {
                $errors[] = "Baris ke-{$rowNumber}: Nama lengkap wajib diisi dan maksimal 100 karakter.";
                continue;
            }

            // Validasi NISN
            if (empty($nisn) || strlen($nisn) > 20) {
                $errors[] = "Baris ke-{$rowNumber}: NISN '{$nisn}' wajib diisi dan maksimal 20 karakter.";
                continue;
            }

            // Cek duplikasi NISN di dalam berkas CSV
            if (in_array($nisn, $batchNisns)) {
                $errors[] = "Baris ke-{$rowNumber}: NISN '{$nisn}' terdeteksi ganda di dalam berkas yang sama.";
                continue;
            }

            // Cek duplikasi NISN di database
            if (StudentProfile::where('nisn', $nisn)->exists()) {
                $errors[] = "Baris ke-{$rowNumber}: NISN '{$nisn}' sudah terdaftar di sistem.";
                continue;
            }

            // Validasi format Email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Baris ke-{$rowNumber}: Format email '{$email}' tidak valid.";
                continue;
            }

            // Cek duplikasi Email di dalam berkas CSV
            if (in_array($email, $batchEmails)) {
                $errors[] = "Baris ke-{$rowNumber}: Email '{$email}' terdeteksi ganda di dalam berkas yang sama.";
                continue;
            }

            // Cek duplikasi Email di database
            if (User::where('email', $email)->exists()) {
                $errors[] = "Baris ke-{$rowNumber}: Email '{$email}' sudah digunakan oleh pengguna lain.";
                continue;
            }

            // Normalisasi pilihan Jenis Kelamin
            if (in_array($genderRaw, ['l', 'laki-laki', 'laki laki', 'pria'])) {
                $gender = 'laki-laki';
            } elseif (in_array($genderRaw, ['p', 'perempuan', 'wanita'])) {
                $gender = 'perempuan';
            } else {
                $errors[] = "Baris ke-{$rowNumber}: Jenis kelamin '{$row[3]}' tidak valid. Gunakan 'Laki-laki' atau 'Perempuan'.";
                continue;
            }

            // Validasi Kelas jika kolom Kelas diisi (Dukungan penuh untuk seluruh kelas 7, 8, dan 9)
            $classRoomId = null;
            if (!empty($className)) {
                $rawClassKey   = strtolower(trim($className));
                $cleanClassKey = preg_replace('/\s+|-/', '', $rawClassKey);
                $arabicKey     = str_replace(['viii', 'vii', 'ix', 'vi', 'x'], ['8', '7', '9', '6', '10'], $cleanClassKey);
                $romanKey      = str_replace(['8', '7', '9', '6', '10'], ['viii', 'vii', 'ix', 'vi', 'x'], $cleanClassKey);

                if (isset($classMap[$rawClassKey])) {
                    $classRoomId = $classMap[$rawClassKey];
                } elseif (isset($classMap[$cleanClassKey])) {
                    $classRoomId = $classMap[$cleanClassKey];
                } elseif (isset($classMap[$arabicKey])) {
                    $classRoomId = $classMap[$arabicKey];
                } elseif (isset($classMap[$romanKey])) {
                    $classRoomId = $classMap[$romanKey];
                } else {
                    $availableNames = $allClasses->pluck('name')->implode(', ');
                    $errors[] = "Baris ke-{$rowNumber}: Kelas '{$className}' tidak ditemukan di sistem. Pilihan kelas yang terdaftar: {$availableNames}.";
                    continue;
                }

                if (!$activeYear) {
                    $errors[] = "Baris ke-{$rowNumber}: Gagal mengalokasikan kelas '{$className}' karena belum ada Tahun Ajaran aktif.";
                    continue;
                }
            }

            // Catat ke daftar data yang siap disimpan
            $batchEmails[] = $email;
            $batchNisns[]  = $nisn;

            $studentsToInsert[] = [
                'full_name'     => $fullName,
                'nisn'          => $nisn,
                'email'         => $email,
                'gender'        => $gender,
                'phone_number'  => $phoneNumber ?: null,
                'parent_phone'  => $parentPhone ?: null,
                'class_room_id' => $classRoomId,
            ];
        }

        fclose($handle);

        // Jika terdapat baris yang bermasalah, kembalikan dengan pesan kesalahan lengkap
        if (!empty($errors)) {
            return redirect()->back()
                ->with('import_errors', $errors)
                ->with('error', 'Proses impor dibatalkan karena ditemukan kesalahan pada format data. Silakan periksa rincian kesalahan di bawah ini.');
        }

        // Pastikan ada setidaknya satu baris data valid untuk diimpor
        if (empty($studentsToInsert)) {
            return redirect()->back()->with('error', 'Berkas CSV tidak memuat data siswa yang dapat diimpor.');
        }

        // 4. Eksekusi penyimpanan ke basis data menggunakan Database Transaction
        DB::transaction(function () use ($studentsToInsert, $activeYear) {
            foreach ($studentsToInsert as $studentData) {
                // Membuat akun pengguna (password null karena menggunakan Google SSO Belajar.id)
                $user = User::create([
                    'email' => $studentData['email'],
                    'role'  => 'siswa',
                ]);

                // Membuat profil siswa terkait
                StudentProfile::create([
                    'user_id'      => $user->id,
                    'full_name'    => $studentData['full_name'],
                    'nisn'         => $studentData['nisn'],
                    'gender'       => $studentData['gender'],
                    'phone_number' => $studentData['phone_number'],
                    'parent_phone' => $studentData['parent_phone'],
                    'status'       => 'aktif',
                ]);

                // Jika kolom kelas diisi, daftarkan langsung ke rombel kelas tersebut pada tahun ajaran aktif
                if (!empty($studentData['class_room_id']) && $activeYear) {
                    ClassEnrollment::create([
                        'student_id'       => $user->id,
                        'class_room_id'    => $studentData['class_room_id'],
                        'academic_year_id' => $activeYear->id,
                        'status'           => 'aktif',
                    ]);
                }
            }
        });

        $total = count($studentsToInsert);
        return redirect()->route('students.index')->with('success', "Alhamdulillah! Sebanyak {$total} data siswa berhasil diimpor ke sistem.");
    }
}

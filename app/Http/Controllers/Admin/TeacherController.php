<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\TeacherProfile;
use App\Http\Requests\Admin\StoreTeacherRequest;
use App\Http\Requests\Admin\UpdateTeacherRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    // Ngambil data buat ditampilin di tabel utama dengan pencarian & paginasi
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::where('role', 'guru')
            ->with('teacherProfile');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhereHas('teacherProfile', function($tp) use ($search) {
                      $tp->where('full_name', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        $teachers = $query->latest()->paginate(10)->appends($request->all());

        return view('admin.teachers.index', compact('teachers', 'search'));
    }

    // Nampilin halaman form tambah data
    public function create()
    {
        return view('admin.teachers.create');
    }

    // Proses nyimpen data baru ke database (masukin ke 2 tabel sekaligus)
    public function store(StoreTeacherRequest $request)
    {
        // 1. Data sudah tervalidasi otomatis oleh StoreTeacherRequest
        $validatedData = $request->validated();

        // Pake DB transaction biar aman: kalo profilnya gagal disimpen, akunnya otomatis gak jadi ke-create (rollback). Gak ada data sampah!
        DB::transaction(function () use ($validatedData) {
            
            // 1. Bikin akun usernya dulu (password disengaja kosongin soalnya nanti murni login pake SSO Google Belajar.id)
            $user = User::create([
                'email' => $validatedData['email'],
                'role' => 'guru',
            ]);

            // 2. Kalo akunnya sukses dibuat, langsung bikinin profilnya dan sambungin pake ID user tadi
            TeacherProfile::create([
                'user_id' => $user->id,
                'full_name' => $validatedData['full_name'],
                'nip' => $validatedData['nip'],
                'gender' => $validatedData['gender'],
                'position' => $validatedData['position'],
                'phone_number' => $validatedData['phone_number'],
            ]);
        });

        // Balik ke halaman daftar guru sambil ngasih pesan sukses
        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil ditambahkan!');
    }

    // Nampilin halaman form buat edit data (narik datanya dulu berdasarkan ID)
    public function edit($id)
    {
        // Cari usernya, pastiin dapet
        $teacher = User::with('teacherProfile')->findOrFail($id);
        return view('admin.teachers.edit', compact('teacher'));
    }

    // Proses nyimpen update data ke database
    public function update(UpdateTeacherRequest $request, $id)
    {
        $teacher = User::findOrFail($id);

        // 1. Ambil data yang sudah lolos validasi
        $validatedData = $request->validated();

        // Pake transaction juga pas ngedit biar tetep aman
        DB::transaction(function () use ($validatedData, $teacher) {
            // Update emailnya doang di tabel users
            $teacher->update([
                'email' => $validatedData['email'],
            ]);

            // Kalo profilnya udah ada, langsung diupdate. 
            // Kalo ternyata dari database-nya belum punya profil (jaga-jaga error manual), ya dibikinin baru.
            if ($teacher->teacherProfile) {
                $teacher->teacherProfile->update([
                    'full_name' => $validatedData['full_name'],
                    'nip' => $validatedData['nip'],
                    'gender' => $validatedData['gender'],
                    'position' => $validatedData['position'],
                    'phone_number' => $validatedData['phone_number'],
                ]);
            } else {
                TeacherProfile::create([
                    'user_id' => $teacher->id,
                    'full_name' => $validatedData['full_name'],
                    'nip' => $validatedData['nip'],
                    'gender' => $validatedData['gender'],
                    'position' => $validatedData['position'],
                    'phone_number' => $validatedData['phone_number'],
                ]);
            }
        });

        return redirect()->route('teachers.index')->with('success', 'Sip! Perubahan data guru sudah disimpan.');
    }

    // Proses hapus data guru
    public function destroy($id)
    {
        $teacher = User::findOrFail($id);
        
        // Mending kita hapus profilnya duluan secara manual baru hapus akunnya biar database-nya bener-bener bersih (gak ada data nyangkut)
        if ($teacher->teacherProfile) {
            $teacher->teacherProfile->delete();
        }
        
        $teacher->delete();

        return redirect()->back()->with('success', 'Oke, data guru tersebut berhasil dihapus permanen.');
    }

    /**
     * Mengunduh berkas template CSV resmi untuk mempermudah admin
     * dalam menyiapkan data guru yang akan diimpor secara massal.
     * Menggunakan tanda BOM UTF-8 agar berkas langsung terbaca rapi saat dibuka di Microsoft Excel.
     */
    public function downloadTemplate()
    {
        $fileName = 'template_data_guru.csv';

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

            // Baris header kolom template
            fputcsv($handle, [
                'Nama Lengkap',
                'NIP',
                'Email Akun Belajar',
                'Jabatan',
                'Jenis Kelamin',
                'Nomor HP',
            ]);

            // Baris contoh data pengisian ke-1 (Guru Pengajar) - Gunakan ="..." agar Excel tidak mengubah NIP & No HP jadi format ilmiah E+
            fputcsv($handle, [
                'Budi Santoso, S.Pd',
                '="198501012010011001"',
                'budi.santoso@guru.smp.belajar.id',
                'Guru',
                'Laki-laki',
                '="081234567890"',
            ]);

            // Baris contoh data pengisian ke-2 (Kepala Sekolah)
            fputcsv($handle, [
                'Dra. Hj. Maryam, M.Pd',
                '="197503152000032002"',
                'maryam@guru.smp.belajar.id',
                'Kepala Sekolah',
                'Perempuan',
                '="081334455667"',
            ]);

            // Baris contoh data pengisian ke-3 (Wakil Kepala Sekolah)
            fputcsv($handle, [
                'Ahmad Rifai, S.Si',
                '="199008202015021003"',
                'ahmad.rifai@guru.smp.belajar.id',
                'Wakil Kepala Sekolah',
                'Laki-laki',
                '="081398765432"',
            ]);

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Memproses impor massal data guru dari berkas CSV/Excel.
     * Sistem memvalidasi setiap baris data dan menyimpan akun serta profil guru secara aman.
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

        $rowNumber = 1;
        $errors = [];
        $teachersToInsert = [];
        $batchEmails = [];
        $batchNips = [];

        // 3. Membaca dan memvalidasi setiap baris data dari berkas CSV
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $rowNumber++;

            // Abaikan baris kosong
            if (empty(array_filter($row))) {
                continue;
            }

            // Pastikan baris memiliki minimal 5 kolom utama
            if (count($row) < 5) {
                $errors[] = "Baris ke-{$rowNumber}: Jumlah kolom tidak lengkap. Pastikan mengisi Nama, NIP, Email, Jabatan, dan Jenis Kelamin.";
                continue;
            }

            // Bersihkan data dari karakter ekstra tanda kutip / formula Excel (contoh: ="0812..." atau '0812...)
            $fullName    = trim($row[0] ?? '');
            $nip         = trim(trim($row[1] ?? ''), "=\"' \t\n\r\0\x0B");
            $email       = trim($row[2] ?? '');
            $positionRaw = strtolower(trim($row[3] ?? ''));
            $genderRaw   = strtolower(trim($row[4] ?? ''));
            $phoneNumber = trim(trim($row[5] ?? ''), "=\"' \t\n\r\0\x0B");

            // Validasi Nama Lengkap
            if (empty($fullName) || strlen($fullName) > 100) {
                $errors[] = "Baris ke-{$rowNumber}: Nama lengkap wajib diisi dan maksimal 100 karakter.";
                continue;
            }

            // Validasi NIP
            if (empty($nip) || strlen($nip) > 30) {
                $errors[] = "Baris ke-{$rowNumber}: NIP '{$nip}' wajib diisi dan maksimal 30 karakter.";
                continue;
            }

            // Cek duplikasi NIP di dalam berkas CSV
            if (in_array($nip, $batchNips)) {
                $errors[] = "Baris ke-{$rowNumber}: NIP '{$nip}' terdeteksi ganda di dalam berkas yang sama.";
                continue;
            }

            // Cek duplikasi NIP di database
            if (TeacherProfile::where('nip', $nip)->exists()) {
                $errors[] = "Baris ke-{$rowNumber}: NIP '{$nip}' sudah terdaftar di sistem.";
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

            // Normalisasi pilihan Jabatan
            if (in_array($positionRaw, ['kepala sekolah', 'kepala_sekolah', 'kepsek'])) {
                $position = 'kepala_sekolah';
            } elseif (in_array($positionRaw, ['wakil kepala sekolah', 'wakil_kepala_sekolah', 'wakasek', 'waka'])) {
                $position = 'wakil_kepala_sekolah';
            } elseif (in_array($positionRaw, ['guru', 'guru pengajar', 'pengajar', 'guru mata pelajaran'])) {
                $position = 'guru';
            } else {
                $errors[] = "Baris ke-{$rowNumber}: Jabatan '{$row[3]}' tidak valid. Gunakan 'Guru', 'Kepala Sekolah', atau 'Wakil Kepala Sekolah'.";
                continue;
            }

            // Normalisasi pilihan Jenis Kelamin
            if (in_array($genderRaw, ['l', 'laki-laki', 'laki laki', 'pria'])) {
                $gender = 'laki-laki';
            } elseif (in_array($genderRaw, ['p', 'perempuan', 'wanita'])) {
                $gender = 'perempuan';
            } else {
                $errors[] = "Baris ke-{$rowNumber}: Jenis kelamin '{$row[4]}' tidak valid. Gunakan 'Laki-laki' atau 'Perempuan'.";
                continue;
            }

            // Catat ke daftar data yang siap disimpan
            $batchEmails[] = $email;
            $batchNips[]   = $nip;

            $teachersToInsert[] = [
                'full_name'    => $fullName,
                'nip'          => $nip,
                'email'        => $email,
                'position'     => $position,
                'gender'       => $gender,
                'phone_number' => $phoneNumber ?: null,
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
        if (empty($teachersToInsert)) {
            return redirect()->back()->with('error', 'Berkas CSV tidak memuat data guru yang dapat diimpor.');
        }

        // 4. Eksekusi penyimpanan ke basis data menggunakan Database Transaction
        DB::transaction(function () use ($teachersToInsert) {
            foreach ($teachersToInsert as $teacherData) {
                // Membuat akun pengguna (password null karena menggunakan Google SSO Belajar.id)
                $user = User::create([
                    'email' => $teacherData['email'],
                    'role'  => 'guru',
                ]);

                // Membuat profil guru terkait
                TeacherProfile::create([
                    'user_id'      => $user->id,
                    'full_name'    => $teacherData['full_name'],
                    'nip'          => $teacherData['nip'],
                    'gender'       => $teacherData['gender'],
                    'position'     => $teacherData['position'],
                    'phone_number' => $teacherData['phone_number'],
                ]);
            }
        });

        $total = count($teachersToInsert);
        return redirect()->route('teachers.index')->with('success', "Alhamdulillah! Sebanyak {$total} data guru berhasil diimpor ke sistem.");
    }
}

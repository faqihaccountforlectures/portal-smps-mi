<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Subject;
use App\Http\Requests\Admin\StoreSubjectRequest;
use App\Http\Requests\Admin\UpdateSubjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Nampilin halaman utama daftar semua mata pelajaran dengan fitur pencarian & paginasi.
     * Ini dipanggil pas user buka /admin/subjects.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Subject::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('grade_level', 'like', "%{$search}%");
            });
        }

        // Ambil data mata pelajaran dengan pagination dan pertahankan parameter pencarian
        $subjects = $query->orderBy('code', 'asc')->paginate(10)->appends($request->all());
        
        // Kirim datanya ke view (tampilan HTML) yang ada di folder resources/views/admin/subjects/index.blade.php
        return view('admin.subjects.index', compact('subjects', 'search'));
    }

    /**
     * Nampilin form kosong buat nambah mata pelajaran baru.
     * Ini dipanggil pas user nge-klik tombol "Tambah Data" atau buka /admin/subjects/create.
     */
    public function create()
    {
        // Langsung aja arahin ke halaman form create
        return view('admin.subjects.create');
    }

    /**
     * Nah, fungsi ini buat nangkap data dari form tambah mata pelajaran, 
     * trus di-save ke database.
     */
    public function store(StoreSubjectRequest $request)
    {
        // 1. Data yang sampai di sini sudah 100% tervalidasi oleh StoreSubjectRequest
        $validatedData = $request->validated();

        // 2. Kita tinggal simpan datanya ke tabel subjects
        Subject::create($validatedData);

        // 3. Habis nyimpen, balikin user ke halaman daftar mapel sambil bawa pesan sukses
        return redirect()->route('subjects.index')
                         ->with('success', 'Berhasil! Data mata pelajaran baru udah ditambahkan.');
    }

    /**
     * Nampilin form edit data mata pelajaran yang udah ada.
     * Ini kepanggil pas user klik tombol edit (logo pensil biasanya) di baris tabel tertentu.
     */
    public function edit($id)
    {
        // Cari data mapel berdasarkan ID-nya. Kalau nggak ketemu, langsung munculin halaman 404 (Not Found)
        $subject = Subject::findOrFail($id);
        
        // Kalau ketemu, bawa datanya ke halaman form edit biar formnya udah keisi otomatis
        return view('admin.subjects.edit', compact('subject'));
    }

    /**
     * Fungsi ini buat nangkap hasil editan dari form edit, trus di-update ke database.
     */
    public function update(UpdateSubjectRequest $request, $id)
    {
        // Cari dulu data aslinya di database
        $subject = Subject::findOrFail($id);

        // 1. Ambil data yang sudah lolos validasi dari UpdateSubjectRequest
        $validatedData = $request->validated();

        // 2. Update datanya di database
        $subject->update($validatedData);

        // 3. Tendang balik usernya ke halaman daftar mapel pake notifikasi sukses
        return redirect()->route('subjects.index')
                         ->with('success', 'Mantap! Data mata pelajaran berhasil diupdate.');
    }

    /**
     * Buat ngehapus mata pelajaran dari muka bumi (database).
     * Biasanya dipanggil via Modal Konfirmasi Hapus.
     */
    public function destroy($id)
    {
        // Cari datanya dulu
        $subject = Subject::findOrFail($id);
        
        // Hapus datanya dari tabel subjects
        $subject->delete();

        // Balik ke halaman daftar mapel bawa pesan sukses
        return redirect()->route('subjects.index')
                         ->with('success', 'Oke, data mata pelajaran sudah dihapus selamanya.');
    }

    /**
     * Mengunduh berkas template CSV untuk mempermudah pengisian data massal mata pelajaran.
     * Dilengkapi dengan UTF-8 BOM agar terbaca rapi saat dibuka di Microsoft Excel.
     */
    public function downloadTemplate()
    {
        $fileName = 'template_impor_mata_pelajaran.csv';
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
                'Kode Mata Pelajaran',
                'Nama Mata Pelajaran',
                'Tingkat Kelas',
                'KKM',
                'Kategori',
            ]);

            // Baris contoh data pengisian ke-1 (PAI BP - Kode A, Kelas 7 -> A-7)
            fputcsv($handle, [
                'A-7',
                'PAI BP',
                '7',
                '75.00',
                'Wajib',
            ]);

            // Baris contoh data pengisian ke-2 (PPKN - Kode B, Kelas 8 -> B-8)
            fputcsv($handle, [
                'B-8',
                'PPKN',
                '8',
                '75.00',
                'Wajib',
            ]);

            // Baris contoh data pengisian ke-3 (Matematika - Kode D, Kelas 7 -> D-7)
            fputcsv($handle, [
                'D-7',
                'Matematika',
                '7',
                '75.00',
                'Wajib',
            ]);

            // Baris contoh data pengisian ke-4 (Bahasa Sunda - Kode I, Kelas 9 -> I-9)
            fputcsv($handle, [
                'I-9',
                'Bahasa Sunda',
                '9',
                '75.00',
                'Muatan Lokal',
            ]);

            // Baris contoh data pengisian ke-5 (KKA & Robotik - Kode O, Kelas 7 -> O-7)
            fputcsv($handle, [
                'O-7',
                'KKA & Robotik',
                '7',
                '75.00',
                'Muatan Lokal',
            ]);

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Memproses impor massal data mata pelajaran dari berkas CSV/Excel.
     * Sistem memvalidasi setiap baris data dan menyimpannya secara aman ke database.
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
        $subjectsToInsert = [];
        $batchCodes = [];

        // 3. Membaca dan memvalidasi setiap baris data dari berkas CSV
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $rowNumber++;

            // Abaikan baris kosong
            if (empty(array_filter($row))) {
                continue;
            }

            // Pastikan baris memiliki minimal 4 kolom (Kode, Nama, Kelas, KKM)
            if (count($row) < 4) {
                $errors[] = "Baris ke-{$rowNumber}: Jumlah kolom tidak lengkap. Pastikan mengisi minimal Kode, Nama Mata Pelajaran, Tingkat Kelas, dan KKM.";
                continue;
            }

            // Bersihkan data dari karakter ekstra tanda kutip / spasi
            $code           = strtoupper(trim(trim($row[0] ?? ''), "=\"' \t\n\r\0\x0B"));
            $name           = trim($row[1] ?? '');
            $gradeLevelRaw  = trim(trim($row[2] ?? ''), "=\"' \t\n\r\0\x0B");
            $kkmRaw         = trim(str_replace(',', '.', trim($row[3] ?? '')));
            $categoryRaw    = strtolower(trim($row[4] ?? ''));

            // Validasi Kode Mata Pelajaran
            if (empty($code)) {
                $errors[] = "Baris ke-{$rowNumber}: Kode mata pelajaran wajib diisi.";
                continue;
            }

            if (strlen($code) > 20) {
                $errors[] = "Baris ke-{$rowNumber}: Kode mata pelajaran '{$code}' melebihi batas maksimal 20 karakter.";
                continue;
            }

            // Cek duplikasi Kode di dalam berkas CSV
            if (in_array($code, $batchCodes)) {
                $errors[] = "Baris ke-{$rowNumber}: Kode mata pelajaran '{$code}' terdeteksi ganda di dalam berkas yang sama.";
                continue;
            }

            // Validasi Nama Mata Pelajaran
            if (empty($name) || strlen($name) > 100) {
                $errors[] = "Baris ke-{$rowNumber}: Nama mata pelajaran wajib diisi dan maksimal 100 karakter.";
                continue;
            }

            // Validasi Tingkat Kelas (Opsional, jika diisi harus angka)
            $gradeLevel = null;
            if ($gradeLevelRaw !== '') {
                $extractedNumber = preg_replace('/[^0-9]/', '', $gradeLevelRaw);
                if ($extractedNumber === '' || !is_numeric($extractedNumber)) {
                    $errors[] = "Baris ke-{$rowNumber}: Tingkat kelas '{$gradeLevelRaw}' tidak valid. Isi dengan angka (contoh: 7, 8, atau 9).";
                    continue;
                }
                $gradeLevel = (int) $extractedNumber;
            }

            // Validasi KKM
            if ($kkmRaw === '') {
                $errors[] = "Baris ke-{$rowNumber}: Nilai KKM wajib diisi.";
                continue;
            }

            if (!is_numeric($kkmRaw) || (float)$kkmRaw < 0 || (float)$kkmRaw > 100) {
                $errors[] = "Baris ke-{$rowNumber}: Nilai KKM '{$row[3]}' harus berupa angka antara 0 sampai 100.";
                continue;
            }
            $kkm = round((float) $kkmRaw, 2);

            // Validasi Kategori (Wajib / Muatan Lokal / A / B)
            $category = null;
            if ($categoryRaw !== '') {
                if (in_array($categoryRaw, ['a', 'wajib', 'wajib (a)', 'wajib(a)', 'kelompok a'])) {
                    $category = 'A';
                } elseif (in_array($categoryRaw, ['b', 'muatan lokal', 'muatan lokal (b)', 'muatan_lokal', 'mulok', 'kelompok b'])) {
                    $category = 'B';
                } else {
                    $errors[] = "Baris ke-{$rowNumber}: Kategori '{$row[4]}' tidak valid. Gunakan 'Wajib' (A) atau 'Muatan Lokal' (B).";
                    continue;
                }
            }

            // Catat kode agar tidak dobel dalam 1 file batch
            $batchCodes[] = $code;

            $subjectsToInsert[] = [
                'code'        => $code,
                'name'        => $name,
                'grade_level' => $gradeLevel,
                'kkm'         => $kkm,
                'category'    => $category,
            ];
        }

        fclose($handle);

        // Jika terdapat baris yang bermasalah, kembalikan dengan rincian kesalahan lengkap
        if (!empty($errors)) {
            return redirect()->back()
                ->with('import_errors', $errors)
                ->with('error', 'Proses impor dibatalkan karena ditemukan kesalahan pada format data. Silakan periksa rincian kesalahan di bawah ini.');
        }

        // Pastikan ada data valid yang siap diimpor
        if (empty($subjectsToInsert)) {
            return redirect()->back()->with('error', 'Berkas CSV tidak memuat data mata pelajaran yang dapat diimpor.');
        }

        // 4. Eksekusi penyimpanan ke basis data menggunakan Database Transaction (updateOrCreate agar data yang sudah ada dapat disinkronisasi)
        DB::transaction(function () use ($subjectsToInsert) {
            foreach ($subjectsToInsert as $subjectData) {
                Subject::updateOrCreate(
                    ['code' => $subjectData['code']],
                    [
                        'name'        => $subjectData['name'],
                        'grade_level' => $subjectData['grade_level'],
                        'kkm'         => $subjectData['kkm'],
                        'category'    => $subjectData['category'],
                    ]
                );
            }
        });

        $total = count($subjectsToInsert);
        return redirect()->route('subjects.index')->with('success', "Alhamdulillah! Sebanyak {$total} data mata pelajaran berhasil diimpor ke sistem.");
    }
}

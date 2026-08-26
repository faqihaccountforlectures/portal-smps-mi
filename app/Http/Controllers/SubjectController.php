<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Nampilin halaman utama daftar semua mata pelajaran.
     * Ini dipanggil pas user buka /admin/subjects.
     */
    public function index()
    {
        // Ambil semua data mata pelajaran dari database, urutin berdasarkan kode biar rapi
        $subjects = Subject::orderBy('code', 'asc')->get();
        
        // Kirim datanya ke view (tampilan HTML) yang ada di folder resources/views/admin/subjects/index.blade.php
        return view('admin.subjects.index', compact('subjects'));
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
    public function store(Request $request)
    {
        // 1. Validasi dulu inputannya, mastiin data yang diisi bener dan lengkap
        $validatedData = $request->validate([
            // Kode mapel wajib diisi, maksimal 20 karakter, dan nggak boleh kembar (unik di tabel subjects)
            'code' => 'required|string|max:20|unique:subjects,code',
            // Nama mapel wajib diisi, maksimal 100 karakter
            'name' => 'required|string|max:100',
            // Tingkat kelas (misal kelas 7, 8, 9). Opsional (boleh kosong), kalau diisi harus angka
            'grade_level' => 'nullable|integer',
            // KKM (Kriteria Ketuntasan Minimal). Wajib diisi, formatnya angka/desimal (misal 75.00)
            'kkm' => 'required|numeric|min:0|max:100',
            // Kategori mapel. Opsional, tapi kalau diisi cuma boleh 'A' (Wajib) atau 'B' (Muatan Lokal)
            'category' => 'nullable|in:A,B',
        ], [
            // Ini pesan error custom biar lebih gampang dimengerti sama user (bahasa Indonesia)
            'code.unique' => 'Kode mata pelajaran ini udah dipakai. Cari kode lain ya!',
            'code.required' => 'Kode mata pelajaran nggak boleh kosong.',
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'kkm.required' => 'Nilai KKM harus diisi.',
            'kkm.numeric' => 'Nilai KKM harus berupa angka.',
            'category.in' => 'Kategori cuma bisa pilih Wajib (A) atau Muatan Lokal (B).',
        ]);

        // 2. Kalau validasi lolos, kita simpan datanya ke tabel subjects
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
    public function update(Request $request, $id)
    {
        // Cari dulu data aslinya di database
        $subject = Subject::findOrFail($id);

        // 1. Validasi inputan hasil editannya
        $validatedData = $request->validate([
            // Kode mapel wajib unik, TAPI kecualikan ID mapel ini sendiri biar dia tetep bisa simpan kodenya yang lama
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'name' => 'required|string|max:100',
            'grade_level' => 'nullable|integer',
            'kkm' => 'required|numeric|min:0|max:100',
            'category' => 'nullable|in:A,B',
        ], [
            'code.unique' => 'Kode mata pelajaran ini udah dipakai mata pelajaran lain.',
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'kkm.required' => 'Nilai KKM harus diisi.',
            'kkm.numeric' => 'Nilai KKM harus berupa angka.',
            'category.in' => 'Pilihan kategori tidak valid.',
        ]);

        // 2. Update datanya di database pake data baru yang udah tervalidasi
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
}

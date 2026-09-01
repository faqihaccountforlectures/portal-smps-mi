<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Subject;
use App\Http\Requests\Admin\StoreSubjectRequest;
use App\Http\Requests\Admin\UpdateSubjectRequest;
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
}

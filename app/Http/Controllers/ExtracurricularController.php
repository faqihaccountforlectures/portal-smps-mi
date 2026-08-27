<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExtracurricularController extends Controller
{
    /**
     * Nampilin halaman utama daftar semua ekstrakurikuler.
     * Ini dipanggil pas admin buka menu Ekstrakurikuler di sidebar.
     */
    public function index()
    {
        // Ambil semua data ekskul beserta data guru pembinanya dan profil gurunya, urutkan berdasarkan nama
        $extracurriculars = Extracurricular::with('teacher.teacherProfile')->orderBy('name')->get();
        
        // Kirim datanya ke halaman index
        return view('admin.extracurriculars.index', compact('extracurriculars'));
    }

    /**
     * Nampilin form kosong buat nambah data ekstrakurikuler baru.
     */
    public function create()
    {
        // Ambil data user yang jabatannya (role) adalah guru, untuk dimasukkan ke pilihan dropdown pembina
        $teachers = User::where('role', 'guru')->with('teacherProfile')->get();
        
        // Buka form create dengan membawa data guru
        return view('admin.extracurriculars.create', compact('teachers'));
    }

    /**
     * Menyimpan data dari form tambah ke dalam database.
     */
    public function store(Request $request)
    {
        // 1. Validasi dulu inputannya
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // maksimal 2MB
            'description' => 'nullable|string',
            'schedule' => 'required|string|max:100',
            'teacher_id' => 'required|exists:users,id',
            'fee' => 'required|numeric|min:0',
        ], [
            // Pesan error custom bahasa indonesia
            'name.required' => 'Nama ekstrakurikuler wajib diisi.',
            'schedule.required' => 'Jadwal wajib diisi.',
            'teacher_id.required' => 'Pembina harus dipilih.',
            'fee.required' => 'Biaya per bulan wajib diisi.',
        ]);

        // 2. Kalau admin upload foto/banner, simpan ke storage public
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('extracurriculars', 'public');
            $validated['image'] = $path; // simpan lokasi gambarnya ke array validasi
        }

        // 3. Simpan datanya ke database
        Extracurricular::create($validated);

        // 4. Balikin admin ke halaman daftar ekskul dengan pesan sukses
        return redirect()->route('extracurriculars.index')
                         ->with('success', 'Berhasil! Data Ekstrakurikuler baru telah ditambahkan.');
    }

    /**
     * Nampilin form edit buat data ekskul yang sudah ada.
     */
    public function edit(string $id)
    {
        // Cari data ekskulnya, kalau tidak ada otomatis error 404
        $extracurricular = Extracurricular::findOrFail($id);
        
        // Ambil data guru untuk dropdown pembina
        $teachers = User::where('role', 'guru')->with('teacherProfile')->get();
        
        // Buka halaman form edit
        return view('admin.extracurriculars.edit', compact('extracurricular', 'teachers'));
    }

    /**
     * Menyimpan perubahan dari form edit ke database.
     */
    public function update(Request $request, string $id)
    {
        // Cari data aslinya dulu
        $extracurricular = Extracurricular::findOrFail($id);

        // 1. Validasi inputannya
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'schedule' => 'required|string|max:100',
            'teacher_id' => 'required|exists:users,id',
            'fee' => 'required|numeric|min:0',
        ]);

        // 2. Kalau admin upload foto baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari server (jika ada) biar gak menuh-menuhin memori
            if ($extracurricular->image) {
                Storage::disk('public')->delete($extracurricular->image);
            }
            // Simpan gambar baru
            $path = $request->file('image')->store('extracurriculars', 'public');
            $validated['image'] = $path;
        }

        // 3. Update datanya di database
        $extracurricular->update($validated);

        return redirect()->route('extracurriculars.index')
                         ->with('success', 'Mantap! Data Ekstrakurikuler berhasil diperbarui.');
    }

    /**
     * Hapus data ekstrakurikuler selamanya dari database.
     */
    public function destroy(string $id)
    {
        // Cari data ekskulnya
        $extracurricular = Extracurricular::findOrFail($id);
        
        // Hapus juga foto bannernya dari server jika ada
        if ($extracurricular->image) {
            Storage::disk('public')->delete($extracurricular->image);
        }
        
        // Hapus dari tabel
        $extracurricular->delete();

        return redirect()->route('extracurriculars.index')
                         ->with('success', 'Oke, data Ekstrakurikuler telah dihapus selamanya.');
    }
}

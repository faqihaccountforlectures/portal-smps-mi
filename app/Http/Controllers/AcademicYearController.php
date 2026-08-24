<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    // Menampilkan daftar tahun ajaran
    public function index()
    {
        $academicYears = AcademicYear::all();
        return view('admin.academic-years.index', compact('academicYears'));
    }

    // Menyimpan tahun ajaran baru
    public function store(Request $request)
    {
       // Validasi pastiin data yang diinputkan user udah bener dan gak kosong
       $request->validate([
        'year_name' => 'required|string|max:20', // Nyesuaiin nama kolom di database
        'semester' => 'required|in:ganjil,genap',
        'is_active' => 'required|boolean',
       ]);
       
       // Kalo user nyentang status "Aktif" (is_active = true) buat tahun ajaran baru ini, 
       // berarti tahun ajaran lain yang tadinya aktif harus dimatiin (false).
       // Biar cuma ada 1 tahun ajaran yang aktif pada satu waktu.
       if ($request->is_active) {
        AcademicYear::where('is_active', true)->update(['is_active' => false]);
       }

       // Simpan data yang diinputin ke tabel academic_years
       AcademicYear::create($request->all());

       return redirect()->back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    // Nampilin form khusus buat ngedit data
    public function edit($id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        return view('admin.academic-years.edit', compact('academicYear'));
    }

    // Mengupdate (Edit) data tahun ajaran yang udah ada
    public function update(Request $request, $id)
    {
        // Cari data tahun ajaran berdasarkan ID yang dikirim. Kalo gak ketemu otomatis error 404 (Not Found)
        $academicYear = AcademicYear::findOrFail($id);

        // Validasi lagi datanya
        $request->validate([
            'year_name' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'is_active' => 'required|boolean',
        ]);

        // Kalo user ngubah status jadi "Aktif", pastiin kita non-aktifin tahun ajaran yang lain dulu
        if ($request->is_active && !$academicYear->is_active) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        // Simpan perubahan data ke database
        $academicYear->update($request->all());

        // Balikin user ke halaman daftar utama habis ngedit
        return redirect()->route('academic-years.index')->with('success', 'Data tahun ajaran berhasil diperbarui.');
    }

    // Menghapus data tahun ajaran
    public function destroy($id)
    {
        // Cari data tahun ajaran yang mau dihapus pake ID
        $academicYear = AcademicYear::findOrFail($id);

        // Proteksi nih, biar user gak asal hapus tahun ajaran yang lagi jalan (Aktif)
        if ($academicYear->is_active) {
            // Balikin pesan error
            return redirect()->back()->with('error', 'Tahun ajaran yang sedang aktif tidak dapat dihapus. Non-aktifkan terlebih dahulu.');
        }

        // Hapus datanya dari database secara permanen
        $academicYear->delete();

        return redirect()->back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
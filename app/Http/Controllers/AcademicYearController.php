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
       // PENJELASAN: Validasi memastikan data yang diinputkan user sudah benar dan tidak kosong
       $request->validate([
        'year_name' => 'required|string|max:20', // CATATAN: Ini sebelumnya 'name', kita perbaiki ke 'year_name' menyesuaikan kolom tabel database
        'semester' => 'required|in:ganjil,genap',
        'is_active' => 'required|boolean',
       ]);
       
       // PENJELASAN: Jika user mencentang status "Aktif" (is_active = true) untuk tahun ajaran baru ini, 
       // maka kita harus mengubah semua tahun ajaran lain yang tadinya aktif menjadi tidak aktif (false).
       // Tujuannya agar hanya ada 1 tahun ajaran yang aktif pada satu waktu.
       if ($request->is_active) {
        AcademicYear::where('is_active', true)->update(['is_active' => false]);
       }

       // PENJELASAN: Menyimpan data yang diinputkan ke dalam tabel academic_years
       AcademicYear::create($request->all());

       return redirect()->back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    // PENJELASAN: Menampilkan halaman khusus untuk form Edit Data
    public function edit($id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        return view('admin.academic-years.edit', compact('academicYear'));
    }

    // Mengupdate (Edit) data tahun ajaran yang sudah ada
    public function update(Request $request, $id)
    {
        // PENJELASAN: Mencari data tahun ajaran berdasarkan ID yang dikirim. Jika ID tidak ada, akan otomatis error 404 (Not Found)
        $academicYear = AcademicYear::findOrFail($id);

        // PENJELASAN: Memvalidasi data yang baru diinputkan user
        $request->validate([
            'year_name' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'is_active' => 'required|boolean',
        ]);

        // PENJELASAN: Jika user mengubah status menjadi "Aktif", pastikan kita menonaktifkan tahun ajaran yang lain dulu
        if ($request->is_active && !$academicYear->is_active) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        // PENJELASAN: Menyimpan perubahan data ke dalam database
        $academicYear->update($request->all());

        // PENJELASAN: Mengembalikan user ke halaman daftar utama setelah berhasil diedit
        return redirect()->route('academic-years.index')->with('success', 'Data tahun ajaran berhasil diperbarui.');
    }

    // Menghapus data tahun ajaran
    public function destroy($id)
    {
        // PENJELASAN: Mencari data tahun ajaran yang ingin dihapus berdasarkan ID
        $academicYear = AcademicYear::findOrFail($id);

        // PENJELASAN: Proteksi (pencegahan) agar user tidak menghapus tahun ajaran yang sedang berjalan (Aktif)
        if ($academicYear->is_active) {
            // Mengembalikan pesan error (harus ditangani di file .blade.php nantinya jika ingin dimunculkan)
            return redirect()->back()->with('error', 'Tahun ajaran yang sedang aktif tidak dapat dihapus. Non-aktifkan terlebih dahulu.');
        }

        // PENJELASAN: Menghapus data dari database secara permanen
        $academicYear->delete();

        return redirect()->back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
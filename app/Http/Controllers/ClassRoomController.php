<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    // PENJELASAN: Menampilkan halaman utama daftar Data Kelas
    public function index()
    {
        // Mengambil semua data kelas beserta relasi wali kelas (dan profil gurunya)
        // Diurutkan berdasarkan tingkat kelas (grade_level) lalu nama kelas
        $classes = ClassRoom::with('homeroomTeacher.teacherProfile')
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();
        
        // Mengambil daftar akun yang ber-role 'guru' untuk mengisi pilihan dropdown Wali Kelas
        $teachers = User::where('role', 'guru')->with('teacherProfile')->get();

        return view('admin.classes.index', compact('classes', 'teachers'));
    }

    // PENJELASAN: Menyimpan data kelas baru ke tabel class_rooms
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
        ]);

        ClassRoom::create($request->all());

        return redirect()->back()->with('success', 'Data kelas berhasil ditambahkan.');
    }

    // PENJELASAN: Menampilkan halaman khusus untuk form Edit Data Kelas
    public function edit($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        
        // Perlu mengambil daftar guru lagi untuk dropdown di halaman edit
        $teachers = User::where('role', 'guru')->with('teacherProfile')->get();

        return view('admin.classes.edit', compact('classRoom', 'teachers'));
    }

    // PENJELASAN: Mengupdate (Edit) data kelas yang sudah ada di database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
        ]);

        $classRoom = ClassRoom::findOrFail($id);
        $classRoom->update($request->all());

        // Mengarahkan kembali ke halaman index tabel kelas
        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    // PENJELASAN: Menghapus data kelas dari database
    public function destroy($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        
        // Catatan: Di masa depan jika diperlukan, kita bisa menambahkan proteksi 
        // agar kelas tidak bisa dihapus jika sudah ada siswa di dalamnya.
        
        $classRoom->delete();

        return redirect()->back()->with('success', 'Data kelas berhasil dihapus secara permanen.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    // Nampilin halaman utama daftar Data Kelas
    public function index()
    {
        // Ngambil semua data kelas beserta relasi wali kelas (dan profil gurunya)
        // Diurutin berdasarkan tingkat kelas (grade_level) terus nama kelas
        $classes = ClassRoom::with('homeroomTeacher.teacherProfile')
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();
        
        // Ngambil daftar akun yang ber-role 'guru' buat ngisi pilihan dropdown Wali Kelas
        $teachers = User::where('role', 'guru')->with('teacherProfile')->get();

        return view('admin.classes.index', compact('classes', 'teachers'));
    }

    // Nyimpen data kelas baru ke tabel class_rooms
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

    // Nampilin halaman khusus buat form Edit Data Kelas
    public function edit($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        
        // Perlu ngambil daftar guru lagi buat dropdown di halaman edit
        $teachers = User::where('role', 'guru')->with('teacherProfile')->get();

        return view('admin.classes.edit', compact('classRoom', 'teachers'));
    }

    // Ngupdate (Edit) data kelas yang udah ada di database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
        ]);

        $classRoom = ClassRoom::findOrFail($id);
        $classRoom->update($request->all());

        // Balikin lagi ke halaman index tabel kelas
        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    // Menghapus data kelas dari database
    public function destroy($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        
        // Buat jaga-jaga di masa depan, kita bisa nambahin proteksi 
        // biar kelas gak bisa dihapus kalo udah ada siswa di dalemnya.
        
        $classRoom->delete();

        return redirect()->back()->with('success', 'Data kelas berhasil dihapus secara permanen.');
    }
}

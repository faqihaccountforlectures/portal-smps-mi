<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    // Catatan: Ngambil data buat ditampilin di tabel utama
    public function index()
    {
        // Narik user yang rolenya 'guru', sekalian ngambil profilnya biar gampang pas ditampilin
        $teachers = User::where('role', 'guru')->with('teacherProfile')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    // Catatan: Nampilin halaman form tambah data
    public function create()
    {
        return view('admin.teachers.create');
    }

    // Catatan: Proses nyimpen data baru ke database (masukin ke 2 tabel sekaligus)
    public function store(Request $request)
    {
        // Validasi inputan dari form admin biar datanya bener dan gak kosong melompong
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'gender' => 'required|in:laki-laki,perempuan',
            'position' => 'required|in:guru,kepala_sekolah,wakil_kepala_sekolah',
            'phone_number' => 'nullable|string|max:20',
        ]);

        // Pake DB transaction biar aman: kalo profilnya gagal disimpen, akunnya otomatis gak jadi ke-create (rollback). Gak ada data sampah!
        DB::transaction(function () use ($request) {
            
            // 1. Bikin akun usernya dulu (password disengaja kosongin soalnya nanti murni login pake SSO Google Belajar.id)
            $user = User::create([
                'email' => $request->email,
                'role' => 'guru',
            ]);

            // 2. Kalo akunnya sukses dibuat, langsung bikinin profilnya dan sambungin pake ID user tadi
            TeacherProfile::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'nip' => $request->nip,
                'gender' => $request->gender,
                'position' => $request->position,
                'phone_number' => $request->phone_number,
            ]);
        });

        // Balik ke halaman daftar guru sambil ngasih pesan sukses
        return redirect()->route('teachers.index')->with('success', 'Asik! Data guru baru berhasil ditambahkan.');
    }

    // Catatan: Nampilin halaman form buat edit data (narik datanya dulu berdasarkan ID)
    public function edit($id)
    {
        // Cari usernya, pastiin dapet
        $teacher = User::with('teacherProfile')->findOrFail($id);
        return view('admin.teachers.edit', compact('teacher'));
    }

    // Catatan: Proses nyimpen update data ke database
    public function update(Request $request, $id)
    {
        $teacher = User::findOrFail($id);

        // Validasi lagi, khusus buat email biar nggak bentrok sama email orang lain (tapi ngebolehin pake email dia sendiri yang sekarang)
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'full_name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'gender' => 'required|in:laki-laki,perempuan',
            'position' => 'required|in:guru,kepala_sekolah,wakil_kepala_sekolah',
            'phone_number' => 'nullable|string|max:20',
        ]);

        // Pake transaction juga pas ngedit biar tetep aman
        DB::transaction(function () use ($request, $teacher) {
            // Update emailnya doang di tabel users
            $teacher->update([
                'email' => $request->email,
            ]);

            // Kalo profilnya udah ada, langsung diupdate. 
            // Kalo ternyata dari database-nya belum punya profil (jaga-jaga error manual), ya dibikinin baru.
            if ($teacher->teacherProfile) {
                $teacher->teacherProfile->update([
                    'full_name' => $request->full_name,
                    'nip' => $request->nip,
                    'gender' => $request->gender,
                    'position' => $request->position,
                    'phone_number' => $request->phone_number,
                ]);
            } else {
                TeacherProfile::create([
                    'user_id' => $teacher->id,
                    'full_name' => $request->full_name,
                    'nip' => $request->nip,
                    'gender' => $request->gender,
                    'position' => $request->position,
                    'phone_number' => $request->phone_number,
                ]);
            }
        });

        return redirect()->route('teachers.index')->with('success', 'Sip! Perubahan data guru sudah disimpan.');
    }

    // Catatan: Proses hapus data guru
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
}

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
    // Ngambil data buat ditampilin di tabel utama
    public function index()
    {
        // Narik user yang rolenya 'guru', sekalian ngambil profilnya biar gampang pas ditampilin
        $teachers = User::where('role', 'guru')->with('teacherProfile')->get();
        return view('admin.teachers.index', compact('teachers'));
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
        return redirect()->route('teachers.index')->with('success', 'Asik! Data guru baru berhasil ditambahkan.');
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
}

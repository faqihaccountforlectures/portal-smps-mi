<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\StudentProfile;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // Nampilin daftar siswa di tabel utama dengan pencarian & paginasi
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::where('role', 'siswa')
            ->with('studentProfile');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhereHas('studentProfile', function($sp) use ($search) {
                      $sp->where('full_name', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                        ->orWhere('parent_phone', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->latest()->paginate(10)->appends($request->all());

        return view('admin.students.index', compact('students', 'search'));
    }

    // Nampilin form tambah data
    public function create()
    {
        return view('admin.students.create');
    }

    // Proses simpan data ke 2 tabel pakai transaction
    public function store(StoreStudentRequest $request)
    {
        // 1. Data sudah tervalidasi oleh StoreStudentRequest
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData) {
            // Bikin akun login (password kosong karena pakai SSO Google Belajar.id)
            $user = User::create([
                'email' => $validatedData['email'],
                'role' => 'siswa',
            ]);

            // Bikin profil siswanya
            StudentProfile::create([
                'user_id' => $user->id,
                'full_name' => $validatedData['full_name'],
                'nisn' => $validatedData['nisn'],
                'gender' => $validatedData['gender'],
                'phone_number' => $validatedData['phone_number'],
                'parent_phone' => $validatedData['parent_phone'],
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Sip! Data siswa baru berhasil ditambahkan.');
    }

    // Nampilin form edit
    public function edit($id)
    {
        $student = User::with('studentProfile')->findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    // Proses update data
    public function update(UpdateStudentRequest $request, $id)
    {
        $student = User::findOrFail($id);

        // 1. Ambil data yang sudah lolos validasi
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData, $student) {
            // Update email loginnya
            $student->update([
                'email' => $validatedData['email'],
            ]);

            // Update profil siswanya
            if ($student->studentProfile) {
                $student->studentProfile->update([
                    'full_name' => $validatedData['full_name'],
                    'nisn' => $validatedData['nisn'],
                    'gender' => $validatedData['gender'],
                    'phone_number' => $validatedData['phone_number'],
                    'parent_phone' => $validatedData['parent_phone'],
                ]);
            } else {
                StudentProfile::create([
                    'user_id' => $student->id,
                    'full_name' => $validatedData['full_name'],
                    'nisn' => $validatedData['nisn'],
                    'gender' => $validatedData['gender'],
                    'phone_number' => $validatedData['phone_number'],
                    'parent_phone' => $validatedData['parent_phone'],
                ]);
            }
        });

        return redirect()->route('students.index')->with('success', 'Oke mantap! Perubahan data siswa sudah tersimpan.');
    }

    // Proses hapus data
    public function destroy($id)
    {
        $student = User::findOrFail($id);
        
        // Hapus profilnya dulu biar bersih, baru hapus akunnya
        if ($student->studentProfile) {
            $student->studentProfile->delete();
        }
        
        $student->delete();

        return redirect()->back()->with('success', 'Data siswa tersebut berhasil dihapus permanen.');
    }
}

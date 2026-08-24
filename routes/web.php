<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AcademicYearController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Route Halaman Login
Route::get('/login', function () {
    // Kalau user sudah login, alihkan ke halaman dashboard
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
})->name('login');

// Route untuk memproses form login manual (Email & Password)
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

// Route untuk Login Google
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// Route Halaman Dashboard (Wajib Login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// Route Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Halaman Dashboard (Pisah berdasarkan Role)
Route::get('/dashboard', function () {
    // Ambil data role user yang sedang login
    $role = Auth::user()->role;

    // Arahkan ke folder masing-masing sesuai role
    if ($role === 'admin') {
        return view('admin.dashboard');
    } elseif ($role === 'guru') {
        return view('guru.dashboard');
    } elseif ($role === 'siswa') {
        return view('siswa.dashboard');
    }

    // Jika rolenya tidak jelas, alihkan kembali ke depan
    Auth::logout();
    return redirect('/login')->with('error', 'Hak akses tidak valid.');
})->middleware('auth');

// ==========================================
// ROUTES MASTER DATA TAHUN AJARAN (ACADEMIC YEARS)
// ==========================================
// Kumpulan route buat ngatur Tahun Ajaran (sementara ini cuma admin yang boleh akses)
// Nampilin halaman utama daftar tahun ajaran
Route::get('/admin/academic-years', [AcademicYearController::class, 'index'])->name('academic-years.index');
// Buat nyimpen data tahun ajaran baru dari form tambah
Route::post('/admin/academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store');
// Nampilin form khusus buat ngedit tahun ajaran
Route::get('/admin/academic-years/{id}/edit', [AcademicYearController::class, 'edit'])->name('academic-years.edit');
// Proses update data tahun ajaran ke database
Route::put('/admin/academic-years/{id}', [AcademicYearController::class, 'update'])->name('academic-years.update');
// Proses ngehapus data tahun ajaran secara permanen
Route::delete('/admin/academic-years/{id}', [AcademicYearController::class, 'destroy'])->name('academic-years.destroy');

// ==========================================
// ROUTES MASTER DATA KELAS (CLASS ROOMS)
// ==========================================
// Kumpulan route buat fitur manajemen Kelas
// Nampilin daftar kelas sekalian nama wali kelasnya
Route::get('/admin/classes', [\App\Http\Controllers\ClassRoomController::class, 'index'])->name('classes.index');
// Proses nyimpen kelas baru
Route::post('/admin/classes', [\App\Http\Controllers\ClassRoomController::class, 'store'])->name('classes.store');
// Buka halaman buat ngedit kelas tertentu
Route::get('/admin/classes/{id}/edit', [\App\Http\Controllers\ClassRoomController::class, 'edit'])->name('classes.edit');
// Simpan hasil editan kelas ke database
Route::put('/admin/classes/{id}', [\App\Http\Controllers\ClassRoomController::class, 'update'])->name('classes.update');
// Hapus data kelas (hati-hati, kelas yang udah ada muridnya baiknya jangan dihapus)
Route::delete('/admin/classes/{id}', [\App\Http\Controllers\ClassRoomController::class, 'destroy'])->name('classes.destroy');

// ==========================================
// ROUTES MASTER DATA GURU (TEACHERS)
// ==========================================
// Kumpulan route buat kelola data Guru (plus sekalian bikin akun loginnya)
// Nampilin tabel daftar semua guru yang terdaftar
Route::get('/admin/teachers', [\App\Http\Controllers\TeacherController::class, 'index'])->name('teachers.index');
// Nampilin form pendaftaran guru baru
Route::get('/admin/teachers/create', [\App\Http\Controllers\TeacherController::class, 'create'])->name('teachers.create');
// Nyimpen data guru ke tabel user dan profilnya pake DB transaction
Route::post('/admin/teachers', [\App\Http\Controllers\TeacherController::class, 'store'])->name('teachers.store');
// Nampilin form edit data guru
Route::get('/admin/teachers/{id}/edit', [\App\Http\Controllers\TeacherController::class, 'edit'])->name('teachers.edit');
// Nyimpen update profil sama email guru
Route::put('/admin/teachers/{id}', [\App\Http\Controllers\TeacherController::class, 'update'])->name('teachers.update');
// Hapus akun sama profil guru sekaligus
Route::delete('/admin/teachers/{id}', [\App\Http\Controllers\TeacherController::class, 'destroy'])->name('teachers.destroy');

// ==========================================
// ROUTES DATA SISWA (STUDENTS)
// ==========================================
// Kumpulan route buat pendaftaran dan manajemen Siswa
// Nampilin daftar semua siswa (beserta info NISN dan kontak ortu)
Route::get('/admin/students', [\App\Http\Controllers\StudentController::class, 'index'])->name('students.index');
// Nampilin form pendaftaran siswa baru
Route::get('/admin/students/create', [\App\Http\Controllers\StudentController::class, 'create'])->name('students.create');
// Nyimpen biodata siswa ke tabel profil dan bikin akunnya otomatis
Route::post('/admin/students', [\App\Http\Controllers\StudentController::class, 'store'])->name('students.store');
// Nampilin form update biodata siswa
Route::get('/admin/students/{id}/edit', [\App\Http\Controllers\StudentController::class, 'edit'])->name('students.edit');
// Proses update data siswa di database
Route::put('/admin/students/{id}', [\App\Http\Controllers\StudentController::class, 'update'])->name('students.update');
// Hapus akun dan biodata siswa (biasanya kalo salah input atau udah lulus)
Route::delete('/admin/students/{id}', [\App\Http\Controllers\StudentController::class, 'destroy'])->name('students.destroy');
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

// Route Tahun Ajaran
// PENJELASAN: Route GET untuk menampilkan halaman daftar tahun ajaran
Route::get('/admin/academic-years', [AcademicYearController::class, 'index'])->name('academic-years.index');
// PENJELASAN: Route POST untuk memproses form tambah tahun ajaran baru
Route::post('/admin/academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store');
// PENJELASAN: Route GET untuk menampilkan halaman terpisah khusus Edit
Route::get('/admin/academic-years/{id}/edit', [AcademicYearController::class, 'edit'])->name('academic-years.edit');
// PENJELASAN: Route PUT untuk memproses form edit (update) tahun ajaran berdasarkan ID
Route::put('/admin/academic-years/{id}', [AcademicYearController::class, 'update'])->name('academic-years.update');
// PENJELASAN: Route DELETE untuk memproses penghapusan tahun ajaran berdasarkan ID
Route::delete('/admin/academic-years/{id}', [AcademicYearController::class, 'destroy'])->name('academic-years.destroy');

// ==========================================
// ROUTES MASTER DATA KELAS (CLASS ROOMS)
// ==========================================
// PENJELASAN: Kumpulan route untuk CRUD Data Kelas (hanya bisa diakses admin sesuai grup middleware)
Route::get('/admin/classes', [\App\Http\Controllers\ClassRoomController::class, 'index'])->name('classes.index');
Route::post('/admin/classes', [\App\Http\Controllers\ClassRoomController::class, 'store'])->name('classes.store');
Route::get('/admin/classes/{id}/edit', [\App\Http\Controllers\ClassRoomController::class, 'edit'])->name('classes.edit');
Route::put('/admin/classes/{id}', [\App\Http\Controllers\ClassRoomController::class, 'update'])->name('classes.update');
Route::delete('/admin/classes/{id}', [\App\Http\Controllers\ClassRoomController::class, 'destroy'])->name('classes.destroy');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AuthController;

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
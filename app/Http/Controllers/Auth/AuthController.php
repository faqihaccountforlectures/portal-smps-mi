<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk menangani Otentikasi / Login Manual.
 * 
 * Sesuai kebijakan keamanan portal:
 * - Login manual (Email & Password) KHUSUS diperuntukkan bagi Administrator.
 * - Siswa dan Guru diwajibkan menggunakan SSO Google (@belajar.id).
 */
class AuthController extends Controller
{
    /**
     * Memproses percobaan login manual dari formulir login administrator.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        // Validasi input email / username dan password
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Percobaan otentikasi kredensial pengguna
        if (Auth::attempt($credentials)) {
            // Verifikasi Peran: Jika akun yang berhasil diautentikasi BUKAN admin, tolak akses dan logout
            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error', 'Siswa dan Guru wajib masuk menggunakan tombol Akun Google (@belajar.id).');
            }

            // Regenerasi session ID untuk mencegah session fixation attacks
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Kembali dengan pesan kesalahan jika email/password tidak valid
        return back()->with('error', 'Email atau password admin salah. Silakan coba lagi.');
    }
}

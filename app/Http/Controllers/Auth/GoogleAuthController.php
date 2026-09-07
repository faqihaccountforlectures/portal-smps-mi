<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // Mengarahkan user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // Menangkap balikan (respon) dari Google setelah user memilih akun
    public function callback()
    {
        try {
            // Menggunakan stateless() untuk menghindari InvalidStateException (mismatch session state pada OAuth redirect)
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cari user di database berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();
            
            if ($user) {
                // Jika user ditemukan (sudah didaftarkan oleh admin)
                // Update google_id untuk berjaga-jaga jika login pertama kali pakai Google
                $user->update(['google_id' => $googleUser->getId()]);
                
                // Daftarkan sesi login
                Auth::login($user);

                // Arahkan ke dashboard
                return redirect()->intended('/dashboard');
            } else {
                // Jika akun siswa/guru tidak ada di database
                return redirect('/login')->with('error', 'Akses ditolak!
                Akun Anda belum terdaftar di sistem Portal Akademik SMP Science Mutiara Insani.');
            }

        } catch (\Exception $e) {
            // Log the full exception for debugging
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage(), ['exception' => $e]);
            
            // Jika terjadi error
            return redirect('/login')->with('error', 'Gagal login via Google: ' . $e->getMessage());
        }
    }
}
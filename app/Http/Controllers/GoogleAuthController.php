<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // Mengarahkan user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Menangkap balikan (respon) dari Google setelah user memilih akun
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

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
                    // Jika email siswa/guru tidak ada di database
                    return redirect('/login')->with('error', 'Akses ditolak!
                    Email Anda belum terdaftar di sistem Portal Akademik SMP Science Mutiara Insani.');
                }

            } catch (\Exception $e) {
                // Jika terjadi error (misal user batal memilih akun Google)
                return redirect('/login')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
    }
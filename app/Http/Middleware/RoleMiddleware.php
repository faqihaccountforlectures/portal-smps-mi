<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Menangani permintaan masuk dan memeriksa peran (role) pengguna.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Role yang diizinkan (misal: 'admin', 'guru', 'siswa')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Cek apakah role pengguna yang sedang login SAMA dengan role yang disyaratkan oleh rute
        if (Auth::user()->role !== $role) {
            // Jika tidak sama, tolak akses dengan status 403 (Forbidden)
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk membuka halaman ini.');
        }

        // Jika lolos, silakan lanjutkan ke rute tujuan
        return $next($request);
    }
}

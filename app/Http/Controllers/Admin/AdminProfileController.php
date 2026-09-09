<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * Menampilkan halaman utama Profil & Pengaturan Akun Admin (Gabungan).
     * Memuat data admin yang sedang login untuk ditampilkan pada form.
     */
    public function index()
    {
        // Mengambil instance data user/admin yang sedang aktif login
        $user = Auth::user();

        // Mengembalikan view khusus profil admin dengan membawa data user
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Memproses pembaruan data profil admin (Nama Lengkap, Email/Username, Nomor HP).
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi input data profil
        // Email / username harus unik kecuali untuk user itu sendiri
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email / username wajib diisi.',
            'email.unique'   => 'Email / username ini sudah digunakan oleh akun lain.',
        ]);

        // Simpan perubahan profil ke database
        $user->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return back()->with('success', 'Profil akun admin berhasil diperbarui.');
    }

    /**
     * Memproses pembaruan password admin.
     * Menghentikan sesi login di perangkat lain secara otomatis demi keamanan serah terima.
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi form penggantian password (current_password dibuat opsional)
        $request->validate([
            'current_password' => 'nullable|string',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Jika admin mengisi password saat ini, verifikasi kebenarannya
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini yang Anda ketikkan tidak sesuai. Silakan periksa kembali.']);
            }

            if ($request->password === $request->current_password) {
                return back()->withErrors(['password' => 'Password baru harus berbeda dan tidak boleh sama dengan password saat ini.']);
            }
        }

        // 1. Update password baru ke database (akan di-hash otomatis oleh model User $casts)
        $user->update([
            'password' => $request->password,
        ]);

        // 2. FITUR KEAMANAN UTAMA: Purge Sesi Login di Perangkat Lain!
        // Ketika password diganti (misal saat serah terima admin baru),
        // fungsi ini akan menghancurkan cookie/sesi aktif admin di laptop/HP lain secara otomatis.
        Auth::logoutOtherDevices($request->password);

        return back()->with('success', 'Password akun admin berhasil diubah! Semua sesi aktif admin di perangkat lain telah dihentikan demi keamanan.');
    }
}

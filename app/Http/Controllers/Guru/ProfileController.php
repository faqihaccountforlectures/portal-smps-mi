<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil guru.
     * Mengambil data akun yang sedang login beserta relasi tabel teacherProfile-nya.
     */
    public function index()
    {
        // Ambil data user yang sedang login beserta profil gurunya
        $user = Auth::user()->load('teacherProfile');

        // Buka halaman view profil dengan membawa data user tersebut
        return view('guru.profile.index', compact('user'));
    }

    /**
     * Memproses form penyimpanan update profil.
     * Demi keamanan, kita hanya mengizinkan guru untuk mengubah nomor HP (phone_number).
     * Data sensitif seperti NIP, Nama, atau Jabatan tidak boleh diubah dari sini.
     */
    public function update(Request $request)
    {
        // 1. Validasi data yang dikirim dari form
        // Kita pastikan nomor HP wajib diisi (required), berupa teks maksimal 20 huruf,
        // dan formatnya harus cocok dengan aturan regex (hanya boleh angka, plus, atau minus)
        $validatedData = $request->validate([
            'phone_number' => 'required|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/',
        ], [
            'phone_number.required' => 'Nomor HP tidak boleh dikosongkan.',
            'phone_number.regex' => 'Format nomor HP tidak valid (hanya boleh angka, spasi, atau tanda tambah).',
            'phone_number.max' => 'Nomor HP terlalu panjang, maksimal 20 karakter.',
        ]);

        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        // 3. Pastikan user tersebut benar-benar memiliki profil guru
        if (!$user->teacherProfile) {
            // Jika anehnya tidak ada profil, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Profil guru tidak ditemukan di database.');
        }

        // 4. Update data profil gurunya (HANYA nomor HP yang diperbarui)
        $user->teacherProfile->update([
            'phone_number' => $validatedData['phone_number']
        ]);

        // 5. Kembalikan ke halaman profil dengan pesan sukses
        return redirect()->back()->with('success', 'Data kontak profil Anda berhasil diperbarui!');
    }
}

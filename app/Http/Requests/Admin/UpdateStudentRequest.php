<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Mengatur otorisasi penggunaan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendefinisikan aturan validasi untuk pembaruan (update) data Siswa.
     */
    public function rules(): array
    {
        // Mengambil ID akun dari parameter URL
        $userId = $this->route('id');
        
        // Mengambil data siswa beserta profilnya untuk mendapatkan ID profil
        $student = User::with('studentProfile')->find($userId);
        $profileId = $student->studentProfile->id ?? null;

        return [
            // Pengecualian ID akun untuk validasi keunikan email
            'email' => 'required|email|unique:users,email,' . $userId,
            
            'full_name' => 'required|string|max:255',
            
            // Pengecualian ID profil untuk validasi keunikan NISN
            'nisn' => 'required|string|max:20|unique:student_profiles,nisn,' . $profileId,
            
            'gender' => 'required|in:laki-laki,perempuan',
            'phone_number' => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
        ];
    }

    /**
     * Menyediakan pesan kesalahan khusus (Custom Error Messages) dalam bahasa Indonesia formal.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email tersebut sudah digunakan oleh pengguna lain.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN tersebut sudah digunakan oleh siswa lain.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
        ];
    }
}

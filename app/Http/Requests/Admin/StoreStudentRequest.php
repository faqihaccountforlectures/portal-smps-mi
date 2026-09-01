<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Mengatur otorisasi penggunaan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendefinisikan aturan validasi untuk penambahan data Siswa baru.
     */
    public function rules(): array
    {
        return [
            // Alamat email unik untuk login
            'email' => 'required|email|unique:users,email',
            
            'full_name' => 'required|string|max:255',
            
            // NISN bersifat wajib dan harus belum terdaftar di tabel profil siswa
            'nisn' => 'required|string|max:20|unique:student_profiles,nisn',
            
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
            'email.unique' => 'Alamat email tersebut sudah terdaftar pada sistem.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN tersebut sudah terdaftar untuk siswa lain.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
        ];
    }
}

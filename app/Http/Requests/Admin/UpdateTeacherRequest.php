<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
{
    /**
     * Mengatur otorisasi penggunaan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendefinisikan aturan validasi untuk pembaruan (update) data Guru.
     */
    public function rules(): array
    {
        // Mengambil ID pengguna (user) yang sedang diperbarui dari parameter URL
        $userId = $this->route('id');

        return [
            // Memastikan alamat email unik, NAMUN mengecualikan alamat email pengguna ini sendiri
            // agar sistem tidak menganggap email lamanya sebagai duplikat saat proses pembaruan.
            'email' => 'required|email|unique:users,email,' . $userId,
            
            'full_name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'gender' => 'required|in:laki-laki,perempuan',
            'position' => 'required|in:guru,kepala_sekolah,wakil_kepala_sekolah',
            'phone_number' => 'nullable|string|max:20',
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
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'position.required' => 'Jabatan wajib dipilih.',
            'position.in' => 'Pilihan jabatan tidak valid.',
        ];
    }
}

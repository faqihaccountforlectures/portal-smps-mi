<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRoomRequest extends FormRequest
{
    /**
     * Setujui semua eksekusi karena role admin sudah dijaga di level router.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk proses penambahan Data Kelas baru.
     */
    public function rules(): array
    {
        return [
            // Nama kelas maksimal 255 karakter
            'name' => 'required|string|max:255',
            
            // Tingkat kelas (misal: 7, 8, 9) maksimal 50 karakter
            'grade_level' => 'required|string|max:50',
            
            // Wali kelas boleh kosong (karena mungkin belum ditentukan).
            // Tapi JIKA diisi, ID guru tersebut WAJIB ada di tabel users.
            'homeroom_teacher_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Kumpulan pesan error custom dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kelas wajib diisi (misal: VII-A).',
            'grade_level.required' => 'Tingkat kelas wajib diisi.',
            'homeroom_teacher_id.exists' => 'Wali kelas yang dipilih tidak valid atau tidak terdaftar di sistem.',
        ];
    }
}

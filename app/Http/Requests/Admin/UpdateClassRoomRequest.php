<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassRoomRequest extends FormRequest
{
    /**
     * Otorisasi diset true (role admin dijaga dari router).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk proses Edit (Update) Data Kelas.
     * Secara kebetulan, aturan update untuk kelas sama persis dengan aturan store.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Pesan error custom.
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

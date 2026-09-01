<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectRequest extends FormRequest
{
    /**
     * Otorisasi diset ke true karena keamanan sudah ditangani oleh middleware.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Daftarkan aturan validasi untuk UPDATE (edit) data mata pelajaran.
     */
    public function rules(): array
    {
        // Mengambil ID mapel yang sedang diedit dari parameter URL (contoh: /admin/subjects/{id})
        $subjectId = $this->route('id');

        return [
            // Perbedaan utama saat Update: Aturan 'unique' harus MENGECUALIKAN ID mapel ini sendiri.
            // Jika tidak, sistem akan mengira mapel ini sedang mencoba mencuri kode miliknya sendiri!
            'code' => 'required|string|max:20|unique:subjects,code,' . $subjectId,
            
            'name' => 'required|string|max:100',
            'grade_level' => 'nullable|integer',
            'kkm' => 'required|numeric|min:0|max:100',
            'category' => 'nullable|in:A,B',
        ];
    }

    /**
     * Pesan error custom dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'code.unique' => 'Kode mata pelajaran ini sudah digunakan oleh mata pelajaran lain.',
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'kkm.required' => 'Nilai KKM harus diisi.',
            'kkm.numeric' => 'Nilai KKM harus berupa angka.',
            'category.in' => 'Pilihan kategori tidak valid.',
        ];
    }
}

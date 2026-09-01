<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    /**
     * Tentukan apakah user punya izin (otorisasi) untuk menggunakan request ini.
     * Karena pengecekan role 'admin' sudah dilakukan di routes/web.php (middleware),
     * kita kembalikan nilai true saja di sini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Daftarkan aturan validasi (rules) untuk form tambah mata pelajaran.
     * Aturan ini persis sama seperti yang sebelumnya ada di dalam Controller.
     */
    public function rules(): array
    {
        return [
            // Kode mapel wajib diisi, maksimal 20 karakter, dan harus unik di tabel subjects
            'code' => 'required|string|max:20|unique:subjects,code',
            
            // Nama mapel wajib diisi, maksimal 100 karakter
            'name' => 'required|string|max:100',
            
            // Tingkat kelas (misal kelas 7, 8, 9). Opsional (boleh kosong), kalau diisi harus angka
            'grade_level' => 'nullable|integer',
            
            // KKM (Kriteria Ketuntasan Minimal). Wajib diisi, angka desimal antara 0 sampai 100
            'kkm' => 'required|numeric|min:0|max:100',
            
            // Kategori mapel. Opsional, kalau diisi cuma boleh 'A' (Wajib) atau 'B' (Muatan Lokal)
            'category' => 'nullable|in:A,B',
        ];
    }

    /**
     * (Opsional) Mengubah pesan error bawaan bahasa Inggris menjadi bahasa Indonesia 
     * agar lebih ramah dibaca oleh pengguna.
     */
    public function messages(): array
    {
        return [
            'code.unique' => 'Kode mata pelajaran ini sudah dipakai. Silakan cari kode lain.',
            'code.required' => 'Kode mata pelajaran wajib diisi dan tidak boleh kosong.',
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'kkm.required' => 'Nilai KKM harus diisi.',
            'kkm.numeric' => 'Nilai KKM harus berupa angka yang valid.',
            'category.in' => 'Kategori yang dipilih tidak valid. Hanya bisa memilih Wajib (A) atau Muatan Lokal (B).',
        ];
    }
}

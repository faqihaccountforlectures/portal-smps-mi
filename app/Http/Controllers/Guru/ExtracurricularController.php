<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Extracurricular;

class ExtracurricularController extends Controller
{
    /**
     * Menampilkan daftar ekstrakurikuler yang dibina oleh guru yang sedang login.
     */
    public function index()
    {
        // Mendapatkan ID user (guru) yang sedang login
        $teacherId = Auth::id();
        
        // Mengambil daftar ekstrakurikuler di mana guru ini ditugaskan sebagai pembina.
        // Sekaligus menghitung total siswa yang mendaftar (registrations) di masing-masing ekstrakurikuler.
        $extracurriculars = Extracurricular::where('teacher_id', $teacherId)
            ->withCount('registrations')
            ->get();
            
        return view('guru.extracurriculars.index', compact('extracurriculars'));
    }

    /**
     * Menampilkan detail spesifik dari satu ekstrakurikuler beserta daftar siswanya.
     * Hanya ekstrakurikuler yang dibina oleh guru ini yang bisa diakses.
     *
     * @param int $id ID ekstrakurikuler
     */
    public function show($id)
    {
        // Mendapatkan ID user (guru) yang sedang login
        $teacherId = Auth::id();
        
        // Mengambil detail ekstrakurikuler, dipastikan hanya milik guru yang login (where teacher_id).
        // Kita juga memuat relasi (Eager Loading) pendaftaran, data akun siswa, dan profil siswa
        // agar tidak terjadi query N+1 (performa lebih cepat).
        $extracurricular = Extracurricular::where('teacher_id', $teacherId)
            ->with(['registrations.student.studentProfile'])
            ->findOrFail($id);
            
        return view('guru.extracurriculars.show', compact('extracurricular'));
    }
}

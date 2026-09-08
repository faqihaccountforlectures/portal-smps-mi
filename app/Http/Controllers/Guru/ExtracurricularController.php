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
     * Menampilkan detail spesifik dari satu ekstrakurikuler beserta daftar siswanya (dengan paginasi 5).
     * Hanya ekstrakurikuler yang dibina oleh guru ini yang bisa diakses.
     *
     * @param int $id ID ekstrakurikuler
     */
    public function show($id)
    {
        // Mendapatkan ID user (guru) yang sedang login
        $teacherId = Auth::id();
        
        // Mengambil detail ekstrakurikuler milik guru yang login beserta total pendaftar
        $extracurricular = Extracurricular::where('teacher_id', $teacherId)
            ->withCount('registrations')
            ->findOrFail($id);

        // Mengambil daftar pendaftaran siswa dengan paginasi 5 per halaman
        $registrations = $extracurricular->registrations()
            ->with(['student.studentProfile'])
            ->paginate(5);
            
        return view('guru.extracurriculars.show', compact('extracurricular', 'registrations'));
    }
}

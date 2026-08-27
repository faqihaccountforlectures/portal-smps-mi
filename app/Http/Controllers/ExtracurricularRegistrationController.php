<?php

namespace App\Http\Controllers;

use App\Models\ExtracurricularRegistration;
use Illuminate\Http\Request;

class ExtracurricularRegistrationController extends Controller
{
    /**
     * Menampilkan daftar pendaftaran ekstrakurikuler (Hanya untuk Admin)
     */
    public function index()
    {
        // Mengambil semua data pendaftaran beserta relasi siswa (dan profilnya) serta data ekstrakurikulernya
        // Diurutkan berdasarkan tanggal daftar terbaru (created_at)
        $registrations = ExtracurricularRegistration::with(['student.studentProfile', 'extracurricular'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.extracurricular_registrations.index', compact('registrations'));
    }

    /**
     * Menyetujui (Approve) pendaftaran ekstrakurikuler
     */
    public function approve($id)
    {
        // Cari data pendaftaran berdasarkan ID
        $registration = ExtracurricularRegistration::findOrFail($id);
        
        // Ubah status menjadi approved
        $registration->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Pendaftaran siswa berhasil disetujui.');
    }

    /**
     * Menolak (Reject) pendaftaran ekstrakurikuler
     */
    public function reject($id)
    {
        // Cari data pendaftaran berdasarkan ID
        $registration = ExtracurricularRegistration::findOrFail($id);
        
        // Ubah status menjadi rejected
        $registration->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Pendaftaran siswa berhasil ditolak.');
    }
}

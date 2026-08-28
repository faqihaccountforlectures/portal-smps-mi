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

    /**
     * Memproses pendaftaran ekstrakurikuler oleh siswa.
     * Siswa memilih ekskul dari katalog, lalu datanya disimpan ke tabel registrasi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'extracurricular_id' => 'required|exists:extracurriculars,id'
        ]);

        // Mencegah pendaftaran ganda jika statusnya masih pending atau sudah approved
        $existingRegistration = ExtracurricularRegistration::where('student_id', auth()->id())
            ->where('extracurricular_id', $request->extracurricular_id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRegistration) {
            return back()->with('error', 'Anda sudah mendaftar di ekstrakurikuler ini.');
        }

        // Simpan pendaftaran baru (Otomatis statusnya pending karena default database/sistem)
        ExtracurricularRegistration::create([
            'student_id' => auth()->id(),
            'extracurricular_id' => $request->extracurricular_id,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Pendaftaran berhasil! Silakan tunggu persetujuan dari Admin.');
    }

    /**
     * Memproses pembatalan/keluar dari ekstrakurikuler oleh siswa.
     */
    public function destroy($id)
    {
        $registration = ExtracurricularRegistration::where('extracurricular_id', $id)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        $registration->delete();

        return back()->with('success', 'Kamu telah berhasil keluar dari ekstrakurikuler tersebut.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ExtracurricularRegistration;
use App\Models\Payment;
use App\Models\Extracurricular;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama admin dengan data statistik dinamis.
     */
    public function index()
    {
        // 1. Hitung total siswa aktif
        $totalSiswa = User::where('role', 'siswa')->count();

        // 2. Hitung total guru
        $totalGuru = User::where('role', 'guru')->count();

        // 3. Hitung pendaftaran ekstrakurikuler yang perlu verifikasi (pending)
        $pendingRegistrations = ExtracurricularRegistration::where('status', 'pending')->count();

        // 4. Hitung pembayaran ekstrakurikuler yang perlu verifikasi (pending)
        $pendingPayments = Payment::where('payment_status', 'pending')->count();

        // 5. Hitung total pemasukan bulan ini (berdasarkan waktu verifikasi)
        $pemasukanBulanIni = Payment::where('payment_status', 'verified')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('total_amount');

        // 6. Data untuk Chart.js (Grafik Ekskul Terfavorit)
        $extracurriculars = Extracurricular::withCount(['registrations' => function ($query) {
            $query->where('status', 'approved');
        }])->get();
        
        $chartLabels = $extracurriculars->pluck('name');
        $chartData = $extracurriculars->pluck('registrations_count');

        // 7. Ambil 5 setoran pembayaran terbaru
        $recentPayments = Payment::with(['student.studentProfile', 'extracurricular'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'pendingRegistrations',
            'pendingPayments',
            'pemasukanBulanIni',
            'chartLabels',
            'chartData',
            'recentPayments'
        ));
    }
}

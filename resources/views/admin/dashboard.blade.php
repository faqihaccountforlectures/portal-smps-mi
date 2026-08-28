<!-- Menggunakan cetakan app.blade.php -->
@extends('layouts.app')

<!-- Mengisi judul halaman -->
@section('title', 'Dashboard Utama')
@section('header', 'Dashboard Admin')

<!-- Mengisi bagian konten -->
@section('content')
    
    <!-- Kartu Sambutan -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 text-white shadow-lg shadow-blue-200 mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold mb-2">Selamat Datang di Portal! 🎉</h2>
            <p class="text-blue-100 text-lg">Anda masuk sebagai <span class="font-bold uppercase">{{ Auth::user()->role }}</span>. Jangan lupa periksa jadwal dan pengumuman terbaru hari ini.</p>
        </div>
        <div class="hidden md:block">
            <svg class="w-24 h-24 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
        </div>
    </div>

    <!-- Featured Card Pemasukan Keuangan -->
    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg shadow-emerald-200 mb-6 flex justify-between items-center relative overflow-hidden group hover:scale-[1.01] transition-transform">
        <!-- Ornamen -->
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/20 rounded-full blur-2xl"></div>
        <div class="absolute right-12 bottom-0 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
        </div>
        
        <div class="relative z-10">
            <h3 class="text-emerald-50 text-sm font-semibold mb-1 uppercase tracking-wider">Pemasukan Ekstrakurikuler Bulan Ini</h3>
            <p class="text-4xl font-bold drop-shadow-md">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Statistik Dinamis -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Siswa -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4 mb-4">
                <div class="bg-blue-50 p-3 rounded-xl text-blue-600">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-semibold">Total Siswa Aktif</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $totalSiswa }}</p>
                </div>
            </div>
            <a href="{{ route('students.index') }}" class="mt-auto text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1 group">
                Lihat Detail <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <!-- Total Guru -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4 mb-4">
                <div class="bg-indigo-50 p-3 rounded-xl text-indigo-600">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-semibold">Total Guru</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $totalGuru }}</p>
                </div>
            </div>
            <a href="{{ route('teachers.index') }}" class="mt-auto text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 group">
                Lihat Detail <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <!-- Pendaftaran Ekskul Pending -->
        <div class="bg-white p-6 rounded-2xl border {{ $pendingRegistrations > 0 ? 'border-amber-200 shadow-amber-100' : 'border-slate-100' }} shadow-sm flex flex-col hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4 mb-4">
                <div class="bg-amber-50 p-3 rounded-xl text-amber-500 relative">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @if($pendingRegistrations > 0)
                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-semibold">Pendaftaran Ekskul</p>
                    <p class="text-3xl font-bold {{ $pendingRegistrations > 0 ? 'text-amber-600' : 'text-slate-800' }}">{{ $pendingRegistrations }} <span class="text-sm font-normal text-slate-400">pending</span></p>
                </div>
            </div>
            <a href="{{ route('extracurricular-registrations.index') }}" class="mt-auto text-sm font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1 group">
                Cek Verifikasi <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <!-- Pembayaran Ekskul Pending -->
        <div class="bg-white p-6 rounded-2xl border {{ $pendingPayments > 0 ? 'border-rose-200 shadow-rose-100' : 'border-slate-100' }} shadow-sm flex flex-col hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4 mb-4">
                <div class="bg-rose-50 p-3 rounded-xl text-rose-500 relative">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @if($pendingPayments > 0)
                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-semibold">Bukti Pembayaran</p>
                    <p class="text-3xl font-bold {{ $pendingPayments > 0 ? 'text-rose-600' : 'text-slate-800' }}">{{ $pendingPayments }} <span class="text-sm font-normal text-slate-400">pending</span></p>
                </div>
            </div>
            <a href="{{ route('admin.payments.index', ['status' => 'verifikasi']) }}" class="mt-auto text-sm font-semibold text-rose-600 hover:text-rose-700 flex items-center gap-1 group">
                Cek Tagihan <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
    </div>

    <!-- Analitik dan Aktivitas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        
        <!-- Kolom Kiri: Grafik Ekskul -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
            <h3 class="text-base font-bold text-slate-800 mb-1">Grafik Anggota Ekstrakurikuler</h3>
            <p class="text-xs text-slate-500 mb-6">Perbandingan jumlah siswa aktif (yang telah disetujui) pada masing-masing ekskul.</p>
            <div class="flex-1 w-full relative min-h-[250px]">
                <canvas id="ekskulChart" class="absolute inset-0 w-full h-full"></canvas>
            </div>
        </div>

        <!-- Kolom Kanan: 5 Setoran Terakhir -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Setoran Terbaru</h3>
                    <p class="text-xs text-slate-500">5 siswa terakhir bayar</p>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="text-xs font-semibold px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">Lihat Semua</a>
            </div>
            
            <div class="space-y-3">
                @forelse($recentPayments as $payment)
                    <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-50 bg-slate-50/50 hover:bg-slate-50 hover:border-slate-100 transition-all">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 shadow-inner">
                            <span class="font-bold text-sm">{{ substr($payment->student->studentProfile->full_name ?? 'S', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $payment->student->studentProfile->full_name ?? $payment->student->email }}</p>
                            <p class="text-xs font-semibold text-emerald-600 my-0.5">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-slate-400 truncate uppercase tracking-wider">{{ $payment->extracurricular->name }} • {{ $payment->month }}</p>
                        </div>
                        <div>
                            @if($payment->payment_status === 'pending')
                                <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-md text-[10px] font-bold border border-amber-200">Pending</span>
                            @elseif($payment->payment_status === 'verified')
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-md text-[10px] font-bold border border-emerald-200">Lunas</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-md text-[10px] font-bold border border-red-200">Ditolak</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-sm text-slate-500">Belum ada setoran masuk.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('ekskulChart').getContext('2d');
            const ekskulChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Anggota',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.8)', // Tailwind blue-500
                        borderColor: 'rgba(37, 99, 235, 1)', // Tailwind blue-600
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0 // Tidak ada desimal (orang tidak mungkin 1.5)
                            },
                            grid: {
                                color: 'rgba(241, 245, 249, 1)' // slate-100
                            },
                            border: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)', // slate-900
                            padding: 12,
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 },
                            cornerRadius: 8,
                            displayColors: false
                        }
                    }
                }
            });
        });
    </script>
@endsection
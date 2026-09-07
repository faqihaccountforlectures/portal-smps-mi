{{-- Menggunakan cetakan layout utama (app.blade.php) yang memuat Sidebar dan Topbar --}}
@extends('layouts.app')

{{-- Mendefinisikan judul halaman pada tag <title> dan Header Topbar --}}
@section('title', 'Dasbor Utama')
@section('header', 'Dasbor Administrator')

{{-- Bagian Konten Utama Dasbor --}}
@section('content')
    
    <!-- FUNGSI KODE: Kartu Sambutan (Hero Section). Menampilkan sapaan dinamis berdasarkan role pengguna yang login -->
    <div class="bg-gradient-to-br from-navy-dark to-navy-base rounded-2xl p-8 text-white-off shadow-xl shadow-navy-dark/20 mb-8 flex justify-between items-center relative overflow-hidden group">
        <!-- Ornamen Dekoratif Latar Belakang -->
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-navy-light/20 rounded-full blur-3xl group-hover:bg-navy-light/30 transition-colors duration-500"></div>
        <div class="absolute right-0 bottom-0 opacity-10">
            <svg class="w-48 h-48 text-navy-light transform translate-x-8 translate-y-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
        </div>

        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-3 font-heading tracking-wide">Selamat Datang di Dasbor Portal Akademik</h2>
            <p class="text-navy-light text-lg">Anda saat ini masuk sebagai <span class="font-bold uppercase text-white-off px-2 py-0.5 bg-navy-dark/50 rounded-md border border-navy-base/50">{{ Auth::user()->role }}</span>. Berikut adalah ringkasan data dan aktivitas sistem untuk hari ini.</p>
        </div>
    </div>

    <!-- FUNGSI KODE: Kartu Ringkasan Keuangan. Menampilkan total nominal pembayaran yang telah diverifikasi (status='verified') pada bulan berjalan -->
    <div class="bg-gradient-to-r from-navy-base to-navy-dark rounded-2xl p-7 text-white shadow-lg shadow-navy-base/20 mb-8 flex justify-between items-center relative overflow-hidden group hover:-translate-y-1 hover:shadow-xl transition-all duration-300 border border-navy-light/10">
        <!-- Ornamen Keuangan -->
        <div class="absolute right-8 bottom-0 opacity-10 group-hover:opacity-20 transition-opacity duration-300">
            <svg class="w-32 h-32 text-navy-light transform translate-y-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
        </div>
        
        <div class="relative z-10">
            <h3 class="text-navy-light text-xs font-bold mb-2 uppercase tracking-widest">Total Pemasukan Ekstrakurikuler Bulan Ini</h3>
            <p class="text-4xl font-bold font-heading drop-shadow-md text-white-off">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- FUNGSI KODE: Grid Statistik Utama (4 Kartu). Menggunakan data dari controller untuk menampilkan jumlah user aktif dan antrean persetujuan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Kartu 1: Indikator Total Siswa -->
        <div class="bg-white p-5 rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-gray-muted uppercase tracking-wider">Total Siswa Aktif</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $totalSiswa }}</h3>
                <a href="{{ route('students.index') }}" class="text-xs font-bold text-navy-base hover:text-navy-dark inline-flex items-center gap-1 mt-2.5 transition-colors">
                    <span>Lihat Data Siswa</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center border border-navy-light/30 group-hover:scale-110 transition-transform shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
            </div>
        </div>
        
        <!-- Kartu 2: Indikator Total Guru -->
        <div class="bg-white p-5 rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-gray-muted uppercase tracking-wider">Total Guru Aktif</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $totalGuru }}</h3>
                <a href="{{ route('teachers.index') }}" class="text-xs font-bold text-navy-base hover:text-navy-dark inline-flex items-center gap-1 mt-2.5 transition-colors">
                    <span>Lihat Data Guru</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center border border-navy-light/30 group-hover:scale-110 transition-transform shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        <!-- Kartu 3: Indikator Pendaftaran Ekskul -->
        <div class="bg-white p-5 rounded-2xl border border-amber-200/60 shadow-sm shadow-amber-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-amber-300 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Pendaftaran Ekskul</p>
                <h3 class="text-2xl font-bold text-amber-800 font-heading mt-1">{{ $pendingRegistrations }} <span class="text-xs font-medium text-gray-muted lowercase tracking-normal">menunggu</span></h3>
                <a href="{{ route('extracurricular-registrations.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 inline-flex items-center gap-1 mt-2.5 transition-colors">
                    <span>Lihat Pendaftaran</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200 group-hover:scale-110 transition-transform shrink-0 relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                @if($pendingRegistrations > 0)
                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500 border-2 border-white"></span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Kartu 4: Indikator Verifikasi Pembayaran -->
        <div class="bg-white p-5 rounded-2xl border border-rose-200/60 shadow-sm shadow-rose-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-rose-300 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Verifikasi Pembayaran</p>
                <h3 class="text-2xl font-bold text-rose-800 font-heading mt-1">{{ $pendingPayments }} <span class="text-xs font-medium text-gray-muted lowercase tracking-normal">menunggu</span></h3>
                <a href="{{ route('admin.payments.index', ['status' => 'verifikasi']) }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 inline-flex items-center gap-1 mt-2.5 transition-colors">
                    <span>Lihat Pembayaran</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-200 group-hover:scale-110 transition-transform shrink-0 relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                @if($pendingPayments > 0)
                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border-2 border-white"></span>
                    </span>
                @endif
            </div>
        </div>
        
    </div>

    <!-- Area Konten Tambahan: Analitik Visual dan Transaksi Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        
        <!-- FUNGSI KODE: Visualisasi Data (Kiri). Menggunakan Chart.js untuk merender grafik bar dari data JSON yang di-passing dari controller -->
        <div class="lg:col-span-2 bg-white p-7 rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 flex flex-col hover:-translate-y-1 hover:shadow-md hover:border-navy-base/50 transition-all duration-300 group">
            <h3 class="text-lg font-bold text-navy-dark mb-1 font-heading">Statistik Anggota Ekstrakurikuler</h3>
            <p class="text-sm text-gray-muted mb-8">Grafik perbandingan jumlah siswa yang telah disetujui pada setiap kegiatan ekstrakurikuler.</p>
            <div class="flex-1 w-full relative min-h-[300px]">
                <canvas id="ekskulChart" class="absolute inset-0 w-full h-full"></canvas>
            </div>
        </div>

        <!-- FUNGSI KODE: Riwayat Transaksi (Kanan). Melakukan perulangan (looping) pada relasi model Payment terbaru untuk menampilkan riwayat penyetoran biaya ekstrakurikuler -->
        <div class="bg-white p-7 rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 hover:-translate-y-1 hover:shadow-md hover:border-navy-base/50 transition-all duration-300 group">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-navy-dark font-heading">Transaksi Terbaru</h3>
                    <p class="text-sm text-gray-muted">Riwayat setoran pembayaran terbaru</p>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold px-3.5 py-2 bg-white-off text-navy-base rounded-lg hover:bg-navy-light/30 transition-colors border border-navy-light/30">Lihat Semua</a>
            </div>
            
            <div class="space-y-4">
                @forelse($recentPayments as $payment)
                    <!-- Baris Item Transaksi -->
                    <div class="flex items-center gap-4 p-3 rounded-xl border border-transparent hover:bg-white-off hover:border-navy-light/30 transition-all duration-300">
                        <!-- Avatar Inisial -->
                        <div class="w-11 h-11 rounded-full bg-navy-dark text-white-off flex items-center justify-center shrink-0 shadow-inner font-heading">
                            <span class="font-bold text-base">{{ substr($payment->student->studentProfile->full_name ?? 'S', 0, 1) }}</span>
                        </div>
                        
                        <!-- Detail Transaksi -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-navy-dark truncate">{{ $payment->student->studentProfile->full_name ?? $payment->student->email }}</p>
                            <p class="text-[10px] font-bold text-gray-muted truncate uppercase tracking-widest mt-0.5">{{ $payment->extracurricular->name }} • {{ $payment->month }}</p>
                            <p class="text-sm font-bold text-navy-base mt-1">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                        </div>
                        
                        <!-- Lencana Status -->
                        <div class="shrink-0">
                            @if($payment->payment_status === 'pending')
                                <span class="bg-amber-50 text-amber-600 px-3 py-1.5 rounded-lg text-xs font-bold border border-amber-200">menunggu</span>
                            @elseif($payment->payment_status === 'verified')
                                <span class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-lg text-xs font-bold border border-emerald-200">Lunas</span>
                            @else
                                <span class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-lg text-xs font-bold border border-rose-200">Ditolak</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Penanganan Kondisi Kosong (Empty State) -->
                    <div class="text-center py-10">
                        <div class="w-14 h-14 bg-white-off text-navy-light rounded-full flex items-center justify-center mx-auto mb-4 border border-navy-light/20">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="text-sm text-gray-muted font-medium">Belum ada transaksi pembayaran yang tercatat di sistem.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
    </div>

    <!-- FUNGSI KODE: Inisialisasi Script Chart.js menggunakan CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mengambil elemen canvas berdasarkan ID
            const ctx = document.getElementById('ekskulChart').getContext('2d');
            
            // Konfigurasi visual grafik bar
            const ekskulChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    // Inject array labels (Nama Ekskul) dan data (Jumlah Pendaftar) dari PHP Backend ke Javascript
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Anggota',
                        data: {!! json_encode($chartData) !!},
                        // Menggunakan warna dari palet navy-base (#1d4ed8)
                        backgroundColor: '#1d4ed8', 
                        borderColor: '#1e3a8a',
                        borderWidth: 0,
                        borderRadius: 8,
                        barPercentage: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0, // Mencegah munculnya angka desimal pada sumbu Y (jumlah orang tidak mungkin desimal)
                                color: '#64748b',
                                font: {
                                    family: "'Nunito Sans', sans-serif",
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: 'rgba(96, 165, 250, 0.3)' // navy-light dengan opacity
                            },
                            border: { display: false }
                        },
                        x: {
                            ticks: {
                                color: '#1d4ed8',
                                font: {
                                    family: "'Nunito Sans', sans-serif",
                                    weight: 'bold'
                                }
                            },
                            grid: { display: false },
                            border: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e3a8a', // navy-dark
                            titleColor: '#ffffff', // white-pure
                            bodyColor: '#f8fafc', // white-off
                            padding: 14,
                            titleFont: { family: "'Nunito Sans', sans-serif", size: 14, weight: 'bold' },
                            bodyFont: { family: "'Nunito Sans', sans-serif", size: 13 },
                            cornerRadius: 10,
                            displayColors: false
                        }
                    }
                }
            });
        });
    </script>
@endsection


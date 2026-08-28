@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('header', 'Dashboard')

@section('content')
    <!-- KARTU SAMBUTAN PERSONAL (WELCOME BANNER) -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow-lg shadow-blue-200 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h2 class="text-3xl font-bold mb-2">Halo, {{ $user->studentProfile->full_name ?? $user->name }}! 👋</h2>
            <p class="text-blue-100 text-lg mb-4">Selamat datang kembali di Portal Akademik SMPS MI.</p>
            
            <!-- Badges Informasi Diri -->
            <div class="flex flex-wrap gap-3">
                <div class="bg-white/20 px-4 py-2 rounded-lg flex items-center gap-2 backdrop-blur-sm border border-white/10">
                    <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    <span class="font-medium text-sm">NISN: {{ $user->studentProfile->nisn ?? 'Belum Diatur' }}</span>
                </div>
                <div class="bg-white/20 px-4 py-2 rounded-lg flex items-center gap-2 backdrop-blur-sm border border-white/10">
                    <svg class="w-5 h-5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="font-medium text-sm">Kelas: {{ $className }}</span>
                </div>
            </div>
        </div>
        
        <!-- Ilustrasi SVG di sebelah kanan -->
        <div class="hidden lg:block opacity-80">
            <svg class="w-32 h-32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 14L2 9L12 4L22 9L12 14Z" fill="currentColor" fill-opacity="0.2"/>
                <path d="M22 9L12 14V22L22 17V9Z" fill="currentColor" fill-opacity="0.4"/>
                <path d="M12 14L2 9V17L12 22V14Z" fill="currentColor" fill-opacity="0.6"/>
            </svg>
        </div>
    </div>

    <!-- PENGINGAT TAGIHAN (Hanya muncul jika ada tunggakan) -->
    @if($hasUnpaidBills)
    <div class="bg-rose-50 border-l-4 border-rose-500 rounded-r-xl p-5 mb-8 flex items-start gap-4 shadow-sm relative overflow-hidden group">
        <div class="bg-rose-100 p-2.5 rounded-full text-rose-600 shrink-0 relative z-10 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div class="flex-1 relative z-10">
            <h3 class="text-rose-800 font-bold text-lg mb-1">Peringatan Tunggakan Pembayaran</h3>
            <p class="text-rose-600 text-sm mb-3">Anda memiliki tagihan iuran ekstrakurikuler yang belum dibayar atau sedang ditolak. Segera lakukan pembayaran agar tidak dikeluarkan dari keanggotaan.</p>
            <a href="{{ route('siswa.payments.index') }}" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                Bayar Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        <!-- Ornamen background -->
        <svg class="absolute right-0 bottom-0 text-rose-100 w-32 h-32 transform translate-x-1/4 translate-y-1/4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>
    </div>
    @endif

    <!-- GRID LAYOUT (Jadwal Pelajaran & Jadwal Ekskul) -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        
        <!-- KOLOM KIRI: Jadwal Pelajaran Hari Ini -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="bg-slate-50/80 border-b border-slate-100 px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-indigo-100 text-indigo-600 p-2 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Jadwal Hari Ini</h3>
                </div>
                <!-- Tanggal Hari Ini -->
                <span class="text-sm font-semibold text-slate-500 bg-white px-3 py-1 rounded-full border border-slate-200">
                    {{ $todayIndo }}, {{ date('d M Y') }}
                </span>
            </div>

            <div class="p-6 flex-1">
                @if($todaySchedules->count() > 0)
                    <div class="space-y-6">
                        @foreach($todaySchedules as $jadwal)
                        <div class="relative pl-8 before:absolute before:left-2 before:top-2 before:w-3 before:h-3 before:bg-indigo-100 before:border-2 before:border-indigo-500 before:rounded-full after:absolute after:left-3 after:top-5 after:bottom-[-24px] last:after:hidden after:w-[2px] after:bg-slate-100">
                            
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-slate-800">{{ $jadwal->teacherAssignment->subject->name ?? 'Mapel' }}</h4>
                                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md tracking-wider">
                                        {{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-500">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-500 shrink-0">
                                        {{ substr($jadwal->teacherAssignment->teacher->name ?? 'G', 0, 1) }}
                                    </div>
                                    <p class="font-medium truncate">{{ $jadwal->teacherAssignment->teacher->teacherProfile->full_name ?? ($jadwal->teacherAssignment->teacher->name ?? 'Guru Belum Ditentukan') }}</p>
                                </div>
                            </div>
                            
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Tampilan Kosong Jika Libur/Tidak ada kelas -->
                    <div class="h-full flex flex-col items-center justify-center text-center py-10">
                        <img src="https://illustrations.popsy.co/amber/surreal-hourglass.svg" alt="Libur" class="w-32 h-32 mb-4 opacity-75">
                        <h4 class="font-bold text-slate-700 text-lg mb-1">Wah, hari ini libur! 🎉</h4>
                        <p class="text-slate-500 text-sm">Tidak ada jadwal kelas untuk Anda di hari {{ $todayIndo }}. Selamat beristirahat!</p>
                    </div>
                @endif
            </div>
            <!-- Footer dengan link menuju jadwal lengkap -->
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 text-center">
                <a href="{{ route('siswa.timetables.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center justify-center gap-1">
                    Lihat Jadwal Seminggu Penuh
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- KOLOM KANAN: Ekskul Saya -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="bg-slate-50/80 border-b border-slate-100 px-6 py-5 flex items-center gap-3">
                <div class="bg-emerald-100 text-emerald-600 p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1h-4l-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">Ekstrakurikuler Aktif</h3>
            </div>
            
            <div class="p-6 flex-1">
                @if($myExtracurriculars->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-4">
                        @foreach($myExtracurriculars as $reg)
                        <div class="group border border-slate-200 rounded-xl p-4 flex items-center gap-4 hover:border-emerald-300 hover:shadow-md transition-all bg-white relative overflow-hidden">
                            <!-- Garis dekoratif -->
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></div>
                            
                            <!-- Foto Ekskul -->
                            <div class="w-14 h-14 rounded-lg bg-slate-100 shrink-0 overflow-hidden border border-slate-200">
                                @if($reg->extracurricular->image)
                                    <img src="{{ Storage::url($reg->extracurricular->image) }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L28 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Info Ekskul -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-slate-800 text-base truncate">{{ $reg->extracurricular->name }}</h4>
                                <p class="text-sm text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="truncate">{{ $reg->extracurricular->schedule }}</span>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Tampilan Kosong Jika Belum Ikut Ekskul -->
                    <div class="h-full flex flex-col items-center justify-center text-center py-10">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100 shadow-sm">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h4 class="font-bold text-slate-700 text-lg mb-1">Belum Ada Ekskul</h4>
                        <p class="text-slate-500 text-sm mb-4 px-4">Anda belum bergabung dengan ekstrakurikuler apa pun saat ini.</p>
                        <a href="{{ route('siswa.extracurriculars.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm">
                            Telusuri Katalog Ekskul
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
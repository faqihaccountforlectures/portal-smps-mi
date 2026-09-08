@extends('layouts.app')

@section('title', 'Dasbor Utama')
@section('header', 'Dasbor Guru')

@section('content')
    <!-- FUNGSI KODE: Kartu Sambutan (Hero Banner) bertema Navy -->
    <div class="bg-gradient-to-br from-navy-dark to-navy-base rounded-2xl p-8 text-white-off shadow-xl shadow-navy-dark/20 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden group">
        <!-- Ornamen Dekoratif Latar Belakang -->
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-navy-light/20 rounded-full blur-3xl group-hover:bg-navy-light/30 transition-colors duration-500"></div>
        <div class="absolute right-0 bottom-0 opacity-10">
            <svg class="w-48 h-48 text-navy-light transform translate-x-8 translate-y-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
        </div>

        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-2 font-heading tracking-wide">Selamat Datang, {{ $user->teacherProfile->full_name ?? 'Bapak/Ibu Guru' }}! 🎉</h2>
            <p class="text-navy-light text-base mb-4">Semoga hari ini penuh inspirasi. Berikut adalah ringkasan aktivitas mengajar Anda hari ini.</p>
            
            <!-- Badges Informasi Guru -->
            <div class="flex flex-wrap gap-3">
                <div class="bg-navy-dark/50 border border-navy-light/30 px-3.5 py-1.5 rounded-lg flex items-center gap-2 backdrop-blur-sm shadow-sm">
                    <svg class="w-4 h-4 text-navy-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    <span class="font-bold text-xs text-white-off tracking-wide font-mono">NIP: {{ $user->teacherProfile->nip ?? 'Belum Diatur' }}</span>
                </div>
                <div class="bg-navy-dark/50 border border-navy-light/30 px-3.5 py-1.5 rounded-lg flex items-center gap-2 backdrop-blur-sm shadow-sm">
                    <svg class="w-4 h-4 text-navy-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span class="font-bold text-xs text-white-off tracking-wide">Jabatan: {{ $user->teacherProfile->position ?? 'Guru' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FUNGSI KODE: Grid Statistik Utama (4 Kartu) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        
        <!-- Kartu 1: Total Kelas -->
        <div class="bg-white p-5 rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/40 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-gray-muted uppercase tracking-wider">Total Kelas Diajar</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $totalClasses }} <span class="text-xs font-normal text-gray-muted lowercase">kelas</span></h3>
                <a href="{{ route('guru.classes.index') }}" class="text-xs font-bold text-navy-base hover:text-navy-dark inline-flex items-center gap-1 mt-2.5 transition-colors">
                    <span>Lihat Kelas Saya</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center border border-navy-light/30 group-hover:scale-110 transition-transform shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>

        <!-- Kartu 2: Total Siswa -->
        <div class="bg-white p-5 rounded-2xl border border-emerald-200/80 shadow-sm shadow-emerald-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-emerald-400 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Total Siswa Diajar</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $totalStudents }} <span class="text-xs font-normal text-gray-muted lowercase">siswa</span></h3>
                <span class="text-[11px] font-medium text-emerald-600 inline-flex items-center gap-1 mt-2.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Tahun Ajaran Aktif
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200 group-hover:scale-110 transition-transform shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <!-- Kartu 3: Ekstrakurikuler Binaan -->
        <div class="bg-white p-5 rounded-2xl border border-amber-200/80 shadow-sm shadow-amber-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-amber-400 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Ekstrakurikuler Binaan</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $totalExtracurriculars }} <span class="text-xs font-normal text-gray-muted lowercase">ekstrakurikuler</span></h3>
                <a href="{{ route('guru.extracurriculars.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 inline-flex items-center gap-1 mt-2.5 transition-colors">
                    <span>Lihat Ekstrakurikuler</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200 group-hover:scale-110 transition-transform shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </div>
        </div>

        <!-- Kartu 4: Jadwal Mengajar Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-purple-200/80 shadow-sm shadow-purple-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-purple-400 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-purple-700 uppercase tracking-wider">Jadwal Hari Ini</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $todaySchedules->count() }} <span class="text-xs font-normal text-gray-muted lowercase">sesi</span></h3>
                <a href="{{ route('guru.schedules.index') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700 inline-flex items-center gap-1 mt-2.5 transition-colors">
                    <span>Lihat Jadwal Pekan Ini</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-200 group-hover:scale-110 transition-transform shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        
    </div>

    <!-- FUNGSI KODE: Grid Layout Utama (Jadwal Hari Ini & Akses Cepat Guru) -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI (2/3): Jadwal Mengajar Hari Ini -->
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-dark text-base font-heading tracking-wide">Jadwal Mengajar Hari Ini</h3>
                        <p class="text-[11px] text-gray-muted mt-0.5 font-bold tracking-wide">Daftar kelas yang wajib Anda ajar khusus untuk hari ini.</p>
                    </div>
                </div>
                <!-- Badge Hari Ini -->
                <span class="text-xs font-bold text-navy-base bg-navy-light/20 px-3.5 py-1.5 rounded-lg border border-navy-light/40 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-navy-base animate-pulse"></span>
                    {{ $todayName }}, {{ date('d M Y') }}
                </span>
            </div>

            <div class="p-7 flex-1">
                @if($todaySchedules->count() > 0)
                    <div class="space-y-5">
                        @foreach($todaySchedules as $schedule)
                        <div class="relative pl-7 before:absolute before:left-2 before:top-2 before:w-3 before:h-3 before:bg-white before:border-2 before:border-navy-base before:rounded-full after:absolute after:left-3.5 after:top-5 after:bottom-[-20px] last:after:hidden after:w-[2px] after:bg-navy-light/30">
                            
                            <div class="bg-white-off/50 border border-navy-light/30 rounded-xl p-5 hover:border-navy-base/40 hover:bg-white transition-all duration-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <span class="text-[10px] font-bold font-mono text-navy-base bg-navy-light/10 px-2.5 py-1 rounded-md border border-navy-light/30 tracking-wider uppercase">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </span>
                                        @php
                                            $start = \Carbon\Carbon::parse($schedule->start_time);
                                            $end = \Carbon\Carbon::parse($schedule->end_time);
                                            $diff = $start->diffInMinutes($end);
                                        @endphp
                                        <span class="text-[10px] font-bold text-gray-muted bg-white px-2 py-0.5 rounded border border-navy-light/20">
                                            {{ $diff }} Menit
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-navy-dark text-lg font-heading">{{ $schedule->teacherAssignment->subject->name ?? 'Mata Pelajaran Tidak Diketahui' }}</h4>
                                </div>

                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-navy-light/20 text-navy-dark border border-navy-light/40">
                                        <svg class="w-4 h-4 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        Kelas {{ $schedule->teacherAssignment->classRoom->name ?? '-' }}
                                    </span>
                                </div>
                            </div>
                            
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Tampilan Kosong Jika Tidak Ada Jadwal -->
                    <div class="h-full flex flex-col items-center justify-center text-center py-12">
                        <div class="w-16 h-16 bg-white-off text-navy-light rounded-full flex items-center justify-center mb-4 border border-navy-light/20 shadow-inner">
                            <svg class="w-8 h-8 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h4 class="font-bold text-navy-dark text-lg font-heading mb-1">Tidak Ada Jadwal Mengajar 🎉</h4>
                        <p class="text-xs text-gray-muted font-medium max-w-sm leading-relaxed">Anda tidak memiliki jam mengajar pada hari {{ $todayName }}. Manfaatkan waktu ini untuk persiapan materi atau evaluasi pembelajaran.</p>
                    </div>
                @endif
            </div>

            <!-- Footer Link -->
            <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 text-center">
                <a href="{{ route('guru.schedules.index') }}" class="text-xs font-bold text-navy-base hover:text-navy-dark transition-colors inline-flex items-center gap-1.5">
                    <span>Lihat Jadwal Mengajar Pekan Ini</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- KOLOM KANAN (1/3): Akses Cepat & Informasi Akun -->
        <div class="space-y-6">
            
            <!-- Kartu Akses Pintar (Quick Links) -->
            <div class="bg-white rounded-2xl p-6 border border-navy-light/30 shadow-sm shadow-navy-base/5 hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <h3 class="font-bold text-navy-dark text-base font-heading mb-4 pb-3 border-b border-navy-light/20 flex items-center gap-2">
                    <svg class="w-5 h-5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Akses Pintar Menu
                </h3>
                
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('guru.profile.index') }}" class="p-3.5 rounded-xl border border-navy-light/20 bg-white-off/50 hover:bg-navy-light/10 hover:border-navy-light/50 transition-all text-center group">
                        <div class="w-9 h-9 mx-auto rounded-lg bg-navy-light/10 text-navy-base flex items-center justify-center mb-2 group-hover:scale-110 transition-transform border border-navy-light/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-navy-dark block">Profil Saya</span>
                    </a>
                    
                    <a href="{{ route('guru.classes.index') }}" class="p-3.5 rounded-xl border border-navy-light/20 bg-white-off/50 hover:bg-navy-light/10 hover:border-navy-light/50 transition-all text-center group">
                        <div class="w-9 h-9 mx-auto rounded-lg bg-navy-light/10 text-navy-base flex items-center justify-center mb-2 group-hover:scale-110 transition-transform border border-navy-light/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-navy-dark block">Kelas Saya</span>
                    </a>

                    <a href="{{ route('guru.schedules.index') }}" class="p-3.5 rounded-xl border border-navy-light/20 bg-white-off/50 hover:bg-navy-light/10 hover:border-navy-light/50 transition-all text-center group">
                        <div class="w-9 h-9 mx-auto rounded-lg bg-navy-light/10 text-navy-base flex items-center justify-center mb-2 group-hover:scale-110 transition-transform border border-navy-light/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-navy-dark block">Jadwal Mengajar</span>
                    </a>

                    <a href="{{ route('guru.extracurriculars.index') }}" class="p-3.5 rounded-xl border border-navy-light/20 bg-white-off/50 hover:bg-navy-light/10 hover:border-navy-light/50 transition-all text-center group">
                        <div class="w-9 h-9 mx-auto rounded-lg bg-navy-light/10 text-navy-base flex items-center justify-center mb-2 group-hover:scale-110 transition-transform border border-navy-light/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-navy-dark block">Ekstrakurikuler Binaan</span>
                    </a>
                </div>
            </div>

            <!-- Kartu Kontak Pengajar -->
            <div class="bg-gradient-to-br from-white to-white-off rounded-2xl p-6 border border-navy-light/30 shadow-sm shadow-navy-base/5 relative overflow-hidden group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-navy-dark text-white-off flex items-center justify-center font-bold font-heading text-lg shrink-0 shadow-inner">
                        {{ substr($user->teacherProfile->full_name ?? ($user->name ?? 'G'), 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="font-bold text-navy-dark text-sm truncate font-heading">{{ $user->teacherProfile->full_name ?? $user->name }}</h4>
                        <p class="text-[11px] text-gray-muted truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-2.5 pt-3 border-t border-navy-light/20 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-muted font-medium">Nomor Telepon:</span>
                        <span class="font-bold text-navy-dark font-mono">{{ $user->teacherProfile->phone_number ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-muted font-medium">Jenis Kelamin:</span>
                        @php
                            $g = strtolower($user->teacherProfile->gender ?? '');
                            $gLabel = '-';
                            if ($g === 'l' || $g === 'laki-laki') $gLabel = 'Laki-laki';
                            elseif ($g === 'p' || $g === 'perempuan') $gLabel = 'Perempuan';
                        @endphp
                        <span class="font-bold text-navy-dark">{{ $gLabel }}</span>
                    </div>
                </div>

                <a href="{{ route('guru.profile.index') }}" class="mt-4 w-full block text-center py-2 px-4 bg-navy-light/10 hover:bg-navy-light/20 text-navy-base font-bold text-xs rounded-xl border border-navy-light/30 transition-colors">
                    Perbarui Data Kontak
                </a>
            </div>

        </div>

    </div>
@endsection
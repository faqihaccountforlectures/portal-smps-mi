@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')
@section('header', 'Jadwal Pelajaran')

@section('content')
    <!-- FUNGSI KODE: Hero Banner Informasi Kelas bertema Navy -->
    <div class="bg-gradient-to-br from-navy-dark to-navy-base rounded-2xl p-7 text-white-off shadow-xl shadow-navy-dark/20 mb-8 relative overflow-hidden group">
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-navy-light/20 rounded-full blur-3xl group-hover:bg-navy-light/30 transition-colors duration-500"></div>
        
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-1.5 font-heading tracking-wide">Jadwal Pelajaran Mingguan</h2>
            @if($hasClass)
                <p class="text-navy-light text-sm">Menampilkan jadwal pelajaran lengkap untuk kelas <span class="font-bold text-white-off px-2.5 py-1 bg-navy-dark/60 rounded-md border border-navy-light/30 font-mono">{{ $className }}</span>.</p>
            @else
                <p class="text-navy-light text-sm">Status pembagian kelas Anda belum ditentukan oleh pihak sekolah.</p>
            @endif
        </div>
    </div>

    <!-- FUNGSI KODE: Penanganan Kondisi Belum Punya Kelas VS Grid Jadwal Pelajaran -->
    @if(!$hasClass)
        <div class="bg-white border-l-4 border-amber-500 rounded-2xl p-6 flex items-start gap-4 shadow-sm border border-navy-light/20">
            <div class="bg-amber-50 border border-amber-200 p-3 rounded-xl text-amber-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-navy-dark text-base font-heading mb-1">Informasi Penempatan Kelas</h3>
                <p class="text-xs text-gray-muted leading-relaxed font-medium">{{ $message }}</p>
            </div>
        </div>
    @else
        @php
            $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        @endphp

        <!-- Layout Grid Jadwal (Senin - Jumat) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($hariUrut as $hari)
                <!-- Card Per Hari -->
                <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col h-full group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                    
                    <!-- Header Hari -->
                    <div class="bg-white-off/50 border-b border-navy-light/30 px-6 py-4 flex items-center justify-between">
                        <h3 class="font-bold text-navy-dark text-base font-heading tracking-wide flex items-center gap-2">
                            <svg class="w-4 h-4 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $hari }}
                        </h3>
                    </div>

                    <!-- List Mata Pelajaran -->
                    <div class="p-6 flex-1 space-y-5">
                        @if(isset($groupedTimetables[$hari]) && $groupedTimetables[$hari]->count() > 0)
                            @foreach($groupedTimetables[$hari] as $jadwal)
                                <div class="relative pl-6 before:absolute before:left-0 before:top-1.5 before:w-3 before:h-3 before:bg-white before:border-2 before:border-navy-base before:rounded-full after:absolute after:left-1.5 after:top-4 after:bottom-[-20px] last:after:hidden after:w-[2px] after:bg-navy-light/30">
                                    
                                    <!-- Waktu -->
                                    <p class="text-[10px] font-bold font-mono text-navy-base bg-navy-light/10 px-2 py-0.5 rounded-md border border-navy-light/30 tracking-wider inline-block uppercase mb-1">
                                        {{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}
                                    </p>
                                    
                                    <!-- Nama Mata Pelajaran -->
                                    <p class="text-sm font-bold text-navy-dark font-heading block mt-0.5">{{ $jadwal->teacherAssignment->subject->name ?? 'Mata Pelajaran' }}</p>
                                    
                                    <!-- Nama Guru -->
                                    <p class="text-xs text-gray-muted font-medium mt-1 flex items-center gap-1.5 truncate">
                                        <svg class="w-3.5 h-3.5 text-navy-light shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <span class="truncate">{{ $jadwal->teacherAssignment->teacher->teacherProfile->full_name ?? ($jadwal->teacherAssignment->teacher->name ?? 'Guru Belum Ditentukan') }}</span>
                                    </p>
                                </div>
                            @endforeach
                        @else
                            <!-- Tampilan Kosong Jika Hari Libur -->
                            <div class="h-full flex flex-col items-center justify-center text-center py-8 text-gray-muted">
                                <div class="w-10 h-10 bg-white-off text-navy-light rounded-full flex items-center justify-center mb-2 border border-navy-light/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                </div>
                                <p class="text-[11px] font-bold text-gray-muted uppercase tracking-wider">Tidak Ada Kelas</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

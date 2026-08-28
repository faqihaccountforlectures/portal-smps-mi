@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')
@section('header', 'Jadwal Pelajaran')

@section('content')
    <!-- Kartu Informasi Kelas -->
    <!-- Desain warna biru gradient yang modern dan kekinian (cocok buat anak sekolah) -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg mb-8">
        <h2 class="text-2xl font-bold mb-1">Jadwal Pelajaran Mingguan</h2>
        <!-- Cek apakah siswa punya kelas (variabel ini dilempar dari controller) -->
        @if($hasClass)
            <p class="text-blue-100">Menampilkan jadwal pelajaran untuk kelas <span class="font-bold text-white px-2 py-0.5 bg-white/20 rounded">{{ $className }}</span>.</p>
        @else
            <p class="text-blue-100">Status kelas Anda belum ditentukan.</p>
        @endif
    </div>

    <!-- Logika Tampilan: Punya kelas VS Belum punya kelas -->
    @if(!$hasClass)
        <!-- Tampilan Error/Peringatan Jika Belum Punya Kelas -->
        <div class="bg-amber-50 border border-amber-200 text-amber-800 p-6 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="bg-amber-100 p-3 rounded-full shrink-0">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-1">Peringatan</h3>
                <p>{{ $message }}</p>
            </div>
        </div>
    @else
        <!-- Grid Jadwal Per Hari -->
        <!-- Kita definisikan array urutan hari agar Card yang muncul rapi berurutan (Senin - Jumat) -->
        @php
            $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        @endphp

        <!-- Layout Grid: 1 kolom di HP, 2 di Tablet, 3 di Desktop Besar -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($hariUrut as $hari)
                <!-- Card (Kotak) untuk masing-masing hari -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                    
                    <!-- Header Hari (Bagian atas kotak yang berisi tulisan "Senin", "Selasa", dll) -->
                    <div class="bg-slate-50/80 border-b border-slate-100 px-5 py-4">
                        <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $hari }}
                        </h3>
                    </div>

                    <!-- Isi Jadwal (Mata Pelajaran) -->
                    <div class="p-5 flex-1 space-y-5">
                        <!-- Cek apakah di hari tersebut ada jadwal untuk kelas ini -->
                        @if(isset($groupedTimetables[$hari]) && $groupedTimetables[$hari]->count() > 0)
                            <!-- Melakukan perulangan untuk setiap mata pelajaran di hari tersebut -->
                            @foreach($groupedTimetables[$hari] as $jadwal)
                                <!-- Desain list berupa Timeline (garis vertikal dengan titik biru) biar kelihatan estetis -->
                                <div class="relative pl-6 before:absolute before:left-0 before:top-1.5 before:w-3 before:h-3 before:bg-white before:border-2 before:border-blue-500 before:rounded-full after:absolute after:left-1.5 after:top-4 after:bottom-[-20px] last:after:hidden after:w-[2px] after:bg-slate-100">
                                    
                                    <!-- Waktu (Jam Pelajaran) -->
                                    <p class="text-[11px] font-bold text-blue-600 tracking-wider mb-1 uppercase">
                                        {{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}
                                    </p>
                                    
                                    <!-- Nama Mata Pelajaran -->
                                    <p class="text-sm font-bold text-slate-800">{{ $jadwal->teacherAssignment->subject->name ?? 'Mata Pelajaran' }}</p>
                                    
                                    <!-- Nama Guru -->
                                    <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1.5 font-medium">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ $jadwal->teacherAssignment->teacher->teacherProfile->full_name ?? ($jadwal->teacherAssignment->teacher->name ?? 'Guru Belum Ditentukan') }}
                                    </p>
                                </div>
                            @endforeach
                        @else
                            <!-- Tampilan kosong jika di hari tersebut libur / tidak ada mapel -->
                            <div class="h-full flex flex-col items-center justify-center text-center py-6">
                                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                </div>
                                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Tidak ada kelas</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

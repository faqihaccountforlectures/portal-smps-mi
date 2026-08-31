@extends('layouts.app')

@section('title', 'Jadwal Mengajar Saya')
@section('header', 'Jadwal Mengajar Saya')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Pesan Peringatan Jika Tidak Ada Tahun Ajaran Aktif -->
    @if(isset($error))
    <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-800 p-4 rounded-r-xl shadow-sm flex items-start gap-3">
        <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <h4 class="font-bold">Perhatian</h4>
            <p class="text-sm mt-1">{{ $error }}</p>
        </div>
    </div>
    @else
    
    <!-- Informasi Tahun Ajaran Aktif -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 px-6 py-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="bg-indigo-100 p-2.5 rounded-lg text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Jadwal Mingguan</h2>
                <p class="text-sm text-slate-500">Jadwal kegiatan belajar mengajar Anda untuk satu pekan.</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Tahun Ajaran Aktif</p>
            <span class="inline-block bg-indigo-50 text-indigo-700 font-bold px-3 py-1 rounded-md border border-indigo-200">
                {{ $activeAcademicYear->year_name }} - {{ ucfirst($activeAcademicYear->semester) }}
            </span>
        </div>
    </div>

    <!-- Layout Grid: 1 kolom di HP, 2 di Tablet, 3 di Desktop Besar -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            // Label bahasa Indonesia untuk mempercantik nama hari
            $hariLabels = [
                'senin' => 'Senin',
                'selasa' => 'Selasa',
                'rabu' => 'Rabu',
                'kamis' => 'Kamis',
                'jumat' => 'Jumat'
            ];
        @endphp

        @foreach($hariLabels as $dayKey => $hari)
        <!-- Card (Kotak) untuk masing-masing hari -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
            
            <!-- Header Hari (Bagian atas kotak yang berisi tulisan "Senin", "Selasa", dll) -->
            <div class="bg-slate-50/80 border-b border-slate-100 px-5 py-4">
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $hari }}
                </h3>
            </div>
            
            <!-- Isi Jadwal (Mata Pelajaran) -->
            <div class="p-5 flex-1 space-y-5">
                @if(isset($schedulesByDay[$dayKey]) && count($schedulesByDay[$dayKey]) > 0)
                    <!-- Melakukan perulangan untuk setiap jadwal di hari tersebut -->
                    @foreach($schedulesByDay[$dayKey] as $jadwal)
                        <!-- Desain list berupa Timeline (garis vertikal dengan titik biru) -->
                        <div class="relative pl-6 before:absolute before:left-0 before:top-1.5 before:w-3 before:h-3 before:bg-white before:border-2 before:border-indigo-500 before:rounded-full after:absolute after:left-1.5 after:top-4 after:bottom-[-20px] last:after:hidden after:w-[2px] after:bg-slate-100">
                            
                            <!-- Waktu (Jam Pelajaran) -->
                            <p class="text-[11px] font-bold text-indigo-600 tracking-wider mb-1 uppercase">
                                {{ $jadwal['start_time'] }} - {{ $jadwal['end_time'] }}
                            </p>
                            
                            <!-- Nama Mata Pelajaran -->
                            <p class="text-sm font-bold text-slate-800">{{ $jadwal['subject_name'] }}</p>
                            
                            <!-- Ruang Kelas & Tingkat -->
                            <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1.5 font-medium">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Kelas {{ $jadwal['grade_level'] }} ({{ $jadwal['class_name'] }})
                            </p>
                        </div>
                    @endforeach
                @else
                    <!-- Tampilan kosong jika di hari tersebut libur / tidak ada jadwal -->
                    <div class="h-full flex flex-col items-center justify-center text-center py-6">
                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Tidak ada jadwal</p>
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @endif
</div>
@endsection

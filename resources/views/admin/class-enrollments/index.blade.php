@extends('layouts.app')

@section('title', 'Pembagian Kelas')
@section('header', 'Pembagian Kelas')

@section('content')
    
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl relative flex items-center gap-3" role="alert">
        <div class="bg-emerald-100 p-1.5 rounded-lg">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-center gap-3" role="alert">
        <div class="bg-red-100 p-1.5 rounded-lg">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        <div>
            <span class="block sm:inline font-medium">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- TAHUN AJARAN INFO -->
    {{-- FUNGSI KODE: Kartu Informasi Tahun Ajaran dengan desain Navy solid yang ringkas (compact) --}}
    <div class="mb-6 bg-navy-dark rounded-2xl p-5 text-white-off shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4 relative overflow-hidden group">
        <!-- Dekorasi Background Tipis -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white-off/5 opacity-50 blur-3xl pointer-events-none transition-transform duration-700 group-hover:scale-110"></div>
        
        <div class="relative z-10">
            <h2 class="text-lg font-bold font-heading mb-0.5 tracking-wide">Tahun Ajaran: <span class="text-white-off">{{ strtoupper($activeYear->semester) }} {{ $activeYear->year_name }}</span></h2>
            <p class="text-white-off/70 text-xs">Pilih kelas di bawah ini untuk mengelola pendaftaran siswa.</p>
        </div>
        <div class="relative z-10 flex items-center gap-4">
            <div class="bg-white-off/10 px-4 py-2 rounded-xl backdrop-blur-sm border border-white-off/10 text-center shadow-inner">
                <span class="block text-2xl font-black text-white-off">{{ $classes->sum('enrollments_count') }}</span>
                <span class="text-[9px] font-bold text-white-off/70 uppercase tracking-widest mt-0.5 block">Total Siswa</span>
            </div>
            <div>
                <a href="{{ route('class-enrollments.graduate') }}" class="bg-white-off text-navy-dark hover:bg-gray-muted hover:text-white-off px-4 py-2.5 rounded-xl font-bold shadow-sm transition-all duration-300 flex items-center gap-2 whitespace-nowrap border border-transparent hover:border-white-off/30 text-sm active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    <span>Kelulusan Kelas 9</span>
                </a>
            </div>
        </div>
    </div>

    <!-- DAFTAR KELAS -->
    {{-- FUNGSI KODE: Menampilkan daftar kelas dalam tabel yang elegan dengan palet warna Navy --}}
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-navy-light/10 p-2 rounded-lg text-navy-base border border-navy-light/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h2 class="font-bold text-navy-dark font-heading tracking-wide text-base">Daftar Pembagian Kelas</h2>
            </div>
            <span class="bg-navy-light/10 text-navy-dark text-xs font-bold px-3 py-1.5 rounded-lg border border-navy-light/30 tracking-widest uppercase">{{ $classes->count() }} Kelas</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                        <th class="px-7 py-4 font-bold">Kelas</th>
                        <th class="px-7 py-4 font-bold">Wali Kelas</th>
                        <th class="px-7 py-4 font-bold text-center">Jumlah Siswa</th>
                        <th class="px-7 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white-off text-navy-base">
                    @forelse($classes as $classRoom)
                    <tr class="hover:bg-white-off/50 transition-colors group">
                        <td class="px-7 py-4">
                            <div class="flex items-center gap-4">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-navy-light/10 text-navy-base border border-navy-light/30 font-bold font-mono text-base shadow-sm group-hover:scale-105 transition-transform">
                                    {{ $classRoom->grade_level }}
                                </span>
                                <div>
                                    <span class="font-bold text-navy-dark block text-base font-heading">{{ $classRoom->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-7 py-4 font-medium text-gray-muted">
                            @if($classRoom->homeroomTeacher)
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-navy-light/20 text-navy-base border border-navy-light/40 flex items-center justify-center text-[11px] font-bold shadow-sm">
                                        {{ substr($classRoom->homeroomTeacher->teacherProfile->full_name ?? 'G', 0, 1) }}
                                    </div>
                                    <span>{{ $classRoom->homeroomTeacher->teacherProfile->full_name ?? $classRoom->homeroomTeacher->email }}</span>
                                </div>
                            @else
                                <span class="text-gray-muted italic text-[11px] bg-white-off/50 px-2.5 py-1 rounded-md border border-navy-light/30">Belum diatur</span>
                            @endif
                        </td>
                        <td class="px-7 py-4 text-center">
                            <span class="inline-block px-3.5 py-1.5 rounded-lg text-xs {{ $classRoom->enrollments_count > 0 ? 'bg-navy-base text-white-off font-bold shadow-sm' : 'bg-white-off border border-navy-light/40 text-gray-muted font-semibold' }}">
                                {{ $classRoom->enrollments_count }} Siswa
                            </span>
                        </td>
                        <td class="px-7 py-4 text-right">
                            <a href="{{ route('class-enrollments.show', $classRoom->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-navy-light/40 text-navy-dark text-xs font-bold rounded-xl hover:bg-navy-light/10 hover:text-navy-base transition-all shadow-sm active:scale-95 group-hover:border-navy-light/80">
                                <svg class="w-4 h-4 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Kelola Siswa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-7 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-white-off rounded-full flex items-center justify-center text-gray-muted mb-4 border border-navy-light/40">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <p class="text-base font-bold text-navy-dark font-heading mb-1">Belum ada data kelas</p>
                                <p class="text-xs mt-1.5 text-center text-gray-muted mb-5">Silakan tambahkan data kelas terlebih dahulu di menu Data Kelas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Kelas')
@section('header', 'Daftar Siswa - ' . $classRoom->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Tombol Kembali -->
    <a href="{{ route('guru.classes.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-navy-base hover:text-navy-dark transition-colors group">
        <div class="p-2 bg-white rounded-xl border border-navy-light/30 shadow-sm group-hover:border-navy-base/50 group-hover:scale-105 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </div>
        <span>Kembali ke Daftar Kelas</span>
    </a>

    <!-- Informasi Kelas Utama -->
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden relative group hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div class="h-2.5 bg-gradient-to-r from-navy-dark via-navy-base to-navy-light"></div>
        <div class="p-7 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h2 class="text-3xl font-bold text-navy-dark font-heading">{{ $classRoom->name }}</h2>
                    @if($isHomeroomTeacher)
                    <span class="bg-navy-light/20 text-navy-dark text-xs font-bold px-3 py-1 rounded-lg border border-navy-light/40 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-navy-base" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        Wali Kelas
                    </span>
                    @endif
                </div>
                <p class="text-xs text-gray-muted font-medium flex items-center gap-2">
                    <span>Tingkat Kelas:</span>
                    <span class="font-bold text-navy-dark bg-white-off px-2.5 py-0.5 rounded border border-navy-light/20">{{ $classRoom->grade_level }}</span>
                </p>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-white-off px-5 py-3.5 rounded-xl border border-navy-light/30 text-center min-w-[130px]">
                    <p class="text-[10px] font-bold text-gray-muted uppercase tracking-wider mb-0.5">Total Siswa</p>
                    <p class="text-2xl font-bold text-navy-dark font-heading">{{ $classRoom->enrollments->count() }} <span class="text-xs font-normal text-gray-muted lowercase">Orang</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Siswa -->
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div class="bg-white-off/50 border-b border-navy-light/20 px-7 py-4 flex items-center gap-3">
            <div class="bg-navy-light/10 p-2 rounded-lg text-navy-base border border-navy-light/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="font-bold text-navy-dark text-base font-heading">Daftar Siswa Terdaftar di Kelas</h3>
        </div>

        @if($classRoom->enrollments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-navy-light/20 text-gray-muted text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-4 px-7 w-16">No</th>
                        <th class="py-4 px-7">NISN</th>
                        <th class="py-4 px-7">Nama Lengkap Siswa</th>
                        <th class="py-4 px-7">Jenis Kelamin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-light/10 text-sm">
                    @foreach($classRoom->enrollments as $index => $enrollment)
                    <tr class="hover:bg-white-off/80 transition-colors">
                        <td class="py-4 px-7 font-bold text-gray-muted text-xs">{{ $index + 1 }}</td>
                        <td class="py-4 px-7 font-bold text-navy-dark font-mono text-xs">
                            <span class="bg-white-off px-2.5 py-1 rounded border border-navy-light/20">
                                {{ $enrollment->student->studentProfile->nisn ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-7">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-navy-dark text-white-off flex items-center justify-center font-bold text-xs shrink-0 shadow-inner font-heading">
                                    {{ substr($enrollment->student->studentProfile->full_name ?? ($enrollment->student->name ?? 'S'), 0, 1) }}
                                </div>
                                <span class="font-bold text-navy-dark font-heading">
                                    {{ $enrollment->student->studentProfile->full_name ?? ($enrollment->student->name ?? 'Nama Belum Diisi') }}
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-7">
                            @php
                                $gender = strtolower($enrollment->student->studentProfile->gender ?? '');
                            @endphp
                            
                            @if($gender === 'laki-laki' || $gender === 'l')
                                <span class="inline-flex items-center gap-1.5 text-navy-base bg-navy-light/10 px-3 py-1 rounded-lg border border-navy-light/30 font-bold text-xs">
                                    Laki-laki
                                </span>
                            @elseif($gender === 'perempuan' || $gender === 'p')
                                <span class="inline-flex items-center gap-1.5 text-rose-600 bg-rose-50 px-3 py-1 rounded-lg border border-rose-200 font-bold text-xs">
                                    Perempuan
                                </span>
                            @else
                                <span class="text-gray-muted text-xs font-semibold">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <!-- Tampilan Kosong -->
        <div class="p-12 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 bg-white-off text-navy-light rounded-full flex items-center justify-center mb-3 border border-navy-light/20">
                <svg class="w-8 h-8 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h4 class="font-bold text-navy-dark text-base font-heading mb-1">Belum Ada Siswa</h4>
            <p class="text-xs text-gray-muted max-w-xs">Admin sekolah belum memasukkan data siswa ke dalam kelas ini.</p>
        </div>
        @endif
    </div>

</div>
@endsection

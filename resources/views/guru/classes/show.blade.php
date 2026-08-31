@extends('layouts.app')

@section('title', 'Detail Kelas')
@section('header', 'Daftar Siswa - ' . $classRoom->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Tombol Kembali -->
    <a href="{{ route('guru.classes.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Kelas
    </a>

    <!-- Informasi Kelas Utama -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
        <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
        <div class="p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-3xl font-bold text-slate-800">{{ $classRoom->name }}</h2>
                    <!-- Badge Jika Guru ini adalah Wali Kelasnya -->
                    @if($isHomeroomTeacher)
                    <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-md border border-indigo-200">Wali Kelas</span>
                    @endif
                </div>
                <p class="text-slate-500">Tingkat Kelas: <span class="font-semibold text-slate-700">{{ $classRoom->grade_level }}</span></p>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-slate-50 px-4 py-3 rounded-xl border border-slate-100 text-center">
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Total Siswa</p>
                    <p class="text-xl font-bold text-slate-700">{{ $classRoom->enrollments->count() }} <span class="text-sm font-normal text-slate-500">Orang</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Siswa -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <h3 class="font-bold text-slate-700">Daftar Siswa Terdaftar</h3>
        </div>

        @if($classRoom->enrollments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider w-16">No</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">NISN</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">L/P</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($classRoom->enrollments as $index => $enrollment)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-4 px-6 text-sm font-medium text-slate-500">{{ $index + 1 }}</td>
                        <!-- Mengambil data NISN dari profil siswa jika ada -->
                        <td class="py-4 px-6 text-sm font-medium text-slate-700 font-mono">
                            {{ $enrollment->student->studentProfile->nisn ?? '-' }}
                        </td>
                        <td class="py-4 px-6 text-sm font-bold text-slate-800">
                            {{ $enrollment->student->studentProfile->full_name ?? 'Belum ada nama' }}
                        </td>
                        <!-- Mengambil data Jenis Kelamin dari profil siswa -->
                        <td class="py-4 px-6 text-sm text-slate-600">
                            @php
                                $gender = strtolower($enrollment->student->studentProfile->gender ?? '');
                            @endphp
                            
                            @if($gender === 'laki-laki' || $gender === 'l')
                                <span class="inline-flex items-center gap-1 text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded border border-blue-100 font-medium text-xs">
                                    Laki-laki
                                </span>
                            @elseif($gender === 'perempuan' || $gender === 'p')
                                <span class="inline-flex items-center gap-1 text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded border border-rose-100 font-medium text-xs">
                                    Perempuan
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <!-- Jika belum ada siswa yang dimasukkan ke kelas ini -->
        <div class="p-12 flex flex-col items-center justify-center text-center text-slate-500">
            <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <p class="font-medium text-slate-600 text-lg">Belum Ada Siswa</p>
            <p class="text-sm mt-1">Admin belum memasukkan satupun siswa ke dalam kelas ini.</p>
        </div>
        @endif
    </div>

</div>
@endsection

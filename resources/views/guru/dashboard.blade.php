@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('header', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    <!-- Kartu Sambutan (Welcome Card) -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl p-8 text-white shadow-lg shadow-blue-200/50 flex justify-between items-center relative overflow-hidden">
        <!-- Dekorasi Latar Belakang -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-white opacity-10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-24 right-12 w-48 h-48 bg-white opacity-10 rounded-full blur-xl"></div>
        
        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-2">Selamat Datang, {{ $user->teacherProfile->full_name ?? 'Bapak/Ibu Guru' }}! 🎉</h2>
            <p class="text-blue-100 text-lg">Semoga hari ini penuh inspirasi. Berikut adalah ringkasan aktivitas mengajar Anda hari ini.</p>
        </div>
        
        <div class="hidden md:block relative z-10">
            <svg class="w-32 h-32 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
        </div>
    </div>

    <!-- Tiga Kotak Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Kotak Total Kelas -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 hover:shadow-md transition">
            <div class="bg-indigo-50 p-4 rounded-xl text-indigo-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Kelas yang Diajar</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalClasses }} <span class="text-sm font-medium text-gray-400">Kelas</span></p>
            </div>
        </div>
        
        <!-- Kotak Total Siswa -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 hover:shadow-md transition">
            <div class="bg-emerald-50 p-4 rounded-xl text-emerald-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Siswa yang Diajar</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalStudents }} <span class="text-sm font-medium text-gray-400">Siswa</span></p>
            </div>
        </div>

        <!-- Kotak Total Ekstrakurikuler -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 hover:shadow-md transition">
            <div class="bg-amber-50 p-4 rounded-xl text-amber-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Ekstrakurikuler Binaan</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalExtracurriculars }} <span class="text-sm font-medium text-gray-400">Ekskul</span></p>
            </div>
        </div>
    </div>

    <!-- Area Jadwal Mengajar Hari Ini -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Jadwal Mengajar Hari Ini</h3>
                <p class="text-sm text-slate-500">Daftar jadwal pelajaran Anda khusus untuk hari <strong>{{ $todayName }}</strong>.</p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        @if($todaySchedules->isEmpty())
            <!-- Tampilan jika tidak ada jadwal hari ini -->
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h4 class="text-lg font-bold text-slate-800">Hore! Tidak Ada Jadwal</h4>
                <p class="text-slate-500 mt-1 max-w-md mx-auto">Anda tidak memiliki jadwal mengajar pada hari ini. Anda bisa bersantai sejenak atau menyiapkan materi untuk pertemuan berikutnya.</p>
            </div>
        @else
            <!-- Tabel Jadwal Hari Ini -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 text-slate-500 text-sm">
                        <tr>
                            <th class="py-4 px-6 font-semibold w-1/4">Jam Pelajaran</th>
                            <th class="py-4 px-6 font-semibold w-1/3">Mata Pelajaran</th>
                            <th class="py-4 px-6 font-semibold w-1/4">Kelas</th>
                            <th class="py-4 px-6 font-semibold text-right">Durasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @foreach($todaySchedules as $schedule)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                    <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-slate-800">{{ $schedule->teacherAssignment->subject->name ?? 'Mata Pelajaran Tidak Diketahui' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    Kelas {{ $schedule->teacherAssignment->classRoom->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right text-slate-500">
                                @php
                                    $start = \Carbon\Carbon::parse($schedule->start_time);
                                    $end = \Carbon\Carbon::parse($schedule->end_time);
                                    $diff = $start->diffInMinutes($end);
                                @endphp
                                {{ $diff }} Menit
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
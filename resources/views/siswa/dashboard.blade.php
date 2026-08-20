<!-- Menggunakan cetakan app.blade.php -->
@extends('layouts.app')

<!-- Mengisi judul halaman -->
@section('title', 'Dashboard Utama')
@section('header', 'Dashboard')

<!-- Mengisi bagian konten -->
@section('content')
    
    <!-- Kartu Sambutan -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 text-white shadow-lg shadow-blue-200 mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold mb-2">Selamat Datang di Portal! 🎉</h2>
            <p class="text-blue-100 text-lg">Anda masuk sebagai <span class="font-bold uppercase">{{ Auth::user()->role }}</span>. Jangan lupa periksa jadwal dan pengumuman terbaru hari ini.</p>
        </div>
        <div class="hidden md:block">
            <svg class="w-24 h-24 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Kotak 1 -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-indigo-100 p-3 rounded-lg text-indigo-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Siswa Aktif</p>
                <p class="text-2xl font-bold text-slate-800">450</p>
            </div>
        </div>
        
        <!-- Kotak 2 -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-green-100 p-3 rounded-lg text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Kehadiran Hari Ini</p>
                <p class="text-2xl font-bold text-slate-800">98%</p>
            </div>
        </div>

        <!-- Kotak 3 -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-amber-100 p-3 rounded-lg text-amber-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Agenda Terdekat</p>
                <p class="text-lg font-bold text-slate-800">Ujian Tengah Semester</p>
            </div>
        </div>
    </div>

@endsection
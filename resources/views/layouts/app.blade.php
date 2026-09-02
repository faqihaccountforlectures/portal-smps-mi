<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Akademik') - SMP Science Mutiara Insani</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-catskill-white text-blue-zodiac antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar (Kiri) -->
        <aside class="w-64 bg-blue-zodiac border-r border-bismark/30 flex flex-col shadow-2xl z-20">
            <!-- Logo area -->
            <div class="h-20 flex items-center px-6 border-b border-bismark/30 shrink-0">
                <div class="bg-botticelli/10 text-botticelli p-2.5 rounded-xl mr-3 border border-botticelli/20 shadow-lg shadow-botticelli/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                </div>
                <div>
                    <h2 class="text-[15px] font-bold text-catskill-white tracking-wide leading-tight font-heading">Portal Akademik</h2>
                    <p class="text-[10px] text-gull-gray font-semibold uppercase tracking-widest mt-0.5">SMP Science Mutiara Insani</p>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="flex-1 px-4 py-3 space-y-0.5 overflow-y-auto hide-scrollbar">
                <a href="/dashboard" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->is('dashboard') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->is('dashboard') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                
                <p class="px-4 pt-3 pb-1 text-[10px] font-bold text-bismark uppercase tracking-widest">Menu Utama</p>

                <!-- Menu Tahun Ajaran (Hanya muncul jika yang login adalah admin) -->
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('academic-years.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('academic-years.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('academic-years.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tahun Ajaran
                </a>
                
                <a href="{{ route('classes.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('classes.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('classes.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Kelas
                </a>

                <a href="{{ route('subjects.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('subjects.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('subjects.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Mata Pelajaran
                </a>

                <a href="{{ route('teacher-assignments.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('teacher-assignments.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('teacher-assignments.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Penugasan Guru
                </a>

                <a href="{{ route('lesson-schedules.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('lesson-schedules.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('lesson-schedules.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jadwal Pelajaran
                </a>

                <a href="{{ route('class-enrollments.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('class-enrollments.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('class-enrollments.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Pembagian Kelas
                </a>

                <p class="px-4 pt-3 pb-1 text-[10px] font-bold text-bismark uppercase tracking-widest">Pengguna</p>

                <!-- Menu Data Guru -->
                <a href="{{ route('teachers.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('teachers.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('teachers.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Data Guru
                </a>
                
                <!-- Menu Data Siswa -->
                <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('students.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('students.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                    Data Siswa
                </a>
                <p class="px-4 pt-3 pb-1 text-[10px] font-bold text-bismark uppercase tracking-widest">Keuangan & Ekskul</p>

                <!-- Navigasi Menu Ekstrakurikuler -->
                <a href="{{ route('extracurriculars.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('extracurriculars.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('extracurriculars.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Data Ekstrakurikuler
                </a>

                <!-- Navigasi Menu Pendaftaran Ekstrakurikuler -->
                <a href="{{ route('extracurricular-registrations.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('extracurricular-registrations.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('extracurricular-registrations.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Pendaftaran Ekstrakurikuler
                </a>

                <!-- Navigasi Menu Verifikasi Pembayaran -->
                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('admin.payments.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.payments.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Verifikasi Pembayaran
                </a>
                @elseif(Auth::user()->role === 'guru')
                <!-- MENU KHUSUS GURU -->
                <a href="{{ route('guru.profile.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('guru.profile.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('guru.profile.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profil Saya
                </a>
                <a href="{{ route('guru.classes.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('guru.classes.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('guru.classes.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Kelas Saya
                </a>
                
                <a href="{{ route('guru.schedules.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('guru.schedules.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('guru.schedules.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jadwal Mengajar
                </a>

                <a href="{{ route('guru.extracurriculars.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('guru.extracurriculars.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('guru.extracurriculars.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Ekstrakurikuler Binaan
                </a>

                @elseif(Auth::user()->role === 'siswa')
                <!-- Menu Khusus Siswa -->
                <a href="{{ route('siswa.profile.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('siswa.profile.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('siswa.profile.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profil Saya
                </a>

                <a href="{{ route('siswa.timetables.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('siswa.timetables.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('siswa.timetables.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jadwal Pelajaran
                </a>

                <a href="{{ route('siswa.extracurriculars.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('siswa.extracurriculars.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('siswa.extracurriculars.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Katalog Ekstrakurikuler
                </a>
                
                <a href="{{ route('siswa.payments.index') }}" class="flex items-center gap-3 px-4 py-1.5 rounded-lg text-sm transition-all {{ request()->routeIs('siswa.payments.*') ? 'bg-bismark/40 text-catskill-white font-bold border-l-4 border-botticelli shadow-inner' : 'text-gull-gray hover:bg-bismark/20 hover:text-catskill-white font-medium group' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('siswa.payments.*') ? 'text-botticelli' : 'text-bismark group-hover:text-botticelli transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Pembayaran
                </a>
                @endif
            </nav>
        </aside>


        <!-- Area Kanan (Topbar & Konten) -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Topbar (Atas) -->
            <header class="h-20 bg-catskill-white border-b border-catskill-white/20 flex items-center justify-between px-8 z-10 shadow-sm shadow-bismark/5">
                <h1 class="text-xl font-bold text-blue-zodiac font-heading">@yield('header', 'Dashboard')</h1>
                
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-700">{{ Auth::user()->email }}</p>
                        <p class="text-xs text-blue-600 font-bold uppercase">{{ Auth::user()->role }}</p>
                    </div>
                    <!-- Tombol Logout -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Keluar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Area Konten Dinamis -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-catskill-white/50 p-8">
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>

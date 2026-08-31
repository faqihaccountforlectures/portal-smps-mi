<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Akademik') - SMPS MI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar (Kiri) -->
        <aside class="w-64 bg-white border-r border-gray-100 flex flex-col shadow-sm">
            <!-- Logo area -->
            <div class="h-20 flex items-center px-6 border-b border-gray-50">
                <div class="bg-blue-900 text-white p-2 rounded-lg mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-blue-900 leading-tight">Portal Akademik</h2>
                    <p class="text-xs text-gray-500">SMPS MI</p>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 bg-blue-50 text-blue-700 rounded-lg font-medium text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                
                <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Utama</p>

                <!-- Menu Tahun Ajaran (Hanya muncul jika yang login adalah admin) -->
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('academic-years.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tahun Ajaran
                </a>
                
                <a href="{{ route('classes.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Data Kelas
                </a>

                <a href="{{ route('subjects.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Mata Pelajaran
                </a>

                <a href="{{ route('teacher-assignments.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Penugasan Guru
                </a>

                <a href="{{ route('lesson-schedules.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jadwal Pelajaran
                </a>

                <a href="{{ route('class-enrollments.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Pembagian Kelas
                </a>

                <!-- Navigasi Menu Ekstrakurikuler -->
                <a href="{{ route('extracurriculars.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Ekstrakurikuler
                </a>

                <!-- Navigasi Menu Pendaftaran Ekstrakurikuler -->
                <a href="{{ route('extracurricular-registrations.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Pendaftaran Ekstrakurikuler
                </a>

                <!-- Navigasi Menu Verifikasi Pembayaran -->
                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Verifikasi Pembayaran
                </a>
                
                <!-- Menu Data Guru (Baru diganti namanya) -->
                <a href="{{ route('teachers.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Data Guru
                </a>
                
                <!-- Menu Data Siswa -->
                <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                    Data Siswa
                </a>
                @elseif(Auth::user()->role === 'guru')
                <!-- MENU KHUSUS GURU -->
                <a href="{{ route('guru.classes.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('guru.classes.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('guru.classes.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Kelas Saya
                </a>
                
                <a href="{{ route('guru.schedules.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('guru.schedules.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('guru.schedules.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Jadwal Mengajar
                </a>

                @elseif(Auth::user()->role === 'siswa')
                <!-- Menu Khusus Siswa -->
                <a href="{{ route('siswa.profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profil Saya
                </a>

                <a href="{{ route('siswa.timetables.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Jadwal Pelajaran
                </a>

                <a href="{{ route('siswa.extracurriculars.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Katalog Ekstrakurikuler
                </a>
                
                <a href="{{ route('siswa.payments.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-medium text-sm transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Riwayat Pembayaran
                </a>
                @endif
            </nav>
        </aside>

        <!-- Area Kanan (Topbar & Konten) -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Topbar (Atas) -->
            <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8">
                <h1 class="text-xl font-semibold text-slate-800">@yield('header', 'Dashboard')</h1>
                
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
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>
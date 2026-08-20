<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMP Mutiara Insani - Beranda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-white">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo & Nama Sekolah -->
                <div class="flex items-center gap-3">
                    <div class="bg-blue-900 text-white p-2.5 rounded-full shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-bold text-xl text-blue-900 leading-tight">SMP Science Mutiara Insani</h1>
                        <p class="text-xs text-gray-500 font-medium">Terakreditasi A</p>
                    </div>
                </div>

                <!-- Menu Tengah -->
                <div class="hidden md:flex space-x-8">
                    <a href="#" class="text-blue-700 font-semibold border-b-2 border-blue-700 pb-1">Beranda</a>
                    <a href="#" class="text-gray-500 hover:text-blue-700 font-medium transition">Profil</a>
                    <a href="#" class="text-gray-500 hover:text-blue-700 font-medium transition">Fasilitas</a>
                    <a href="#" class="text-gray-500 hover:text-blue-700 font-medium transition">Berita</a>
                </div>

                <!-- Tombol Login (Arah ke Portal) -->
                <div>
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-full font-semibold transition shadow-lg shadow-blue-200 flex items-center gap-2">
                        Portal Akademik
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-slate-50 overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-blue-100 opacity-50 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-blue-200 opacity-40 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-32 text-center">
            <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-4 py-1.5 rounded-full mb-6 inline-block">
                Tahun Ajaran 2026/2027
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 mb-6 leading-tight">
                Membentuk Generasi Cerdas, <br> <span class="text-blue-600">Berakhlak Mulia & Berprestasi</span>
            </h1>
            <p class="text-lg text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                Selamat datang di website resmi SMP Science Mutiara Insani. Kami berkomitmen memberikan pendidikan terbaik dengan mengintegrasikan teknologi terkini dalam proses belajar mengajar.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-full font-semibold text-lg transition shadow-xl shadow-blue-200 flex items-center gap-2">
                    Masuk Portal Siswa & Guru
                </a>
                <a href="#" class="bg-white hover:bg-gray-50 text-slate-700 border border-gray-200 px-8 py-3.5 rounded-full font-semibold text-lg transition shadow-sm">
                    Jelajahi Sekolah
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-10 text-center">
        <p class="text-slate-400 text-sm">© 2026 SMP Science Mutiara Insani Purwakarta. All rights reserved.</p>
    </footer>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Akademik SMPS MI</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] w-full max-w-md text-center border border-gray-100">
        
        <!-- Logo Icon (Topi Toga) -->
        <div class="mx-auto bg-[#1e3a8a] text-white w-12 h-12 rounded-full flex items-center justify-center mb-4 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
            </svg>
        </div>

        <!-- Teks Judul -->
        <h1 class="text-xl font-bold text-[#0f172a] mb-0.5">Portal Akademik</h1>
        <p class="text-gray-500 text-xs font-medium mb-5">SMP Mutiara Insani</p>

        <!-- Pesan Error (Muncul kalau ada email yang belum terdaftar coba login) -->
        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm mb-6 border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Login Manual -->
        <form method="POST" action="{{ route('login.post') }}" class="mb-4 text-left">
            @csrf
            <div class="mb-3">
                <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email SSO / Sekolah</label>
                <input type="email" name="email" id="email" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" placeholder="nama@smpsmi.com" required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" placeholder="••••••••" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-all duration-200 shadow-md shadow-blue-200 text-sm">
                Masuk
            </button>
        </form>

        <div class="relative flex py-1 items-center mb-4">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="flex-shrink-0 mx-3 text-[10px] font-medium text-gray-400">ATAU MASUK DENGAN</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <!-- Tombol Login Google -->
        <a href="{{ route('google.redirect') }}" class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 rounded-lg p-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:shadow-sm transition-all duration-200 mb-5">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Sign in with Google
        </a>

        <!-- Footer (Garis & Ikon Gembok) -->
        <div class="relative flex py-3 items-center">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="flex-shrink-0 mx-3 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>
        
        <p class="text-[11px] text-gray-400 leading-relaxed mt-1 px-2">
            Hanya untuk akses siswa, guru, dan staf terdaftar.
        </p>
    </div>

</body>
</html>
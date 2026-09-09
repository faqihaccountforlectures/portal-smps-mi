<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Akademik SMPS MI</title>
    <!-- Vite Assets (CSS & JS) untuk Custom Scrollbar & Styling -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Tailwind CSS CDN Fallback -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <!-- Container Kartu Login Utama -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] w-full max-w-md text-center border border-gray-100 transition-all">
        
        <!-- Logo Sekolah -->
        <div class="mx-auto w-16 h-16 mb-3 flex items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo SMP Science Mutiara Insani" class="w-full h-full object-contain drop-shadow-sm">
        </div>

        <!-- Teks Judul Portal -->
        <h1 class="text-xl font-bold text-[#0f172a] mb-0.5">Portal Akademik</h1>
        <p class="text-gray-500 text-xs font-semibold mb-6">SMP Science Mutiara Insani</p>

        <!-- Pesan Error Session (Jika ada error login atau akses ditolak) -->
        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-3.5 rounded-xl text-xs font-semibold mb-6 border border-red-100 flex items-center gap-2.5 text-left">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- TAB NAVIGASI METODE LOGIN (PISAHKAN SISWA/GURU DAN ADMIN) -->
        <div class="grid grid-cols-2 p-1 bg-slate-100 rounded-xl mb-6 text-xs font-bold">
            <!-- Tombol Tab 1: Siswa & Guru -->
            <button type="button" id="tab-btn-user" onclick="switchLoginTab('user')" class="py-2.5 rounded-lg transition-all shadow-sm bg-white text-blue-600 font-bold">
                🎓 Siswa & Guru
            </button>
            <!-- Tombol Tab 2: Admin -->
            <button type="button" id="tab-btn-admin" onclick="switchLoginTab('admin')" class="py-2.5 rounded-lg transition-all text-gray-500 font-medium hover:text-gray-900">
                🔐 Administrator
            </button>
        </div>

        <!-- ========================================================= -->
        <!-- JALUR 1: LOGIN SISWA & GURU (MENGGUNAKAN GOOGLE BELAJAR.ID) -->
        <!-- ========================================================= -->
        <div id="panel-user" class="space-y-4">
            <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-100 text-left">
                <p class="text-xs font-bold text-blue-950 mb-1 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Akses Siswa & Guru
                </p>
                <p class="text-[11px] text-blue-800 leading-relaxed font-medium">
                    Seluruh siswa dan guru wajib masuk menggunakan **Akun Google belajar resmi dari pemerintah** (<code class="bg-blue-100 px-1 py-0.5 rounded text-blue-900 font-semibold">@belajar.id</code> atau email terdaftar).
                </p>
            </div>

            <!-- Tombol Login Google Resmi -->
            <a href="{{ route('google.redirect') }}" class="w-full flex items-center justify-center gap-3 bg-white border border-gray-300 rounded-xl p-3 text-sm font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-400 hover:shadow-md active:scale-95 transition-all duration-200 group">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Masuk dengan Google</span>
            </a>
        </div>

        <!-- ========================================================= -->
        <!-- JALUR 2: LOGIN ADMINISTRATOR (EMAIL ADMIN & PASSWORD) -->
        <!-- ========================================================= -->
        <div id="panel-admin" class="hidden space-y-4">
            <div class="p-3.5 rounded-xl bg-slate-100 border border-slate-200 text-left mb-4">
                <p class="text-xs font-bold text-slate-800 mb-0.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Akses Staf Administrator
                </p>
                <p class="text-[11px] text-slate-600 leading-relaxed font-medium">
                    Masukkan username admin dan password resmi Anda untuk mengelola portal.
                </p>
            </div>

            <!-- Form Login Manual khusus Admin -->
            <form method="POST" action="{{ route('login.post') }}" class="text-left space-y-3.5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 mb-1">Username Administrator</label>
                    <input type="text" name="email" id="email" value="{{ old('email') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-xs font-medium" placeholder="masukkan username admin" required>
                </div>
                
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="admin_password" class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-xs font-medium" placeholder="••••••••" required>
                        <button type="button" onclick="toggleAdminPassword()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none" title="Lihat Password">
                            <svg id="eye-icon-admin" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition-all duration-200 shadow-md shadow-blue-200 text-xs">
                    Masuk Administrator
                </button>
            </form>
        </div>

        <!-- Footer Footer Informasi -->
        <div class="relative flex py-3 items-center mt-6">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="flex-shrink-0 mx-3 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>
        
        <p class="text-[11px] text-gray-400 leading-relaxed font-medium">
            Portal resmi SMP Science Mutiara Insani.
        </p>
    </div>

    <!-- Script JS untuk Alih Tab & Toggle Password -->
    <script>
        function switchLoginTab(type) {
            const btnUser = document.getElementById('tab-btn-user');
            const btnAdmin = document.getElementById('tab-btn-admin');
            const panelUser = document.getElementById('panel-user');
            const panelAdmin = document.getElementById('panel-admin');

            if (type === 'admin') {
                // Aktifkan Tab Admin
                btnAdmin.className = "py-2.5 rounded-lg transition-all shadow-sm bg-white text-blue-600 font-bold";
                btnUser.className = "py-2.5 rounded-lg transition-all text-gray-500 font-medium hover:text-gray-900";
                panelAdmin.classList.remove('hidden');
                panelUser.classList.add('hidden');
            } else {
                // Aktifkan Tab Siswa & Guru
                btnUser.className = "py-2.5 rounded-lg transition-all shadow-sm bg-white text-blue-600 font-bold";
                btnAdmin.className = "py-2.5 rounded-lg transition-all text-gray-500 font-medium hover:text-gray-900";
                panelUser.classList.remove('hidden');
                panelAdmin.classList.add('hidden');
            }
        }

        function toggleAdminPassword() {
            const input = document.getElementById('admin_password');
            const icon = document.getElementById('eye-icon-admin');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.038 10.038 0 014.122-.963c4.478 0 8.268 2.943 9.543 7-1.025 1.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"></path>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        // Buka tab admin otomatis jika ada error login manual
        @if(session('error') || $errors->any())
            switchLoginTab('admin');
        @endif
    </script>

</body>
</html>
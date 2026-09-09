@extends('layouts.app')

@section('title', 'Profil & Pengaturan Akun Admin')
@section('header', 'Profil & Pengaturan Akun Admin')

@section('content')
    <!-- Alert Pesan Sukses jika berhasil update profil / password -->
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 px-5 py-4 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-emerald-500 p-2 rounded-lg text-white shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Alert Pesan Error umum / error validasi -->
    @if($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 px-5 py-4 rounded-xl mb-6 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="bg-rose-500 p-2 rounded-lg text-white shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="text-sm font-bold tracking-wide">Terjadi kesalahan input, mohon periksa kembali:</span>
            </div>
            <ul class="list-disc list-inside text-xs font-semibold text-rose-700 pl-11 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Grid Utama: Membagi Halaman Menjadi 2 Kartu (Profil & Keamanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- ========================================================= -->
        <!-- KARTU 1: FORM INFORMASI PROFIL ADMIN -->
        <!-- ========================================================= -->
        <div class="lg:col-span-6 bg-white rounded-2xl border border-navy-light/30 shadow-sm overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <!-- Header Kartu Profil -->
            <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Data Profil Admin</h2>
                        <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-semibold">Kelola identitas utama penanggung jawab akun administrator.</p>
                    </div>
                </div>
                <span class="bg-navy-dark text-white-off px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">Role: {{ strtoupper($user->role) }}</span>
            </div>

            <!-- Body Kartu Form Profil -->
            <div class="p-7 flex-1 flex flex-col justify-between">
                <!-- Banner Avatar & Ringkasan Akun -->
                <div class="flex items-center gap-4 p-4 rounded-xl bg-white-off/50 border border-navy-light/20 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-navy-base text-white-off font-bold text-xl flex items-center justify-center border border-navy-light/40 shadow-sm shrink-0">
                        {{ strtoupper(substr($user->name ?? $user->email, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-navy-dark font-heading">{{ $user->name ?? 'Administrator Utama' }}</h3>
                        <p class="text-xs text-gray-muted font-medium mt-0.5">{{ $user->email }}</p>
                        <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider mt-1 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Akun Aktif
                        </p>
                    </div>
                </div>

                <!-- Form Perubahan Data Profil Admin -->
                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Input 1: Nama Lengkap Admin -->
                    <div>
                        <label class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-1.5">Nama Lengkap Admin <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="Contoh: Ahmad Hidayat, S.Kom." class="w-full bg-white border border-navy-light/40 text-navy-dark text-xs font-semibold rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all shadow-sm" required>
                        <p class="text-[10px] text-gray-muted mt-1">Nama ini akan tercantum saat admin melakukan verifikasi atau aksi sistem.</p>
                    </div>

                    <!-- Input 2: Email / Username Login Admin -->
                    <div>
                        <label class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-1.5">Email / Username Login <span class="text-rose-500">*</span></label>
                        <input type="text" name="email" value="{{ old('email', $user->email) }}" placeholder="admin@smpsmi.com atau adminsmpsmi" class="w-full bg-white border border-navy-light/40 text-navy-dark text-xs font-semibold rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all shadow-sm" required>
                        <p class="text-[10px] text-gray-muted mt-1">Gunakan email atau username ini untuk masuk / login ke dalam portal akademik.</p>
                    </div>

                    <!-- Input 3: Nomor Telepon / WhatsApp Admin -->
                    <div>
                        <label class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-1.5">Nomor HP / WhatsApp Admin</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}" placeholder="Contoh: 081234567890" class="w-full bg-white border border-navy-light/40 text-navy-dark text-xs font-semibold rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all shadow-sm">
                        <p class="text-[10px] text-gray-muted mt-1">Kontak resmi admin yang dapat dihubungi untuk urusan operasional.</p>
                    </div>

                    <!-- Tombol Simpan Perubahan Profil -->
                    <div class="pt-3 border-t border-navy-light/20 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-navy-dark text-white-off font-bold text-xs rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md active:scale-95 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- KARTU 2: FORM KEAMANAN & GANTI PASSWORD AKUN ADMIN -->
        <!-- ========================================================= -->
        <div class="lg:col-span-6 bg-white rounded-2xl border border-navy-light/30 shadow-sm overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <!-- Header Kartu Keamanan -->
            <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-amber-50 p-2.5 rounded-xl text-amber-700 border border-amber-200/60 shadow-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Keamanan & Password</h2>
                        <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-semibold">Atur password baru saat terjadi pergantian admin atau *handover*.</p>
                    </div>
                </div>
            </div>

            <!-- Body Kartu Form Keamanan -->
            <div class="p-7 flex-1 flex flex-col justify-between">
                <!-- Info Peringatan Keamanan Handover -->
                <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-200 text-amber-900 text-xs leading-relaxed mb-6 flex gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <span class="font-bold block text-amber-900 mb-0.5">Catatan Keamanan Serah Terima (Handover):</span>
                        Mengubah password di halaman ini secara otomatis akan **menghentikan seluruh sesi aktif login admin di laptop/HP lain**. Hal ini menjamin admin lama tidak dapat mengakses sistem lagi.
                    </div>
                </div>

                <!-- Form Ganti Password Admin -->
                <form action="{{ route('admin.profile.update-password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Input 1: Password Saat Ini (Opsional & Dapat Dilihat Ikon Mata) -->
                    <div>
                        <label class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-1.5">Password Saat Ini <span class="text-gray-muted font-normal font-sans text-[10px]">(Opsional)</span></label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password" value="{{ old('current_password') }}" placeholder="Ketik password saat ini (opsional)" class="w-full bg-white border border-navy-light/40 text-navy-dark text-xs font-semibold rounded-xl pl-4 pr-11 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all shadow-sm">
                            <button type="button" onclick="togglePasswordVisibility('current_password', 'icon-current_password')" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-navy-base/60 hover:text-navy-dark transition-colors focus:outline-none" title="Tampilkan / Sembunyikan Password">
                                <svg id="icon-current_password" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="text-[10px] text-gray-muted mt-1">Dapat dikosongkan. Klik ikon mata 👁️ untuk melihat karakter password yang diketikkan.</p>
                    </div>

                    <!-- Input 2: Password Baru -->
                    <div>
                        <label class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-1.5">Password Baru <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter campuran huruf & angka" class="w-full bg-white border border-navy-light/40 text-navy-dark text-xs font-semibold rounded-xl pl-4 pr-11 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all shadow-sm" required>
                            <button type="button" onclick="togglePasswordVisibility('password', 'icon-password')" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-navy-base/60 hover:text-navy-dark transition-colors focus:outline-none" title="Tampilkan / Sembunyikan Password">
                                <svg id="icon-password" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="text-[10px] text-gray-muted mt-1">Gunakan kombinasi password yang kuat untuk keamanan sistem sekolah.</p>
                    </div>

                    <!-- Input 2: Konfirmasi Password Baru -->
                    <div>
                        <label class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-1.5">Ulangi Password Baru <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang password baru Anda" class="w-full bg-white border border-navy-light/40 text-navy-dark text-xs font-semibold rounded-xl pl-4 pr-11 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all shadow-sm" required>
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'icon-password_confirmation')" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-navy-base/60 hover:text-navy-dark transition-colors focus:outline-none" title="Tampilkan / Sembunyikan Password">
                                <svg id="icon-password_confirmation" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Perbarui Password -->
                    <div class="pt-3 border-t border-navy-light/20 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-rose-700 text-white-off font-bold text-xs rounded-xl hover:bg-rose-800 shadow-sm hover:shadow-md active:scale-95 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <span>Perbarui Password & Hentikan Sesi Lain</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Script JavaScript untuk Fitur Toggle Show/Hide Password -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                // Ubah ke Ikon Eye-Off (Mata Disilang)
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.038 10.038 0 014.122-.963c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"></path>';
            } else {
                input.type = 'password';
                // Ubah ke Ikon Eye-Open (Mata Terbuka)
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
@endsection

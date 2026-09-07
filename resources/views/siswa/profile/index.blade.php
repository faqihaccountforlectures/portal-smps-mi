@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header', 'Pengaturan Akun & Profil')

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Pesan Flash Notifikasi Sukses -->
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-emerald-500/10 flex items-center justify-between animate-[fade-in-down_0.5s_ease-out]">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-500 p-2 rounded-lg text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.style.display='none'" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-rose-500/10 flex items-center justify-between animate-[fade-in-down_0.5s_ease-out]">
            <div class="flex items-center gap-3">
                <div class="bg-rose-500 p-2 rounded-lg text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="text-sm font-bold tracking-wide">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.style.display='none'" class="text-rose-500 hover:text-rose-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-rose-500/10 flex flex-col gap-1.5 animate-[fade-in-down_0.5s_ease-out]">
            @foreach($errors->all() as $error)
                <span class="text-xs font-bold tracking-wide flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $error }}
                </span>
            @endforeach
        </div>
    @endif

    <!-- Alert Informasi SSO -->
    <div class="bg-navy-light/10 border border-navy-light/30 text-navy-dark px-5 py-3.5 rounded-2xl shadow-sm mb-6 flex items-center gap-3">
        <div class="bg-navy-light/20 p-2 rounded-xl text-navy-base shrink-0 border border-navy-light/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold leading-relaxed">Anda masuk menggunakan akun Google Belajar. Email, Nama, dan NISN adalah data resmi sekolah yang hanya dapat diubah oleh administrator.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- BAGIAN KIRI: Info Profil Siswa (Hanya Baca) -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden relative group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <!-- Latar Belakang Banner Navy -->
                <div class="h-28 bg-gradient-to-br from-navy-dark to-navy-base relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-navy-light/20 rounded-full blur-xl"></div>
                </div>
                
                <!-- Foto Profil (Avatar Circle) -->
                <div class="relative px-6 pb-6 flex flex-col items-center mt-[-3.5rem]">
                    <div class="w-24 h-24 bg-white p-1.5 rounded-full shadow-md border border-navy-light/30">
                        <div class="w-full h-full bg-navy-light/20 text-navy-dark rounded-full flex items-center justify-center font-bold text-2xl border border-navy-light/40 font-heading">
                            {{ substr($profile->full_name ?? ($user->name ?? 'S'), 0, 1) }}
                        </div>
                    </div>
                    
                    <h2 class="mt-4 text-xl font-bold text-navy-dark text-center font-heading tracking-wide">{{ $profile->full_name ?? $user->name }}</h2>
                    <span class="mt-1.5 inline-flex items-center gap-1.5 bg-navy-light/10 text-navy-dark border border-navy-light/30 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Siswa Terverifikasi
                    </span>

                    <!-- Data Utama (Hanya Baca) -->
                    <div class="w-full space-y-4 pt-5 mt-5 border-t border-navy-light/20">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-muted uppercase tracking-wider mb-1">Email Google SSO</label>
                            <div class="bg-white-off/70 border border-navy-light/30 px-4 py-2.5 rounded-xl flex items-center gap-3 cursor-not-allowed">
                                <svg class="w-4 h-4 text-navy-light shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span class="text-navy-dark font-medium text-xs truncate">{{ $user->email }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-bold text-gray-muted uppercase tracking-wider mb-1">Nomor Induk (NISN)</label>
                            <div class="bg-white-off/70 border border-navy-light/30 px-4 py-2.5 rounded-xl flex items-center gap-3 cursor-not-allowed">
                                <svg class="w-4 h-4 text-navy-light shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                <span class="text-navy-dark font-medium font-mono text-xs">{{ $profile->nisn ?? '-' }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-muted uppercase tracking-wider mb-1">Jenis Kelamin</label>
                            <div class="bg-white-off/70 border border-navy-light/30 px-4 py-2.5 rounded-xl flex items-center gap-3 cursor-not-allowed">
                                @if(strtolower($profile->gender ?? '') === 'laki-laki' || strtolower($profile->gender ?? '') === 'l')
                                    <svg class="w-4 h-4 text-navy-base shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="text-navy-dark font-medium text-xs">Laki-laki</span>
                                @elseif(strtolower($profile->gender ?? '') === 'perempuan' || strtolower($profile->gender ?? '') === 'p')
                                    <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="text-navy-dark font-medium text-xs">Perempuan</span>
                                @else
                                    <span class="text-navy-dark font-medium text-xs">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: Form Edit Kontak Siswa -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex items-center gap-3">
                    <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-dark font-heading tracking-wide text-base">Informasi Kontak Siswa</h3>
                        <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-bold">Perbarui nomor telepon aktif Anda dan nomor telepon orang tua di sini.</p>
                    </div>
                </div>

                <form action="{{ route('siswa.profile.update') }}" method="POST" class="p-7">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                        <!-- Nomor HP Siswa -->
                        <div>
                            <label for="phone_number" class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-2">Nomor Telepon Siswa</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-navy-base/60">
                                    <svg class="h-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $profile->phone_number) }}" 
                                    class="w-full bg-white-off/50 border border-navy-light/40 text-navy-dark font-mono font-medium text-xs rounded-xl pl-9 pr-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-200 shadow-sm"
                                    placeholder="Contoh: 081234567890">
                            </div>
                            @error('phone_number')
                                <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor HP Orang Tua -->
                        <div>
                            <label for="parent_phone" class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-2">Nomor Telepon Orang Tua</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-navy-base/60">
                                    <svg class="h-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone', $profile->parent_phone) }}" 
                                    class="w-full bg-white-off/50 border border-navy-light/40 text-navy-dark font-mono font-medium text-xs rounded-xl pl-9 pr-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-200 shadow-sm"
                                    placeholder="Contoh: 081298765432">
                            </div>
                            @error('parent_phone')
                                <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-5 border-t border-navy-light/20 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-navy-dark text-white-off font-bold text-xs rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 flex items-center gap-2 border border-transparent">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

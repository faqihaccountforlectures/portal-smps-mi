@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header', 'Pengaturan Akun & Profil')

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Pesan Flash untuk Notifikasi Sukses -->
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg shadow-sm mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.style.display='none'" class="text-emerald-500 hover:text-emerald-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    <!-- Alert Informasi -->
    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-3 py-2 rounded-lg shadow-sm mb-4 flex items-center gap-3">
        <div class="bg-blue-100 p-1.5 rounded-md shrink-0">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs text-blue-700/90"><strong class="font-bold text-blue-800">Informasi Akun:</strong> Anda masuk menggunakan SSO Google. Email, Nama, dan NISN adalah data resmi yang hanya dapat diubah oleh admin.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <!-- BAGIAN KIRI: Info Profil (Hanya Baca) -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                <!-- Latar Belakang Banner -->
                <div class="h-24 bg-gradient-to-r from-blue-600 to-indigo-700"></div>
                
                <!-- Foto Profil (Avatar) -->
                <div class="relative px-6 pb-6 flex flex-col items-center mt-[-3rem]">
                    <div class="w-20 h-20 bg-white p-1.5 rounded-full shadow-md">
                        <div class="w-full h-full bg-slate-100 rounded-full flex items-center justify-center text-slate-400 border border-slate-200">
                            <!-- Ikon Profil Generik -->
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    </div>
                    
                    <h2 class="mt-4 text-xl font-bold text-slate-800 text-center">{{ $profile->full_name ?? $user->name }}</h2>
                    <p class="text-indigo-600 font-medium text-sm mt-1 mb-4 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Siswa Terverifikasi
                    </p>

                    <!-- Data Utama (Hanya Baca) -->
                    <div class="w-full space-y-4 pt-4 border-t border-slate-100">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Email Google SSO</label>
                            <div class="bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-lg flex items-center gap-3 cursor-not-allowed opacity-80">
                                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span class="text-slate-700 font-medium text-sm truncate">{{ $user->email }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nomor Induk (NISN)</label>
                            <div class="bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-lg flex items-center gap-3 cursor-not-allowed opacity-80">
                                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                <span class="text-slate-700 font-medium text-sm">{{ $profile->nisn ?? '-' }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                            <div class="bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-lg flex items-center gap-3 cursor-not-allowed opacity-80">
                                @if(strtolower($profile->gender ?? '') === 'laki-laki' || strtolower($profile->gender ?? '') === 'l')
                                    <svg class="w-5 h-5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="text-slate-700 font-medium text-sm">Laki-laki</span>
                                @elseif(strtolower($profile->gender ?? '') === 'perempuan' || strtolower($profile->gender ?? '') === 'p')
                                    <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="text-slate-700 font-medium text-sm">Perempuan</span>
                                @else
                                    <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-slate-700 font-medium text-sm">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: Form Edit Data Kontak -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-50/80 border-b border-slate-100 px-6 py-4 flex items-center gap-3">
                    <div class="bg-indigo-100 text-indigo-600 p-2 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Informasi Kontak</h3>
                        <p class="text-sm text-slate-500">Perbarui nomor telepon yang dapat dihubungi.</p>
                    </div>
                </div>

                <form action="{{ route('siswa.profile.update') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <!-- Nomor HP Siswa -->
                        <div>
                            <label for="phone_number" class="block text-sm font-semibold text-slate-700 mb-2">Nomor HP Siswa</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $profile->phone_number) }}" 
                                    class="pl-10 block w-full rounded-xl border-slate-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2 bg-slate-50/50 hover:bg-white transition-colors"
                                    placeholder="Contoh: 081234567890">
                            </div>
                            @error('phone_number')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor HP Orang Tua -->
                        <div>
                            <label for="parent_phone" class="block text-sm font-semibold text-slate-700 mb-2">Nomor HP Orang Tua</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone', $profile->parent_phone) }}" 
                                    class="pl-10 block w-full rounded-xl border-slate-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2 bg-slate-50/50 hover:bg-white transition-colors"
                                    placeholder="Contoh: 081298765432">
                            </div>
                            @error('parent_phone')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:-translate-y-0.5 shadow-indigo-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

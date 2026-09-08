@extends('layouts.app')

@section('title', 'Profil Guru')
@section('header', 'Profil Saya')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Notifikasi Sukses / Error -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm shadow-emerald-500/10">
        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm shadow-rose-500/10">
        <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <span class="text-sm font-bold">{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- BAGIAN 1: Kartu Profil Utama (Kiri) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Kartu Identitas Diri -->
            <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden text-center relative group hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                <!-- Cover Banner Navy -->
                <div class="h-28 bg-gradient-to-br from-navy-dark to-navy-base relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-navy-light/20 rounded-full blur-2xl"></div>
                </div>
                
                <div class="px-6 pb-6 relative">
                    <!-- Avatar Inisial Guru -->
                    <div class="w-24 h-24 mx-auto rounded-full bg-white border-4 border-white shadow-lg flex items-center justify-center -mt-12 mb-4 overflow-hidden relative">
                        <div class="w-full h-full bg-navy-dark text-white-off flex items-center justify-center font-heading font-bold text-3xl shadow-inner">
                            {{ substr($user->teacherProfile->full_name ?? ($user->name ?? 'G'), 0, 1) }}
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-navy-dark font-heading leading-tight mb-1">
                        {{ $user->teacherProfile->full_name ?? 'Data Belum Lengkap' }}
                    </h3>
                    
                    <div class="inline-block px-3 py-1 bg-navy-light/10 text-navy-base font-bold text-xs rounded-lg border border-navy-light/30 mb-4">
                        {{ $user->teacherProfile->position ?? 'Guru Pengajar' }}
                    </div>
                    
                    <div class="space-y-3 text-left">
                        <div class="bg-white-off p-3.5 rounded-xl border border-navy-light/20">
                            <p class="text-[10px] font-bold text-gray-muted uppercase tracking-wider mb-0.5">NIP</p>
                            <p class="font-mono font-bold text-navy-dark text-sm">{{ $user->teacherProfile->nip ?? 'Belum Diatur' }}</p>
                        </div>
                        
                        <div class="bg-white-off p-3.5 rounded-xl border border-navy-light/20">
                            <p class="text-[10px] font-bold text-gray-muted uppercase tracking-wider mb-0.5">Email Akses</p>
                            <p class="font-semibold text-navy-dark text-xs truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: Data Kepegawaian & Form Kontak (Kanan) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Data Kepegawaian (Read-Only) -->
            <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 p-7 group hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-navy-light/20">
                    <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-navy-dark font-heading">Informasi Kepegawaian</h3>
                        <p class="text-xs text-gray-muted">Data identitas resmi yang terdaftar pada sistem sekolah.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                        <input type="text" value="{{ $user->teacherProfile->full_name ?? '-' }}" disabled class="w-full bg-white-off border border-navy-light/20 text-navy-dark font-semibold rounded-xl px-4 py-2.5 text-sm cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted uppercase tracking-wider mb-1.5">Jenis Kelamin</label>
                        @php
                            $gender = strtolower($user->teacherProfile->gender ?? '');
                            $genderText = '-';
                            if ($gender === 'l' || $gender === 'laki-laki') $genderText = 'Laki-laki';
                            elseif ($gender === 'p' || $gender === 'perempuan') $genderText = 'Perempuan';
                        @endphp
                        <input type="text" value="{{ $genderText }}" disabled class="w-full bg-white-off border border-navy-light/20 text-navy-dark font-semibold rounded-xl px-4 py-2.5 text-sm cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted uppercase tracking-wider mb-1.5">Jabatan / Posisi</label>
                        <input type="text" value="{{ $user->teacherProfile->position ?? '-' }}" disabled class="w-full bg-white-off border border-navy-light/20 text-navy-dark font-semibold rounded-xl px-4 py-2.5 text-sm cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted uppercase tracking-wider mb-1.5">Email Akun</label>
                        <input type="text" value="{{ $user->email }}" disabled class="w-full bg-white-off border border-navy-light/20 text-navy-dark font-semibold rounded-xl px-4 py-2.5 text-sm cursor-not-allowed">
                    </div>
                </div>
                <p class="text-xs text-gray-muted mt-5 italic flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-navy-light shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    * Apabila terdapat kekeliruan data kepegawaian, silakan ajukan perubahan melalui Administrator Sekolah.
                </p>
            </div>

            <!-- Formulir Pembaruan Kontak -->
            <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                <div class="bg-white-off/50 border-b border-navy-light/20 px-7 py-4 flex items-center gap-3">
                    <div class="bg-navy-light/10 p-2 rounded-lg text-navy-base border border-navy-light/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <h3 class="font-bold text-navy-dark text-base font-heading">Pengaturan Nomor Kontak</h3>
                </div>
                
                <form action="{{ route('guru.profile.update') }}" method="POST" class="p-7">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="phone_number" class="block text-sm font-bold text-navy-dark mb-2">Nomor Telepon / WhatsApp</label>
                        <div class="relative max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-muted">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->teacherProfile->phone_number ?? '') }}" placeholder="Contoh: 081234567890" class="w-full pl-10 bg-white border @error('phone_number') border-rose-400 ring-rose-100 @else border-navy-light/30 focus:border-navy-base focus:ring-navy-light/20 @enderror rounded-xl px-4 py-2.5 text-sm transition-all outline-none focus:ring-4 font-mono font-medium">
                        </div>
                        
                        @error('phone_number')
                            <p class="text-rose-500 text-xs font-bold mt-2 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @else
                            <p class="text-gray-muted text-xs mt-2">Nomor kontak ini digunakan oleh admin dan wali siswa untuk keperluan koordinasi akademik.</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="inline-flex items-center gap-2 bg-navy-base hover:bg-navy-dark text-white font-bold py-2.5 px-6 rounded-xl text-xs active:scale-95 transition-all shadow-md shadow-navy-base/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Simpan Perubahan Kontak</span>
                    </button>
                </form>
            </div>
            
        </div>
    </div>

</div>
@endsection

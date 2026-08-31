@extends('layouts.app')

@section('title', 'Profil Guru')
@section('header', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Notifikasi Sukses / Error -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- BAGIAN 1: Kartu Profil Utama (Kiri) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Kartu Identitas Diri -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden text-center relative">
                <div class="h-24 bg-gradient-to-r from-indigo-500 to-blue-500"></div>
                <div class="px-6 pb-6 relative">
                    <!-- Ikon Profil Generik -->
                    <div class="w-24 h-24 mx-auto rounded-full bg-white border-4 border-white shadow-md flex items-center justify-center -mt-12 mb-4 overflow-hidden relative">
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-800 leading-tight mb-1">
                        {{ $user->teacherProfile->full_name ?? 'Data belum lengkap' }}
                    </h3>
                    <p class="text-sm font-medium text-indigo-600 mb-4">{{ $user->teacherProfile->position ?? 'Guru' }}</p>
                    
                    <div class="bg-slate-50 rounded-xl p-3 text-sm text-left border border-slate-100">
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-0.5">NIP / NUPTK</p>
                        <p class="font-mono font-medium text-slate-800">{{ $user->teacherProfile->nip ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: Formulir Data & Kontak (Kanan) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Data Akademik & Identitas (Read-Only) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8">
                <div class="flex items-center gap-2 mb-5">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    <h3 class="text-lg font-bold text-slate-800">Informasi Kepegawaian</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                        <input type="text" value="{{ $user->teacherProfile->full_name ?? '-' }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-700 rounded-lg px-4 py-2.5 text-sm cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Kelamin</label>
                        @php
                            $gender = strtolower($user->teacherProfile->gender ?? '');
                            $genderText = '-';
                            if ($gender === 'l' || $gender === 'laki-laki') $genderText = 'Laki-laki';
                            elseif ($gender === 'p' || $gender === 'perempuan') $genderText = 'Perempuan';
                        @endphp
                        <input type="text" value="{{ $genderText }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-700 rounded-lg px-4 py-2.5 text-sm cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jabatan</label>
                        <input type="text" value="{{ $user->teacherProfile->position ?? '-' }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-700 rounded-lg px-4 py-2.5 text-sm cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Akses</label>
                        <input type="text" value="{{ $user->email }}" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-700 rounded-lg px-4 py-2.5 text-sm cursor-not-allowed">
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-4 italic">* Hubungi pihak admin jika terdapat kesalahan pada informasi kepegawaian Anda.</p>
            </div>

            <!-- Formulir Pembaruan Kontak -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-indigo-50 border-b border-indigo-100 px-6 py-4 flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <h3 class="font-bold text-indigo-900">Pengaturan Kontak</h3>
                </div>
                
                <form action="{{ route('guru.profile.update') }}" method="POST" class="p-6 md:p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="phone_number" class="block text-sm font-bold text-slate-700 mb-2">Nomor Telepon / WhatsApp</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->teacherProfile->phone_number ?? '') }}" placeholder="Contoh: 08123456789" class="w-full md:w-2/3 bg-white border @error('phone_number') border-rose-300 ring-rose-100 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-100 @enderror rounded-xl px-4 py-2.5 text-sm transition-all outline-none focus:ring-4">
                        
                        @error('phone_number')
                            <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p>
                        @else
                            <p class="text-slate-500 text-xs mt-1.5">Nomor kontak aktif yang dapat dihubungi oleh admin sekolah atau pihak wali murid.</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-xl text-sm transition-colors shadow-sm shadow-indigo-200">
                        Simpan Perubahan Kontak
                    </button>
                </form>
            </div>
            
        </div>
    </div>

</div>
@endsection

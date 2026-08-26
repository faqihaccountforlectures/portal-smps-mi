@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')
@section('header', 'Tambah Mata Pelajaran')

@section('content')
    <!-- Bungkus formnya dibikin lebih lebar (max-w-5xl) biar muat banyak kolom ke samping -->
    <div class="max-w-5xl mx-auto">
        
        <!-- Tombol Back buat balik ke halaman list -->
        <a href="{{ route('subjects.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 mb-6 transition-colors group font-medium">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
            <!-- Garis pemanis di atas form (warna ungu biar beda sama tahun ajaran) -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
            
            <div class="p-6 sm:p-8">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-800">Form Tambah Mata Pelajaran</h2>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi data-data di bawah ini untuk menambahkan mata pelajaran baru.</p>
                </div>

                <!-- Nampilin pesan error kalau validasinya gagal (misal kode udah kepakai) -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm font-bold">Ada yang salah nih:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1 ml-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('subjects.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <!-- Pakai Grid 3 Kolom biar ke samping semua -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        
                        <!-- BARIS 1 -->
                        <!-- Input Kode Mata Pelajaran (1 Kolom) -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kode Mapel <span class="text-red-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: BIND" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all uppercase">
                            <p class="text-[10px] text-slate-400 mt-1.5 ml-1">Maksimal 20 karakter unik.</p>
                        </div>

                        <!-- Input Nama Mata Pelajaran (2 Kolom biar lega) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Bahasa Indonesia" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <!-- BARIS 2 -->
                        <!-- Input Tingkat Kelas (1 Kolom) -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tingkat Kelas (Opsional)</label>
                            <div class="relative">
                                <select name="grade_level" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl pl-4 pr-10 py-2.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="">-- Berlaku semua kelas --</option>
                                    <option value="7" {{ old('grade_level') == '7' ? 'selected' : '' }}>Kelas 7 (Tujuh)</option>
                                    <option value="8" {{ old('grade_level') == '8' ? 'selected' : '' }}>Kelas 8 (Delapan)</option>
                                    <option value="9" {{ old('grade_level') == '9' ? 'selected' : '' }}>Kelas 9 (Sembilan)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Input KKM (1 Kolom) -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">KKM <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="kkm" value="{{ old('kkm') }}" placeholder="Contoh: 75.00" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                            <p class="text-[10px] text-slate-400 mt-1.5 ml-1">Kriteria Ketuntasan Minimal (0-100)</p>
                        </div>

                        <!-- Dropdown Kategori Mapel (1 Kolom) -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori Mapel <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="category" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl pl-4 pr-10 py-2.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih Kategori...</option>
                                    <option value="A" {{ old('category') == 'A' ? 'selected' : '' }}>Wajib (Kelompok A)</option>
                                    <option value="B" {{ old('category') == 'B' ? 'selected' : '' }}>Muatan Lokal (Kelompok B)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-50 flex items-center justify-end gap-3">
                        <button type="reset" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                            Reset Form
                        </button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/20 rounded-xl active:scale-[0.98] transition-all flex items-center gap-2">
                            <span>Simpan Mata Pelajaran</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

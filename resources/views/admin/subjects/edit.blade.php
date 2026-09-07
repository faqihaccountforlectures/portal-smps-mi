@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')
@section('header', 'Edit Mata Pelajaran')

@section('content')
    {{-- Sama kayak halaman create, kita bungkus di lebar max-w-5xl --}}
    <div class="max-w-5xl mx-auto">
        
        <a href="{{ route('subjects.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-muted hover:text-navy-base mb-6 transition-colors group font-medium">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Batal & Kembali
        </a>

        <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 relative overflow-hidden group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            {{-- Garis pemanis (kuning/orange karena ini mode edit) --}}
            <div class="p-6 sm:p-8">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-navy-dark font-heading">Edit Mata Pelajaran: {{ $subject->name }}</h2>
                    <p class="text-sm text-gray-muted mt-1">Ubah informasi mata pelajaran di bawah ini. Pastikan kode mata pelajarannya unik ya.</p>
                </div>

                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm font-bold">Waduh, ada error:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1 ml-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Perhatikan route-nya nembak ke subjects.update sambil bawa ID, dan ada @method('PUT') --}}
                <form action="{{ route('subjects.update', $subject->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    
                    {{-- Pakai Grid 3 Kolom biar ke samping semua --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        
                        {{-- BARIS 1 --}}
                        {{-- Input Kode Mata Pelajaran (1 Kolom) --}}
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-navy-dark mb-1.5">Kode Mapel <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code', $subject->code) }}" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all uppercase">
                        </div>

                        {{-- Input Nama Mata Pelajaran (2 Kolom) --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-navy-dark mb-1.5">Nama Mata Pelajaran <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $subject->name) }}" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all">
                        </div>

                        {{-- BARIS 2 --}}
                        {{-- Input Tingkat Kelas (1 Kolom) --}}
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-navy-dark mb-1.5">Tingkat Kelas</label>
                            <div class="relative">
                                <select name="grade_level" class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl pl-4 pr-10 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all appearance-none cursor-pointer">
                                    <option value="">-- Berlaku untuk semua kelas --</option>
                                    <option value="7" {{ old('grade_level', $subject->grade_level) == '7' ? 'selected' : '' }}>Kelas 7 (Tujuh)</option>
                                    <option value="8" {{ old('grade_level', $subject->grade_level) == '8' ? 'selected' : '' }}>Kelas 8 (Delapan)</option>
                                    <option value="9" {{ old('grade_level', $subject->grade_level) == '9' ? 'selected' : '' }}>Kelas 9 (Sembilan)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy-base">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Input KKM (1 Kolom) --}}
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-navy-dark mb-1.5">KKM <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="kkm" value="{{ old('kkm', $subject->kkm) }}" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all">
                        </div>

                        {{-- Dropdown Kategori Mapel (1 Kolom) --}}
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-navy-dark mb-1.5">Kategori Mapel <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="category" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl pl-4 pr-10 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all appearance-none cursor-pointer">
                                    <option value="" disabled>Pilih Kategori...</option>
                                    <option value="A" {{ old('category', $subject->category) == 'A' ? 'selected' : '' }}>Wajib (Kelompok A)</option>
                                    <option value="B" {{ old('category', $subject->category) == 'B' ? 'selected' : '' }}>Muatan Lokal (Kelompok B)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy-base">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-50 flex items-center justify-end gap-3">
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white-off bg-navy-dark hover:bg-navy-base hover:shadow-lg hover:shadow-navy-base/20 rounded-xl active:scale-[0.98] transition-all flex items-center gap-2">
                            <span>Update Mata Pelajaran</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


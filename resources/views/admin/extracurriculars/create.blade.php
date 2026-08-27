@extends('layouts.app')

@section('title', 'Tambah Ekstrakurikuler')
@section('header', 'Tambah Ekstrakurikuler')

@section('content')
    <div class="max-w-5xl mx-auto">
        
        <!-- Tombol kembali ke halaman daftar ekstrakurikuler -->
        <a href="{{ route('extracurriculars.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 mb-3 transition-colors group font-medium">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
            
            <div class="p-5 sm:p-6">
                <div class="mb-5">
                    <h2 class="text-xl font-bold text-slate-800">Form Tambah Ekstrakurikuler</h2>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi data-data di bawah ini untuk menambahkan ekstrakurikuler baru.</p>
                </div>

                <!-- Alert error dari hasil validasi di Controller -->
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

                <!-- Form dengan enctype="multipart/form-data" penting untuk upload file/gambar -->
                <form action="{{ route('extracurriculars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Input Nama Ekstrakurikuler -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Ekstrakurikuler <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Pramuka" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-3 py-1.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <!-- Dropdown Guru Pembina -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Guru Pembina <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="teacher_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl pl-3 pr-10 py-1.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>Pilih Pembina...</option>
                                    <!-- Melakukan perulangan data $teachers dari controller -->
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->teacherProfile->full_name ?? $teacher->email }}</option>
                                    @endforeach
                                </select>
                                <!-- Panah dropdown -->
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Input Jadwal -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Jadwal <span class="text-red-500">*</span></label>
                            <input type="text" name="schedule" value="{{ old('schedule') }}" placeholder="Contoh: Sabtu, 14:00 - 16:00" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-3 py-1.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <!-- Input Biaya/Tagihan per bulan -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Biaya Per Bulan (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="fee" value="{{ old('fee', 100000) }}" required min="0" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-3 py-1.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <!-- Input Upload Gambar -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Foto / Banner (Opsional)</label>
                            <!-- Hanya menerima file bertipe image (gambar) -->
                            <input type="file" name="image" accept="image/*" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-3 py-1 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>

                        <!-- Input Deskripsi -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi (Opsional)</label>
                            <textarea name="description" rows="2" placeholder="Tuliskan deskripsi singkat tentang ekstrakurikuler ini..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-3 py-1.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Tombol Aksi Bawah -->
                    <div class="pt-4 border-t border-slate-50 flex items-center justify-end gap-3">
                        <button type="reset" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                            Reset Form
                        </button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/20 rounded-xl active:scale-[0.98] transition-all flex items-center gap-2">
                            <span>Simpan Data</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

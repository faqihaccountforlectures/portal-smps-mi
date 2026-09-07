@extends('layouts.app')

@section('title', 'Tambah Ekstrakurikuler')
@section('header', 'Tambah Ekstrakurikuler')

@section('content')
    <div class="max-w-6xl mx-auto">
        
        <!-- Header dengan Tombol Kembali -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('extracurriculars.index') }}" class="bg-white border border-navy-light/30 text-gray-muted hover:text-navy-base hover:bg-navy-light/10 p-2.5 rounded-xl transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-navy-dark font-heading">Form Tambah Ekstrakurikuler</h2>
                <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-bold">Lengkapi data-data di bawah ini untuk menambahkan ekstrakurikuler baru.</p>
            </div>
        </div>

        <!-- Alert error dari hasil validasi di Controller -->
        @if($errors->any())
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-rose-500/10 flex items-start gap-3">
                <div class="bg-rose-100 p-2 rounded-lg text-rose-600 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <span class="text-sm font-bold tracking-wide">Ada beberapa kesalahan:</span>
                    <ul class="list-disc list-inside text-xs font-semibold mt-2 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-navy-light/30 overflow-hidden relative p-6 sm:p-8">
            <!-- Form dengan enctype="multipart/form-data" penting untuk upload file/gambar -->
            <form action="{{ route('extracurriculars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Input Nama Ekstrakurikuler -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Nama Ekstrakurikuler <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Pramuka" required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium">
                    </div>

                    <!-- Dropdown Guru Pembina -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Guru Pembina <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="teacher_id" required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl pl-4 pr-10 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium appearance-none cursor-pointer">
                                <option value="" disabled selected>Pilih Pembina...</option>
                                <!-- Melakukan perulangan data $teachers dari controller -->
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->teacherProfile->full_name ?? $teacher->email }}</option>
                                @endforeach
                            </select>
                            <!-- Panah dropdown -->
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy-base">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Input Jadwal -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Jadwal <span class="text-rose-500">*</span></label>
                        <input type="text" name="schedule" value="{{ old('schedule') }}" placeholder="Contoh: Sabtu, 14:00 - 16:00" required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium">
                    </div>

                    <!-- Input Biaya/Tagihan per bulan -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Biaya (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="fee" value="{{ old('fee', 100000) }}" required min="0" class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium">
                    </div>

                    <!-- Input Upload Gambar -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Foto / Banner <span class="text-gray-muted font-normal text-xs italic">(Opsional)</span></label>
                        <input type="file" name="image" accept="image/*" class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-3 py-1.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-navy-light/20 file:text-navy-dark hover:file:bg-navy-light/40 shadow-sm font-medium">
                    </div>

                    <!-- Input Deskripsi -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Deskripsi <span class="text-gray-muted font-normal text-xs italic">(Opsional)</span></label>
                        <textarea name="description" rows="1" placeholder="Tuliskan deskripsi singkat..." class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Tombol Aksi Bawah -->
                <div class="mt-8 pt-6 border-t border-navy-light/20 flex items-center justify-end gap-3">
                    <a href="{{ route('extracurriculars.index') }}" class="px-6 py-2.5 bg-white-off text-gray-muted font-bold text-sm rounded-xl hover:bg-navy-light/10 hover:text-navy-dark border border-navy-light/30 transition-colors shadow-sm active:scale-95">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-2.5 bg-navy-dark text-white-off font-bold text-sm rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 flex items-center gap-2 border border-transparent">
                        <span>Simpan Data</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

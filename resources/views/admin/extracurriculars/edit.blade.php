@extends('layouts.app')

@section('title', 'Edit Ekstrakurikuler')
@section('header', 'Edit Ekstrakurikuler')

@section('content')
    <div class="max-w-5xl mx-auto">
        <!-- Tombol kembali -->
        <a href="{{ route('extracurriculars.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 mb-3 transition-colors group font-medium">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
            <!-- Garis warna gradien di atas form -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
            
            <div class="p-5 sm:p-6">
                <!-- Header Form Edit -->
                <div class="mb-5 flex items-center gap-4">
                    <!-- Tampilkan foto ekskul saat ini jika ada -->
                    @if($extracurricular->image)
                        <img src="{{ asset('storage/' . $extracurricular->image) }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                    @endif
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Edit Data: {{ $extracurricular->name }}</h2>
                        <p class="text-sm text-slate-500 mt-1">Perbarui data ekstrakurikuler di bawah ini.</p>
                    </div>
                </div>

                <!-- Alert jika ada error validasi -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 shadow-sm">
                        <ul class="list-disc list-inside text-xs space-y-1 ml-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form edit menggunakan method POST namun ditimpa dengan method PUT di bawah -->
                <form action="{{ route('extracurriculars.update', $extracurricular->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <!-- Timpa method jadi PUT khusus untuk update data -->
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Ekstrakurikuler <span class="text-red-500">*</span></label>
                            <!-- value diisi dengan nilai yang ada di database ($extracurricular->name) -->
                            <input type="text" name="name" value="{{ old('name', $extracurricular->name) }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-3 py-1.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Guru Pembina <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="teacher_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl pl-3 pr-10 py-1.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="" disabled>Pilih Pembina...</option>
                                    @foreach($teachers as $teacher)
                                        <!-- Tandai guru yang sebelumnya dipilih dengan 'selected' -->
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $extracurricular->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->teacherProfile->full_name ?? $teacher->email }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Jadwal <span class="text-red-500">*</span></label>
                            <input type="text" name="schedule" value="{{ old('schedule', $extracurricular->schedule) }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-3 py-1.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Biaya Per Bulan (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="fee" value="{{ old('fee', $extracurricular->fee) }}" required min="0" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-3 py-1.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Ubah Foto / Banner (Opsional)</label>
                            <input type="file" name="image" accept="image/*" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-3 py-1 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <!-- Pesan informasi agar tidak bingung -->
                            <p class="text-[10px] text-slate-400 mt-1 ml-1">Biarkan kosong jika tidak ingin mengubah foto.</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi (Opsional)</label>
                            <textarea name="description" rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-3 py-1.5 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">{{ old('description', $extracurricular->description) }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-50 flex items-center justify-end gap-3">
                        <!-- Tombol submit data edit -->
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/20 rounded-xl active:scale-[0.98] transition-all flex items-center gap-2">
                            <span>Simpan Perubahan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

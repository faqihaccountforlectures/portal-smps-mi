{{-- FUNGSI KODE: Menggunakan layout utama portal akademik --}}
@extends('layouts.app')

@section('title', 'Tambah Materi Pelajaran')
@section('header', 'Unggah Materi Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Tombol Navigasi Kembali --}}
    <a href="{{ route('guru.materials.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-navy-base hover:text-navy-dark transition-colors group">
        <div class="p-2 bg-white rounded-xl border border-navy-light/30 shadow-sm group-hover:border-navy-base/50 group-hover:scale-105 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </div>
        <span>Kembali ke Daftar Materi</span>
    </a>

    {{-- Notifikasi Error Validasi Global --}}
    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
        <div class="bg-rose-100 p-2 rounded-xl shrink-0 text-rose-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="font-bold text-sm">{{ session('error') }}</p>
    </div>
    @endif

    {{-- FUNGSI KODE: Formulir Utama Penambahan Materi Pelajaran --}}
    <div class="bg-white rounded-2xl shadow-sm border border-navy-light/30 overflow-hidden">
        <div class="h-2.5 bg-gradient-to-r from-navy-dark via-navy-base to-navy-light"></div>
        
        <div class="p-8">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-navy-dark font-heading">Informasi Bahan Ajar</h3>
                <p class="text-xs text-gray-muted mt-1">Lengkapi data modul, lampirkan berkas digital, atau sematkan tautan belajar online untuk siswa.</p>
            </div>

            <form action="{{ route('guru.materials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Pilihan Mata Pelajaran dan Kelas --}}
                <div>
                    <label for="teacher_assignment_id" class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-2">
                        Pilih Mata Pelajaran & Kelas Sasaran <span class="text-rose-500">*</span>
                    </label>
                    <select name="teacher_assignment_id" id="teacher_assignment_id" required class="w-full bg-white-off border @error('teacher_assignment_id') border-rose-400 @else border-navy-light/40 @enderror text-navy-dark font-semibold text-xs rounded-xl px-4 py-3 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all cursor-pointer">
                        <option value="">-- Pilih Mata Pelajaran & Kelas yang Anda Ajar --</option>
                        @foreach($assignments as $assign)
                            <option value="{{ $assign->id }}" {{ old('teacher_assignment_id') == $assign->id ? 'selected' : '' }}>
                                {{ $assign->subject->name ?? '-' }} — Kelas {{ $assign->classRoom->name ?? '-' }} (T.A. {{ $assign->academicYear->year_name ?? '-' }} {{ ucfirst($assign->academicYear->semester ?? '') }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_assignment_id')
                        <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Judul Materi --}}
                <div>
                    <label for="title" class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-2">
                        Judul Materi Pembelajaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Contoh: Bab 2 - Sistem Pernapasan Manusia (Modul & Latihan Soal)" class="w-full bg-white-off border @error('title') border-rose-400 @else border-navy-light/40 @enderror text-navy-dark font-semibold text-xs rounded-xl px-4 py-3 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all placeholder:text-gray-muted/50">
                    @error('title')
                        <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Deskripsi / Catatan Instruksi --}}
                <div>
                    <label for="description" class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-2">
                        Petunjuk / Catatan untuk Siswa <span class="text-gray-muted text-[10px] lowercase font-normal">(opsional)</span>
                    </label>
                    <textarea name="description" id="description" rows="4" placeholder="Tuliskan instruksi belajar, rangkuman bab, atau nomor halaman yang wajib dipelajari siswa..." class="w-full bg-white-off border @error('description') border-rose-400 @else border-navy-light/40 @enderror text-navy-dark font-medium text-xs rounded-xl p-4 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all placeholder:text-gray-muted/50">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 border-t border-navy-light/20">
                    
                    {{-- Input Unggah Berkas --}}
                    <div>
                        <label for="file" class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-2">
                            Unggah Berkas Materi <span class="text-gray-muted text-[10px] lowercase font-normal">(opsional)</span>
                        </label>
                        <div class="relative border-2 border-dashed @error('file') border-rose-400 @else border-navy-light/40 @enderror rounded-2xl p-5 bg-white-off hover:bg-navy-light/5 transition-colors text-center">
                            <input type="file" name="file" id="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="updateFileName(this)">
                            
                            <div class="flex flex-col items-center pointer-events-none">
                                <div class="w-10 h-10 rounded-full bg-navy-light/15 text-navy-base flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                </div>
                                <p id="file-label" class="text-xs font-bold text-navy-dark mb-0.5">Pilih atau Seret Berkas ke Sini</p>
                                <p class="text-[10px] text-gray-muted">PDF, DOCX, PPTX, XLSX, ZIP (Maks. 20 MB)</p>
                            </div>
                        </div>
                        @error('file')
                            <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Tautan Eksternal --}}
                    <div>
                        <label for="link_url" class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-2">
                            Tautan Luar Pembelajaran <span class="text-gray-muted text-[10px] lowercase font-normal">(opsional)</span>
                        </label>
                        <div class="space-y-2">
                            <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}" placeholder="https://drive.google.com/... atau YouTube" class="w-full bg-white-off border @error('link_url') border-rose-400 @else border-navy-light/40 @enderror text-navy-dark font-semibold text-xs rounded-xl px-4 py-3 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all placeholder:text-gray-muted/50">
                            <p class="text-[11px] text-gray-muted leading-relaxed">
                                Gunakan kolom ini jika materi berbentuk video YouTube, folder Google Drive, presentasi Canva, atau Google Form.
                            </p>
                        </div>
                        @error('link_url')
                            <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-navy-light/20">
                    <a href="{{ route('guru.materials.index') }}" class="px-5 py-2.5 bg-white-off text-navy-dark hover:bg-gray-200 font-bold text-xs rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-navy-dark hover:bg-navy-base text-white-pure font-bold text-xs rounded-xl shadow-md active:scale-95 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Publikasikan Materi</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
    // FUNGSI KODE: Memperbarui label nama berkas saat guru memilih berkas dari perangkat
    function updateFileName(input) {
        const label = document.getElementById('file-label');
        if (input.files && input.files[0]) {
            label.textContent = 'Berkas dipilih: ' + input.files[0].name;
            label.classList.add('text-navy-base');
        } else {
            label.textContent = 'Pilih atau Seret Berkas ke Sini';
            label.classList.remove('text-navy-base');
        }
    }
</script>
@endsection

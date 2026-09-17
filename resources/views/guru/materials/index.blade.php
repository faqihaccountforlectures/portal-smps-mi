{{-- FUNGSI KODE: Menggunakan layout utama portal akademik dengan navigasi sidebar dan topbar --}}
@extends('layouts.app')

@section('title', 'Materi Pelajaran')
@section('header', 'Kelola Materi Pelajaran')

@section('content')
<div class="space-y-6">

    {{-- FUNGSI KODE: Menampilkan notifikasi umpan balik sukses atau error dari proses aksi CRUD --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
        <div class="bg-emerald-100 p-2 rounded-xl shrink-0 text-emerald-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <p class="font-bold text-sm leading-relaxed">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
        <div class="bg-rose-100 p-2 rounded-xl shrink-0 text-rose-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="font-bold text-sm leading-relaxed">{{ session('error') }}</p>
    </div>
    @endif

    {{-- FUNGSI KODE: Banner Pengantar dan Ringkasan Statistik Materi Guru --}}
    <div class="bg-gradient-to-br from-navy-dark to-navy-base rounded-2xl p-7 text-white-off shadow-lg shadow-navy-dark/15 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-40 h-40 bg-navy-light/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 max-w-xl">
            <div class="inline-flex items-center gap-2 bg-navy-dark/60 border border-navy-light/30 px-3.5 py-1 rounded-full text-xs font-bold text-navy-light mb-2.5 backdrop-blur-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span>Pusat Sumber Belajar Digital</span>
            </div>
            <h2 class="text-2xl font-bold font-heading mb-1.5 text-white-pure">Bagikan Materi & Modul Ajar</h2>
            <p class="text-xs text-navy-light leading-relaxed">Unggah berkas PDF, dokumen ringkasan, atau tautkan link Google Drive/YouTube pembelajaran untuk mempermudah siswa belajar secara mandiri.</p>
        </div>

        <div class="flex items-center gap-3 relative z-10 w-full md:w-auto">
            <a href="{{ route('guru.materials.create') }}" class="w-full md:w-auto px-5 py-3 bg-white-pure text-navy-dark hover:bg-navy-light/20 hover:text-white-pure font-bold text-xs rounded-xl shadow-md active:scale-95 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Materi Baru</span>
            </a>
        </div>
    </div>

    {{-- FUNGSI KODE: Kartu Filter dan Navigasi Materi --}}
    <div class="bg-white rounded-2xl p-6 border border-navy-light/30 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h3 class="font-bold text-navy-dark text-base font-heading">Daftar Bahan Ajar yang Telah Diunggah</h3>
            <p class="text-xs text-gray-muted mt-0.5">Total terdapat <span class="font-bold text-navy-base">{{ $totalMaterials }}</span> materi pada <span class="font-bold text-navy-base">{{ $totalClasses }}</span> kelas yang Anda ampu.</p>
        </div>

        {{-- Form Filter Kelas & Mata Pelajaran --}}
        <form method="GET" action="{{ route('guru.materials.index') }}" class="w-full md:w-auto flex items-center gap-2">
            <div class="relative w-full md:w-72">
                <select name="assignment_id" onchange="this.form.submit()" class="w-full bg-white-off border border-navy-light/40 text-navy-dark font-semibold text-xs rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all cursor-pointer">
                    <option value="">Semua Mapel & Kelas</option>
                    @foreach($assignments as $assign)
                        <option value="{{ $assign->id }}" {{ $selectedAssignmentId == $assign->id ? 'selected' : '' }}>
                            {{ $assign->subject->name ?? '-' }} (Kelas {{ $assign->classRoom->name ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>
            @if($selectedAssignmentId)
                <a href="{{ route('guru.materials.index') }}" class="p-2.5 bg-white-off text-gray-muted hover:text-rose-600 rounded-xl border border-navy-light/30 transition-colors" title="Reset Filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            @endif
        </form>
    </div>

    {{-- FUNGSI KODE: Daftar Materi dalam Bentuk Grid Kartu Elegan --}}
    @if($materials->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($materials as $material)
        <div class="bg-white rounded-2xl border border-navy-light/30 p-6 shadow-sm shadow-navy-base/5 flex flex-col justify-between hover:shadow-md hover:border-navy-base/40 transition-all duration-300 group relative">
            
            <div>
                {{-- Badge Kelas dan Mata Pelajaran --}}
                <div class="flex items-center justify-between gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[11px] font-bold bg-navy-light/15 text-navy-dark border border-navy-light/30">
                        <svg class="w-3.5 h-3.5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Kelas {{ $material->teacherAssignment->classRoom->name ?? '-' }}
                    </span>

                    <span class="text-[10px] text-gray-muted font-mono font-medium">
                        {{ $material->created_at->format('d M Y') }}
                    </span>
                </div>

                {{-- Judul Materi --}}
                <h4 class="text-base font-bold text-navy-dark font-heading group-hover:text-navy-base transition-colors line-clamp-2 mb-1.5">
                    {{ $material->title }}
                </h4>

                {{-- Nama Mata Pelajaran --}}
                <p class="text-xs font-bold text-navy-base mb-3">
                    {{ $material->teacherAssignment->subject->name ?? 'Mata Pelajaran' }}
                </p>

                {{-- Deskripsi / Catatan Instruksi --}}
                @if($material->description)
                <p class="text-xs text-gray-muted line-clamp-3 leading-relaxed mb-4 bg-white-off/70 p-3 rounded-xl border border-navy-light/20">
                    {{ $material->description }}
                </p>
                @endif
            </div>

            <div class="space-y-3 pt-3 border-t border-navy-light/20">
                {{-- Informasi Lampiran Berkas --}}
                @if($material->file_path)
                <div class="flex items-center justify-between gap-2 bg-white-off p-2.5 rounded-xl border border-navy-light/30">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-navy-dark text-white-pure flex items-center justify-center font-bold text-[10px] font-mono shrink-0 uppercase">
                            {{ $material->file_extension ?? 'FILE' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-navy-dark truncate" title="{{ $material->file_name }}">{{ $material->file_name }}</p>
                            <p class="text-[10px] text-gray-muted font-mono">{{ $material->file_size }}</p>
                        </div>
                    </div>
                    <a href="{{ route('guru.materials.download', $material->id) }}" class="p-2 bg-navy-light/20 hover:bg-navy-base hover:text-white-pure text-navy-dark rounded-lg transition-colors shrink-0" title="Unduh Berkas">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                </div>
                @endif

                {{-- Informasi Tautan Eksternal --}}
                @if($material->link_url)
                <a href="{{ $material->link_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between p-2.5 rounded-xl bg-blue-50/70 border border-blue-200 text-blue-800 hover:bg-blue-100/70 transition-colors text-xs font-semibold">
                    <span class="inline-flex items-center gap-1.5 truncate">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        <span class="truncate">Buka Tautan Pembelajaran</span>
                    </span>
                    <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                @endif

                {{-- Tombol Aksi: Edit dan Hapus --}}
                <div class="flex items-center justify-end gap-2 pt-2">
                    <a href="{{ route('guru.materials.edit', $material->id) }}" class="px-3.5 py-1.5 bg-white-off hover:bg-navy-light/20 text-navy-dark font-bold text-xs rounded-lg border border-navy-light/30 transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Ubah</span>
                    </a>

                    <form action="{{ route('guru.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini secara permanen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-lg border border-rose-200 transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>

            </div>

        </div>
        @endforeach
    </div>

    {{-- Navigasi Paginasi --}}
    <div class="mt-6">
        {{ $materials->links() }}
    </div>

    @else
    {{-- Tampilan Kosong (Empty State) --}}
    <div class="bg-white rounded-2xl p-12 border border-navy-light/30 text-center flex flex-col items-center justify-center">
        <div class="w-16 h-16 rounded-full bg-navy-light/15 text-navy-base flex items-center justify-center mb-4 border border-navy-light/30">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-navy-dark font-heading mb-1">Belum Ada Materi Pelajaran</h3>
        <p class="text-xs text-gray-muted max-w-md leading-relaxed mb-6">
            @if($selectedAssignmentId)
                Tidak ada materi untuk filter mata pelajaran yang dipilih. Silakan pilih kelas lain atau tambahkan materi baru.
            @else
                Anda belum membagikan berkas materi atau modul pembelajaran apa pun kepada siswa.
            @endif
        </p>
        <a href="{{ route('guru.materials.create') }}" class="px-5 py-2.5 bg-navy-dark hover:bg-navy-base text-white-pure font-bold text-xs rounded-xl transition-all shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Unggah Materi Pertama</span>
        </a>
    </div>
    @endif

</div>
@endsection

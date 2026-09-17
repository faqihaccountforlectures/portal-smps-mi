{{-- FUNGSI KODE: Menggunakan layout utama portal akademik --}}
@extends('layouts.app')

@section('title', 'Materi Pelajaran')
@section('header', 'Materi Pembelajaran')

@section('content')
<div class="space-y-6">

    {{-- Notifikasi Error / Sukses --}}
    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
        <div class="bg-rose-100 p-2 rounded-xl shrink-0 text-rose-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="font-bold text-sm">{{ session('error') }}</p>
    </div>
    @endif

    {{-- FUNGSI KODE: Pengecekan apakah siswa sudah terdaftar di dalam kelas --}}
    @if(!$hasClass)
    <div class="bg-white rounded-2xl p-12 border border-navy-light/30 text-center flex flex-col items-center justify-center shadow-sm">
        <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mb-4 border border-amber-200">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-navy-dark font-heading mb-1">Belum Terdaftar di Kelas</h3>
        <p class="text-xs text-gray-muted max-w-md leading-relaxed mb-6">{{ $message }}</p>
        <a href="{{ route('siswa.profile.index') }}" class="px-5 py-2.5 bg-navy-dark text-white-pure font-bold text-xs rounded-xl hover:bg-navy-base transition-colors">
            Lihat Profil Saya
        </a>
    </div>
    @else

    {{-- FUNGSI KODE: Banner Informasi Materi Pembelajaran Siswa --}}
    <div class="bg-gradient-to-br from-navy-dark to-navy-base rounded-2xl p-7 text-white-off shadow-lg shadow-navy-dark/15 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-40 h-40 bg-navy-light/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 max-w-xl">
            <div class="inline-flex items-center gap-2 bg-navy-dark/60 border border-navy-light/30 px-3.5 py-1 rounded-full text-xs font-bold text-navy-light mb-2.5 backdrop-blur-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Materi Kelas {{ $classRoom->name }}</span>
            </div>
            <h2 class="text-2xl font-bold font-heading mb-1 text-white-pure">Pusat Belajar Mandiri Siswa</h2>
            <p class="text-xs text-navy-light leading-relaxed">Unduh modul, ringkasan bab, dan akses materi yang dibagikan oleh guru pengampu Anda untuk persiapan belajar dan ujian.</p>
        </div>

        <div class="bg-navy-dark/50 border border-navy-light/30 px-5 py-3.5 rounded-xl backdrop-blur-sm relative z-10 text-center shrink-0">
            <p class="text-[10px] font-bold text-navy-light uppercase tracking-wider mb-0.5">Total Materi</p>
            <p class="text-2xl font-bold text-white-pure font-heading">{{ $totalClassMaterials }} <span class="text-xs font-normal text-navy-light lowercase">modul</span></p>
        </div>
    </div>

    {{-- FUNGSI KODE: Navigasi Tab / Filter Per Mata Pelajaran --}}
    <div class="bg-white rounded-2xl p-4 border border-navy-light/30 shadow-sm overflow-x-auto thin-scrollbar">
        <div class="flex items-center gap-2 min-w-max">
            <a href="{{ route('siswa.materials.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($selectedSubjectId) ? 'bg-navy-dark text-white-pure shadow-sm' : 'bg-white-off text-navy-dark hover:bg-navy-light/20 border border-navy-light/30' }}">
                Semua Mata Pelajaran
            </a>
            @foreach($subjects as $subj)
            <a href="{{ route('siswa.materials.index', ['subject_id' => $subj->id]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $selectedSubjectId == $subj->id ? 'bg-navy-dark text-white-pure shadow-sm' : 'bg-white-off text-navy-dark hover:bg-navy-light/20 border border-navy-light/30' }}">
                {{ $subj->name }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- FUNGSI KODE: Grid Daftar Kartu Materi Pelajaran untuk Siswa --}}
    @if($materials->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($materials as $mat)
        <div class="bg-white rounded-2xl border border-navy-light/30 p-6 shadow-sm shadow-navy-base/5 flex flex-col justify-between hover:shadow-md hover:border-navy-base/40 transition-all duration-300 group">
            
            <div>
                {{-- Badge Mata Pelajaran & Tanggal --}}
                <div class="flex items-center justify-between gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[11px] font-bold bg-navy-light/15 text-navy-base border border-navy-light/30">
                        {{ $mat->teacherAssignment->subject->name ?? 'Mata Pelajaran' }}
                    </span>
                    <span class="text-[10px] text-gray-muted font-mono font-medium">
                        {{ $mat->created_at->format('d M Y') }}
                    </span>
                </div>

                {{-- Judul Materi --}}
                <h3 class="text-base font-bold text-navy-dark font-heading group-hover:text-navy-base transition-colors line-clamp-2 mb-2">
                    {{ $mat->title }}
                </h3>

                {{-- Informasi Guru Pengampu --}}
                <div class="flex items-center gap-2 mb-3 text-xs text-gray-muted font-medium">
                    <div class="w-6 h-6 rounded-full bg-navy-dark text-white-pure flex items-center justify-center text-[10px] font-bold shrink-0 font-heading">
                        {{ substr($mat->teacherAssignment->teacher->teacherProfile->full_name ?? ($mat->teacherAssignment->teacher->name ?? 'G'), 0, 1) }}
                    </div>
                    <span class="truncate font-semibold text-navy-dark">
                        {{ $mat->teacherAssignment->teacher->teacherProfile->full_name ?? ($mat->teacherAssignment->teacher->name ?? 'Guru') }}
                    </span>
                </div>

                {{-- Deskripsi / Catatan Instruksi --}}
                @if($mat->description)
                <div class="bg-white-off/70 p-3.5 rounded-xl border border-navy-light/20 mb-4">
                    <p class="text-xs text-gray-muted leading-relaxed line-clamp-3">
                        {{ $mat->description }}
                    </p>
                </div>
                @endif
            </div>

            {{-- Bagian Tombol Aksi Unduh & Tautan --}}
            <div class="space-y-2.5 pt-3 border-t border-navy-light/20">
                
                {{-- Tombol Unduh Berkas --}}
                @if($mat->file_path)
                <a href="{{ route('siswa.materials.download', $mat->id) }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-navy-dark hover:bg-navy-base text-white-pure transition-all shadow-sm active:scale-95 group/btn">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-7 h-7 rounded-lg bg-white-pure/20 text-white-pure flex items-center justify-center font-bold text-[9px] font-mono shrink-0 uppercase">
                            {{ $mat->file_extension ?? 'FILE' }}
                        </div>
                        <div class="min-w-0 text-left">
                            <p class="text-xs font-bold truncate">{{ $mat->file_name }}</p>
                            <p class="text-[10px] text-navy-light font-mono">{{ $mat->file_size }}</p>
                        </div>
                    </div>
                    <div class="p-1.5 bg-white-pure/15 rounded-lg shrink-0 group-hover/btn:translate-y-0.5 transition-transform">
                        <svg class="w-4 h-4 text-white-pure" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </div>
                </a>
                @endif

                {{-- Tombol Buka Tautan Eksternal --}}
                @if($mat->link_url)
                <a href="{{ $mat->link_url }}" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-between p-2.5 rounded-xl bg-blue-50/80 border border-blue-200 text-blue-800 hover:bg-blue-100 transition-colors text-xs font-bold">
                    <span class="inline-flex items-center gap-2 truncate">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        <span class="truncate">Buka Tautan Pembelajaran</span>
                    </span>
                    <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                @endif

            </div>

        </div>
        @endforeach
    </div>

    {{-- Navigasi Paginasi --}}
    <div class="mt-6">
        {{ $materials->links() }}
    </div>

    @else
    {{-- Tampilan Kosong Jika Belum Ada Materi --}}
    <div class="bg-white rounded-2xl p-12 border border-navy-light/30 text-center flex flex-col items-center justify-center">
        <div class="w-16 h-16 rounded-full bg-navy-light/15 text-navy-base flex items-center justify-center mb-4 border border-navy-light/30">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-navy-dark font-heading mb-1">Belum Ada Materi Pelajaran</h3>
        <p class="text-xs text-gray-muted max-w-sm leading-relaxed">
            @if($selectedSubjectId)
                Belum ada berkas materi atau tautan yang diunggah guru untuk mata pelajaran ini.
            @else
                Bapak/Ibu Guru belum mengunggah materi pelajaran untuk kelas Anda saat ini. Silakan periksa kembali secara berkala.
            @endif
        </p>
    </div>
    @endif

    @endif

</div>
@endsection

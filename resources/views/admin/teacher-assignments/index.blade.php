@extends('layouts.app')

@section('title', 'Penugasan Guru')
@section('header', 'Penugasan Guru')

@section('content')
    {{-- Notifikasi kalau sukses atau ada pesan error --}}
        {{-- FUNGSI KODE: Menampilkan Notifikasi Sukses dengan Animasi Melayang (Fade In Down) --}}
    @if(session('success'))
        <div class="bg-white border-l-4 border-navy-base text-navy-dark px-5 py-4 rounded-xl mb-6 shadow-sm shadow-navy-base/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-navy-base p-2 rounded-lg text-white-off">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-rose-500/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-rose-100 p-2 rounded-lg text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('error') }}</span>
        </div>
    @endif

    {{-- 
      Desainnya kita samakan dengan halaman Mata Pelajaran (lebar penuh)
      Biar admin bisa ngeliat data-datanya dengan leluasa.
    --}}
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        
        {{-- Header area --}}
        <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
            <div>
                <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Jadwal & Penugasan Guru</h2>
                {{-- Kita tampilin tahun ajaran aktifnya di sini biar jelas lagi ngatur tahun berapa --}}
                <p class="text-xs text-gray-muted mt-1">Tahun Ajaran Aktif: <b class="text-navy-base">{{ $activeYear->year_name ?? '-' }}</b> ({{ ucfirst($activeYear->semester ?? '') }})</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 items-center w-full lg:w-auto">
                <form action="{{ route('teacher-assignments.index') }}" method="GET" class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-navy-base/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama guru / mapel..." class="w-full bg-white border border-navy-light/50 text-navy-dark font-semibold text-xs rounded-xl pl-9 pr-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all placeholder:text-gray-muted/60">
                    <button type="submit" class="hidden">Cari</button>
                </form>

                <a href="{{ route('teacher-assignments.create') }}" class="inline-flex items-center justify-center gap-2 bg-navy-dark text-white-off font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-navy-base hover:shadow-lg hover:shadow-navy-base/20 active:scale-[0.98] transition-all duration-300 w-full sm:w-auto whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Penugasan</span>
                </a>
            </div>
        </div>
        
        {{-- Area Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                        <th class="px-7 py-4 font-bold">Nama Guru</th>
                        <th class="px-7 py-4 font-bold">Mata Pelajaran</th>
                        <th class="px-7 py-4 font-bold">Kelas</th>
                        <th class="px-7 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white-off text-sm text-navy-base">
                    {{-- Kita looping datanya satu-satu --}}
                    @forelse($assignments as $assignment)
                    <tr class="hover:bg-white-off/50 transition-colors group">
                        
                        {{-- Kolom Guru --}}
                        <td class="px-7 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Lingkaran inisial nama guru buat pemanis --}}
                                <div class="w-8 h-8 rounded-full bg-navy-light/20 text-navy-dark flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($assignment->teacher->teacherProfile->full_name ?? '?', 0, 1)) }}
                                </div>
                                <div class="font-bold text-navy-dark">
                                    {{ $assignment->teacher->teacherProfile->full_name ?? 'Tanpa Nama' }}
                                </div>
                            </div>
                        </td>
                        
                        {{-- Kolom Mata Pelajaran --}}
                        <td class="px-7 py-4">
                            <div class="font-semibold text-navy-dark">{{ $assignment->subject->name }}</div>
                            <div class="text-xs text-gray-muted font-mono font-bold mt-0.5">{{ $assignment->subject->code }}</div>
                        </td>
                        
                        {{-- Kolom Kelas --}}
                        <td class="px-7 py-4">
                            <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                @foreach($assignment->classRooms as $cls)
                                    <span class="bg-navy-light/10 text-navy-dark px-2.5 py-1.5 rounded-lg text-[10px] font-bold border border-navy-light/30 uppercase tracking-widest">
                                        {{ $cls->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        
                        {{-- Kolom Tombol Aksi --}}
                        <td class="px-7 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('teacher-assignments.edit', ['teacher_id' => $assignment->teacher_id, 'subject_id' => $assignment->subject_id]) }}" class="text-gray-muted hover:text-navy-base transition-all bg-white border border-transparent hover:border-navy-light/50 hover:bg-navy-light/30 p-2 rounded-lg shadow-sm transition-all" title="Edit Penugasan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button onclick="document.getElementById('deleteModal-{{ $assignment->teacher_id }}-{{ $assignment->subject_id }}').classList.remove('hidden')" class="text-gray-muted hover:text-rose-600 transition-all bg-white border border-transparent hover:border-rose-200 hover:bg-rose-50 p-2 rounded-lg shadow-sm transition-all" title="Hapus Penugasan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Memanggil modal konfirmasi hapusnya di sini --}}
                    @include('admin.teacher-assignments.delete')

                    @empty
                    {{-- Kalo belum ada guru yang dikasih tugas --}}
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-white-off rounded-full flex items-center justify-center text-gray-muted mb-4 border border-navy-light/40">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-base font-bold text-navy-dark font-heading mb-1">Belum ada penugasan guru</p>
                                <p class="text-xs mt-1.5 text-center text-gray-muted mb-5">Mari mulai membagi jadwal mengajar guru ke setiap kelas.</p>
                                <a href="{{ route('teacher-assignments.create') }}" class="text-xs text-navy-base font-bold bg-navy-light/20 border border-navy-light/40 px-4 py-2 rounded-lg hover:bg-navy-light/40 transition-colors">
                                    + Tambah Penugasan
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Footer & Paginasi --}}
        @if($assignments->hasPages())
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-center gap-4 mt-auto">
            <span class="text-xs text-gray-muted font-medium">
                Menampilkan <b class="text-navy-dark">{{ $assignments->firstItem() }}</b> - <b class="text-navy-dark">{{ $assignments->lastItem() }}</b> dari <b class="text-navy-dark">{{ $assignments->total() }}</b> penugasan kelas
            </span>
            <div class="pagination-wrapper">
                {{ $assignments->appends(request()->all())->links() }}
            </div>
        </div>
        @else
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex justify-between items-center text-xs text-gray-muted font-medium mt-auto">
            <span>Total: <b class="text-navy-dark">{{ $assignments->total() }}</b> penugasan kelas</span>
        </div>
        @endif
    </div>
@endsection





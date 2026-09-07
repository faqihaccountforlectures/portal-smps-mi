@extends('layouts.app')

@section('title', 'Manajemen Mata Pelajaran')
@section('header', 'Manajemen Mata Pelajaran')

@section('content')
    {{-- Ini bagian alert pesan sukses/error (dibikin nge-pop-up elegan di atas) --}}
        {{-- FUNGSI KODE: Menampilkan Notifikasi Sukses dengan Animasi Melayang (Fade In Down) --}}
    @if(session('success'))
        <div class="bg-white border-l-4 border-navy-base text-navy-dark px-5 py-4 rounded-xl mb-6 shadow-sm shadow-navy-base/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-navy-base p-2 rounded-lg text-white-off">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
        </div>
    @endif

    {{-- 
      Berbeda dengan halaman Tahun Ajaran yang layout-nya dibelah dua (Kiri Form, Kanan Tabel).
      Di sini kita pakai layout full-width (lebar penuh) biar keliatan lebih luas dan clean.
      Cocok buat daftar mata pelajaran yang datanya biasanya panjang-panjang.
    --}}
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        
        {{-- Header Tabel & Tombol Tambah Data --}}
        <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Mata Pelajaran</h2>
                <p class="text-xs text-gray-muted mt-1">Kelola data mata pelajaran wajib dan muatan lokal di sini.</p>
            </div>
            
            {{-- Tombol Tambah Data ditaruh di pojok kanan atas tabel --}}
            <a href="{{ route('subjects.create') }}" class="inline-flex items-center gap-2 bg-navy-dark text-white-off font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-navy-base hover:shadow-lg hover:shadow-navy-base/20 active:scale-[0.98] transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Mata Pelajaran</span>
            </a>
        </div>
        
        {{-- Container untuk Tabel (Biar bisa di-scroll kalau di HP) --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                        <th class="px-7 py-4 font-bold">Kode</th>
                        <th class="px-7 py-4 font-bold">Mata Pelajaran</th>
                        <th class="px-7 py-4 font-bold text-center">Kelas</th>
                        <th class="px-7 py-4 font-bold text-center">KKM</th>
                        <th class="px-7 py-4 font-bold">Kategori</th>
                        <th class="px-7 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white-off text-sm text-navy-base">
                    @forelse($subjects as $subject)
                    <tr class="hover:bg-white-off/50 transition-colors group">
                        {{-- Kolom Kode --}}
                        <td class="px-7 py-4 font-mono text-xs font-bold text-navy-base bg-white-off/50">
                            {{ $subject->code }}
                        </td>
                        
                        {{-- Kolom Nama --}}
                        <td class="px-7 py-4 font-bold text-navy-dark">
                            {{ $subject->name }}
                        </td>
                        
                        {{-- Kolom Tingkat Kelas --}}
                        <td class="px-7 py-4 text-center">
                            @if($subject->grade_level)
                                <span class="bg-navy-light/10 text-navy-dark px-2.5 py-1.5 rounded-lg text-[10px] font-bold border border-navy-light/30 uppercase tracking-widest">
                                    Kelas {{ $subject->grade_level }}
                                </span>
                            @else
                                <span class="text-gray-muted text-[10px] font-medium italic">Umum</span>
                            @endif
                        </td>
                        
                        {{-- Kolom KKM --}}
                        <td class="px-7 py-4 text-center font-bold {{ $subject->kkm >= 80 ? 'text-emerald-500' : 'text-amber-500' }}">
                            {{ $subject->kkm }}
                        </td>
                        
                        {{-- Kolom Kategori (Wajib / Muatan Lokal) --}}
                        <td class="px-7 py-4">
                            @if($subject->category === 'A')
                                <span class="inline-flex items-center gap-1.5 bg-navy-base/10 border border-navy-light/50 text-navy-dark px-3 py-1.5 rounded-lg text-[10px] font-bold tracking-widest uppercase shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-navy-base"></span>
                                    Wajib (A)
                                </span>
                            @elseif($subject->category === 'B')
                                <span class="inline-flex items-center gap-1.5 bg-white-off border border-navy-light/40 text-gray-muted px-3 py-1.5 rounded-lg text-[10px] font-bold tracking-widest uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-muted"></span>
                                    Muatan Lokal (B)
                                </span>
                            @else
                                <span class="text-gray-muted text-[10px] font-medium italic">-</span>
                            @endif
                        </td>
                        
                        {{-- Kolom Tombol Aksi (Edit & Hapus) --}}
                        <td class="px-7 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                {{-- Tombol Edit ngarah ke halaman form edit --}}
                                <a href="{{ route('subjects.edit', $subject->id) }}" class="text-gray-muted hover:text-navy-base transition-all p-2 rounded-xl hover:bg-navy-light/30 border border-transparent hover:border-navy-light/50 bg-white transition-all" title="Edit Mata Pelajaran">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>

                                {{-- Tombol Hapus buka modal konfirmasi --}}
                                <button onclick="document.getElementById('deleteModal-{{ $subject->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-rose-600 transition-all p-2 rounded-xl hover:bg-rose-50 border border-transparent hover:border-rose-200 bg-white transition-all" title="Hapus Mata Pelajaran">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Sisipin pop-up modal hapus di sini, persis di bawah masing-masing baris data --}}
                    @include('admin.subjects.delete')

                    @empty
                    {{-- Tampilan kalau tabelnya masih kosong banget (belum ada data) --}}
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-white-off rounded-full flex items-center justify-center text-gray-muted mb-4 border border-navy-light/40">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <p class="text-base font-bold text-navy-dark font-heading mb-1">Belum ada mata pelajaran</p>
                                <p class="text-xs mt-1.5 text-center text-gray-muted mb-5">Yuk, mulai tambahkan data mata pelajaran pertama Anda.</p>
                                <a href="{{ route('subjects.create') }}" class="text-xs text-navy-base font-bold bg-navy-light/20 px-4 py-2 rounded-lg border border-navy-light/40 hover:bg-navy-light/40 transition-colors">
                                    + Tambah Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- FUNGSI KODE: Paginasi (Pagination) --}}
        @if($subjects->hasPages())
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 mt-auto">
            {{ $subjects->links() }}
        </div>
        @else
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex justify-between items-center text-xs text-gray-muted font-medium mt-auto">
            <span>Total: <b class="text-navy-dark">{{ $subjects->total() }}</b> mata pelajaran</span>
        </div>
        @endif
    </div>
@endsection





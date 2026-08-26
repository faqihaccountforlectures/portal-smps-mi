@extends('layouts.app')

@section('title', 'Manajemen Mata Pelajaran')
@section('header', 'Manajemen Mata Pelajaran')

@section('content')
    <!-- Ini bagian alert pesan sukses/error (dibikin nge-pop-up elegan di atas) -->
    @if(session('success'))
        <div class="bg-indigo-50 border border-indigo-200 text-indigo-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- 
      Berbeda dengan halaman Tahun Ajaran yang layout-nya dibelah dua (Kiri Form, Kanan Tabel).
      Di sini kita pakai layout full-width (lebar penuh) biar keliatan lebih luas dan clean.
      Cocok buat daftar mata pelajaran yang datanya biasanya panjang-panjang.
    -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        
        <!-- Header Tabel & Tombol Tambah Data -->
        <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-800">Daftar Mata Pelajaran</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola data mata pelajaran wajib dan muatan lokal di sini.</p>
            </div>
            
            <!-- Tombol Tambah Data ditaruh di pojok kanan atas tabel -->
            <a href="{{ route('subjects.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white font-semibold text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/20 active:scale-[0.98] transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Mata Pelajaran</span>
            </a>
        </div>
        
        <!-- Container untuk Tabel (Biar bisa di-scroll kalau di HP) -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-4 font-semibold">Kode</th>
                        <th class="px-6 py-4 font-semibold">Mata Pelajaran</th>
                        <th class="px-6 py-4 font-semibold text-center">Kelas</th>
                        <th class="px-6 py-4 font-semibold text-center">KKM</th>
                        <th class="px-6 py-4 font-semibold">Kategori</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-600">
                    @forelse($subjects as $subject)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <!-- Kolom Kode -->
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-indigo-600 bg-indigo-50/10">
                            {{ $subject->code }}
                        </td>
                        
                        <!-- Kolom Nama -->
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $subject->name }}
                        </td>
                        
                        <!-- Kolom Tingkat Kelas -->
                        <td class="px-6 py-4 text-center">
                            @if($subject->grade_level)
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md text-xs font-bold border border-slate-200">
                                    Kelas {{ $subject->grade_level }}
                                </span>
                            @else
                                <span class="text-slate-400 text-xs italic">Umum</span>
                            @endif
                        </td>
                        
                        <!-- Kolom KKM -->
                        <td class="px-6 py-4 text-center font-bold {{ $subject->kkm >= 80 ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $subject->kkm }}
                        </td>
                        
                        <!-- Kolom Kategori (Wajib / Muatan Lokal) -->
                        <td class="px-6 py-4">
                            @if($subject->category === 'A')
                                <span class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    Wajib (A)
                                </span>
                            @elseif($subject->category === 'B')
                                <span class="inline-flex items-center gap-1.5 bg-purple-50 border border-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                    Muatan Lokal (B)
                                </span>
                            @else
                                <span class="text-slate-400 text-xs italic">-</span>
                            @endif
                        </td>
                        
                        <!-- Kolom Tombol Aksi (Edit & Hapus) -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <!-- Tombol Edit ngarah ke halaman form edit -->
                                <a href="{{ route('subjects.edit', $subject->id) }}" class="text-slate-400 hover:text-indigo-600 bg-white hover:bg-indigo-50 border border-slate-100 hover:border-indigo-100 p-2 rounded-lg shadow-sm transition-all" title="Edit Mata Pelajaran">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>

                                <!-- Tombol Hapus buka modal konfirmasi -->
                                <button onclick="document.getElementById('deleteModal-{{ $subject->id }}').classList.remove('hidden')" class="text-slate-400 hover:text-red-600 bg-white hover:bg-red-50 border border-slate-100 hover:border-red-100 p-2 rounded-lg shadow-sm transition-all" title="Hapus Mata Pelajaran">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Sisipin pop-up modal hapus di sini, persis di bawah masing-masing baris data -->
                    @include('admin.subjects.delete')

                    @empty
                    <!-- Tampilan kalau tabelnya masih kosong banget (belum ada data) -->
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4 border border-slate-100 shadow-inner">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <p class="text-base font-bold text-slate-700 mb-1">Belum ada mata pelajaran</p>
                                <p class="text-sm text-slate-500 mb-5">Yuk, mulai tambahkan data mata pelajaran pertama Anda.</p>
                                <a href="{{ route('subjects.create') }}" class="text-sm text-indigo-600 font-semibold bg-indigo-50 px-4 py-2 rounded-lg hover:bg-indigo-100 transition-colors">
                                    + Tambah Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Footer Tabel (Bisa dipakai buat pagination nanti kalau datanya ratusan) -->
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50 flex justify-between items-center text-xs text-slate-500">
            <span>Total: <b class="text-slate-700">{{ $subjects->count() }}</b> mata pelajaran</span>
        </div>
    </div>
@endsection

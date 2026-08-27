@extends('layouts.app')

@section('title', 'Manajemen Ekstrakurikuler')
@section('header', 'Manajemen Ekstrakurikuler')

@section('content')
    <!-- Alert Pesan Sukses -->
    @if(session('success'))
        <div class="bg-indigo-50 border border-indigo-200 text-indigo-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        
        <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-800">Daftar Ekstrakurikuler</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola data ekstrakurikuler sekolah beserta pembina dan jadwalnya.</p>
            </div>
            
            <a href="{{ route('extracurriculars.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white font-semibold text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/20 active:scale-[0.98] transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Ekstrakurikuler</span>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-4 font-semibold">Nama Ekstrakurikuler</th>
                        <th class="px-6 py-4 font-semibold">Guru Pembina</th>
                        <th class="px-6 py-4 font-semibold">Jadwal</th>
                        <th class="px-6 py-4 font-semibold text-right">Biaya/Bulan</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-600">
                    <!-- Melakukan perulangan untuk setiap data ekskul yang ada di database -->
                    @forelse($extracurriculars as $ekskul)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- Nama Ekstrakurikuler & Fotonya -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <!-- Jika ekskul ada gambarnya, tampilkan gambarnya -->
                                @if($ekskul->image)
                                    <img src="{{ asset('storage/' . $ekskul->image) }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200" alt="{{ $ekskul->name }}">
                                @else
                                    <!-- Jika gak ada gambar, pakai inisial huruf pertama namanya -->
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-500 font-bold">
                                        {{ substr($ekskul->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-800">{{ $ekskul->name }}</div>
                                    <div class="text-xs text-slate-400 max-w-[200px] truncate" title="{{ $ekskul->description }}">{{ $ekskul->description ?: 'Tidak ada deskripsi' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Guru Pembina -->
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-700">
                                {{ $ekskul->teacher->teacherProfile->full_name ?? 'Belum ada pembina' }}
                            </div>
                        </td>
                        
                        <!-- Jadwal -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-100 text-amber-700 px-3 py-1 rounded-md text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $ekskul->schedule }}
                            </span>
                        </td>
                        
                        <!-- Biaya Per Bulan -->
                        <td class="px-6 py-4 text-right font-bold text-slate-800">
                            Rp {{ number_format($ekskul->fee, 0, ',', '.') }}
                        </td>
                        
                        <!-- Tombol Aksi (Edit dan Hapus) -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <!-- Tombol menuju halaman edit -->
                                <a href="{{ route('extracurriculars.edit', $ekskul->id) }}" class="text-slate-400 hover:text-indigo-600 bg-white hover:bg-indigo-50 border border-slate-100 hover:border-indigo-100 p-2 rounded-lg shadow-sm transition-all" title="Edit Ekstrakurikuler">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>

                                <!-- Tombol pemicu Modal Hapus (menghilangkan class 'hidden' dari modal) -->
                                <button onclick="document.getElementById('deleteModal-{{ $ekskul->id }}').classList.remove('hidden')" class="text-slate-400 hover:text-red-600 bg-white hover:bg-red-50 border border-slate-100 hover:border-red-100 p-2 rounded-lg shadow-sm transition-all" title="Hapus Ekstrakurikuler">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- File terpisah untuk modal konfirmasi penghapusan agar kode rapi -->
                    @include('admin.extracurriculars.delete')

                    @empty
                    <!-- Tampilan kalau tabel kosong (belum ada ekskul yang dibuat) -->
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4 border border-slate-100 shadow-inner">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                                </div>
                                <p class="text-base font-bold text-slate-700 mb-1">Belum ada ekstrakurikuler</p>
                                <p class="text-sm text-slate-500 mb-5">Silakan tambahkan data ekstrakurikuler baru.</p>
                                <a href="{{ route('extracurriculars.create') }}" class="text-sm text-indigo-600 font-semibold bg-indigo-50 px-4 py-2 rounded-lg hover:bg-indigo-100 transition-colors">
                                    + Tambah Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Footer tabel -->
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50 flex justify-between items-center text-xs text-slate-500">
            <span>Total: <b class="text-slate-700">{{ $extracurriculars->count() }}</b> ekstrakurikuler</span>
        </div>
    </div>
@endsection

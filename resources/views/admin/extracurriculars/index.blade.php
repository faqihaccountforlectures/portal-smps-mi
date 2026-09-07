@extends('layouts.app')

@section('title', 'Manajemen Ekstrakurikuler')
@section('header', 'Manajemen Ekstrakurikuler')

@section('content')
    <!-- Alert Pesan Sukses -->
    @if(session('success'))
        <div class="bg-white border-l-4 border-navy-base text-navy-dark px-5 py-4 rounded-xl mb-6 shadow-sm shadow-navy-base/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-navy-base p-2 rounded-lg text-white-off">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        
        <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Ekstrakurikuler</h2>
                    <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-bold">Kelola data ekstrakurikuler sekolah beserta pembina dan jadwalnya.</p>
                </div>
            </div>
            
            <a href="{{ route('extracurriculars.create') }}" class="px-5 py-2.5 bg-navy-dark text-white-off font-bold text-sm rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 flex items-center gap-2 border border-transparent">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Ekstrakurikuler</span>
            </a>
        </div>
        
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                        <th class="px-7 py-4 font-bold">Nama Ekstrakurikuler</th>
                        <th class="px-7 py-4 font-bold">Guru Pembina</th>
                        <th class="px-7 py-4 font-bold">Jadwal</th>
                        <th class="px-7 py-4 font-bold text-right">Biaya/Bulan</th>
                        <th class="px-7 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white-off text-sm text-navy-base">
                    <!-- Melakukan perulangan untuk setiap data ekskul yang ada di database -->
                    @forelse($extracurriculars as $ekskul)
                    <tr class="hover:bg-white-off/50 transition-colors group/row">
                        
                        <!-- Nama Ekstrakurikuler & Fotonya -->
                        <td class="px-7 py-4">
                            <div class="flex items-center gap-4">
                                <!-- Jika ekskul ada gambarnya, tampilkan gambarnya -->
                                @if($ekskul->image)
                                    <img src="{{ asset('storage/' . $ekskul->image) }}" class="w-12 h-12 rounded-xl object-cover border border-navy-light/30 shadow-sm group-hover/row:scale-105 transition-transform" alt="{{ $ekskul->name }}">
                                @else
                                    <!-- Jika gak ada gambar, pakai inisial huruf pertama namanya -->
                                    <div class="w-12 h-12 rounded-xl bg-navy-light/10 border border-navy-light/30 flex items-center justify-center text-navy-base font-bold text-lg shadow-sm group-hover/row:scale-105 transition-transform">
                                        {{ substr($ekskul->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-navy-dark text-base block">{{ $ekskul->name }}</div>
                                    <div class="text-[11px] text-gray-muted max-w-[200px] truncate font-semibold mt-0.5" title="{{ $ekskul->description }}">{{ $ekskul->description ?: 'Tidak ada deskripsi' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Guru Pembina -->
                        <td class="px-7 py-4">
                            <div class="font-bold text-navy-dark">
                                {{ $ekskul->teacher->teacherProfile->full_name ?? 'Belum ada pembina' }}
                            </div>
                        </td>
                        
                        <!-- Jadwal -->
                        <td class="px-7 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-navy-light/10 border border-navy-light/30 text-navy-dark px-3 py-1.5 rounded-lg text-[10px] font-bold tracking-widest uppercase shadow-sm">
                                <svg class="w-3.5 h-3.5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $ekskul->schedule }}
                            </span>
                        </td>
                        
                        <!-- Biaya Per Bulan -->
                        <td class="px-7 py-4 text-right font-bold text-navy-dark font-mono text-sm">
                            Rp {{ number_format($ekskul->fee, 0, ',', '.') }}
                        </td>
                        
                        <!-- Tombol Aksi (Edit dan Hapus) -->
                        <td class="px-7 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <!-- Tombol menuju halaman edit -->
                                <a href="{{ route('extracurriculars.edit', $ekskul->id) }}" class="text-gray-muted hover:text-navy-base transition-colors p-2 rounded-xl hover:bg-navy-light/10 border border-transparent hover:border-navy-light/30 block active:scale-95 shadow-sm bg-white" title="Edit Ekstrakurikuler">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>

                                <!-- Tombol pemicu Modal Hapus (menghilangkan class 'hidden' dari modal) -->
                                <button onclick="document.getElementById('deleteModal-{{ $ekskul->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-rose-600 transition-colors p-2 rounded-xl hover:bg-rose-50 border border-transparent hover:border-rose-100 block active:scale-95 shadow-sm bg-white" title="Hapus Ekstrakurikuler">
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
                        <td colspan="5" class="px-7 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-muted">
                                <div class="bg-white-off border border-navy-light/30 p-4 rounded-full mb-4 shadow-inner text-navy-base">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                                </div>
                                <h3 class="text-base font-bold text-navy-dark mb-1 font-heading">Belum ada ekstrakurikuler</h3>
                                <p class="text-sm mb-5 leading-relaxed max-w-md">Silakan tambahkan data ekstrakurikuler baru.</p>
                                <a href="{{ route('extracurriculars.create') }}" class="px-6 py-2.5 bg-white-off border border-navy-light/40 text-navy-dark font-bold text-sm rounded-xl hover:bg-navy-light/10 hover:border-navy-light/60 transition-all shadow-sm active:scale-95">
                                    Tambah Ekstrakurikuler Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Footer tabel & Pagination -->
        @if($extracurriculars->hasPages())
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 mt-auto">
            {{ $extracurriculars->links() }}
        </div>
        @else
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex justify-between items-center text-xs text-gray-muted font-medium mt-auto">
            <span>Total: <b class="text-navy-dark">{{ $extracurriculars->total() }}</b> ekstrakurikuler</span>
        </div>
        @endif
    </div>
@endsection

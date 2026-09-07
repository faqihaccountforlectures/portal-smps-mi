@extends('layouts.app')

@section('title', 'Manajemen Tahun Ajaran')
@section('header', 'Tahun Ajaran')

@section('content')
    
    <!-- FUNGSI KODE: Menampilkan Notifikasi Sukses dengan Animasi Melayang (Fade In Down) -->
    @if(session('success'))
        <div class="bg-white border-l-4 border-navy-base text-navy-dark px-5 py-4 rounded-xl mb-6 shadow-sm shadow-navy-base/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-navy-base p-2 rounded-lg text-white-off">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
        </div>
    @endif

    <!-- FUNGSI KODE: Menampilkan Notifikasi Error (Contoh: Menghapus tahun ajaran yang sedang aktif) -->
    @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-rose-500/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-rose-100 p-2 rounded-lg text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('error') }}</span>
        </div>
    @endif

    <!-- FUNGSI KODE: Layout Utama menggunakan CSS Grid (1 Kolom di Kiri untuk Form, 2 Kolom di Kanan untuk Tabel) -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI: Panel Formulir Tambah Data -->
        <div class="xl:col-span-1">
            <div class="bg-white p-7 rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 relative overflow-hidden group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <div class="flex items-center gap-3 mb-6 border-b border-white-off pb-4">
                    <div class="bg-white-off p-2.5 rounded-xl text-navy-dark group-hover:bg-navy-dark group-hover:text-white-off transition-colors duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Registrasi Tahun Ajaran</h2>
                </div>
                
                <!-- FUNGSI KODE: Form mengarah ke method STORE di AcademicYearController -->
                <form action="{{ route('academic-years.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted mb-1.5 uppercase tracking-wider">Nama Tahun Ajaran</label>
                        <!-- FUNGSI KODE: Name di-set 'year_name' karena pada database kolomnya bernama year_name -->
                        <input type="text" name="year_name" placeholder="Contoh: 2026/2027" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-300 placeholder:text-gray-muted/50 placeholder:font-normal">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted mb-1.5 uppercase tracking-wider">Semester Berjalan</label>
                        <div class="relative">
                            <select name="semester" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl pl-4 pr-10 py-3 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-300 appearance-none">
                                <option value="ganjil">Semester 1 (Ganjil)</option>
                                <option value="genap">Semester 2 (Genap)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy-base">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted mb-1.5 uppercase tracking-wider">Status Aktivasi</label>
                        <div class="relative">
                            <select name="is_active" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl pl-4 pr-10 py-3 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-300 appearance-none">
                                <option value="1">Aktifkan Secara Global</option>
                                <option value="0">Tandai Tidak Aktif</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy-base">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <!-- FUNGSI KODE: Informasi Bantuan Tambahan -->
                        <div class="mt-3 flex items-start gap-2 text-gray-muted bg-white-off p-3 rounded-lg border border-navy-light/30">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-[11px] leading-relaxed">Pengaktifan data ini akan membatalkan status aktif pada entri Tahun Ajaran lainnya secara otomatis.</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-navy-dark text-white-off font-bold text-sm py-3.5 rounded-xl hover:bg-navy-base hover:shadow-lg hover:shadow-navy-base/20 active:scale-[0.98] transition-all duration-300 flex justify-center items-center gap-2 mt-2">
                        <span>Simpan Konfigurasi</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: Panel Tabel Daftar Data -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col h-full group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                
                <!-- FUNGSI KODE: Header Panel Tabel -->
                <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-2 rounded-xl text-navy-base shadow-sm border border-navy-light/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </div>
                        <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Tahun Ajaran</h2>
                    </div>
                    <!-- Menampilkan Total Data Saat Ini -->
                    <span class="text-xs font-bold text-navy-base bg-navy-light/20 px-3 py-1.5 rounded-lg border border-navy-light/40">Total: {{ $academicYears->total() }} Data</span>
                </div>
                
                <!-- FUNGSI KODE: Pembungkus (Wrapper) Tabel -->
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                                <th class="px-7 py-4 font-bold">Periode</th>
                                <th class="px-7 py-4 font-bold text-center">Semester</th>
                                <th class="px-7 py-4 font-bold text-center">Status</th>
                                <th class="px-7 py-4 font-bold text-right">Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white-off text-sm text-navy-base">
                            @forelse($academicYears as $year)
                            <tr class="hover:bg-white-off/50 transition-colors group">
                                <td class="px-7 py-4">
                                    <div class="flex items-center gap-3">
                                        <!-- Titik penanda warna untuk kolom nama -->
                                        <div class="w-2.5 h-2.5 rounded-full {{ $year->is_active ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-gray-muted/40' }}"></div>
                                        <span class="font-bold text-navy-dark text-base">{{ $year->year_name }}</span>
                                    </div>
                                </td>
                                <td class="px-7 py-4">
                                    <div class="flex items-center gap-2.5">
                                        @if($year->semester == 'ganjil')
                                            <span class="w-6 h-6 rounded-lg flex items-center justify-center bg-amber-50 text-amber-600 border border-amber-200/50 text-[11px] font-extrabold shadow-sm">1</span>
                                            <span class="text-xs font-bold text-slate-600">Ganjil</span>
                                        @else
                                            <span class="w-6 h-6 rounded-lg flex items-center justify-center bg-navy-dark/5 text-navy-dark border border-navy-light/50 text-[11px] font-extrabold shadow-sm">2</span>
                                            <span class="text-xs font-bold text-slate-600">Genap</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-7 py-4">
                                    <!-- FUNGSI KODE: Logika percabangan untuk mengubah warna Badge jika Aktif vs Tidak Aktif -->
                                    @if($year->is_active)
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200/50 text-emerald-700 px-3 py-1.5 rounded-lg text-[10px] font-bold tracking-widest uppercase shadow-sm">
                                            <span class="relative flex h-2 w-2">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                            </span>
                                            Status Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-white-off border border-navy-light/50 text-gray-muted px-3 py-1.5 rounded-lg text-[10px] font-bold tracking-widest uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-muted/50"></span>
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-7 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <!-- Tombol Perbarui (Edit) -->
                                        <a href="{{ route('academic-years.edit', $year->id) }}" class="text-gray-muted hover:text-navy-base transition-all p-2 rounded-xl hover:bg-navy-light/30 border border-transparent hover:border-navy-light/50 flex items-center justify-center bg-white" title="Ubah Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        <!-- Tombol Hapus Data -->
                                        <!-- FUNGSI KODE: Trigger untuk menampilkan komponen Modal Delete -->
                                        <button onclick="document.getElementById('deleteModal-{{ $year->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-rose-600 transition-all p-2 rounded-xl hover:bg-rose-50 border border-transparent hover:border-rose-200 flex items-center justify-center bg-white" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Menyisipkan Komponen Modal Hapus --}}
                            @include('admin.academic-years.delete')
                            
                            @empty
                            {{-- FUNGSI KODE: State Kosong (Empty State) yang ditampilkan ketika variabel $academicYears kosong --}}
                            <tr>
                                <td colspan="4" class="px-7 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-muted">
                                        <div class="w-16 h-16 bg-white-off rounded-full flex items-center justify-center mb-4 border border-navy-light/40">
                                            <svg class="w-8 h-8 text-navy-base/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <p class="text-base font-bold text-navy-dark font-heading">Data Masih Kosong</p>
                                        <p class="text-xs mt-1.5 max-w-sm text-center">Sistem mendeteksi belum terdapat catatan Tahun Ajaran. Mohon lengkapi data melalui form registrasi di panel sebelah kiri.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- FUNGSI KODE: Paginasi (Pagination) -->
                @if($academicYears->hasPages())
                <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30">
                    {{ $academicYears->links() }}
                </div>
                @endif
                
            </div>
        </div>

    </div>
@endsection




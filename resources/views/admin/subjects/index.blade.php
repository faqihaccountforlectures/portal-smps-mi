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

    {{-- FUNGSI KODE: Menampilkan notifikasi kesalahan jika proses impor berkas CSV mengalami kendala --}}
    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-xl relative flex flex-col gap-2 shadow-sm" role="alert">
        <div class="flex items-center gap-3">
            <div class="bg-rose-100 p-1.5 rounded-lg shrink-0">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
        @if(session('import_errors'))
        <div class="mt-2 pl-10">
            <p class="text-[11px] font-bold uppercase tracking-wider mb-1 text-rose-800">Rincian Baris yang Perlu Diperbaiki:</p>
            <ul class="list-disc list-inside text-xs space-y-1 text-rose-700 max-h-48 overflow-y-auto thin-scrollbar bg-white/60 p-3 rounded-lg border border-rose-200">
                @foreach(session('import_errors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

    {{-- 
      Layout full-width (lebar penuh) biar keliatan lebih luas dan clean.
      Cocok buat daftar mata pelajaran yang datanya biasanya panjang-panjang.
    --}}
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        
        {{-- Header Tabel, Form Pencarian, Tombol Impor & Tombol Tambah Data --}}
        <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Mata Pelajaran</h2>
                <p class="text-xs text-gray-muted mt-1">Kelola data mata pelajaran wajib dan muatan lokal di sini.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <!-- Form Pencarian Mata Pelajaran -->
                <form action="{{ route('subjects.index') }}" method="GET" class="w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-navy-base/60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari kode / mata pelajaran..." class="w-full bg-white border border-navy-light/40 text-navy-dark font-semibold text-xs rounded-xl pl-9 pr-8 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all placeholder:text-gray-muted/60 shadow-sm">
                        @if(!empty($search))
                            <a href="{{ route('subjects.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-muted hover:text-rose-500 transition-colors" title="Hapus pencarian">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Tombol Impor Excel / CSV --}}
                <button type="button" onclick="openSubjectImportModal()" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-navy-light/50 text-navy-dark hover:text-navy-base hover:bg-navy-light/10 font-bold text-sm rounded-xl shadow-sm hover:shadow active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Impor Excel / CSV</span>
                </button>

                {{-- Tombol Tambah Data ditaruh di pojok kanan atas tabel --}}
                <a href="{{ route('subjects.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-navy-dark text-white-off font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-navy-base hover:shadow-lg hover:shadow-navy-base/20 active:scale-[0.98] transition-all duration-300 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Mata Pelajaran</span>
                </a>
            </div>
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
                                @if(!empty($search))
                                    <p class="text-base font-bold text-navy-dark font-heading mb-1">Mata pelajaran tidak ditemukan</p>
                                    <p class="text-xs mt-1 text-center text-gray-muted mb-4">Tidak ada data yang cocok dengan kata kunci "<b>{{ $search }}</b>".</p>
                                    <a href="{{ route('subjects.index') }}" class="text-xs text-navy-base font-bold bg-navy-light/20 px-4 py-2 rounded-lg border border-navy-light/40 hover:bg-navy-light/40 transition-colors">
                                        Reset Pencarian
                                    </a>
                                @else
                                    <p class="text-base font-bold text-navy-dark font-heading mb-1">Belum ada mata pelajaran</p>
                                    <p class="text-xs mt-1.5 text-center text-gray-muted mb-5">Yuk, mulai tambahkan data mata pelajaran pertama Anda.</p>
                                    <a href="{{ route('subjects.create') }}" class="text-xs text-navy-base font-bold bg-navy-light/20 px-4 py-2 rounded-lg border border-navy-light/40 hover:bg-navy-light/40 transition-colors">
                                        + Tambah Sekarang
                                    </a>
                                @endif
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
            <span>Total: <b class="text-navy-dark">{{ $subjects->total() }}</b> mata pelajaran {{ !empty($search) ? "ditemukan" : "" }}</span>
        </div>
        @endif
    </div>

    {{-- FUNGSI KODE: Modal Pop-up untuk Mengunggah Berkas CSV / Excel Data Mata Pelajaran --}}
    <div id="subjectImportModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-navy-dark/60 backdrop-blur-sm p-4 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-navy-light/30 max-w-lg w-full overflow-hidden transform transition-all">
            <!-- Header Modal -->
            <div class="px-6 py-5 border-b border-navy-light/20 flex justify-between items-center bg-white-off/40">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-dark font-heading text-base">Impor Data Mata Pelajaran</h3>
                        <p class="text-[11px] text-gray-muted font-medium mt-0.5">Unggah berkas CSV/Excel untuk menambahkan banyak mata pelajaran sekaligus.</p>
                    </div>
                </div>
                <button type="button" onclick="closeSubjectImportModal()" class="text-gray-muted hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Isi Modal & Formulir -->
            <form action="{{ route('subjects.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                <!-- Panduan & Tombol Unduh Template -->
                <div class="bg-navy-light/10 border border-navy-light/30 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-navy-dark">Gunakan Template Resmi</p>
                        <p class="text-[11px] text-gray-muted mt-0.5">Unduh template berformat CSV yang dapat langsung diedit di Microsoft Excel.</p>
                    </div>
                    <a href="{{ route('subjects.template') }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm flex items-center gap-1.5 shrink-0 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Template
                    </a>
                </div>

                <!-- Input File CSV -->
                <div class="space-y-1.5">
                    <label for="subject_csv_file" class="block text-xs font-bold text-navy-dark">Pilih Berkas CSV (.csv)</label>
                    <input type="file" id="subject_csv_file" name="file" accept=".csv,text/csv,text/plain" required class="w-full text-xs text-navy-dark border border-navy-light/40 rounded-xl file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-bold file:bg-navy-dark file:text-white-off hover:file:bg-navy-base cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-base/20">
                    <p class="text-[10px] text-gray-muted">Ukuran berkas maksimal 5 MB. Pastikan kolom Kode, Nama Mata Pelajaran, Tingkat Kelas, dan KKM terisi.</p>
                </div>

                <!-- Petunjuk Format Pengisian -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2.5 text-amber-800">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="text-[11px] leading-relaxed space-y-1.5">
                        <p class="font-bold">Panduan Format Pengisian Data:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li><b>Kode:</b> Gabungan kode huruf mapel dan tingkat kelas. Contoh:
                                <div class="grid grid-cols-2 gap-1 mt-1 pl-4 text-[10px] text-amber-900 font-mono">
                                    <span>• A-7 : PAI BP Kelas 7</span>
                                    <span>• B-8 : PPKN Kelas 8</span>
                                    <span>• C-7 : B. Indonesia Kelas 7</span>
                                    <span>• D-7 : Matematika Kelas 7</span>
                                    <span>• E-8 : IPA Kelas 8</span>
                                    <span>• F-9 : B. Inggris Kelas 9</span>
                                    <span>• I-9 : B. Sunda Kelas 9</span>
                                    <span>• O-7 : KKA & Robotik Kelas 7</span>
                                </div>
                            </li>
                            <li><b>Tingkat Kelas:</b> Angka kelas (<code class="bg-amber-100 px-1 rounded font-mono">7</code>, <code class="bg-amber-100 px-1 rounded font-mono">8</code>, <code class="bg-amber-100 px-1 rounded font-mono">9</code>) atau kosongkan untuk umum.</li>
                            <li><b>KKM:</b> Angka ketuntasan minimal (contoh: <code class="bg-amber-100 px-1 rounded font-mono">75.00</code>).</li>
                            <li><b>Kategori:</b> Isi dengan <code class="bg-amber-100 px-1 rounded font-mono">Wajib</code> atau <code class="bg-amber-100 px-1 rounded font-mono">Muatan Lokal</code>.</li>
                        </ul>
                    </div>
                </div>

                <!-- Tombol Aksi Modal -->
                <div class="pt-3 border-t border-navy-light/20 flex justify-end gap-2.5">
                    <button type="button" onclick="closeSubjectImportModal()" class="px-4 py-2 text-xs font-bold text-gray-muted hover:text-navy-dark rounded-xl hover:bg-navy-light/10 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-navy-dark text-white-off hover:bg-navy-base text-xs font-bold rounded-xl shadow-sm hover:shadow active:scale-95 transition-all">
                        Mulai Impor Mapel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- FUNGSI KODE: Skrip kendali interaksi buka/tutup modal impor data mata pelajaran --}}
    <script>
        function openSubjectImportModal() {
            const modal = document.getElementById('subjectImportModal');
            modal.classList.remove('hidden');
        }

        function closeSubjectImportModal() {
            const modal = document.getElementById('subjectImportModal');
            modal.classList.add('hidden');
        }

        // Menutup modal jika pengguna menekan tombol Escape (ESC) pada keyboard
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSubjectImportModal();
            }
        });
    </script>
@endsection





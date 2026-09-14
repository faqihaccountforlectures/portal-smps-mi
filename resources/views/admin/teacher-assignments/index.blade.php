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
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-xl relative flex flex-col gap-2 shadow-sm animate-[fade-in-down_0.5s_ease-out]" role="alert">
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

                {{-- Tombol Impor Excel / CSV --}}
                <button type="button" onclick="openTeacherAssignmentImportModal()" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-navy-light/50 text-navy-dark hover:text-navy-base hover:bg-navy-light/10 font-bold text-xs rounded-xl shadow-sm hover:shadow active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Impor Excel / CSV</span>
                </button>

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

    {{-- FUNGSI KODE: Modal Pop-up untuk Mengunggah Berkas CSV / Excel Data Penugasan Guru --}}
    <div id="teacherAssignmentImportModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-navy-dark/60 backdrop-blur-sm p-4 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-navy-light/30 max-w-lg w-full overflow-hidden transform transition-all">
            <!-- Header Modal -->
            <div class="px-6 py-5 border-b border-navy-light/20 flex justify-between items-center bg-white-off/40">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-dark font-heading text-base">Impor Penugasan Guru</h3>
                        <p class="text-[11px] text-gray-muted font-medium mt-0.5">Unggah berkas CSV untuk membagi tugas mengajar guru ke setiap kelas secara massal.</p>
                    </div>
                </div>
                <button type="button" onclick="closeTeacherAssignmentImportModal()" class="text-gray-muted hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Isi Modal & Formulir -->
            <form action="{{ route('teacher-assignments.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                <!-- Panduan & Tombol Unduh Template -->
                <div class="bg-navy-light/10 border border-navy-light/30 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-navy-dark">Gunakan Template Resmi</p>
                        <p class="text-[11px] text-gray-muted mt-0.5">Unduh template berformat CSV untuk mengisi daftar penugasan guru.</p>
                    </div>
                    <a href="{{ route('teacher-assignments.template') }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm flex items-center gap-1.5 shrink-0 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Template
                    </a>
                </div>

                <!-- Input File CSV -->
                <div class="space-y-1.5">
                    <label for="assignment_csv_file" class="block text-xs font-bold text-navy-dark">Pilih Berkas CSV (.csv)</label>
                    <input type="file" id="assignment_csv_file" name="file" accept=".csv,text/csv,text/plain" required class="w-full text-xs text-navy-dark border border-navy-light/40 rounded-xl file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-bold file:bg-navy-dark file:text-white-off hover:file:bg-navy-base cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-base/20">
                    <p class="text-[10px] text-gray-muted">Ukuran berkas maksimal 5 MB. Pastikan kolom Kode Guru, Kode Mapel, dan Kelas terisi.</p>
                </div>

                <!-- Petunjuk Format Pengisian -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2.5 text-amber-800">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="text-[11px] leading-relaxed space-y-1.5">
                        <p class="font-bold">Panduan Format Kolom CSV:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li><b>Kode Guru:</b> Nomor kode guru pada dokumen (contoh: <code class="bg-amber-100 px-1 rounded font-mono">1</code>, <code class="bg-amber-100 px-1 rounded font-mono">2</code>, atau <code class="bg-amber-100 px-1 rounded font-mono">19</code> untuk Kokurikuler, <code class="bg-amber-100 px-1 rounded font-mono">20</code> untuk Pembiasaan).</li>
                            <li><b>Kode Mata Pelajaran:</b> Kode mapel di sistem (contoh: <code class="bg-amber-100 px-1 rounded font-mono">F-7</code>, <code class="bg-amber-100 px-1 rounded font-mono">B-8</code>, <code class="bg-amber-100 px-1 rounded font-mono">S</code>, <code class="bg-amber-100 px-1 rounded font-mono">U</code>).</li>
                            <li><b>Kelas:</b> Nama ruang kelas tujuan (contoh: <code class="bg-amber-100 px-1 rounded font-mono">VII A</code>, <code class="bg-amber-100 px-1 rounded font-mono">VIII B</code>).</li>
                        </ul>
                    </div>
                </div>

                <!-- Tombol Aksi Modal -->
                <div class="pt-3 border-t border-navy-light/20 flex justify-end gap-2.5">
                    <button type="button" onclick="closeTeacherAssignmentImportModal()" class="px-4 py-2 text-xs font-bold text-gray-muted hover:text-navy-dark rounded-xl hover:bg-navy-light/10 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-navy-dark text-white-off hover:bg-navy-base text-xs font-bold rounded-xl shadow-sm hover:shadow active:scale-95 transition-all">
                        Mulai Impor Penugasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- FUNGSI KODE: Skrip kendali interaksi buka/tutup modal impor penugasan guru --}}
    <script>
        function openTeacherAssignmentImportModal() {
            const modal = document.getElementById('teacherAssignmentImportModal');
            modal.classList.remove('hidden');
        }

        function closeTeacherAssignmentImportModal() {
            const modal = document.getElementById('teacherAssignmentImportModal');
            modal.classList.add('hidden');
        }

        // Menutup modal jika pengguna menekan tombol Escape (ESC) pada keyboard
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeTeacherAssignmentImportModal();
            }
        });
    </script>
@endsection





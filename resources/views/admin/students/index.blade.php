@extends('layouts.app')

@section('title', 'Data Siswa')
@section('header', 'Data Siswa')

@section('content')
    
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl relative flex items-center gap-3 shadow-sm" role="alert">
        <div class="bg-emerald-100 p-1.5 rounded-lg">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
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

    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        <!-- Header Tabel & Form Filter -->
        <div class="px-7 py-5 border-b border-navy-light/30 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white-off/30">
            <div class="flex items-center gap-3">
                <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-navy-dark font-heading tracking-wide text-base">Daftar Peserta Didik</h2>
                    <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-bold">Kelola biodata dan akses akun belajar siswa di sini.</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <!-- Form Pencarian -->
                <form action="{{ route('students.index') }}" method="GET" class="w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-navy-base/60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / NISN..." class="w-full bg-white border border-navy-light/40 text-navy-dark font-semibold text-xs rounded-xl pl-9 pr-4 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all placeholder:text-gray-muted/60 shadow-sm">
                    </div>
                </form>

                {{-- FUNGSI KODE: Tombol pemicu modal pop-up untuk mengimpor berkas Excel / CSV siswa --}}
                <button type="button" onclick="openStudentImportModal()" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-navy-light/50 text-navy-dark hover:text-navy-base hover:bg-navy-light/10 font-bold text-sm rounded-xl shadow-sm hover:shadow active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Impor Excel / CSV
                </button>

                <a href="{{ route('students.create') }}" class="w-full sm:w-auto px-5 py-2.5 bg-navy-dark text-white-off font-bold text-sm rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 border border-transparent whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Siswa
                </a>
            </div>
        </div>
        
        <!-- Tabel Data -->
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                        <th class="px-7 py-4 font-bold w-16 text-center">No</th>
                        <th class="px-7 py-4 font-bold">Profil Siswa</th>
                        <th class="px-7 py-4 font-bold">NISN</th>
                        <th class="px-7 py-4 font-bold">No. Orang Tua</th>
                        <th class="px-7 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white-off text-navy-base">
                    @forelse($students as $index => $student)
                    <tr class="hover:bg-white-off/50 transition-colors group/row">
                        <td class="px-7 py-4 text-center text-gray-muted font-bold font-mono">
                            {{ $students->firstItem() + $index }}
                        </td>
                        <td class="px-7 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-navy-light/20 text-navy-dark flex items-center justify-center font-bold text-sm border border-navy-light/40 shrink-0">
                                    {{ substr($student->studentProfile->full_name ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-navy-dark group-hover/row:text-navy-base transition-colors">{{ $student->studentProfile->full_name ?? 'Belum ada nama' }}</p>
                                    <p class="text-[11px] text-gray-muted font-semibold flex items-center gap-1.5 mt-0.5 tracking-wide">
                                        <svg class="w-3 h-3 text-navy-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $student->email }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-7 py-4 font-mono font-medium text-sm">
                            @if($student->studentProfile && $student->studentProfile->nisn)
                                {{ $student->studentProfile->nisn }}
                            @else
                                <span class="text-gray-muted italic text-xs">Belum diisi</span>
                            @endif
                        </td>
                        <td class="px-7 py-4 font-mono font-medium text-sm">
                            @if($student->studentProfile && $student->studentProfile->parent_phone)
                                {{ $student->studentProfile->parent_phone }}
                            @else
                                <span class="text-gray-muted italic text-xs">Belum diisi</span>
                            @endif
                        </td>
                        <td class="px-7 py-4">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('students.edit', $student->id) }}" class="text-gray-muted hover:text-navy-base hover:bg-navy-light/10 p-2 rounded-xl transition-all duration-200" title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                <button type="button" onclick="document.getElementById('deleteModal-{{ $student->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-rose-500 hover:bg-rose-50 p-2 rounded-xl transition-all duration-200" title="Hapus Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @include('admin.students.delete')
                    @empty
                    <tr>
                        <td colspan="5" class="px-7 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-navy-light/10 p-4 rounded-2xl mb-4 border border-navy-light/30">
                                    <svg class="w-10 h-10 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-navy-dark font-heading mb-1">Belum Ada Data Siswa</h3>
                                <p class="text-[13px] text-gray-muted mb-5">Belum ada data peserta didik yang cocok dengan pencarian Anda.</p>
                                <a href="{{ route('students.create') }}" class="px-5 py-2.5 bg-white-off text-navy-dark font-bold text-sm rounded-xl hover:bg-navy-light/20 transition-colors shadow-sm border border-navy-light/30">
                                    Tambah Siswa Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Tabel & Paginasi -->
        @if($students->hasPages())
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-center gap-4 mt-auto">
            <span class="text-xs text-gray-muted font-medium">
                Menampilkan <b class="text-navy-dark">{{ $students->firstItem() }}</b> - <b class="text-navy-dark">{{ $students->lastItem() }}</b> dari <b class="text-navy-dark">{{ $students->total() }}</b> siswa
            </span>
            <div class="pagination-wrapper">
                {{ $students->appends(request()->all())->links() }}
            </div>
        </div>
        @else
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex justify-between items-center text-xs text-gray-muted font-medium mt-auto">
            <span>Total Siswa: <b class="text-navy-dark">{{ $students->total() }}</b> data</span>
        </div>
        @endif
    </div>

    {{-- FUNGSI KODE: Modal Pop-up untuk Mengunggah Berkas CSV / Excel Data Siswa --}}
    <div id="studentImportModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-navy-dark/60 backdrop-blur-sm p-4 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-navy-light/30 max-w-lg w-full overflow-hidden transform transition-all">
            <!-- Header Modal -->
            <div class="px-6 py-5 border-b border-navy-light/20 flex justify-between items-center bg-white-off/40">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-dark font-heading text-base">Impor Data Siswa</h3>
                        <p class="text-[11px] text-gray-muted font-medium mt-0.5">Unggah berkas CSV/Excel untuk menambahkan banyak siswa sekaligus.</p>
                    </div>
                </div>
                <button type="button" onclick="closeStudentImportModal()" class="text-gray-muted hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Isi Modal & Formulir -->
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                <!-- Panduan & Tombol Unduh Template -->
                <div class="bg-navy-light/10 border border-navy-light/30 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-navy-dark">Gunakan Template Resmi</p>
                        <p class="text-[11px] text-gray-muted mt-0.5">Unduh template berformat CSV yang dapat langsung diedit di Microsoft Excel.</p>
                    </div>
                    <a href="{{ route('students.template') }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm flex items-center gap-1.5 shrink-0 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Template
                    </a>
                </div>

                <!-- Input File CSV -->
                <div class="space-y-1.5">
                    <label for="student_csv_file" class="block text-xs font-bold text-navy-dark">Pilih Berkas CSV (.csv)</label>
                    <input type="file" id="student_csv_file" name="file" accept=".csv,text/csv,text/plain" required class="w-full text-xs text-navy-dark border border-navy-light/40 rounded-xl file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-bold file:bg-navy-dark file:text-white-off hover:file:bg-navy-base cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-base/20">
                    <p class="text-[10px] text-gray-muted">Ukuran berkas maksimal 5 MB. Kolom nama, NISN, email, dan jenis kelamin wajib diisi. Kolom <b>Kelas (contoh: 7A)</b> bersifat opsional jika ingin langsung mendaftarkan siswa ke rombel kelas.</p>
                </div>

                <!-- Petunjuk Pencegahan Format Ilmiah Excel -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2.5 text-amber-800">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-[11px] leading-relaxed">
                        <b>Tips Microsoft Excel:</b> Agar angka <b>NISN</b> atau <b>No. HP</b> tidak otomatis berubah menjadi singkatan ilmiah (seperti <i>1.98E+17</i>) dan angka <i>0</i> di awal tidak hilang, awali angka dengan tanda petik satu (contoh: <code class="bg-amber-100 px-1 py-0.5 rounded text-amber-900 font-mono">'0081234567</code> atau <code class="bg-amber-100 px-1 py-0.5 rounded text-amber-900 font-mono">'081234567890</code>). Tanda petik akan dibersihkan otomatis oleh sistem.
                    </p>
                </div>

                <!-- Tombol Aksi Modal -->
                <div class="pt-3 border-t border-navy-light/20 flex justify-end gap-2.5">
                    <button type="button" onclick="closeStudentImportModal()" class="px-4 py-2 text-xs font-bold text-gray-muted hover:text-navy-dark rounded-xl hover:bg-navy-light/10 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-navy-dark text-white-off hover:bg-navy-base text-xs font-bold rounded-xl shadow-sm hover:shadow active:scale-95 transition-all">
                        Mulai Impor Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- FUNGSI KODE: Skrip kendali interaksi buka/tutup modal impor data siswa --}}
    <script>
        function openStudentImportModal() {
            const modal = document.getElementById('studentImportModal');
            modal.classList.remove('hidden');
        }

        function closeStudentImportModal() {
            const modal = document.getElementById('studentImportModal');
            modal.classList.add('hidden');
        }

        // Menutup modal jika pengguna menekan tombol Escape (ESC) pada keyboard
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeStudentImportModal();
            }
        });
    </script>
@endsection

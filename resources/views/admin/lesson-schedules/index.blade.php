@extends('layouts.app')

@section('title', 'Manajemen Jadwal Pelajaran')
@section('header', 'Manajemen Jadwal Pelajaran')

@section('content')
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
                <p class="text-[11px] font-bold uppercase tracking-wider mb-1 text-rose-800">Rincian Sel/Baris yang Perlu Diperbaiki:</p>
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
        
        <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Jadwal Pelajaran</h2>
                <p class="text-xs text-gray-muted mt-1">Tahun Ajaran Aktif: <b class="text-navy-base">{{ $activeYear->year_name ?? '-' }}</b> ({{ ucfirst($activeYear->semester ?? '') }})</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                {{-- Tombol Impor Matriks Jadwal Excel / CSV --}}
                <button type="button" onclick="openLessonScheduleImportModal()" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-navy-light/50 text-navy-dark hover:text-navy-base hover:bg-navy-light/10 font-bold text-xs rounded-xl shadow-sm hover:shadow active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Impor Excel / CSV Matriks</span>
                </button>

                <a href="{{ route('lesson-schedules.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-navy-dark text-white-off font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-navy-base hover:shadow-lg hover:shadow-navy-base/20 active:scale-[0.98] transition-all duration-300 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Atur Jadwal Baru</span>
                </a>
            </div>
        </div>
        
        <div class="p-7 border-b border-navy-light/30 bg-white-off/50">
            <form action="{{ route('lesson-schedules.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-64">
                    <label class="block text-sm font-bold text-navy-dark mb-1.5">Pilih Kelas</label>
                    <select name="class_room_id" onchange="this.form.submit()" class="w-full bg-white-off border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all">
                        <option value="" disabled {{ !$selectedClassId ? 'selected' : '' }}>-- Silakan Pilih Kelas --</option>
                        @foreach($classRooms as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @if($selectedClassId)
                <div class="w-full sm:w-64">
                    <label class="block text-sm font-bold text-navy-dark mb-1.5">Filter Hari</label>
                    <select name="day_filter" onchange="this.form.submit()" class="w-full bg-white-off border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all">
                        <option value="">Semua Hari</option>
                        @foreach($days as $day)
                            <option value="{{ $day }}" {{ request('day_filter') == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
        </div>

        @if($selectedClassId)
            <div class="bg-white-off/30">
                @if($schedules->isEmpty())
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-white-off rounded-full flex items-center justify-center text-gray-muted mx-auto mb-4 border border-navy-light/40 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-gray-muted">Belum ada jadwal pelajaran untuk kelas ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                                    <th class="px-7 py-4 font-bold">Hari</th>
                                    <th class="px-7 py-4 font-bold">Jam Pelajaran</th>
                                    <th class="px-7 py-4 font-bold">Mata Pelajaran</th>
                                    <th class="px-7 py-4 font-bold">Guru Pengajar</th>
                                    <th class="px-7 py-4 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white-off text-sm text-navy-base">
                                @foreach($days as $day)
                                    @if(isset($schedules[$day]) && count($schedules[$day]) > 0)
                                        @foreach($schedules[$day] as $schedule)
                                        <tr class="hover:bg-white-off/50 transition-colors group">
                                            {{-- Kolom Hari --}}
                                            <td class="px-7 py-4 font-bold text-navy-dark uppercase tracking-wider text-xs">
                                                {{ $day }}
                                            </td>
                                            
                                            {{-- Kolom Jam Pelajaran --}}
                                            <td class="px-7 py-4 font-mono font-bold text-navy-base text-xs">
                                                <div class="inline-flex items-center gap-1.5 bg-navy-light/10 px-3 py-1.5 rounded-lg border border-navy-light/30">
                                                    <span>{{ substr($schedule->start_time, 0, 5) }}</span>
                                                    <span class="text-[10px] text-gray-muted">S/D</span>
                                                    <span>{{ substr($schedule->end_time, 0, 5) }}</span>
                                                </div>
                                            </td>
                                            
                                            {{-- Kolom Mapel --}}
                                            <td class="px-7 py-4 font-bold text-navy-dark">
                                                {{ $schedule->teacherAssignment->subject->name }}
                                            </td>
                                            
                                            {{-- Kolom Guru --}}
                                            <td class="px-7 py-4 font-medium text-gray-muted">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-navy-light/20 flex items-center justify-center text-navy-base border border-navy-light/40">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                    </div>
                                                    {{-- FUNGSI KODE: Jika mapel Pembiasaan (U) atau Kokurikuler (S), tampilkan 'Semua Guru' --}}
                                                    @if(in_array($schedule->teacherAssignment->subject->code ?? '', ['S', 'U']))
                                                        <span class="font-bold text-navy-base">Semua Guru</span>
                                                    @else
                                                        {{ $schedule->teacherAssignment->teacher->teacherProfile->full_name ?? 'Guru' }}
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            {{-- Kolom Aksi --}}
                                            <td class="px-7 py-4 text-right">
                                                <div class="flex justify-end items-center gap-2">
                                                    <a href="{{ route('lesson-schedules.edit', $schedule->id) }}" class="text-gray-muted hover:text-navy-base transition-all p-2 rounded-xl hover:bg-navy-light/30 border border-transparent hover:border-navy-light/50 bg-white" title="Edit Jadwal">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </a>
                                                    <button onclick="document.getElementById('deleteModal-{{ $schedule->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-rose-600 transition-all p-2 rounded-xl hover:bg-rose-50 border border-transparent hover:border-rose-200 bg-white" title="Hapus Jadwal">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @include('admin.lesson-schedules.delete')
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-16 bg-white-off/30">
                <p class="text-gray-muted font-bold text-sm">Silakan pilih kelas terlebih dahulu untuk melihat jadwal pelajaran.</p>
            </div>
        @endif
    </div>

    {{-- FUNGSI KODE: Modal Pop-up untuk Mengunggah Berkas Matriks CSV Jadwal Pelajaran --}}
    <div id="lessonScheduleImportModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-navy-dark/60 backdrop-blur-sm p-4 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl border border-navy-light/30 max-w-xl w-full overflow-hidden transform transition-all">
            <!-- Header Modal -->
            <div class="px-6 py-5 border-b border-navy-light/20 flex justify-between items-center bg-white-off/40">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-dark font-heading text-base">Impor Matriks Jadwal Pelajaran</h3>
                        <p class="text-[11px] text-gray-muted font-medium mt-0.5">Unggah berkas jadwal mingguan dengan format tabel matriks resmi sekolah.</p>
                    </div>
                </div>
                <button type="button" onclick="closeLessonScheduleImportModal()" class="text-gray-muted hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Isi Modal & Formulir -->
            <form action="{{ route('lesson-schedules.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                <!-- Panduan & Tombol Unduh Template -->
                <div class="bg-navy-light/10 border border-navy-light/30 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-navy-dark">Template Matriks Resmi (Sesuai Cetak)</p>
                        <p class="text-[11px] text-gray-muted mt-0.5">Kolom HARI, JAM_KE, WAKTU_MULAI, WAKTU_SELESAI, serta kolom kelas (VII A s/d IX B).</p>
                    </div>
                    <a href="{{ route('lesson-schedules.template') }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm flex items-center gap-1.5 shrink-0 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Template
                    </a>
                </div>

                <!-- Input File CSV -->
                <div class="space-y-1.5">
                    <label for="schedule_csv_file" class="block text-xs font-bold text-navy-dark">Pilih Berkas CSV Matriks (.csv)</label>
                    <input type="file" id="schedule_csv_file" name="file" accept=".csv,text/csv,text/plain" required class="w-full text-xs text-navy-dark border border-navy-light/40 rounded-xl file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-bold file:bg-navy-dark file:text-white-off hover:file:bg-navy-base cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-base/20">
                    <p class="text-[10px] text-gray-muted">Ukuran berkas maksimal 5 MB. Pastikan sel-sel terisi kode kombinasi seperti lembar fisik jadwal.</p>
                </div>

                <!-- Petunjuk Format Pengisian -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex items-start gap-2.5 text-amber-900">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="text-[11px] leading-relaxed space-y-1.5">
                        <p class="font-bold">Fitur Pintar & Aturan Penulisan Kode Sel:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li><b>Format Sel:</b> Gabungan [Huruf Mapel] + [Nomor Kode Guru]. Contoh:
                                <div class="grid grid-cols-2 gap-1 mt-1 pl-4 text-[10px] text-amber-950 font-mono">
                                    <span>• F2 : B. Inggris (F) oleh Guru 2</span>
                                    <span>• B8 : PPKN (B) oleh Guru 8</span>
                                    <span>• M11 : Akidah Akhlak (M) oleh Guru 11</span>
                                    <span>• O13 : KKA & Robotik (O) oleh Guru 13</span>
                                    <span>• S19 : Kokurikuler (Semua Guru)</span>
                                    <span>• U20 : Pembiasaan (Semua Guru)</span>
                                </div>
                            </li>
                            <li><b>Smart Auto-Assignment:</b> Jika penugasan guru belum terdaftar di sistem, sistem <b>otomatis membuatnya</b> saat impor diproses.</li>
                            <li><b>Baris Istirahat:</b> Baris dengan keterangan <code class="bg-amber-100 px-1 rounded font-mono">ISTIRAHAT</code> / sel kosong otomatis dilewati.</li>
                        </ul>
                    </div>
                </div>

                <!-- Tombol Aksi Modal -->
                <div class="pt-3 border-t border-navy-light/20 flex justify-end gap-2.5">
                    <button type="button" onclick="closeLessonScheduleImportModal()" class="px-4 py-2 text-xs font-bold text-gray-muted hover:text-navy-dark rounded-xl hover:bg-navy-light/10 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-navy-dark text-white-off hover:bg-navy-base text-xs font-bold rounded-xl shadow-sm hover:shadow active:scale-95 transition-all">
                        Mulai Impor Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- FUNGSI KODE: Skrip kendali interaksi buka/tutup modal impor jadwal pelajaran matriks --}}
    <script>
        function openLessonScheduleImportModal() {
            const modal = document.getElementById('lessonScheduleImportModal');
            modal.classList.remove('hidden');
        }

        function closeLessonScheduleImportModal() {
            const modal = document.getElementById('lessonScheduleImportModal');
            modal.classList.add('hidden');
        }

        // Menutup modal jika pengguna menekan tombol Escape (ESC) pada keyboard
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeLessonScheduleImportModal();
            }
        });
    </script>
@endsection


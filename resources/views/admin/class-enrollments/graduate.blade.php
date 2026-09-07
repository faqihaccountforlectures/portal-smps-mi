@extends('layouts.app')

@section('title', 'Kelulusan Kelas 9')
@section('header', 'Kelulusan Kelas 9')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('class-enrollments.index') }}" class="inline-flex items-center gap-2 text-gray-muted hover:text-navy-base transition-colors font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="text-sm">Kembali ke Pembagian Kelas</span>
        </a>
    </div>

    <!-- Informasi Fitur -->
    <div class="bg-yellow-50/50 border border-yellow-200/50 rounded-2xl p-5 mb-6 flex items-start gap-4 shadow-sm">
        <div class="bg-white p-2.5 rounded-xl text-yellow-600 border border-yellow-100 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
        </div>
        <div>
            <h3 class="text-yellow-800 font-bold text-lg mb-1 font-heading">Manajemen Kelulusan (Alumni) & Tinggal Kelas</h3>
            <p class="text-yellow-700 text-sm leading-relaxed">Gunakan fitur ini khusus untuk menangani siswa Kelas 9. Siswa yang diluluskan akan diubah statusnya menjadi <strong>Alumni (Lulus)</strong>. Siswa yang gagal (tinggal kelas) akan dimasukkan kembali ke <strong>Kelas 9 Tujuan</strong> di Tahun Ajaran Aktif ({{ $activeYear->semester }} {{ $activeYear->year_name }}).</p>
        </div>
    </div>

    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl relative flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="block sm:inline font-bold">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Formulir Filter Pencarian Kelas Asal -->
    {{-- FUNGSI KODE: Form Filter Kelas 9 Asal --}}
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden mb-8 group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        <div class="px-7 py-4 border-b border-navy-light/30 bg-white-off/30">
            <h3 class="font-bold text-navy-dark font-heading tracking-wide">1. Pilih Kelas 9 Asal (Tahun Sebelumnya)</h3>
        </div>
        <div class="p-7">
            <form action="{{ route('class-enrollments.graduate') }}" method="GET" class="flex flex-col md:flex-row gap-5 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-bold text-navy-dark mb-2 tracking-wide">Tahun Ajaran Asal</label>
                    <select name="source_year_id" class="w-full border border-navy-light/40 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base bg-white-off/30 font-medium text-navy-dark transition-all" required>
                        <option value="">-- Pilih Tahun Ajaran Sebelumnya --</option>
                        @foreach($previousYears as $year)
                            <option value="{{ $year->id }}" {{ $sourceYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->semester }} {{ $year->year_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1 w-full">
                    <label class="block text-sm font-bold text-navy-dark mb-2 tracking-wide">Kelas Asal (Tingkat 9)</label>
                    <select name="source_class_id" class="w-full border border-navy-light/40 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base bg-white-off/30 font-medium text-navy-dark transition-all" required>
                        <option value="">-- Pilih Kelas 9 Asal --</option>
                        @foreach($sourceClasses as $cls)
                            <option value="{{ $cls->id }}" {{ $sourceClassId == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }} (Tingkat {{ $cls->grade_level }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Simpan state Kelas Tujuan agar tidak hilang saat reload -->
                @if($destinationClassId)
                    <input type="hidden" name="destination_class_id" value="{{ $destinationClassId }}">
                @endif
                
                <div class="w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto bg-navy-dark hover:bg-navy-base text-white-off px-6 py-2.5 rounded-xl font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-md shadow-navy-base/20 active:scale-95 border border-transparent">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Tampilkan Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tampilan Daftar Siswa (Hanya muncul jika sudah difilter) -->
    @if($sourceYearId && $sourceClassId)
        <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <h3 class="font-bold text-navy-dark font-heading tracking-wide">2. Proses Kelulusan Siswa</h3>
                <span class="bg-navy-light/10 text-navy-dark border border-navy-light/30 text-[10px] font-bold px-3 py-1.5 rounded-lg tracking-widest uppercase shadow-sm">{{ count($students) }} Siswa Aktif Ditemukan</span>
            </div>

            @if(count($students) > 0)
                <form action="{{ route('class-enrollments.store-graduation') }}" method="POST">
                    @csrf
                    
                    <div class="p-7 bg-white-off/50 border-b border-navy-light/30">
                        <label class="block text-sm font-bold text-navy-dark mb-2 tracking-wide">Kelas Tujuan (Untuk Siswa Tinggal Kelas)</label>
                        <p class="text-xs text-gray-muted mb-4">Jika ada siswa yang <strong class="text-navy-base">tidak dicentang (tinggal kelas)</strong>, mereka akan dimasukkan ke kelas ini pada tahun ajaran aktif saat ini.</p>
                        <select name="destination_class_id" class="w-full md:w-1/2 border border-navy-light/40 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base bg-white font-medium text-navy-dark transition-all" required>
                            <option value="">-- Pilih Kelas 9 Tujuan (Tahun Aktif) --</option>
                            @foreach($destinationClasses as $cls)
                                <option value="{{ $cls->id }}" {{ $destinationClassId == $cls->id ? 'selected' : '' }}>
                                    {{ $cls->name }} (Tingkat {{ $cls->grade_level }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-6 bg-emerald-50/50 text-sm text-emerald-800 border-b border-emerald-100 flex gap-3 items-start">
                        <div class="mt-0.5">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="leading-relaxed">
                            <strong>Petunjuk:</strong> Secara bawaan, seluruh siswa akan tercentang (<strong class="text-emerald-700">LULUS</strong>). Jika ada siswa yang <strong class="text-rose-600">TINGGAL KELAS</strong>, silakan hilangkan centangnya.
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                                    <th class="px-7 py-4 font-bold w-16 text-center">
                                        <input type="checkbox" id="selectAll" checked class="w-4 h-4 text-emerald-600 bg-white-off/50 border-navy-light/40 rounded focus:border-emerald-600 focus:ring focus:ring-emerald-600/20 cursor-pointer shadow-sm">
                                    </th>
                                    <th class="px-7 py-4 font-bold">NISN</th>
                                    <th class="px-7 py-4 font-bold">Nama Siswa</th>
                                    <th class="px-7 py-4 font-bold">Status Saat Ini</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-white-off text-navy-base">
                                @foreach($students as $student)
                                <tr class="hover:bg-white-off/50 transition-colors cursor-pointer" onclick="document.getElementById('checkbox-{{ $student->id }}').click()">
                                    <td class="px-7 py-4 text-center">
                                        <!-- Hidden input to pass ALL student IDs to backend for comparison -->
                                        <input type="hidden" name="all_student_ids[]" value="{{ $student->id }}">
                                        
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="checkbox-{{ $student->id }}" checked onclick="event.stopPropagation()" class="student-checkbox w-4 h-4 text-emerald-600 bg-white-off/50 border-navy-light/40 rounded focus:border-emerald-600 focus:ring focus:ring-emerald-600/20 cursor-pointer shadow-sm">
                                    </td>
                                    <td class="px-7 py-4">
                                        <span class="font-mono text-xs font-bold text-navy-base bg-white-off/50 border border-navy-light/20 px-2.5 py-1.5 rounded-lg">{{ $student->studentProfile->nisn ?? '-' }}</span>
                                    </td>
                                    <td class="px-7 py-4">
                                        <div class="font-bold text-navy-dark mb-0.5">{{ $student->studentProfile->full_name ?? $student->email }}</div>
                                        <div class="text-[11px] text-gray-muted flex items-center gap-1.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $student->email }}
                                        </div>
                                    </td>
                                    <td class="px-7 py-4">
                                        <span class="bg-navy-light/10 text-navy-dark border border-navy-light/30 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest">{{ $student->studentProfile->status ?? 'aktif' }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-6 bg-white-off/30 border-t border-navy-light/30 flex justify-end gap-3 rounded-b-2xl">
                        <a href="{{ route('class-enrollments.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-bold text-gray-muted bg-white-off border border-navy-light/30 hover:bg-navy-light/10 hover:text-navy-dark transition-all">Batal</a>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white-off px-8 py-2.5 rounded-xl font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow-lg hover:shadow-emerald-600/20 active:scale-95 border border-transparent">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Simpan Kelulusan
                        </button>
                    </div>
                </form>
            @else
                <div class="p-12 text-center text-gray-muted flex flex-col items-center">
                    <div class="bg-white-off border border-navy-light/30 p-4 rounded-full mb-4 shadow-inner text-navy-base">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-navy-dark mb-2 font-heading">Tidak Ada Siswa Aktif</h4>
                    <p class="max-w-md text-sm leading-relaxed">Semua siswa di kelas tersebut mungkin sudah diluluskan, atau sudah dimasukkan ke kelas lain pada tahun ajaran ini.</p>
                </div>
            @endif
        </div>
        
        <script>
            // Logika untuk mencentang/menghapus centang semua siswa sekaligus
            document.getElementById('selectAll').addEventListener('change', function() {
                let checkboxes = document.querySelectorAll('.student-checkbox');
                for (let checkbox of checkboxes) {
                    checkbox.checked = this.checked;
                }
            });
        </script>
    @endif

@endsection

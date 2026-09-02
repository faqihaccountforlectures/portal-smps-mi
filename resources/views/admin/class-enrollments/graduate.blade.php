@extends('layouts.app')

@section('title', 'Kelulusan Kelas 9')
@section('header', 'Kelulusan Kelas 9')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('class-enrollments.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="font-medium text-sm">Kembali ke Pembagian Kelas</span>
        </a>
    </div>

    <!-- Informasi Fitur -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6 flex items-start gap-4 shadow-sm">
        <div class="bg-yellow-100 p-2 rounded-lg text-yellow-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
        </div>
        <div>
            <h3 class="text-yellow-800 font-bold text-lg mb-1">Manajemen Kelulusan (Alumni) & Tinggal Kelas</h3>
            <p class="text-yellow-700 text-sm">Gunakan fitur ini khusus untuk menangani siswa Kelas 9. Siswa yang diluluskan akan diubah statusnya menjadi <strong>Alumni (Lulus)</strong>. Siswa yang gagal (tinggal kelas) akan dimasukkan kembali ke <strong>Kelas 9 Tujuan</strong> di Tahun Ajaran Aktif ({{ $activeYear->semester }} {{ $activeYear->year_name }}).</p>
        </div>
    </div>

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-center gap-3">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="block sm:inline font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Formulir Filter Pencarian Kelas Asal -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50">
            <h3 class="font-bold text-slate-800">1. Pilih Kelas 9 Asal (Tahun Sebelumnya)</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('class-enrollments.graduate') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Ajaran Asal</label>
                    <select name="source_year_id" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" required>
                        <option value="">-- Pilih Tahun Ajaran Sebelumnya --</option>
                        @foreach($previousYears as $year)
                            <option value="{{ $year->id }}" {{ $sourceYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->semester }} {{ $year->year_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1 w-full">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kelas Asal (Tingkat 9)</label>
                    <select name="source_class_id" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" required>
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
                    <button type="submit" class="w-full md:w-auto bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2 shadow-md shadow-slate-800/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Tampilkan Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tampilan Daftar Siswa (Hanya muncul jika sudah difilter) -->
    @if($sourceYearId && $sourceClassId)
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <h3 class="font-bold text-slate-800">2. Proses Kelulusan Siswa</h3>
                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full">{{ count($students) }} Siswa Aktif Ditemukan</span>
            </div>

            @if(count($students) > 0)
                <form action="{{ route('class-enrollments.store-graduation') }}" method="POST">
                    @csrf
                    
                    <div class="p-6 bg-slate-50 border-b border-slate-200">
                        <label class="block text-sm font-bold text-slate-800 mb-2">Kelas Tujuan (Untuk Siswa Tinggal Kelas)</label>
                        <p class="text-xs text-slate-500 mb-3">Jika ada siswa yang <strong>tidak dicentang (tinggal kelas)</strong>, mereka akan dimasukkan ke kelas ini pada tahun ajaran aktif saat ini.</p>
                        <select name="destination_class_id" class="w-full md:w-1/2 border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" required>
                            <option value="">-- Pilih Kelas 9 Tujuan (Tahun Aktif) --</option>
                            @foreach($destinationClasses as $cls)
                                <option value="{{ $cls->id }}" {{ $destinationClassId == $cls->id ? 'selected' : '' }}>
                                    {{ $cls->name }} (Tingkat {{ $cls->grade_level }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-6 bg-emerald-50 text-sm text-emerald-800 border-b border-emerald-100">
                        <strong>Petunjuk:</strong> Secara bawaan, seluruh siswa akan tercentang (<strong>LULUS</strong>). Jika ada siswa yang <strong>TINGGAL KELAS</strong>, silakan hilangkan centangnya.
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                                    <th class="px-6 py-4 font-semibold border-b border-slate-100 w-16 text-center">
                                        <input type="checkbox" id="selectAll" checked class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer">
                                    </th>
                                    <th class="px-6 py-4 font-semibold border-b border-slate-100">NISN</th>
                                    <th class="px-6 py-4 font-semibold border-b border-slate-100">Nama Siswa</th>
                                    <th class="px-6 py-4 font-semibold border-b border-slate-100">Status Saat Ini</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-50">
                                @foreach($students as $student)
                                <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer" onclick="document.getElementById('checkbox-{{ $student->id }}').click()">
                                    <td class="px-6 py-4 text-center">
                                        <!-- Hidden input to pass ALL student IDs to backend for comparison -->
                                        <input type="hidden" name="all_student_ids[]" value="{{ $student->id }}">
                                        
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="checkbox-{{ $student->id }}" checked onclick="event.stopPropagation()" class="student-checkbox w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded text-slate-600">{{ $student->studentProfile->nisn ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">{{ $student->studentProfile->full_name ?? $student->email }}</div>
                                        <div class="text-xs text-slate-500">{{ $student->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold uppercase">{{ $student->studentProfile->status ?? 'aktif' }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                        <a href="{{ route('class-enrollments.index') }}" class="px-6 py-2.5 rounded-lg font-semibold text-slate-600 hover:bg-slate-200 transition-colors">Batal</a>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-2.5 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-md shadow-emerald-600/20 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Simpan Kelulusan
                        </button>
                    </div>
                </form>
            @else
                <div class="p-12 text-center text-slate-500 flex flex-col items-center">
                    <div class="bg-slate-100 p-4 rounded-full mb-4">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-700 mb-2">Tidak Ada Siswa Aktif</h4>
                    <p class="max-w-md">Semua siswa di kelas tersebut mungkin sudah diluluskan, atau sudah dimasukkan ke kelas lain pada tahun ajaran ini.</p>
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

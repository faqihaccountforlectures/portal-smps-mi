@extends('layouts.app')

@section('title', 'Tambah Siswa ke Kelas')
@section('header', 'Tambah Siswa: Kelas ' . $classRoom->name)

@section('content')
    
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-center gap-3" role="alert">
        <div class="bg-red-100 p-1.5 rounded-lg">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        <div>
            <span class="block sm:inline font-medium">{{ session('error') }}</span>
        </div>
    </div>
    @endif
    
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
        <ul class="list-disc pl-5 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('class-enrollments.show', $classRoom->id) }}" class="inline-flex items-center gap-2 text-gray-muted hover:text-navy-base transition-colors font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="text-sm">Kembali ke Detail Kelas</span>
        </a>
    </div>

    <!-- HEADER INFO -->
    {{-- FUNGSI KODE: Informasi Header Pilihan Siswa --}}
    <div class="bg-white rounded-xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden mb-6 p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-lg font-bold text-navy-dark font-heading">Pilih Siswa untuk Kelas {{ $classRoom->name }}</h2>
            <p class="text-xs text-gray-muted mt-1.5">Daftar di bawah ini hanya menampilkan siswa yang <strong class="text-navy-base font-bold">BELUM</strong> mendapatkan kelas di Tahun Ajaran {{ $activeYear->semester }} {{ $activeYear->year_name }}.</p>
        </div>
        <div class="bg-navy-light/10 px-4 py-2 rounded-lg text-navy-dark text-xs font-bold border border-navy-light/30 whitespace-nowrap shadow-sm uppercase tracking-widest">
            Tersedia: {{ $availableStudents->count() }} Siswa
        </div>
    </div>

    @if($availableStudents->count() > 0)
        <form action="{{ route('class-enrollments.store-students', $classRoom->id) }}" method="POST">
            @csrf
            {{-- FUNGSI KODE: Tabel form checkbox siswa --}}
            <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden mb-6 flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <div class="px-7 py-4 border-b border-navy-light/30 bg-white-off/30 flex justify-between items-center">
                    <label class="flex items-center gap-3 cursor-pointer group/label">
                        <input type="checkbox" id="checkAll" class="w-5 h-5 rounded border-navy-light/50 text-navy-base shadow-sm focus:border-navy-base focus:ring focus:ring-navy-light/20 focus:ring-opacity-50 transition-colors">
                        <span class="font-bold text-navy-dark group-hover/label:text-navy-base transition-colors tracking-wide">Pilih Semua</span>
                    </label>
                    <span class="text-xs text-gray-muted font-bold tracking-wide" id="selectedCount">0 siswa terpilih</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody class="text-sm divide-y divide-white-off">
                            @foreach($availableStudents as $student)
                            <tr class="hover:bg-white-off/50 transition-colors cursor-pointer group" onclick="document.getElementById('student_{{ $student->id }}').click()">
                                <td class="px-7 py-4 w-12">
                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="student_{{ $student->id }}" class="student-checkbox w-5 h-5 rounded border-navy-light/50 text-navy-base shadow-sm focus:border-navy-base focus:ring focus:ring-navy-light/20 focus:ring-opacity-50 transition-colors" onclick="event.stopPropagation()">
                                </td>
                                <td class="px-7 py-4">
                                    <span class="font-mono text-xs font-bold text-navy-base bg-white-off/50 border border-navy-light/20 px-2.5 py-1.5 rounded-lg">{{ $student->studentProfile->nisn ?? '-' }}</span>
                                </td>
                                <td class="px-7 py-4 font-bold text-navy-dark">
                                    {{ $student->studentProfile->full_name ?? $student->email }}
                                </td>
                                <td class="px-7 py-4">
                                    @if(isset($student->studentProfile->gender))
                                        @if($student->studentProfile->gender === 'laki-laki')
                                            <span class="inline-flex items-center gap-1.5 text-gray-muted text-[11px] font-bold tracking-widest uppercase"><div class="w-2 h-2 rounded-full bg-blue-500 shadow-sm"></div> Laki-laki</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-gray-muted text-[11px] font-bold tracking-widest uppercase"><div class="w-2 h-2 rounded-full bg-pink-500 shadow-sm"></div> Perempuan</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end gap-3 sticky bottom-6 z-10 bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg shadow-navy-base/5 border border-navy-light/30">
                <a href="{{ route('class-enrollments.show', $classRoom->id) }}" class="px-6 py-2.5 rounded-xl text-sm font-bold text-gray-muted bg-white-off border border-navy-light/30 hover:bg-navy-light/10 hover:text-navy-dark transition-all">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white-off bg-navy-dark hover:bg-navy-base shadow-md shadow-navy-base/20 transition-all flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan & Masukkan ke Kelas
                </button>
            </div>
        </form>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-navy-light/30 p-12 text-center flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <div class="flex flex-col items-center justify-center text-gray-muted">
                <div class="w-16 h-16 bg-white-off border border-navy-light/30 p-4 rounded-full mb-4 flex items-center justify-center text-navy-base shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-lg font-bold text-navy-dark font-heading">Semua Siswa Sudah Mendapat Kelas!</p>
                <p class="text-sm mt-2 text-gray-muted max-w-md mx-auto leading-relaxed">Saat ini tidak ada siswa yang tersisa untuk dimasukkan. Semua siswa yang terdaftar sudah tergabung dalam suatu kelas pada tahun ajaran ini.</p>
                
                <a href="{{ route('class-enrollments.show', $classRoom->id) }}" class="mt-6 bg-white-off border border-navy-light/30 hover:bg-navy-light/10 text-navy-dark px-6 py-2.5 rounded-xl font-bold transition-all shadow-sm active:scale-95">
                    Kembali ke Kelas
                </a>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('.student-checkbox');
            const selectedCount = document.getElementById('selectedCount');

            if(checkAll && checkboxes.length > 0) {
                // Update text counter
                function updateCount() {
                    const checked = document.querySelectorAll('.student-checkbox:checked').length;
                    selectedCount.textContent = checked + ' siswa terpilih';
                    
                    // Update checkAll state
                    if (checked === 0) {
                        checkAll.checked = false;
                        checkAll.indeterminate = false;
                    } else if (checked === checkboxes.length) {
                        checkAll.checked = true;
                        checkAll.indeterminate = false;
                    } else {
                        checkAll.checked = false;
                        checkAll.indeterminate = true;
                    }
                }

                // Listen to individual checkboxes
                checkboxes.forEach(cb => {
                    cb.addEventListener('change', updateCount);
                });

                // Listen to "Check All"
                checkAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateCount();
                });
            }
        });
    </script>
@endsection

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
        <a href="{{ route('class-enrollments.show', $classRoom->id) }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="font-medium text-sm">Kembali ke Detail Kelas</span>
        </a>
    </div>

    <!-- HEADER INFO -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-6 p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Pilih Siswa untuk Kelas {{ $classRoom->name }}</h2>
            <p class="text-xs text-slate-500 mt-1">Daftar di bawah ini hanya menampilkan siswa yang <strong>BELUM</strong> mendapatkan kelas di Tahun Ajaran {{ $activeYear->semester }} {{ $activeYear->year_name }}.</p>
        </div>
        <div class="bg-blue-50 px-3 py-1.5 rounded-md text-blue-700 text-xs font-semibold border border-blue-100 whitespace-nowrap">
            Tersedia: {{ $availableStudents->count() }} Siswa
        </div>
    </div>

    @if($availableStudents->count() > 0)
        <form action="{{ route('class-enrollments.store-students', $classRoom->id) }}" method="POST">
            @csrf
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" id="checkAll" class="w-5 h-5 rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-colors">
                        <span class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors">Pilih Semua</span>
                    </label>
                    <span class="text-xs text-slate-400 font-medium" id="selectedCount">0 siswa terpilih</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody class="text-sm divide-y divide-slate-50">
                            @foreach($availableStudents as $student)
                            <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer" onclick="document.getElementById('student_{{ $student->id }}').click()">
                                <td class="px-6 py-4 w-12">
                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="student_{{ $student->id }}" class="student-checkbox w-5 h-5 rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-colors" onclick="event.stopPropagation()">
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded text-slate-600">{{ $student->studentProfile->nisn ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $student->studentProfile->full_name ?? $student->email }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    @if(isset($student->studentProfile->gender))
                                        @if($student->studentProfile->gender === 'laki-laki')
                                            <span class="inline-flex items-center gap-1.5 text-slate-500 text-xs font-semibold"><div class="w-2 h-2 rounded-full bg-blue-500"></div> Laki-laki</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-slate-500 text-xs font-semibold"><div class="w-2 h-2 rounded-full bg-pink-500"></div> Perempuan</span>
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

            <div class="flex justify-end gap-3 sticky bottom-6 z-10 bg-white/80 backdrop-blur p-4 rounded-2xl shadow-lg border border-slate-100">
                <a href="{{ route('class-enrollments.show', $classRoom->id) }}" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan & Masukkan ke Kelas
                </button>
            </div>
        </form>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
            <div class="flex flex-col items-center justify-center text-slate-400">
                <div class="bg-emerald-50 p-5 rounded-full mb-4">
                    <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-lg font-bold text-slate-700">Semua Siswa Sudah Mendapat Kelas!</p>
                <p class="text-sm mt-2 text-slate-500 max-w-md mx-auto">Saat ini tidak ada siswa yang tersisa untuk dimasukkan. Semua siswa yang terdaftar sudah tergabung dalam suatu kelas pada tahun ajaran ini.</p>
                
                <a href="{{ route('class-enrollments.show', $classRoom->id) }}" class="mt-6 bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-2.5 rounded-xl font-semibold transition-colors">
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

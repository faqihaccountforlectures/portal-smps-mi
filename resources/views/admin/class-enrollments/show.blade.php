@extends('layouts.app')

@section('title', 'Daftar Siswa Kelas')
@section('header', 'Daftar Siswa Kelas ' . $classRoom->name)

@section('content')
    
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl relative flex items-center gap-3" role="alert">
        <div class="bg-emerald-100 p-1.5 rounded-lg">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('class-enrollments.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="font-medium text-sm">Kembali ke Daftar Kelas</span>
        </a>
    </div>

    <!-- HEADER INFO -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-6 p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-xl shadow-inner">
                {{ $classRoom->grade_level }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Kelas {{ $classRoom->name }}</h2>
                <div class="flex items-center gap-3 mt-0.5 text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Wali kelas: <strong class="text-slate-700">{{ $classRoom->homeroomTeacher->teacherProfile->full_name ?? 'Belum diatur' }}</strong>
                    </span>
                    <span class="flex items-center gap-1.5 border-l border-slate-200 pl-3">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $activeYear->semester }} {{ $activeYear->year_name }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto">
            <div class="bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg text-center">
                <span class="block text-xl font-black text-blue-600 leading-none mb-0.5">{{ $enrollments->count() }}</span>
                <span class="text-[9px] uppercase font-bold text-slate-500 tracking-wider">Total Siswa</span>
            </div>
            <a href="{{ route('class-enrollments.add-students', $classRoom->id) }}" class="flex-1 md:flex-none bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm rounded-lg font-semibold transition-all duration-200 flex items-center justify-center gap-1.5 shadow-md shadow-blue-600/20 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Siswa
            </a>
        </div>
    </div>

    <!-- TABEL DAFTAR SISWA -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Daftar Siswa di Kelas Ini</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold border-b border-slate-100 w-16 text-center">No</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-100">NISN</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-100">Nama Lengkap</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-100">Jenis Kelamin</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-50">
                    @forelse($enrollments as $index => $enrollment)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded text-slate-600">{{ $enrollment->student->studentProfile->nisn ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">{{ $enrollment->student->studentProfile->full_name ?? $enrollment->student->email }}</div>
                            <div class="text-xs text-slate-500">{{ $enrollment->student->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            @if(isset($enrollment->student->studentProfile->gender))
                                @if($enrollment->student->studentProfile->gender === 'laki-laki')
                                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-xs font-semibold">Laki-laki</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-pink-50 text-pink-700 px-2.5 py-1 rounded-md text-xs font-semibold">Perempuan</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="document.getElementById('removeModal-{{ $enrollment->id }}').classList.remove('hidden')" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Keluarkan dari Kelas">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>

                    <!-- MODAL HAPUS ENROLLMENT -->
                    <div id="removeModal-{{ $enrollment->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('removeModal-{{ $enrollment->id }}').classList.add('hidden')"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            
                            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg leading-6 font-bold text-slate-800" id="modal-title">Keluarkan Siswa</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-slate-500">Anda yakin ingin mengeluarkan <strong class="text-slate-700">{{ $enrollment->student->studentProfile->full_name ?? '' }}</strong> dari kelas ini? Data kehadiran dan nilai mungkin akan terpengaruh.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100">
                                    <form action="{{ route('class-enrollments.destroy', $enrollment->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                            Ya, Keluarkan
                                        </button>
                                    </form>
                                    <button type="button" onclick="document.getElementById('removeModal-{{ $enrollment->id }}').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="bg-slate-100 p-4 rounded-full mb-3">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <p class="text-base font-bold text-slate-600">Belum Ada Siswa</p>
                                <p class="text-sm mt-1 mb-4">Kelas ini masih kosong. Silakan tambahkan siswa.</p>
                                <a href="{{ route('class-enrollments.add-students', $classRoom->id) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
                                    + Tambah Siswa Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
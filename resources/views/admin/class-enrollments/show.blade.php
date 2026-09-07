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
    {{-- FUNGSI KODE: Header Kelas dengan Desain Navy Elegan --}}
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden mb-6 p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-navy-light/10 border border-navy-light/30 flex items-center justify-center text-navy-base font-bold text-2xl shadow-sm font-mono">
                {{ $classRoom->grade_level }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-navy-dark font-heading">Kelas {{ $classRoom->name }}</h2>
                <div class="flex items-center gap-4 mt-1.5 text-xs text-gray-muted font-medium">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Wali kelas: <strong class="text-navy-dark">{{ $classRoom->homeroomTeacher->teacherProfile->full_name ?? 'Belum diatur' }}</strong>
                    </span>
                    <span class="flex items-center gap-1.5 border-l border-navy-light/40 pl-4">
                        <svg class="w-4 h-4 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $activeYear->semester }} {{ $activeYear->year_name }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center justify-end gap-3 w-full md:w-auto">
            <div class="bg-white-off/50 border border-navy-light/30 px-4 py-2 rounded-xl text-center hidden md:flex flex-col items-center justify-center min-w-[90px]">
                <span class="block text-2xl font-black text-navy-base leading-none mb-0.5">{{ $enrollments->count() }}</span>
                <span class="text-[9px] uppercase font-bold text-gray-muted tracking-widest">Total Siswa</span>
            </div>
            
            <!-- Tombol Migrasi Kenaikan Kelas (Baru) -->
            <a href="{{ route('class-enrollments.promote', $classRoom->id) }}" class="flex-1 md:flex-none bg-emerald-600 hover:bg-emerald-700 text-white-off px-5 py-2.5 text-sm rounded-xl font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow-lg hover:shadow-emerald-600/20 active:scale-95 border border-transparent" title="Tarik siswa dari kelas lama">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Migrasi Kenaikan
            </a>
            
            <a href="{{ route('class-enrollments.add-students', $classRoom->id) }}" class="flex-1 md:flex-none bg-navy-dark hover:bg-navy-base text-white-off px-5 py-2.5 text-sm rounded-xl font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow-lg hover:shadow-navy-base/20 active:scale-95 border border-transparent">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Siswa
            </a>
        </div>
    </div>

    <!-- TABEL DAFTAR SISWA -->
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30">
            <h3 class="font-bold text-navy-dark font-heading tracking-wide text-base">Daftar Siswa di Kelas Ini</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                        <th class="px-7 py-4 font-bold w-16 text-center">No</th>
                        <th class="px-7 py-4 font-bold">NISN</th>
                        <th class="px-7 py-4 font-bold">Nama Lengkap</th>
                        <th class="px-7 py-4 font-bold">Jenis Kelamin</th>
                        <th class="px-7 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white-off text-navy-base">
                    @forelse($enrollments as $index => $enrollment)
                    <tr class="hover:bg-white-off/50 transition-colors">
                        <td class="px-7 py-4 text-center font-bold text-navy-light/80">{{ $index + 1 }}</td>
                        <td class="px-7 py-4">
                            <span class="font-mono text-xs font-bold text-navy-base bg-white-off/50 border border-navy-light/20 px-2.5 py-1.5 rounded-lg">{{ $enrollment->student->studentProfile->nisn ?? '-' }}</span>
                        </td>
                        <td class="px-7 py-4">
                            <div class="font-bold text-navy-dark mb-0.5">{{ $enrollment->student->studentProfile->full_name ?? $enrollment->student->email }}</div>
                            <div class="text-[11px] text-gray-muted flex items-center gap-1.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $enrollment->student->email }}
                            </div>
                        </td>
                        <td class="px-7 py-4 font-medium">
                            @if(isset($enrollment->student->studentProfile->gender))
                                @if($enrollment->student->studentProfile->gender === 'laki-laki')
                                    <span class="inline-flex items-center gap-1.5 bg-blue-50/50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-lg text-[11px] font-bold tracking-widest uppercase shadow-sm">Laki-laki</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-pink-50/50 text-pink-700 border border-pink-200 px-3 py-1.5 rounded-lg text-[11px] font-bold tracking-widest uppercase shadow-sm">Perempuan</span>
                                @endif
                            @else
                                <span class="text-gray-muted italic">-</span>
                            @endif
                        </td>
                        <td class="px-7 py-4 text-right">
                            <button onclick="document.getElementById('removeModal-{{ $enrollment->id }}').classList.remove('hidden')" class="inline-flex items-center justify-center p-2 text-gray-muted hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 rounded-xl transition-all shadow-sm" title="Keluarkan dari Kelas">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>

                    <!-- MODAL HAPUS ENROLLMENT -->
                    <div id="removeModal-{{ $enrollment->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-navy-dark/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('removeModal-{{ $enrollment->id }}').classList.add('hidden')"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            
                            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-navy-light/30">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-50 border border-rose-200 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg font-bold text-navy-dark font-heading" id="modal-title">Keluarkan Siswa</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-muted">Anda yakin ingin mengeluarkan <strong class="text-navy-base">{{ $enrollment->student->studentProfile->full_name ?? '' }}</strong> dari kelas ini? Data kehadiran dan nilai mungkin akan terpengaruh.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white-off/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-navy-light/30">
                                    <form action="{{ route('class-enrollments.destroy', $enrollment->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-rose-600 text-base font-bold text-white hover:bg-rose-700 hover:shadow-rose-600/20 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:ml-3 sm:w-auto sm:text-sm transition-all active:scale-95">
                                            Ya, Keluarkan
                                        </button>
                                    </form>
                                    <button type="button" onclick="document.getElementById('removeModal-{{ $enrollment->id }}').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-navy-light/50 shadow-sm px-5 py-2.5 bg-white text-base font-bold text-navy-dark hover:bg-white-off hover:text-navy-base focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-base sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="px-7 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-white-off rounded-full flex items-center justify-center text-gray-muted mb-4 border border-navy-light/40">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <p class="text-base font-bold text-navy-dark font-heading mb-1">Belum Ada Siswa</p>
                                <p class="text-xs mt-1.5 text-center text-gray-muted mb-5">Kelas ini masih kosong. Silakan tambahkan siswa.</p>
                                <a href="{{ route('class-enrollments.add-students', $classRoom->id) }}" class="text-xs text-navy-base font-bold bg-navy-light/20 px-4 py-2 rounded-lg border border-navy-light/40 hover:bg-navy-light/40 transition-colors">
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
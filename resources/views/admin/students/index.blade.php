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

    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        <!-- Header Tabel & Form Filter -->
        <div class="px-7 py-5 border-b border-navy-light/30 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white-off/30">
            <div class="flex items-center gap-3">
                <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
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
                                    <svg class="w-10 h-10 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
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
@endsection

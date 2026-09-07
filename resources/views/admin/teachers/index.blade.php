@extends('layouts.app')

@section('title', 'Data Guru')
@section('header', 'Data Guru')

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

    {{-- FUNGSI KODE: Tabel Manajemen Data Guru diselaraskan dengan gaya tampilan Data Siswa --}}
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        <!-- Header Tabel & Form Filter -->
        <div class="px-7 py-5 border-b border-navy-light/30 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white-off/30">
            <div class="flex items-center gap-3">
                <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-navy-dark font-heading tracking-wide text-base">Daftar Guru / Tenaga Pendidik</h2>
                    <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-bold">Kelola data profil dan akun para guru di sini.</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <!-- Form Pencarian Guru -->
                <form action="{{ route('teachers.index') }}" method="GET" class="w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-navy-base/60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / NIP..." class="w-full bg-white border border-navy-light/40 text-navy-dark font-semibold text-xs rounded-xl pl-9 pr-4 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all placeholder:text-gray-muted/60 shadow-sm">
                    </div>
                </form>

                <a href="{{ route('teachers.create') }}" class="w-full sm:w-auto px-5 py-2.5 bg-navy-dark text-white-off font-bold text-sm rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 border border-transparent whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Guru
                </a>
            </div>
        </div>
        
        <!-- Tabel Data -->
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                        <th class="px-7 py-4 font-bold w-16 text-center">No</th>
                        <th class="px-7 py-4 font-bold">Profil Guru</th>
                        <th class="px-7 py-4 font-bold">NIP</th>
                        <th class="px-7 py-4 font-bold">No. Telepon</th>
                        <th class="px-7 py-4 font-bold">Jabatan</th>
                        <th class="px-7 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white-off text-navy-base">
                    @forelse($teachers as $index => $teacher)
                    <tr class="hover:bg-white-off/50 transition-colors group/row">
                        <td class="px-7 py-4 text-center text-gray-muted font-bold font-mono">
                            {{ $teachers->firstItem() + $index }}
                        </td>
                        <td class="px-7 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-navy-light/20 text-navy-dark flex items-center justify-center font-bold text-sm border border-navy-light/40 shrink-0">
                                    {{ substr($teacher->teacherProfile->full_name ?? 'G', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-navy-dark group-hover/row:text-navy-base transition-colors">{{ $teacher->teacherProfile->full_name ?? 'Belum ada nama' }}</p>
                                    <p class="text-[11px] text-gray-muted font-semibold flex items-center gap-1.5 mt-0.5 tracking-wide">
                                        <svg class="w-3 h-3 text-navy-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $teacher->email }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-7 py-4 font-mono font-medium text-sm">
                            @if(isset($teacher->teacherProfile->nip) && $teacher->teacherProfile->nip)
                                {{ $teacher->teacherProfile->nip }}
                            @else
                                <span class="text-gray-muted italic text-xs">Belum diisi</span>
                            @endif
                        </td>
                        <td class="px-7 py-4 font-mono font-medium text-sm">
                            @if(isset($teacher->teacherProfile->phone_number) && $teacher->teacherProfile->phone_number)
                                {{ $teacher->teacherProfile->phone_number }}
                            @else
                                <span class="text-gray-muted italic text-xs">Belum diisi</span>
                            @endif
                        </td>
                        <td class="px-7 py-4">
                            <span class="inline-flex px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest bg-navy-light/10 text-navy-dark border border-navy-light/30 shadow-sm">
                                {{ str_replace('_', ' ', $teacher->teacherProfile->position ?? '-') }}
                            </span>
                        </td>
                        <td class="px-7 py-4">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('teachers.edit', $teacher->id) }}" class="text-gray-muted hover:text-navy-base hover:bg-navy-light/10 p-2 rounded-xl transition-all duration-200" title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                <button type="button" onclick="document.getElementById('deleteModal-{{ $teacher->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-rose-500 hover:bg-rose-50 p-2 rounded-xl transition-all duration-200" title="Hapus Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @include('admin.teachers.delete')
                    @empty
                    <tr>
                        <td colspan="6" class="px-7 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-muted">
                                <div class="bg-navy-light/10 p-4 rounded-2xl mb-4 border border-navy-light/30 text-navy-base">
                                    <svg class="w-10 h-10 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <h3 class="text-base font-bold text-navy-dark mb-1 font-heading">Belum Ada Data Guru</h3>
                                <p class="text-[13px] text-gray-muted mb-5">Belum ada data tenaga pendidik yang cocok dengan pencarian Anda.</p>
                                <a href="{{ route('teachers.create') }}" class="px-5 py-2.5 bg-white-off text-navy-dark font-bold text-sm rounded-xl hover:bg-navy-light/20 transition-colors shadow-sm border border-navy-light/30">
                                    Tambah Guru Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Tabel & Paginasi -->
        @if($teachers->hasPages())
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-center gap-4 mt-auto">
            <span class="text-xs text-gray-muted font-medium">
                Menampilkan <b class="text-navy-dark">{{ $teachers->firstItem() }}</b> - <b class="text-navy-dark">{{ $teachers->lastItem() }}</b> dari <b class="text-navy-dark">{{ $teachers->total() }}</b> guru
            </span>
            <div class="pagination-wrapper">
                {{ $teachers->appends(request()->all())->links() }}
            </div>
        </div>
        @else
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex justify-between items-center text-xs text-gray-muted font-medium mt-auto">
            <span>Total Guru: <b class="text-navy-dark">{{ $teachers->total() }}</b> data</span>
        </div>
        @endif
    </div>
@endsection

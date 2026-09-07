@extends('layouts.app')

@section('title', 'Data Kelas')
@section('header', 'Data Kelas')

@section('content')
    
    @if(session('success'))
    <div class="bg-white border-l-4 border-navy-base text-navy-dark px-5 py-4 rounded-xl mb-6 shadow-sm shadow-navy-base/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
        <div class="bg-navy-base p-2 rounded-lg text-white-off">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        {{-- FORM TAMBAH KELAS --}}
        <div class="xl:col-span-1">
            <div class="bg-white p-7 rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 relative overflow-hidden group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <div class="flex items-center gap-3 mb-6 border-b border-white-off pb-4">
                    <div class="bg-white-off p-2.5 rounded-xl text-navy-dark group-hover:bg-navy-dark group-hover:text-white-off transition-colors duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Tambah Kelas Baru</h2>
                </div>
                
                <form action="{{ route('classes.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted uppercase tracking-wider mb-1.5">Tingkat Kelas</label>
                        <div class="relative">
                            <select name="grade_level" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl pl-3 pr-10 py-2 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-200 appearance-none">
                                <option value="" disabled selected>Pilih Tingkat...</option>
                                <option value="7">Kelas 7 (VII)</option>
                                <option value="8">Kelas 8 (VIII)</option>
                                <option value="9">Kelas 9 (IX)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-navy-base">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted uppercase tracking-wider mb-1.5">Nama Ruang/Kelas</label>
                        <input type="text" name="name" placeholder="Contoh: 7A, VIII B" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl px-3 py-2 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-200">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted uppercase tracking-wider mb-1.5">Wali Kelas (Opsional)</label>
                        <div class="relative">
                            <select name="homeroom_teacher_id" class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl pl-3 pr-10 py-2 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-200 appearance-none">
                                <option value="">-- Kosongkan / Belum Ada --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->teacherProfile->full_name ?? $teacher->email }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-navy-base">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-2 bg-navy-dark text-white-off font-bold text-sm py-3 rounded-xl hover:bg-navy-base hover:shadow-lg hover:shadow-navy-base/20 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Simpan Data</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- DAFTAR KELAS --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col h-full group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-2 rounded-xl text-navy-base shadow-sm border border-navy-light/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </div>
                        <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Kelas</h2>
                    </div>
                    <span class="text-xs font-bold text-navy-base bg-navy-light/20 px-3 py-1.5 rounded-lg border border-navy-light/40">Total: {{ $classes->total() }} Data</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-wider font-bold">
                                <th class="px-5 py-3.5 font-semibold border-b border-white-off text-center">Tingkat</th>
                                <th class="px-5 py-3.5 font-semibold border-b border-white-off">Nama Kelas</th>
                                <th class="px-5 py-3.5 font-semibold border-b border-white-off">Wali Kelas</th>
                                <th class="px-5 py-3.5 font-semibold border-b border-white-off text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-white-off">
                            @forelse($classes as $classRoom)
                            <tr class="hover:bg-white-off/40 transition-colors group">
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-navy-light/10 text-navy-dark font-bold text-xs">
                                        {{ $classRoom->grade_level }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-navy-dark">
                                    {{ $classRoom->name }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-muted font-medium">
                                    @if($classRoom->homeroomTeacher)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-navy-base/10 text-navy-base flex items-center justify-center text-[10px] font-bold">
                                                {{ substr($classRoom->homeroomTeacher->teacherProfile->full_name ?? 'G', 0, 1) }}
                                            </div>
                                            <span>{{ $classRoom->homeroomTeacher->teacherProfile->full_name ?? $classRoom->homeroomTeacher->email }}</span>
                                        </div>
                                    @else
                                        <span class="text-navy-base italic text-xs">Belum diatur</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex justify-end items-center gap-1">
                                        <a href="{{ route('classes.edit', $classRoom->id) }}" class="text-navy-base hover:text-navy-base transition-colors p-1.5 rounded-md hover:bg-navy-light/10 block" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        <button onclick="document.getElementById('deleteModal-{{ $classRoom->id }}').classList.remove('hidden')" class="text-navy-base hover:text-red-600 transition-colors p-1.5 rounded-md hover:bg-red-50" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @include('admin.classes.delete')
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-navy-base">
                                        <div class="bg-white-off p-4 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-gray-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <p class="text-sm font-medium text-navy-dark">Belum ada data kelas</p>
                                        <p class="text-xs mt-1">Silakan tambahkan data kelas baru melalui form di samping.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- FUNGSI KODE: Paginasi (Pagination) --}}
                @if($classes->hasPages())
                <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 mt-auto">
                    {{ $classes->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection






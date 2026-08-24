@extends('layouts.app')

@section('title', 'Data Guru')
@section('header', 'Master Data Guru')

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

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Header Tabel -->
        <div class="px-6 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100/50 p-2 rounded-lg text-blue-600 border border-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Daftar Guru / Tenaga Pendidik</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Kelola data profil dan akun para guru di sini.</p>
                </div>
            </div>
            
            <a href="{{ route('teachers.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/20 active:scale-[0.98] transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Guru
            </a>
        </div>
        
        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold border-b border-slate-100 w-16 text-center">No</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-100">Profil Guru</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-100">NIP / NUPTK</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-100">Jabatan</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-50">
                    @forelse($teachers as $index => $teacher)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 text-center text-slate-400 font-medium">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm border border-blue-100 shrink-0">
                                    {{ substr($teacher->teacherProfile->full_name ?? 'G', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $teacher->teacherProfile->full_name ?? 'Belum ada nama' }}</p>
                                    <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $teacher->email }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $teacher->teacherProfile->nip ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-600 border border-indigo-100">
                                {{ $teacher->teacherProfile->position ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-1.5">
                                <a href="{{ route('teachers.edit', $teacher->id) }}" class="text-slate-400 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50 block" title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                <button type="button" onclick="document.getElementById('deleteModal-{{ $teacher->id }}').classList.remove('hidden')" class="text-slate-400 hover:text-red-600 transition-colors p-2 rounded-lg hover:bg-red-50" title="Hapus Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Modal hapusnya dimasukin (di-include) di dalem loop ini aja -->
                    @include('admin.teachers.delete')
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-100">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-600 mb-1">Belum ada data Guru</h3>
                                <p class="text-sm mb-4">Mulai kelola tenaga pendidik dengan menambahkan data baru ke dalam sistem.</p>
                                <a href="{{ route('teachers.create') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold text-sm rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                                    Tambah Guru Pertama
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

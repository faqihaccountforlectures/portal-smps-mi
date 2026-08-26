@extends('layouts.app')

@section('title', 'Penugasan Guru')
@section('header', 'Penugasan Guru')

@section('content')
    <!-- Notifikasi kalau sukses atau ada pesan error -->
    @if(session('success'))
        <div class="bg-indigo-50 border border-indigo-200 text-indigo-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- 
      Desainnya kita samakan dengan halaman Mata Pelajaran (lebar penuh)
      Biar admin bisa ngeliat data-datanya dengan leluasa.
    -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-300 overflow-hidden">
        
        <!-- Header area -->
        <div class="px-6 py-5 border-b border-slate-300 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-800">Daftar Jadwal & Penugasan Guru</h2>
                <!-- Kita tampilin tahun ajaran aktifnya di sini biar jelas lagi ngatur tahun berapa -->
                <p class="text-xs text-slate-500 mt-1">Tahun Ajaran Aktif: <b class="text-blue-900">{{ $activeYear->year_name ?? '-' }}</b> ({{ ucfirst($activeYear->semester ?? '') }})</p>
            </div>
            
            <a href="{{ route('teacher-assignments.create') }}" class="inline-flex items-center gap-2 bg-blue-900 text-white font-semibold text-sm px-4 py-2 rounded-lg hover:bg-blue-800 hover:shadow-lg hover:shadow-blue-900/20 active:scale-[0.98] transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Penugasan</span>
            </a>
        </div>
        
        <!-- Area Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider border-b border-slate-300">
                        <th class="px-6 py-4 font-semibold border-r border-slate-300">Nama Guru</th>
                        <th class="px-6 py-4 font-semibold border-r border-slate-300">Mata Pelajaran</th>
                        <th class="px-6 py-4 font-semibold border-r border-slate-300">Kelas</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300 text-sm text-slate-600">
                    <!-- Kita looping datanya satu-satu -->
                    @forelse($assignments as $assignment)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        
                        <!-- Kolom Guru -->
                        <td class="px-6 py-4 border-r border-slate-300">
                            <div class="flex items-center gap-3">
                                <!-- Lingkaran inisial nama guru buat pemanis -->
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-900 flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($assignment->teacher->teacherProfile->full_name ?? '?', 0, 1)) }}
                                </div>
                                <div class="font-bold text-slate-800">
                                    {{ $assignment->teacher->teacherProfile->full_name ?? 'Tanpa Nama' }}
                                </div>
                            </div>
                        </td>
                        
                        <!-- Kolom Mata Pelajaran -->
                        <td class="px-6 py-4 border-r border-slate-300">
                            <div class="font-semibold text-slate-700">{{ $assignment->subject->name }}</div>
                            <div class="text-xs text-slate-400 font-mono mt-0.5">{{ $assignment->subject->code }}</div>
                        </td>
                        
                        <!-- Kolom Kelas -->
                        <td class="px-6 py-4 border-r border-slate-300">
                            <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                @foreach($assignment->classRooms as $cls)
                                    <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded-md text-[11px] font-bold border border-slate-200">
                                        {{ $cls->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        
                        <!-- Kolom Tombol Aksi -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('teacher-assignments.edit', ['teacher_id' => $assignment->teacher_id, 'subject_id' => $assignment->subject_id]) }}" class="text-slate-400 hover:text-blue-900 bg-white hover:bg-blue-50 border border-slate-200 hover:border-blue-200 p-2 rounded-lg shadow-sm transition-all" title="Edit Penugasan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button onclick="document.getElementById('deleteModal-{{ $assignment->teacher_id }}-{{ $assignment->subject_id }}').classList.remove('hidden')" class="text-slate-400 hover:text-red-600 bg-white hover:bg-red-50 border border-slate-200 hover:border-red-200 p-2 rounded-lg shadow-sm transition-all" title="Hapus Penugasan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Memanggil modal konfirmasi hapusnya di sini -->
                    @include('admin.teacher-assignments.delete')

                    @empty
                    <!-- Kalo belum ada guru yang dikasih tugas -->
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4 border border-slate-100 shadow-inner">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-base font-bold text-slate-700 mb-1">Belum ada penugasan guru</p>
                                <p class="text-sm text-slate-500 mb-5">Mari mulai membagi jadwal mengajar guru ke setiap kelas.</p>
                                <a href="{{ route('teacher-assignments.create') }}" class="text-sm text-blue-900 font-semibold bg-blue-50 px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors">
                                    + Tambah Penugasan
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Bawahnya dikasih informasi total data -->
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50 flex justify-between items-center text-xs text-slate-500">
            <span>Total: <b class="text-slate-700">{{ $assignments->count() }}</b> penugasan kelas</span>
        </div>
    </div>
@endsection

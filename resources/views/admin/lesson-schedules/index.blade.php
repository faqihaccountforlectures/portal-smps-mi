@extends('layouts.app')

@section('title', 'Manajemen Jadwal Pelajaran')
@section('header', 'Manajemen Jadwal Pelajaran')

@section('content')
        @if(session('success'))
        <div class="bg-white border-l-4 border-navy-base text-navy-dark px-5 py-4 rounded-xl mb-6 shadow-sm shadow-navy-base/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-navy-base p-2 rounded-lg text-white-off">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-rose-500/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-rose-100 p-2 rounded-lg text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        
        <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Jadwal Pelajaran</h2>
                <p class="text-xs text-gray-muted mt-1">Tahun Ajaran Aktif: <b class="text-navy-base">{{ $activeYear->year_name ?? '-' }}</b> ({{ ucfirst($activeYear->semester ?? '') }})</p>
            </div>
            
            <a href="{{ route('lesson-schedules.create') }}" class="inline-flex items-center gap-2 bg-navy-dark text-white-off font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-navy-base hover:shadow-lg hover:shadow-navy-base/20 active:scale-[0.98] transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Atur Jadwal Baru</span>
            </a>
        </div>
        
        <div class="p-7 border-b border-navy-light/30 bg-white-off/50">
            <form action="{{ route('lesson-schedules.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-64">
                    <label class="block text-sm font-bold text-navy-dark mb-1.5">Pilih Kelas</label>
                    <select name="class_room_id" onchange="this.form.submit()" class="w-full bg-white-off border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all">
                        <option value="" disabled {{ !$selectedClassId ? 'selected' : '' }}>-- Silakan Pilih Kelas --</option>
                        @foreach($classRooms as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @if($selectedClassId)
                <div class="w-full sm:w-64">
                    <label class="block text-sm font-bold text-navy-dark mb-1.5">Filter Hari</label>
                    <select name="day_filter" onchange="this.form.submit()" class="w-full bg-white-off border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all">
                        <option value="">Semua Hari</option>
                        @foreach($days as $day)
                            <option value="{{ $day }}" {{ request('day_filter') == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
        </div>

        @if($selectedClassId)
            <div class="bg-white-off/30">
                @if($schedules->isEmpty())
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-white-off rounded-full flex items-center justify-center text-gray-muted mx-auto mb-4 border border-navy-light/40 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-gray-muted">Belum ada jadwal pelajaran untuk kelas ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                                    <th class="px-7 py-4 font-bold">Hari</th>
                                    <th class="px-7 py-4 font-bold">Jam Pelajaran</th>
                                    <th class="px-7 py-4 font-bold">Mata Pelajaran</th>
                                    <th class="px-7 py-4 font-bold">Guru Pengajar</th>
                                    <th class="px-7 py-4 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white-off text-sm text-navy-base">
                                @foreach($days as $day)
                                    @if(isset($schedules[$day]) && count($schedules[$day]) > 0)
                                        @foreach($schedules[$day] as $schedule)
                                        <tr class="hover:bg-white-off/50 transition-colors group">
                                            {{-- Kolom Hari --}}
                                            <td class="px-7 py-4 font-bold text-navy-dark uppercase tracking-wider text-xs">
                                                {{ $day }}
                                            </td>
                                            
                                            {{-- Kolom Jam Pelajaran --}}
                                            <td class="px-7 py-4 font-mono font-bold text-navy-base text-xs">
                                                <div class="inline-flex items-center gap-1.5 bg-navy-light/10 px-3 py-1.5 rounded-lg border border-navy-light/30">
                                                    <span>{{ substr($schedule->start_time, 0, 5) }}</span>
                                                    <span class="text-[10px] text-gray-muted">S/D</span>
                                                    <span>{{ substr($schedule->end_time, 0, 5) }}</span>
                                                </div>
                                            </td>
                                            
                                            {{-- Kolom Mapel --}}
                                            <td class="px-7 py-4 font-bold text-navy-dark">
                                                {{ $schedule->teacherAssignment->subject->name }}
                                            </td>
                                            
                                            {{-- Kolom Guru --}}
                                            <td class="px-7 py-4 font-medium text-gray-muted">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-navy-light/20 flex items-center justify-center text-navy-base border border-navy-light/40">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                    </div>
                                                    {{ $schedule->teacherAssignment->teacher->teacherProfile->full_name ?? 'Guru' }}
                                                </div>
                                            </td>
                                            
                                            {{-- Kolom Aksi --}}
                                            <td class="px-7 py-4 text-right">
                                                <div class="flex justify-end items-center gap-2">
                                                    <a href="{{ route('lesson-schedules.edit', $schedule->id) }}" class="text-gray-muted hover:text-navy-base transition-all p-2 rounded-xl hover:bg-navy-light/30 border border-transparent hover:border-navy-light/50 bg-white" title="Edit Jadwal">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </a>
                                                    <button onclick="document.getElementById('deleteModal-{{ $schedule->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-rose-600 transition-all p-2 rounded-xl hover:bg-rose-50 border border-transparent hover:border-rose-200 bg-white" title="Hapus Jadwal">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @include('admin.lesson-schedules.delete')
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-16 bg-white-off/30">
                <p class="text-gray-muted font-bold text-sm">Silakan pilih kelas terlebih dahulu untuk melihat jadwal pelajaran.</p>
            </div>
        @endif
    </div>
@endsection


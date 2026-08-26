@extends('layouts.app')

@section('title', 'Manajemen Jadwal Pelajaran')
@section('header', 'Manajemen Jadwal Pelajaran')

@section('content')
    @if(session('success'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-300 overflow-hidden">
        
        <div class="px-6 py-5 border-b border-slate-300 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-800">Daftar Jadwal Pelajaran</h2>
                <p class="text-xs text-slate-500 mt-1">Tahun Ajaran Aktif: <b class="text-blue-900">{{ $activeYear->year_name ?? '-' }}</b> ({{ ucfirst($activeYear->semester ?? '') }})</p>
            </div>
            
            <a href="{{ route('lesson-schedules.create') }}" class="inline-flex items-center gap-2 bg-blue-900 text-white font-semibold text-sm px-4 py-2 rounded-lg hover:bg-blue-800 hover:shadow-lg hover:shadow-blue-900/20 active:scale-[0.98] transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Atur Jadwal Baru</span>
            </a>
        </div>
        
        <div class="p-6 border-b border-slate-300">
            <form action="{{ route('lesson-schedules.index') }}" method="GET" class="flex gap-4 items-end">
                <div class="w-full max-w-sm">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Kelas</label>
                    <select name="class_room_id" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 outline-none transition-all">
                        <option value="" disabled {{ !$selectedClassId ? 'selected' : '' }}>-- Silakan Pilih Kelas --</option>
                        @foreach($classRooms as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        @if($selectedClassId)
            <div class="p-6 bg-slate-50">
                @if($schedules->isEmpty())
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-slate-300 mx-auto mb-4 border border-slate-200 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-slate-500">Belum ada jadwal pelajaran untuk kelas ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 max-w-4xl mx-auto">
                        @foreach($days as $day)
                            @if(isset($schedules[$day]) && count($schedules[$day]) > 0)
                                <div class="border border-slate-300 rounded-xl overflow-hidden shadow-sm">
                                    <div class="bg-blue-900 text-white px-5 py-3 font-bold text-sm">
                                        HARI {{ strtoupper($day) }}
                                    </div>
                                    <div class="divide-y divide-slate-200 bg-white">
                                        @foreach($schedules[$day] as $schedule)
                                            <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center hover:bg-slate-50 transition-colors">
                                                <div class="flex items-center gap-5">
                                                    <div class="text-center w-24 bg-slate-50 rounded-lg p-2 border border-slate-200">
                                                        <div class="text-sm font-bold text-blue-900">{{ substr($schedule->start_time, 0, 5) }}</div>
                                                        <div class="text-[10px] text-slate-400 uppercase font-semibold my-0.5">s/d</div>
                                                        <div class="text-sm font-bold text-slate-700">{{ substr($schedule->end_time, 0, 5) }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-slate-800 text-base mb-1">{{ $schedule->teacherAssignment->subject->name }}</div>
                                                        <div class="text-sm text-slate-600 flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                            <span>{{ $schedule->teacherAssignment->teacher->teacherProfile->full_name ?? 'Guru' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-4 sm:mt-0 flex gap-2 self-end sm:self-auto">
                                                    <a href="{{ route('lesson-schedules.edit', $schedule->id) }}" class="text-slate-400 hover:text-blue-900 bg-white hover:bg-blue-50 border border-slate-200 hover:border-blue-200 p-2 rounded-lg shadow-sm transition-all" title="Edit Jadwal">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </a>
                                                    <button onclick="document.getElementById('deleteModal-{{ $schedule->id }}').classList.remove('hidden')" class="text-slate-400 hover:text-red-600 bg-white hover:bg-red-50 border border-slate-200 hover:border-red-200 p-2 rounded-lg shadow-sm transition-all" title="Hapus Jadwal">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </div>
                                            @include('admin.lesson-schedules.delete')
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-16 bg-slate-50">
                <p class="text-slate-500 font-medium">Silakan pilih kelas terlebih dahulu untuk melihat jadwal pelajaran.</p>
            </div>
        @endif
    </div>
@endsection

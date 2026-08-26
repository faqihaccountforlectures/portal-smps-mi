@extends('layouts.app')

@section('title', 'Edit Jadwal Pelajaran')
@section('header', 'Edit Jadwal Pelajaran')

@section('content')
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('lesson-schedules.index', ['class_room_id' => $schedule->teacherAssignment->class_room_id]) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-900 mb-6 transition-colors group font-medium">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Jadwal
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-300 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-700 to-blue-500"></div>
            
            <div class="p-6 sm:p-8">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-800">Edit Jadwal Pelajaran</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Kelas: <b class="text-slate-800">{{ $schedule->teacherAssignment->classRoom->name }}</b> | 
                        Tahun Ajaran: <b class="text-blue-900">{{ $activeYear->year_name }}</b>
                    </p>
                </div>

                @if($errors->any() || session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm font-bold">Terdapat kesalahan pada data jadwal:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1 ml-1">
                            @if(session('error'))
                                <li>{{ session('error') }}</li>
                            @endif
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('lesson-schedules.update', $schedule->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran & Guru <span class="text-red-500">*</span></label>
                            <select name="teacher_assignment_id" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 transition-all">
                                @foreach($assignments as $assignment)
                                    <option value="{{ $assignment->id }}" {{ $schedule->teacher_assignment_id == $assignment->id ? 'selected' : '' }}>
                                        {{ $assignment->subject->name }} ({{ $assignment->teacher->teacherProfile->full_name ?? 'Guru' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Hari <span class="text-red-500">*</span></label>
                            <select name="day_of_week" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 transition-all">
                                @foreach($days as $day)
                                    <option value="{{ $day }}" {{ $schedule->day_of_week == $day ? 'selected' : '' }}>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Mulai <span class="text-red-500">*</span></label>
                                <input type="time" name="start_time" value="{{ substr($schedule->start_time, 0, 5) }}" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 transition-all">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Selesai <span class="text-red-500">*</span></label>
                                <input type="time" name="end_time" value="{{ substr($schedule->end_time, 0, 5) }}" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex items-center justify-end">
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-900 hover:bg-blue-800 hover:shadow-lg hover:shadow-blue-900/20 rounded-xl active:scale-[0.98] transition-all flex items-center gap-2">
                            <span>Update Jadwal</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

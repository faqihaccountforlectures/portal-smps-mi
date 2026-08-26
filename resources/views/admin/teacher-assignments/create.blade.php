@extends('layouts.app')

@section('title', 'Tambah Penugasan Guru')
@section('header', 'Penugasan Guru')

@section('content')
    <!-- Lebar form dimaksimalkan biar tiga dropdown bisa jejer rapi ke samping -->
    <div class="max-w-5xl mx-auto">
        
        <!-- Tombol buat balik ke halaman list tanpa nyimpen -->
        <a href="{{ route('teacher-assignments.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 mb-6 transition-colors group font-medium">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-300 overflow-hidden relative">
            <!-- Garis gradasi di bagian paling atas (khas tema default kita indigo/ungu) -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-700 to-blue-500"></div>
            
            <div class="p-6 sm:p-8">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-800">Form Tambah Penugasan</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Silakan tentukan guru mana yang akan mengajar mata pelajaran apa di kelas mana.
                        <br>
                        Penugasan ini otomatis masuk ke Tahun Ajaran: <b class="text-blue-900">{{ $activeYear->year_name }} ({{ ucfirst($activeYear->semester) }})</b>.
                    </p>
                </div>

                <!-- Kalau semisal ada error (kayak input kosong atau guru kembar ngajar mapel dan kelas yang sama), pesan errornya muncul di sini -->
                @if($errors->any() || session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm font-bold">Waduh, ada masalah nih:</span>
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

                <!-- Formnya nembak langsung ke function store buat disimpen -->
                <form action="{{ route('teacher-assignments.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <!-- Kita pake grid 3 kolom supaya 3 dropdown sejajar menyamping (layout lebar) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        
                        <!-- Kolom 1: Pilih Guru -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Guru <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="teacher_id" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-xl pl-4 pr-10 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Guru --</option>
                                    @foreach($teachers as $teacher)
                                        <!-- Pengecekan biar pilihan lamanya gak ilang kalau kena validasi error -->
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->teacherProfile->full_name ?? 'Tanpa Nama' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom 2: Pilih Mata Pelajaran -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="subject_id" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-xl pl-4 pr-10 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Mapel --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }} ({{ $subject->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom 3: Pilih Kelas (Checkboxes) -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Kelas (Bisa Centang Banyak) <span class="text-red-500">*</span></label>
                            <div class="bg-slate-50 border border-slate-300 rounded-xl p-4 max-h-[140px] overflow-y-auto custom-scrollbar">
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach($classRooms as $class)
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" name="class_room_ids[]" value="{{ $class->id }}" 
                                                {{ (is_array(old('class_room_ids')) && in_array($class->id, old('class_room_ids'))) ? 'checked' : '' }}
                                                class="w-4 h-4 text-blue-900 bg-white border-slate-400 rounded focus:ring-blue-900/20 focus:ring-2 transition-all">
                                            <span class="text-sm font-medium text-slate-700 group-hover:text-blue-900 transition-colors">{{ $class->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-2 leading-relaxed">Centang kelas mana saja yang diajar oleh guru ini untuk mata pelajaran di samping.</p>
                        </div>
                        
                    </div>

                    <div class="pt-4 border-t border-slate-50 flex items-center justify-between">
                        <a href="{{ route('teacher-assignments.create') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors px-4 py-2 bg-slate-50 hover:bg-slate-100 rounded-lg">
                            Reset Pilihan
                        </a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-900 hover:bg-blue-800 hover:shadow-lg hover:shadow-blue-900/20 rounded-xl active:scale-[0.98] transition-all flex items-center gap-2">
                            <span>Simpan Penugasan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

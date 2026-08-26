@extends('layouts.app')

@section('title', 'Atur Jadwal Pelajaran')
@section('header', 'Atur Jadwal Pelajaran')

@section('content')
    <div class="max-w-6xl mx-auto">
        <a href="{{ route('lesson-schedules.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-900 mb-6 transition-colors group font-medium">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Jadwal
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-300 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-700 to-blue-500"></div>
            
            <div class="p-6 sm:p-8">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-800">Atur Jadwal Baru</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Tahun Ajaran Aktif: <b class="text-blue-900">{{ $activeYear->year_name }} ({{ ucfirst($activeYear->semester) }})</b>.
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

                <!-- STEP 1: Pilih Kelas -->
                <div class="mb-8 pb-6 border-b border-slate-200">
                    <form action="{{ route('lesson-schedules.create') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                        <div class="w-full sm:w-1/2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Langkah 1: Pilih Kelas <span class="text-red-500">*</span></label>
                            <select name="class_room_id" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 outline-none transition-all">
                                <option value="" disabled {{ !$selectedClassId ? 'selected' : '' }}>-- Silakan Pilih Kelas --</option>
                                @foreach($classRooms as $class)
                                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-blue-900 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl transition-all">
                            Tampilkan Mapel
                        </button>
                    </form>
                </div>

                <!-- STEP 2: Input Jadwal Secara Masif -->
                @if($selectedClassId)
                    <form action="{{ route('lesson-schedules.store') }}" method="POST" id="scheduleForm">
                        @csrf
                        <input type="hidden" name="class_room_id" value="{{ $selectedClassId }}">
                        
                        <div class="mb-4 flex justify-between items-end">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-700 mb-1">Langkah 2: Isi Jadwal Pelajaran (Kelas {{ $selectedClass->name }})</h3>
                                <p class="text-xs text-slate-500">Anda dapat mengisi banyak jadwal sekaligus. Klik tombol "Tambah Baris" untuk menambah mapel.</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 border border-slate-300 rounded-xl overflow-hidden mb-6">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse min-w-[700px]">
                                    <thead>
                                        <tr class="bg-slate-200 text-slate-700 text-xs uppercase tracking-wider">
                                            <th class="px-4 py-3 font-semibold border-r border-slate-300 w-1/3">Mata Pelajaran & Guru</th>
                                            <th class="px-4 py-3 font-semibold border-r border-slate-300 w-1/4">Hari</th>
                                            <th class="px-4 py-3 font-semibold border-r border-slate-300">Mulai</th>
                                            <th class="px-4 py-3 font-semibold border-r border-slate-300">Selesai</th>
                                            <th class="px-4 py-3 font-semibold text-center w-16">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scheduleRows" class="divide-y divide-slate-300">
                                        <!-- Baris Pertama (Default) -->
                                        <tr class="schedule-row bg-white">
                                            <td class="p-3 border-r border-slate-300">
                                                <select name="teacher_assignment_id[]" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-blue-900">
                                                    <option value="" disabled selected>-- Pilih Mapel --</option>
                                                    @foreach($assignments as $assignment)
                                                        <option value="{{ $assignment->id }}">
                                                            {{ $assignment->subject->name }} ({{ $assignment->teacher->teacherProfile->full_name ?? 'Guru' }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="p-3 border-r border-slate-300">
                                                <select name="day_of_week[]" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-blue-900">
                                                    @foreach($days as $day)
                                                        <option value="{{ $day }}">{{ $day }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="p-3 border-r border-slate-300">
                                                <input type="time" name="start_time[]" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-blue-900">
                                            </td>
                                            <td class="p-3 border-r border-slate-300">
                                                <input type="time" name="end_time[]" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-blue-900">
                                            </td>
                                            <td class="p-3 text-center">
                                                <button type="button" class="remove-row-btn text-slate-400 hover:text-red-600 transition-colors" title="Hapus Baris">
                                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 border-t border-slate-300 bg-slate-100 flex justify-center">
                                <button type="button" id="addRowBtn" class="text-sm font-semibold text-blue-900 hover:text-blue-700 flex items-center gap-1 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Tambah Baris Jadwal
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-6">
                            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-900 hover:bg-blue-800 hover:shadow-lg hover:shadow-blue-900/20 rounded-xl active:scale-[0.98] transition-all flex items-center gap-2">
                                <span>Simpan Semua Jadwal</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Template Row Hidden (Buat JS Clone) -->
    @if($selectedClassId)
        <template id="rowTemplate">
            <tr class="schedule-row bg-white">
                <td class="p-3 border-r border-slate-300">
                    <select name="teacher_assignment_id[]" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-blue-900">
                        <option value="" disabled selected>-- Pilih Mapel --</option>
                        @foreach($assignments as $assignment)
                            <option value="{{ $assignment->id }}">
                                {{ $assignment->subject->name }} ({{ $assignment->teacher->teacherProfile->full_name ?? 'Guru' }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="p-3 border-r border-slate-300">
                    <select name="day_of_week[]" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-blue-900">
                        @foreach($days as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="p-3 border-r border-slate-300">
                    <input type="time" name="start_time[]" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-blue-900">
                </td>
                <td class="p-3 border-r border-slate-300">
                    <input type="time" name="end_time[]" required class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-blue-900">
                </td>
                <td class="p-3 text-center">
                    <button type="button" class="remove-row-btn text-slate-400 hover:text-red-600 transition-colors" title="Hapus Baris">
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            </tr>
        </template>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addBtn = document.getElementById('addRowBtn');
                const tbody = document.getElementById('scheduleRows');
                const template = document.getElementById('rowTemplate');

                if (addBtn && tbody && template) {
                    // Tambah Baris
                    addBtn.addEventListener('click', function() {
                        const clone = template.content.cloneNode(true);
                        tbody.appendChild(clone);
                    });

                    // Hapus Baris
                    tbody.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-row-btn')) {
                            const rows = tbody.querySelectorAll('.schedule-row');
                            if (rows.length > 1) {
                                e.target.closest('.schedule-row').remove();
                            } else {
                                alert('Minimal harus ada 1 baris jadwal.');
                            }
                        }
                    });
                }
            });
        </script>
    @endif
@endsection

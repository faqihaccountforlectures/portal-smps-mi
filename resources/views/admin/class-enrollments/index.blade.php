@extends('layouts.app')

@section('title', 'Pembagian Kelas')
@section('header', 'Pembagian Kelas')

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

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-center gap-3" role="alert">
        <div class="bg-red-100 p-1.5 rounded-lg">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        <div>
            <span class="block sm:inline font-medium">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- TAHUN AJARAN INFO -->
    <div class="mb-6 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold mb-1">Tahun Ajaran: {{ strtoupper($activeYear->semester) }} {{ $activeYear->year_name }}</h2>
            <p class="text-blue-100 text-sm">Pilih kelas di bawah ini untuk melihat dan mengatur daftar siswanya.</p>
        </div>
        <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm border border-white/20 text-center min-w-[120px]">
            <span class="block text-3xl font-extrabold">{{ $classes->sum('enrollments_count') }}</span>
            <span class="text-xs font-semibold text-blue-100 uppercase tracking-wider">Total Siswa Terdaftar</span>
        </div>
        <div>
            <a href="{{ route('class-enrollments.graduate') }}" class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 px-4 py-2.5 rounded-lg font-bold shadow-md transition-colors flex items-center gap-2 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                Kelulusan & Alumni Kelas 9
            </a>
        </div>
    </div>

    <!-- DAFTAR KELAS -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-100/50 p-2 rounded-lg text-indigo-600 border border-indigo-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h2 class="font-bold text-slate-800 text-lg">Daftar PembagianKelas & Rombongan Belajar</h2>
            </div>
            <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100">{{ $classes->count() }} Kelas</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold border-b border-slate-100">Kelas</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-100">Wali Kelas</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-100 text-center">Jumlah Siswa</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-50">
                    @forelse($classes as $classRoom)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 font-bold text-sm">
                                    {{ $classRoom->grade_level }}
                                </span>
                                <div>
                                    <span class="font-bold text-slate-800 block text-base">{{ $classRoom->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            @if($classRoom->homeroomTeacher)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold">
                                        {{ substr($classRoom->homeroomTeacher->teacherProfile->full_name ?? 'G', 0, 1) }}
                                    </div>
                                    <span class="font-medium">{{ $classRoom->homeroomTeacher->teacherProfile->full_name ?? $classRoom->homeroomTeacher->email }}</span>
                                </div>
                            @else
                                <span class="text-slate-400 italic text-xs bg-slate-100 px-2 py-1 rounded">Belum diatur</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 rounded-full {{ $classRoom->enrollments_count > 0 ? 'bg-blue-50 text-blue-700 font-bold' : 'bg-slate-100 text-slate-500' }}">
                                {{ $classRoom->enrollments_count }} Siswa
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('class-enrollments.show', $classRoom->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Kelola Siswa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="bg-slate-100 p-4 rounded-full mb-3">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <p class="text-sm font-medium text-slate-500">Belum ada data kelas</p>
                                <p class="text-xs mt-1">Silakan tambahkan data kelas terlebih dahulu di menu Data Kelas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

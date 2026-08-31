@extends('layouts.app')

@section('title', 'Daftar Kelas Saya')
@section('header', 'Kelas Saya')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <!-- Pesan Peringatan jika tidak ada Tahun Ajaran aktif -->
    @if(isset($error))
    <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-800 p-4 rounded-r-xl shadow-sm flex items-start gap-3">
        <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <h4 class="font-bold">Perhatian</h4>
            <p class="text-sm mt-1">{{ $error }}</p>
        </div>
    </div>
    @else
    
    <!-- Header Informasi Tahun Ajaran -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 px-6 py-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="bg-blue-100 p-2.5 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Kelas</h2>
                <p class="text-sm text-slate-500">Kelas yang Anda ampu pada tahun ajaran aktif.</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Tahun Ajaran Aktif</p>
            <span class="inline-block bg-blue-50 text-blue-700 font-bold px-3 py-1 rounded-md border border-blue-200">
                {{ $activeAcademicYear->year_name }} - {{ ucfirst($activeAcademicYear->semester) }}
            </span>
        </div>
    </div>

    <!-- BAGIAN 1: Kelas Wali (Homeroom Classes) -->
    @if($homeroomClasses->count() > 0)
    <div>
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <h3 class="text-xl font-bold text-slate-800">Kelas Perwalian</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($homeroomClasses as $kelas)
            <a href="{{ route('guru.classes.show', $kelas->id) }}" class="group block bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all overflow-hidden relative">
                <div class="absolute top-0 right-0">
                    <div class="bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl uppercase tracking-widest shadow-sm">
                        Wali Kelas
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    
                    <h4 class="text-2xl font-bold text-slate-800 mb-1 group-hover:text-indigo-600 transition-colors">{{ $kelas->name }}</h4>
                    <p class="text-sm text-slate-500 flex items-center gap-1.5 mb-4">
                        Tingkat Kelas: <span class="font-semibold text-slate-700">{{ $kelas->grade_level }}</span>
                    </p>
                    
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-sm">
                        <span class="font-medium text-indigo-600 group-hover:text-indigo-800">Lihat Daftar Siswa &rarr;</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- BAGIAN 2: Kelas yang Diajar (Teaching Classes) -->
    @if($teachingClasses->count() > 0)
    <div>
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <h3 class="text-xl font-bold text-slate-800">Mata Pelajaran (Kelas Reguler)</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($teachingClasses as $kelas)
            <a href="{{ route('guru.classes.show', $kelas->id) }}" class="group block bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all overflow-hidden relative">
                <!-- Jika kelas ini kebetulan juga merupakan kelas perwaliannya, tampilkan tanda kecil -->
                @if($kelas->homeroom_teacher_id === Auth::id())
                <div class="absolute top-0 right-0">
                    <div class="bg-indigo-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-bl-lg uppercase tracking-widest shadow-sm">
                        Wali Kelas
                    </div>
                </div>
                @endif

                <div class="p-6">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    
                    <h4 class="text-2xl font-bold text-slate-800 mb-1 group-hover:text-emerald-600 transition-colors">{{ $kelas->name }}</h4>
                    <p class="text-sm text-slate-500 flex items-center gap-1.5 mb-4">
                        Tingkat Kelas: <span class="font-semibold text-slate-700">{{ $kelas->grade_level }}</span>
                    </p>
                    
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-sm">
                        <span class="font-medium text-emerald-600 group-hover:text-emerald-800">Lihat Daftar Siswa &rarr;</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tampilan Kosong jika tidak mengajar dan bukan wali kelas -->
    @if($homeroomClasses->count() === 0 && $teachingClasses->count() === 0)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 flex flex-col items-center justify-center text-center">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Penugasan Kelas</h3>
        <p class="text-slate-500 max-w-md">Anda belum ditugaskan sebagai wali kelas ataupun guru mata pelajaran di tahun ajaran ini. Silakan hubungi admin sekolah jika ini merupakan sebuah kesalahan.</p>
    </div>
    @endif
    
    @endif
</div>
@endsection

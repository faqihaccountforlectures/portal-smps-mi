@extends('layouts.app')

@section('title', 'Daftar Kelas Saya')
@section('header', 'Kelas Saya')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <!-- Pesan Peringatan jika tidak ada Tahun Ajaran aktif -->
    @if(isset($error))
    <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-800 p-5 rounded-r-2xl shadow-sm flex items-start gap-4 border border-amber-200/60">
        <div class="bg-amber-100 p-2.5 rounded-xl text-amber-600 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div>
            <h4 class="font-bold font-heading text-base">Perhatian Informasi</h4>
            <p class="text-xs mt-1 text-amber-700 leading-relaxed">{{ $error }}</p>
        </div>
    </div>
    @else
    
    <!-- Hero Banner Kelas Saya bertema Navy -->
    <div class="bg-gradient-to-br from-navy-dark to-navy-base rounded-2xl p-7 text-white-off shadow-xl shadow-navy-dark/20 mb-8 relative overflow-hidden group">
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-navy-light/20 rounded-full blur-3xl group-hover:bg-navy-light/30 transition-colors duration-500"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold mb-1.5 font-heading tracking-wide">Daftar Kelas Saya</h2>
                <p class="text-navy-light text-sm">Menampilkan daftar seluruh ruang kelas yang Anda ampu pada semester berjalan.</p>
            </div>
            
            <div class="shrink-0">
                <span class="inline-flex items-center gap-1.5 bg-navy-dark/60 text-white-off font-bold text-xs px-3.5 py-1.5 rounded-lg border border-navy-light/30 font-mono">
                    <span class="w-2 h-2 rounded-full bg-navy-light animate-pulse"></span>
                    {{ $activeAcademicYear->year_name }} - {{ ucfirst($activeAcademicYear->semester) }}
                </span>
            </div>
        </div>
    </div>

    <!-- BAGIAN 1: Kelas Wali (Homeroom Classes) -->
    @if($homeroomClasses->count() > 0)
    <div>
        <div class="flex items-center gap-2.5 mb-5">
            <div class="w-8 h-8 rounded-xl bg-navy-base text-white flex items-center justify-center font-bold text-sm shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-navy-dark font-heading">Kelas Perwalian (Wali Kelas)</h3>
                <p class="text-xs text-gray-muted">Anda bertanggung jawab penuh sebagai wali kelas untuk kelas berikut.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($homeroomClasses as $kelas)
            <a href="{{ route('guru.classes.show', $kelas->id) }}" class="group block bg-white rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 hover:shadow-lg hover:border-navy-base/50 hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
                <!-- Badge Banner Wali Kelas -->
                <div class="absolute top-0 right-0">
                    <div class="bg-gradient-to-r from-navy-base to-navy-dark text-white-off text-[10px] font-bold px-3 py-1.5 rounded-bl-xl uppercase tracking-widest shadow-sm border-b border-l border-navy-light/20 flex items-center gap-1">
                        <svg class="w-3 h-3 text-navy-light" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        Wali Kelas
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="w-12 h-12 bg-navy-light/10 rounded-xl flex items-center justify-center text-navy-base mb-4 border border-navy-light/30 group-hover:scale-110 group-hover:bg-navy-base group-hover:text-white transition-all duration-300 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    
                    <h4 class="text-2xl font-bold text-navy-dark font-heading mb-1 group-hover:text-navy-base transition-colors">{{ $kelas->name }}</h4>
                    <p class="text-xs text-gray-muted flex items-center gap-1.5 mb-5 font-medium">
                        <span>Tingkat Kelas:</span>
                        <span class="font-bold text-navy-dark bg-white-off px-2 py-0.5 rounded border border-navy-light/20">{{ $kelas->grade_level }}</span>
                    </p>
                    
                    <div class="pt-4 border-t border-navy-light/20 flex items-center justify-between text-xs font-bold text-navy-base group-hover:text-navy-dark">
                        <span>Lihat Data Siswa</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
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
        <div class="flex items-center gap-2.5 mb-5">
            <div class="w-8 h-8 rounded-xl bg-navy-base text-white flex items-center justify-center font-bold text-sm shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-navy-dark font-heading">Kelas Mata Pelajaran (Kelas Reguler)</h3>
                <p class="text-xs text-gray-muted">Daftar kelas di mana Anda mengampu mata pelajaran.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($teachingClasses as $kelas)
            <a href="{{ route('guru.classes.show', $kelas->id) }}" class="group block bg-white rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 hover:shadow-lg hover:border-navy-base/50 hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
                @if($kelas->homeroom_teacher_id === Auth::id())
                <div class="absolute top-0 right-0">
                    <div class="bg-navy-base text-white-off text-[9px] font-bold px-2.5 py-1 rounded-bl-lg uppercase tracking-widest shadow-sm">
                        Wali Kelas
                    </div>
                </div>
                @endif

                <div class="p-6">
                    <div class="w-12 h-12 bg-navy-light/10 text-navy-base rounded-xl flex items-center justify-center mb-4 border border-navy-light/30 group-hover:scale-110 group-hover:bg-navy-base group-hover:text-white transition-all duration-300 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    
                    <h4 class="text-2xl font-bold text-navy-dark font-heading mb-1 group-hover:text-navy-base transition-colors">{{ $kelas->name }}</h4>
                    <p class="text-xs text-gray-muted flex items-center gap-1.5 mb-5 font-medium">
                        <span>Tingkat Kelas:</span>
                        <span class="font-bold text-navy-dark bg-white-off px-2 py-0.5 rounded border border-navy-light/20">{{ $kelas->grade_level }}</span>
                    </p>
                    
                    <div class="pt-4 border-t border-navy-light/20 flex items-center justify-between text-xs font-bold text-navy-base group-hover:text-navy-dark">
                        <span>Lihat Data Siswa</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tampilan Kosong jika tidak mengajar dan bukan wali kelas -->
    @if($homeroomClasses->count() === 0 && $teachingClasses->count() === 0)
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 p-12 flex flex-col items-center justify-center text-center">
        <div class="w-20 h-20 bg-white-off text-navy-light rounded-full flex items-center justify-center mb-4 border border-navy-light/20 shadow-inner">
            <svg class="w-10 h-10 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-navy-dark font-heading mb-2">Belum Ada Penugasan Kelas</h3>
        <p class="text-gray-muted text-xs max-w-md leading-relaxed">Anda saat ini belum ditugaskan sebagai wali kelas ataupun guru mata pelajaran pada tahun ajaran ini. Silakan hubungi bagian kurikulum atau admin sekolah.</p>
    </div>
    @endif
    
    @endif
</div>
@endsection

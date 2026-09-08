@extends('layouts.app')

@section('title', 'Ekstrakurikuler Binaan')
@section('header', 'Ekstrakurikuler Binaan')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Hero Banner Ekstrakurikuler Binaan bertema Navy -->
    <div class="bg-gradient-to-br from-navy-dark to-navy-base rounded-2xl p-7 text-white-off shadow-xl shadow-navy-dark/20 mb-8 relative overflow-hidden group">
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-navy-light/20 rounded-full blur-3xl group-hover:bg-navy-light/30 transition-colors duration-500"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold mb-1.5 font-heading tracking-wide">Daftar Ekstrakurikuler Binaan</h2>
                <p class="text-navy-light text-sm">Menampilkan kegiatan ekstrakurikuler di mana Anda bertindak sebagai pembina utama.</p>
            </div>
            
            <div class="shrink-0">
                <span class="inline-flex items-center gap-1.5 bg-navy-dark/60 text-white-off font-bold text-xs px-3.5 py-1.5 rounded-lg border border-navy-light/30 font-mono">
                    <span class="w-2 h-2 rounded-full bg-navy-light animate-pulse"></span>
                    Total: {{ $extracurriculars->count() }} Kegiatan
                </span>
            </div>
        </div>
    </div>

    @if($extracurriculars->isEmpty())
        <!-- Tampilan jika belum ada ekstrakurikuler binaan -->
        <div class="bg-white rounded-2xl border border-navy-light/30 p-12 text-center shadow-sm shadow-navy-base/5 flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-white-off text-navy-light rounded-full flex items-center justify-center mb-4 border border-navy-light/20 shadow-inner">
                <svg class="w-10 h-10 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-navy-dark font-heading mb-2">Belum Ada Ekstrakurikuler Binaan</h3>
            <p class="text-xs text-gray-muted max-w-md leading-relaxed">Anda saat ini belum ditugaskan oleh admin sekolah sebagai pembina kegiatan ekstrakurikuler apa pun.</p>
        </div>
    @else
        <!-- Grid Daftar Ekstrakurikuler -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($extracurriculars as $ekskul)
            <div class="bg-white rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 overflow-hidden hover:shadow-lg hover:border-navy-base/40 hover:-translate-y-1 transition-all duration-300 flex flex-col group">
                <!-- Foto/Ilustrasi Ekstrakurikuler -->
                <div class="h-44 bg-navy-light/10 relative overflow-hidden">
                    @if($ekskul->image)
                        <img src="{{ asset('storage/' . $ekskul->image) }}" alt="{{ $ekskul->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-navy-light/40 bg-gradient-to-br from-navy-light/10 to-navy-base/10">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    <!-- Lencana Jumlah Anggota -->
                    <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-navy-dark shadow-sm flex items-center gap-1.5 border border-navy-light/30">
                        <svg class="w-3.5 h-3.5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        {{ $ekskul->registrations_count }} Siswa
                    </div>
                </div>
                
                <!-- Informasi Ekstrakurikuler -->
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold text-navy-dark font-heading mb-2 group-hover:text-navy-base transition-colors">{{ $ekskul->name }}</h3>
                    <p class="text-xs text-gray-muted line-clamp-2 mb-5 leading-relaxed">{{ $ekskul->description ?: 'Belum ada deskripsi singkat.' }}</p>
                    
                    <div class="mt-auto pt-4 border-t border-navy-light/20 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 text-xs font-medium text-navy-dark truncate">
                            <svg class="w-4 h-4 text-navy-base shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="truncate font-semibold">{{ $ekskul->schedule }}</span>
                        </div>
                        <a href="{{ route('guru.extracurriculars.show', $ekskul->id) }}" class="text-navy-base hover:text-navy-dark font-bold text-xs flex items-center gap-1 shrink-0 whitespace-nowrap transition-colors">
                            <span>Detail Anggota</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

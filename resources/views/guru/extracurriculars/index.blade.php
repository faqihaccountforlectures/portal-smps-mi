@extends('layouts.app')

@section('title', 'Ekstrakurikuler Binaan')
@section('header', 'Ekstrakurikuler Binaan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Ekstrakurikuler</h2>
            <p class="text-slate-500 text-sm mt-1">Ekstrakurikuler di mana Anda ditugaskan sebagai pembina utama.</p>
        </div>
    </div>

    @if($extracurriculars->isEmpty())
        <!-- Tampilan jika belum ada ekstrakurikuler binaan -->
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
            <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Ekstrakurikuler</h3>
            <p class="text-slate-500">Anda belum ditugaskan sebagai pembina untuk ekstrakurikuler apa pun.</p>
        </div>
    @else
        <!-- Grid Daftar Ekstrakurikuler -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($extracurriculars as $ekskul)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition duration-300 flex flex-col">
                <!-- Foto/Ilustrasi Ekstrakurikuler -->
                <div class="h-40 bg-blue-50 relative">
                    @if($ekskul->image)
                        <img src="{{ asset('storage/' . $ekskul->image) }}" alt="{{ $ekskul->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-blue-200">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    <!-- Lencana Jumlah Pendaftar -->
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-blue-700 shadow-sm flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        {{ $ekskul->registrations_count }} Anggota
                    </div>
                </div>
                
                <!-- Informasi Ekstrakurikuler -->
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $ekskul->name }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ $ekskul->description ?: 'Tidak ada deskripsi.' }}</p>
                    
                    <div class="mt-auto pt-4 border-t border-gray-50 flex items-start justify-between gap-3">
                        <div class="flex items-start gap-2 text-sm text-slate-600 flex-1">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="line-clamp-2">{{ $ekskul->schedule }}</span>
                        </div>
                        <a href="{{ route('guru.extracurriculars.show', $ekskul->id) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1 group shrink-0 whitespace-nowrap">
                            Lihat Detail
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

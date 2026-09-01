@extends('layouts.app')

@section('title', 'Detail Ekstrakurikuler')
@section('header', 'Detail Ekstrakurikuler')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header/Navigasi -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('guru.extracurriculars.index') }}" class="p-2 bg-white rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">{{ $extracurricular->name }}</h2>
            <p class="text-slate-500 text-sm">Informasi lengkap dan daftar anggota ekstrakurikuler.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Info Panel (Kiri) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <!-- Foto Ekstrakurikuler -->
                <div class="w-full h-48 bg-blue-50 rounded-xl overflow-hidden mb-6 flex items-center justify-center">
                    @if($extracurricular->image)
                        <img src="{{ asset('storage/' . $extracurricular->image) }}" alt="{{ $extracurricular->name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    @endif
                </div>

                <h3 class="font-bold text-slate-800 text-lg mb-4">Informasi Kegiatan</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-0.5">Jadwal</p>
                            <p class="text-sm font-medium text-slate-700">{{ $extracurricular->schedule }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-purple-50 rounded-lg text-purple-600 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-0.5">Total Anggota</p>
                            <p class="text-sm font-medium text-slate-700">{{ $extracurricular->registrations->count() }} Siswa mendaftar</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-2">Deskripsi</p>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $extracurricular->description ?: 'Belum ada deskripsi untuk ekstrakurikuler ini.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Pendaftar (Kanan) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800">Daftar Anggota / Pendaftar</h3>
                        <p class="text-sm text-slate-500 mt-1">Status persetujuan pendaftaran dilakukan oleh Admin.</p>
                    </div>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50 text-slate-500 border-b border-gray-100">
                            <tr>
                                <th class="py-3.5 px-6 font-semibold">Siswa</th>
                                <th class="py-3.5 px-6 font-semibold">NISN</th>
                                <th class="py-3.5 px-6 font-semibold">Tanggal Daftar</th>
                                <th class="py-3.5 px-6 font-semibold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($extracurricular->registrations as $reg)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                            {{ substr($reg->student->studentProfile->full_name ?? 'S', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $reg->student->studentProfile->full_name ?? 'Siswa Tidak Diketahui' }}</p>
                                            <p class="text-xs text-slate-500">{{ $reg->student->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ $reg->student->studentProfile->nisn ?? '-' }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ $reg->created_at->format('d M Y') }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <!-- Menampilkan badge sesuai status, guru hanya bisa melihat (read-only) -->
                                    @if($reg->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Disetujui
                                        </span>
                                    @elseif($reg->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Menunggu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-200/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center">
                                    <div class="text-gray-400 mb-2 flex justify-center">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <p class="text-slate-500 font-medium">Belum ada siswa yang mendaftar</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Ekstrakurikuler')
@section('header', 'Detail Ekstrakurikuler')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header / Navigasi Kembali -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('guru.extracurriculars.index') }}" class="p-2 bg-white rounded-xl border border-navy-light/30 text-navy-base hover:border-navy-base/50 hover:scale-105 transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-navy-dark font-heading">{{ $extracurricular->name }}</h2>
            <p class="text-xs text-gray-muted mt-0.5">Informasi lengkap kegiatan dan daftar seluruh siswa pendaftar.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Info Panel (Kiri) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-navy-light/30 p-6 shadow-sm shadow-navy-base/5 group hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                <!-- Foto Ekstrakurikuler -->
                <div class="w-full h-48 bg-navy-light/10 rounded-xl overflow-hidden mb-6 flex items-center justify-center border border-navy-light/20 relative">
                    @if($extracurricular->image)
                        <img src="{{ asset('storage/' . $extracurricular->image) }}" alt="{{ $extracurricular->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center text-navy-light">
                            <svg class="w-16 h-16 text-navy-base/40 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>

                <h3 class="font-bold text-navy-dark text-lg font-heading mb-4 pb-2 border-b border-navy-light/20">Informasi Kegiatan</h3>
                
                <div class="space-y-4 text-xs">
                    <div class="flex items-start gap-3">
                        <div class="p-2.5 bg-navy-light/10 rounded-xl text-navy-base border border-navy-light/30 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-muted uppercase tracking-wider mb-0.5">Jadwal Pelaksanaan</p>
                            <p class="font-bold text-navy-dark text-sm">{{ $extracurricular->schedule }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-200 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-muted uppercase tracking-wider mb-0.5">Total Anggota</p>
                            <p class="font-bold text-navy-dark text-sm">{{ $extracurricular->registrations_count ?? $registrations->total() }} Siswa mendaftar</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-navy-light/20">
                        <p class="text-[10px] font-bold text-gray-muted uppercase tracking-wider mb-2">Deskripsi Lengkap</p>
                        <p class="text-xs text-navy-dark leading-relaxed font-medium">{{ $extracurricular->description ?: 'Belum ada deskripsi untuk ekstrakurikuler ini.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Pendaftar (Kanan) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 overflow-hidden flex flex-col h-full group hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                <div class="p-6 border-b border-navy-light/20 bg-white-off/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-navy-dark text-base font-heading">Daftar Anggota & Pendaftar</h3>
                        <p class="text-xs text-gray-muted mt-0.5">Status verifikasi persetujuan pendaftaran dikelola oleh Administrator.</p>
                    </div>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-white text-gray-muted border-b border-navy-light/20 text-[10px] font-bold uppercase tracking-wider">
                            <tr>
                                <th class="py-4 px-6 font-semibold">Nama Siswa</th>
                                <th class="py-4 px-6 font-semibold">NISN</th>
                                <th class="py-4 px-6 font-semibold">Tanggal Daftar</th>
                                <th class="py-4 px-6 font-semibold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-navy-light/10">
                            @forelse($registrations as $reg)
                            <tr class="hover:bg-white-off/80 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-navy-dark text-white-off flex items-center justify-center font-bold text-xs shrink-0 shadow-inner font-heading">
                                            {{ substr($reg->student->studentProfile->full_name ?? ($reg->student->name ?? 'S'), 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-navy-dark text-sm font-heading">{{ $reg->student->studentProfile->full_name ?? ($reg->student->name ?? 'Siswa Tidak Diketahui') }}</p>
                                            <p class="text-[10px] text-gray-muted">{{ $reg->student->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-bold text-navy-dark font-mono">
                                    <span class="bg-white-off px-2.5 py-1 rounded border border-navy-light/20">
                                        {{ $reg->student->studentProfile->nisn ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-navy-dark font-medium">
                                    {{ $reg->created_at->format('d M Y') }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($reg->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Disetujui
                                        </span>
                                    @elseif($reg->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Menunggu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center">
                                    <div class="w-14 h-14 bg-white-off text-navy-light rounded-full flex items-center justify-center mx-auto mb-3 border border-navy-light/20">
                                        <svg class="w-7 h-7 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <p class="text-navy-dark font-bold text-sm font-heading mb-0.5">Belum Ada Pendaftar</p>
                                    <p class="text-xs text-gray-muted">Belum ada siswa yang mendaftar pada kegiatan ekstrakurikuler ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($registrations->hasPages())
                <div class="px-6 py-4 border-t border-navy-light/20 bg-white-off/50">
                    {{ $registrations->links() }}
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

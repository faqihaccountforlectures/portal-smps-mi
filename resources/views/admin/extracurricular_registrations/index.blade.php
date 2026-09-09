@extends('layouts.app')

@section('title', 'Pendaftaran Ekstrakurikuler')
@section('header', 'Pendaftaran Ekstrakurikuler')

@section('content')
    <!-- Alert pesan sukses jika admin berhasil menyetujui/menolak pendaftaran -->
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-emerald-500/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-emerald-500 p-2 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-rose-500/10 flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <div class="bg-rose-500 p-2 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <span class="text-sm font-bold tracking-wide">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Cards Ringkasan Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <!-- Total Pendaftaran -->
        <div class="bg-white p-5 rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-gray-muted uppercase tracking-wider">Total Permohonan</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $totalCount ?? $registrations->total() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center border border-navy-light/30 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>

        <!-- Menunggu Persetujuan -->
        <div class="bg-white p-5 rounded-2xl border border-amber-200/60 shadow-sm shadow-amber-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-amber-300 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Menunggu Persetujuan</p>
                <h3 class="text-2xl font-bold text-amber-800 font-heading mt-1">{{ $pendingCount ?? $registrations->where('status', 'pending')->count() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Disetujui -->
        <div class="bg-white p-5 rounded-2xl border border-emerald-200/60 shadow-sm shadow-emerald-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-emerald-300 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Disetujui</p>
                <h3 class="text-2xl font-bold text-emerald-800 font-heading mt-1">{{ $approvedCount ?? $registrations->where('status', 'approved')->count() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="bg-white p-5 rounded-2xl border border-rose-200/60 shadow-sm shadow-rose-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-rose-300 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Ditolak</p>
                <h3 class="text-2xl font-bold text-rose-800 font-heading mt-1">{{ $rejectedCount ?? $registrations->where('status', 'rejected')->count() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-200 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Tabel Utama -->
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        
        <!-- Header Tabel -->
        <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Pendaftaran Ekstrakurikuler</h2>
                    <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-bold">Kelola dan verifikasi permohonan pendaftaran ekskul dari para siswa.</p>
                </div>
            </div>

            <span class="text-xs font-bold text-navy-base bg-navy-light/20 px-3 py-1.5 rounded-lg border border-navy-light/40">
                Total: {{ $totalCount ?? $registrations->total() }} Pendaftar
            </span>
        </div>
        
        <!-- Container untuk Tabel -->
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                        <th class="px-7 py-4 font-bold">Nama Siswa</th>
                        <th class="px-7 py-4 font-bold">Ekstrakurikuler</th>
                        <th class="px-7 py-4 font-bold">Tanggal Daftar</th>
                        <th class="px-7 py-4 font-bold text-center">Status</th>
                        <th class="px-7 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white-off text-sm text-navy-base">
                    @forelse($registrations as $registration)
                    <tr class="hover:bg-white-off/50 transition-colors group/row">
                        
                        <!-- Kolom Nama Siswa -->
                        <td class="px-7 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center font-bold text-sm border border-navy-light/30 shrink-0 shadow-sm group-hover/row:scale-105 transition-transform">
                                    {{ substr($registration->student->studentProfile->full_name ?? $registration->student->email ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-navy-dark block text-sm">{{ $registration->student->studentProfile->full_name ?? $registration->student->email }}</p>
                                    @if($registration->student->studentProfile && $registration->student->studentProfile->nisn)
                                        <p class="text-[11px] text-gray-muted font-semibold flex items-center gap-1 mt-0.5 tracking-wide">
                                            <span class="bg-white-off px-2 py-0.5 rounded border border-navy-light/20 font-mono">NISN: {{ $registration->student->studentProfile->nisn }}</span>
                                        </p>
                                    @else
                                        <p class="text-[11px] text-gray-muted font-semibold flex items-center gap-1 mt-0.5 tracking-wide">
                                            <svg class="w-3.5 h-3.5 text-navy-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $registration->student->email }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <!-- Kolom Ekstrakurikuler -->
                        <td class="px-7 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-navy-light/10 border border-navy-light/30 text-navy-dark px-3 py-1.5 rounded-lg text-[11px] font-bold tracking-wide uppercase shadow-sm">
                                <svg class="w-3.5 h-3.5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                {{ $registration->extracurricular->name }}
                            </span>
                        </td>
                        
                        <!-- Kolom Tanggal Daftar -->
                        <td class="px-7 py-4">
                            <div class="text-xs font-semibold text-navy-dark flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $registration->created_at->format('d M Y') }}
                            </div>
                            <div class="text-[11px] text-gray-muted font-medium mt-0.5 pl-5">
                                Pukul {{ $registration->created_at->format('H:i') }} WIB
                            </div>
                        </td>
                        
                        <!-- Kolom Status -->
                        <td class="px-7 py-4 text-center">
                            @if($registration->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200/60 text-amber-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    <span class="relative flex h-2 w-2">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                    </span>
                                    Menunggu
                                </span>
                            @elseif($registration->status === 'approved')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200/60 text-emerald-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Disetujui
                                </span>
                            @elseif($registration->status === 'rejected')
                                <span class="inline-flex items-center gap-1.5 bg-rose-50 border border-rose-200/60 text-rose-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-white-off border border-navy-light/40 text-navy-dark px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                    {{ ucfirst($registration->status) }}
                                </span>
                            @endif
                        </td>
                        
                        <!-- Kolom Aksi -->
                        <td class="px-7 py-4 text-center">
                            @if($registration->status === 'pending')
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Tombol Approve -->
                                    <form action="{{ route('extracurricular-registrations.approve', $registration->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 shadow-sm active:scale-95 transition-all duration-200" title="Setujui Pendaftaran">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            <span>Terima</span>
                                        </button>
                                    </form>
                                    
                                    <!-- Tombol Reject -->
                                    <button type="button" onclick="document.getElementById('rejectModal-{{ $registration->id }}').classList.remove('hidden')" class="bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 hover:border-transparent font-bold px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 shadow-sm active:scale-95 transition-all duration-200" title="Tolak Pendaftaran">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        <span>Tolak</span>
                                    </button>

                                    <!-- Modal Reject Pendaftaran -->
                                    <div id="rejectModal-{{ $registration->id }}" class="hidden fixed inset-0 z-50 bg-navy-dark/40 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
                                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center relative whitespace-normal border border-navy-light/20 animate-[scale-in_0.2s_ease-out]">
                                            <div class="p-6">
                                                <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl border border-rose-100 flex items-center justify-center mx-auto mb-4 shadow-sm">
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                </div>
                                                <h3 class="font-bold text-base text-navy-dark font-heading mb-1">Tolak Pendaftaran?</h3>
                                                <p class="text-xs text-gray-muted leading-relaxed mb-6">Apakah Anda yakin ingin menolak permohonan <b>{{ $registration->student->studentProfile->full_name ?? $registration->student->email }}</b> untuk ekstrakurikuler <b>{{ $registration->extracurricular->name }}</b>?</p>
                                                
                                                <form action="{{ route('extracurricular-registrations.reject', $registration->id) }}" method="POST" class="flex gap-3 justify-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <button type="button" onclick="document.getElementById('rejectModal-{{ $registration->id }}').classList.add('hidden')" class="px-5 py-2.5 bg-white-off text-navy-dark font-bold text-xs rounded-xl hover:bg-navy-light/20 border border-navy-light/30 transition-all">Batal</button>
                                                    <button type="submit" class="px-5 py-2.5 bg-rose-600 text-white font-bold text-xs rounded-xl hover:bg-rose-700 hover:shadow-lg hover:shadow-rose-600/20 active:scale-95 transition-all">Ya, Tolak</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-muted/60 text-xs italic font-medium">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <!-- Tampilan jika tidak ada data pendaftaran -->
                    <tr>
                        <td colspan="5" class="px-7 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-muted">
                                <div class="bg-white-off border border-navy-light/30 p-4 rounded-2xl mb-4 shadow-inner text-navy-base">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-base font-bold text-navy-dark font-heading mb-1">Belum Ada Pendaftaran</h3>
                                <p class="text-xs text-gray-muted max-w-sm leading-relaxed">Saat ini belum terdapat siswa yang mengajukan pendaftaran ekstrakurikuler.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Footer Tabel & Paginasi -->
        @if($registrations->hasPages())
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-center gap-4 mt-auto">
            <span class="text-xs text-gray-muted font-medium">
                Menampilkan <b class="text-navy-dark">{{ $registrations->firstItem() }}</b> - <b class="text-navy-dark">{{ $registrations->lastItem() }}</b> dari <b class="text-navy-dark">{{ $registrations->total() }}</b> pendaftaran
            </span>
            <div class="pagination-wrapper">
                {{ $registrations->links() }}
            </div>
        </div>
        @else
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex justify-between items-center text-xs text-gray-muted font-medium mt-auto">
            <span>Total Terdaftar: <b class="text-navy-dark">{{ $registrations->total() }}</b> pendaftaran</span>
        </div>
        @endif
    </div>
@endsection

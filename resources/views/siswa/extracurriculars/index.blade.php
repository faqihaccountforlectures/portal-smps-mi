@extends('layouts.app')

@section('title', 'Katalog Ekstrakurikuler')
@section('header', 'Katalog Ekstrakurikuler')

@section('content')
    <!-- Alert pesan sukses atau error -->
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

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-navy-dark font-heading tracking-wide">Pilih Kegiatan Ekstrakurikuler</h2>
            <p class="text-xs text-gray-muted mt-0.5 tracking-wide font-bold">Daftarkan diri Anda ke kegiatan ekstrakurikuler yang sesuai dengan minat dan bakat.</p>
        </div>
    </div>

    <!-- Grid Katalog Ekskul -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($extracurriculars as $ekskul)
            @php
                $registration = $myRegistrations->get($ekskul->id);
                $status = $registration ? $registration->status : null;
            @endphp

            <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <!-- Header Kartu Foto / Gradien -->
                <div class="h-36 relative overflow-hidden text-white-off flex flex-col justify-end p-5 group/header">
                    @if($ekskul->image)
                        <img src="{{ asset('storage/' . $ekskul->image) }}" class="absolute inset-0 w-full h-full object-cover group-hover/header:scale-105 transition-transform duration-500" alt="{{ $ekskul->name }}">
                        <!-- Overlay gradien hitam/navy agar teks nama ekskul kontras dan terbaca jelas -->
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-dark/95 via-navy-dark/50 to-transparent"></div>
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-navy-dark to-navy-base"></div>
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-navy-light/20 rounded-full blur-xl group-hover/header:bg-navy-light/30 transition-colors duration-500"></div>
                    @endif
                    <h3 class="text-xl font-bold font-heading tracking-wide relative z-10 drop-shadow-md text-white-off">{{ $ekskul->name }}</h3>
                </div>
                
                <!-- Body Kartu -->
                <div class="p-6 flex-1 flex flex-col">
                    <div class="space-y-4 mb-6 flex-1">
                        <!-- Info Pembina -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center shrink-0 border border-navy-light/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-muted font-bold uppercase tracking-wider">Pembina Ekskul</p>
                                <p class="text-xs font-bold text-navy-dark mt-0.5">{{ $ekskul->teacher->teacherProfile->full_name ?? $ekskul->teacher->email }}</p>
                            </div>
                        </div>
                        
                        <!-- Info Jadwal -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center shrink-0 border border-navy-light/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-muted font-bold uppercase tracking-wider">Jadwal Latihan</p>
                                <p class="text-xs font-bold text-navy-dark mt-0.5">{{ $ekskul->schedule }}</p>
                            </div>
                        </div>

                        <!-- Info Biaya -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center shrink-0 border border-navy-light/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-muted font-bold uppercase tracking-wider">Iuran per Bulan</p>
                                <p class="text-sm font-bold text-navy-base mt-0.5">
                                    @if($ekskul->fee > 0)
                                        Rp {{ number_format($ekskul->fee, 0, ',', '.') }}
                                    @else
                                        <span class="text-emerald-600">Gratis (Bebas Biaya)</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Berdasarkan Status -->
                    <div class="pt-4 border-t border-navy-light/20 mt-auto">
                        @if(!$status || $status === 'rejected')
                            <form action="{{ route('extracurricular-registrations.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="extracurricular_id" value="{{ $ekskul->id }}">
                                <button type="submit" class="w-full py-2.5 bg-navy-dark text-white-off font-bold text-xs rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 border border-transparent">
                                    Daftar Ekstrakurikuler
                                </button>
                            </form>
                            @if($status === 'rejected')
                                <p class="text-center text-[11px] font-bold text-rose-500 mt-2">Pendaftaran sebelumnya ditolak admin.</p>
                            @endif
                        
                        @elseif($status === 'pending')
                            <div class="w-full py-2.5 bg-amber-50 text-amber-700 border border-amber-200/60 rounded-xl text-xs font-bold text-center tracking-wider uppercase flex items-center justify-center gap-1.5 shadow-sm">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                </span>
                                Menunggu Persetujuan
                            </div>
                        
                        @elseif($status === 'approved')
                            <div class="flex gap-2">
                                <div class="flex-1 py-2.5 bg-emerald-50 border border-emerald-200/60 text-emerald-700 rounded-xl text-xs font-bold uppercase tracking-wider text-center flex items-center justify-center gap-1.5 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Anggota Aktif
                                </div>
                                
                                <button type="button" onclick="document.getElementById('unenrollModal-{{ $ekskul->id }}').classList.remove('hidden')" class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 hover:border-transparent rounded-xl text-xs font-bold active:scale-95 transition-all duration-200" title="Keluar dari Ekskul">
                                    Keluar
                                </button>
                            </div>

                            <!-- Modal Confirm Keluar Ekskul -->
                            <div id="unenrollModal-{{ $ekskul->id }}" class="hidden fixed inset-0 z-50 bg-navy-dark/40 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
                                <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center relative whitespace-normal border border-navy-light/20 animate-[scale-in_0.2s_ease-out]">
                                    <div class="p-6">
                                        <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl border border-rose-100 flex items-center justify-center mx-auto mb-4 shadow-sm">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        </div>
                                        <h3 class="font-bold text-base text-navy-dark font-heading mb-1">Keluar Ekstrakurikuler?</h3>
                                        <p class="text-xs text-gray-muted leading-relaxed mb-6">Apakah Anda yakin ingin berhenti mengikuti ekskul <b>{{ $ekskul->name }}</b>? Riwayat pembayaran Anda akan tetap tersimpan.</p>
                                        
                                        <form action="{{ route('extracurricular-registrations.destroy', $ekskul->id) }}" method="POST" class="flex gap-3 justify-center">
                                            @csrf
                                            @method('DELETE')
                                            
                                            <button type="button" onclick="document.getElementById('unenrollModal-{{ $ekskul->id }}').classList.add('hidden')" class="px-5 py-2.5 bg-white-off text-navy-dark font-bold text-xs rounded-xl hover:bg-navy-light/20 border border-navy-light/30 transition-all">Batal</button>
                                            <button type="submit" class="px-5 py-2.5 bg-rose-600 text-white font-bold text-xs rounded-xl hover:bg-rose-700 hover:shadow-lg hover:shadow-rose-600/20 active:scale-95 transition-all">Ya, Keluar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-navy-light/30 shadow-sm">
                <div class="w-14 h-14 bg-white-off text-navy-light rounded-full flex items-center justify-center mx-auto mb-3 border border-navy-light/20">
                    <svg class="w-7 h-7 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-navy-dark font-heading mb-1">Belum Ada Ekstrakurikuler</h3>
                <p class="text-xs text-gray-muted">Saat ini belum terdapat daftar ekstrakurikuler yang tersedia.</p>
            </div>
        @endforelse
    </div>
@endsection

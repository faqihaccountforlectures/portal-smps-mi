@extends('layouts.app')

@section('title', 'Katalog Ekstrakurikuler')
@section('header', 'Katalog Ekstrakurikuler')

@section('content')
    <!-- Alert sukses atau error -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="mb-6">
        <h2 class="text-lg font-bold text-slate-800">Pilih Ekstrakurikuler</h2>
        <p class="text-sm text-slate-500 mt-1">Daftarkan diri kamu ke ekstrakurikuler yang sesuai dengan minat dan bakatmu.</p>
    </div>

    <!-- Grid Katalog Ekskul -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($extracurriculars as $ekskul)
            @php
                // Cek status pendaftaran siswa pada ekskul ini
                $registration = $myRegistrations->get($ekskul->id);
                $status = $registration ? $registration->status : null;
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <!-- Header Kartu -->
                <div class="h-24 bg-gradient-to-r from-blue-500 to-indigo-600 p-5 flex flex-col justify-end relative overflow-hidden">
                    <!-- Ornamen abstrak -->
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                    <h3 class="text-xl font-bold text-white relative z-10">{{ $ekskul->name }}</h3>
                </div>
                
                <!-- Body Kartu -->
                <div class="p-5 flex-1 flex flex-col">
                    <div class="space-y-3 mb-6 flex-1">
                        <!-- Info Pembina -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Pembina</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $ekskul->teacher->teacherProfile->full_name ?? $ekskul->teacher->email }}</p>
                            </div>
                        </div>
                        
                        <!-- Info Jadwal -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Jadwal</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $ekskul->schedule }}</p>
                            </div>
                        </div>

                        <!-- Info Biaya -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Biaya per Bulan</p>
                                <p class="text-sm font-bold text-emerald-600">Rp {{ number_format($ekskul->fee, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Berdasarkan Status Pendaftaran -->
                    <div class="pt-4 border-t border-slate-100 mt-auto">
                        @if(!$status || $status === 'rejected')
                            <!-- Jika belum daftar atau ditolak, bisa daftar (lagi) -->
                            <form action="{{ route('extracurricular-registrations.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="extracurricular_id" value="{{ $ekskul->id }}">
                                <button type="submit" class="w-full py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/20 active:scale-[0.98] transition-all">
                                    Daftar Ekstrakurikuler
                                </button>
                            </form>
                            @if($status === 'rejected')
                                <p class="text-center text-xs font-semibold text-red-500 mt-2">Pendaftaran sebelumnya ditolak.</p>
                            @endif
                        
                        @elseif($status === 'pending')
                            <!-- Jika sedang diproses admin -->
                            <button disabled class="w-full py-2.5 bg-amber-50 text-amber-600 border border-amber-200 rounded-xl text-sm font-bold cursor-not-allowed">
                                Menunggu Persetujuan
                            </button>
                        
                        @elseif($status === 'approved')
                            <!-- Jika sudah resmi bergabung -->
                            <div class="flex gap-2">
                                <div class="flex-1 py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-xl text-sm font-bold text-center flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Anggota Aktif
                                </div>
                                
                                <button type="button" onclick="document.getElementById('unenrollModal-{{ $ekskul->id }}').classList.remove('hidden')" class="px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-xl text-sm font-bold transition-colors" title="Keluar dari Ekskul">
                                    Keluar
                                </button>
                            </div>

                            <!-- Modal Keluar Ekskul -->
                            <div id="unenrollModal-{{ $ekskul->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
                                <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center relative whitespace-normal">
                                    <div class="p-6">
                                        <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        </div>
                                        <h3 class="font-bold text-lg text-slate-800 mb-1">Keluar Ekstrakurikuler?</h3>
                                        <p class="text-sm text-slate-500 mb-6">Apakah kamu yakin ingin berhenti mengikuti ekskul <b>{{ $ekskul->name }}</b>? Riwayat pembayaranmu sebelumnya akan tetap tersimpan.</p>
                                        
                                        <form action="{{ route('extracurricular-registrations.destroy', $ekskul->id) }}" method="POST" class="flex gap-3 justify-center">
                                            @csrf
                                            @method('DELETE')
                                            
                                            <button type="button" onclick="document.getElementById('unenrollModal-{{ $ekskul->id }}').classList.add('hidden')" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-semibold text-sm rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                                            <button type="submit" class="px-6 py-2.5 bg-red-600 text-white font-semibold text-sm rounded-xl hover:bg-red-700 hover:shadow-lg hover:shadow-red-600/20 active:scale-[0.98] transition-all duration-200">Ya, Keluar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-100 border-dashed">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-700">Belum Ada Ekstrakurikuler</h3>
                <p class="text-sm text-slate-500 mt-1">Saat ini belum ada daftar ekstrakurikuler yang tersedia.</p>
            </div>
        @endforelse
    </div>
@endsection

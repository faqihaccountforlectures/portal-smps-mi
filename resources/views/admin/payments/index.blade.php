@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran Ekstrakurikuler')
@section('header', 'Verifikasi Pembayaran Ekstrakurikuler')

@section('content')
    <!-- Alert pesan sukses jika admin berhasil menyetujui/menolak pembayaran -->
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

    <!-- Ringkasan Kartu Statistik Pembayaran -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <!-- Total Transaksi -->
        <div class="bg-white p-5 rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-gray-muted uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $totalCount ?? $payments->total() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center border border-navy-light/30 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Perlu Verifikasi -->
        <div class="bg-white p-5 rounded-2xl border border-amber-200/60 shadow-sm shadow-amber-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-amber-300 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Butuh Verifikasi</p>
                <h3 class="text-2xl font-bold text-amber-800 font-heading mt-1">{{ $pendingCount ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Terverifikasi / Lunas -->
        <div class="bg-white p-5 rounded-2xl border border-emerald-200/60 shadow-sm shadow-emerald-500/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-emerald-300 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Disetujui / Lunas</p>
                <h3 class="text-2xl font-bold text-emerald-800 font-heading mt-1">{{ $verifiedCount ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Belum Dibayar -->
        <div class="bg-white p-5 rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 flex items-center justify-between group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <div>
                <p class="text-[11px] font-bold text-gray-muted uppercase tracking-wider">Belum Dibayar</p>
                <h3 class="text-2xl font-bold text-navy-dark font-heading mt-1">{{ $unpaidCount ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white-off text-gray-muted flex items-center justify-center border border-navy-light/30 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Container Utama -->
    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
        
        <!-- Header Tabel & Toolbar Filter -->
        <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30 flex flex-col gap-4">
            <!-- Baris 1: Judul Halaman & Tombol Cetak PDF -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Setoran Pembayaran Ekstrakurikuler</h2>
                        <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-bold">Kelola dan verifikasi bukti pembayaran iuran ekstrakurikuler dari para siswa.</p>
                    </div>
                </div>

                <a href="{{ route('admin.payments.export.pdf', request()->all()) }}" target="_blank" class="px-4 py-2.5 bg-navy-dark text-white-off font-bold text-xs rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 border border-transparent whitespace-nowrap self-stretch sm:self-auto shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Cetak PDF</span>
                </a>
            </div>

            <!-- Baris 2: Toolbar Pencarian & Filter Grid -->
            <form action="{{ route('admin.payments.index') }}" method="GET" class="pt-3.5 border-t border-navy-light/20 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                <!-- Search Box -->
                <div class="relative lg:col-span-4">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-navy-base/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama siswa..." class="w-full bg-white border border-navy-light/40 text-navy-dark font-semibold text-xs rounded-xl pl-9 pr-4 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all placeholder:text-gray-muted/60 shadow-sm">
                </div>

                <!-- Filter Bulan -->
                <div class="relative lg:col-span-3">
                    <select name="month" class="w-full bg-white border border-navy-light/40 text-navy-dark font-semibold text-xs rounded-xl pl-4 pr-9 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all appearance-none cursor-pointer shadow-sm" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @foreach($monthsList as $m)
                            <option value="{{ $m }}" {{ ($monthFilter ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-navy-base">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Filter Tahun -->
                <div class="relative lg:col-span-2">
                    <select name="year" class="w-full bg-white border border-navy-light/40 text-navy-dark font-semibold text-xs rounded-xl pl-4 pr-9 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all appearance-none cursor-pointer shadow-sm" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($yearsList as $y)
                            <option value="{{ $y }}" {{ (string)($yearFilter ?? '') === (string)$y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-navy-base">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Filter Status -->
                <div class="relative lg:col-span-3">
                    <select name="status" class="w-full bg-white border border-navy-light/40 text-navy-dark font-semibold text-xs rounded-xl pl-4 pr-9 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all appearance-none cursor-pointer shadow-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="verifikasi" {{ ($statusFilter ?? '') === 'verifikasi' ? 'selected' : '' }}>Butuh Verifikasi</option>
                        <option value="belum_lunas" {{ ($statusFilter ?? '') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="lunas" {{ ($statusFilter ?? '') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="ditolak" {{ ($statusFilter ?? '') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-navy-base">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                
                <button type="submit" class="hidden">Cari</button>
            </form>
        </div>
        
        <!-- Container untuk Tabel -->
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                        <th class="px-7 py-4 font-bold">Nama Siswa</th>
                        <th class="px-7 py-4 font-bold">Bulan & Ekskul</th>
                        <th class="px-7 py-4 font-bold">Nominal & Info</th>
                        <th class="px-7 py-4 font-bold text-center">Status</th>
                        <th class="px-7 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white-off text-sm text-navy-base">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-white-off/50 transition-colors group/row">
                        
                        <!-- Kolom Nama Siswa -->
                        <td class="px-7 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center font-bold text-sm border border-navy-light/30 shrink-0 shadow-sm group-hover/row:scale-105 transition-transform">
                                    {{ substr($payment->student->studentProfile->full_name ?? $payment->student->email ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-navy-dark block text-sm">{{ $payment->student->studentProfile->full_name ?? $payment->student->email }}</p>
                                    @if($payment->student->studentProfile && $payment->student->studentProfile->nisn)
                                        <p class="text-[11px] text-gray-muted font-semibold flex items-center gap-1 mt-0.5 tracking-wide">
                                            <span class="bg-white-off px-2 py-0.5 rounded border border-navy-light/20 font-mono">NISN: {{ $payment->student->studentProfile->nisn }}</span>
                                        </p>
                                    @else
                                        <p class="text-[11px] text-gray-muted font-semibold flex items-center gap-1 mt-0.5 tracking-wide">
                                            <svg class="w-3.5 h-3.5 text-navy-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $payment->student->email }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <!-- Kolom Ekskul & Bulan -->
                        <td class="px-7 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-navy-light/10 border border-navy-light/30 text-navy-dark px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider mb-1.5 shadow-sm">
                                {{ $payment->extracurricular->name }}
                            </span>
                            <p class="font-bold text-navy-dark text-xs flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $payment->month }} {{ $payment->year }}
                            </p>
                        </td>
                        
                        <!-- Kolom Info Pembayaran -->
                        <td class="px-7 py-4">
                            <p class="font-bold text-emerald-600 font-mono text-sm">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                            @if($payment->payment_status !== 'unpaid')
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] text-gray-muted font-bold uppercase bg-white-off px-2 py-0.5 rounded border border-navy-light/20">
                                        Metode: {{ $payment->payment_method ?? 'Transfer' }}
                                    </span>
                                </div>
                                @if($payment->proof_of_payment)
                                    <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank" class="mt-2 inline-flex items-center gap-1.5 text-[10px] bg-navy-light/10 hover:bg-navy-base text-navy-dark hover:text-white-off px-2.5 py-1 rounded-lg font-bold transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span>Lihat Bukti Foto</span>
                                    </a>
                                @endif
                            @endif
                        </td>
                        
                        <!-- Kolom Status -->
                        <td class="px-7 py-4 text-center">
                            @if($payment->payment_status === 'unpaid')
                                <span class="inline-flex items-center gap-1.5 bg-white-off border border-navy-light/40 text-gray-muted px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-muted/50"></span>
                                    Belum Dibayar
                                </span>
                            @elseif($payment->payment_status === 'pending')
                                <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200/60 text-amber-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    <span class="relative flex h-2 w-2">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                    </span>
                                    Perlu Verifikasi
                                </span>
                            @elseif($payment->payment_status === 'verified')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200/60 text-emerald-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Disetujui
                                </span>
                                @if(isset($payment->verifier))
                                    <div class="text-[10px] text-gray-muted mt-1 font-medium">Oleh: {{ $payment->verifier->teacherProfile->full_name ?? 'Admin' }}</div>
                                @endif
                            @elseif($payment->payment_status === 'rejected')
                                <span class="inline-flex items-center gap-1.5 bg-rose-50 border border-rose-200/60 text-rose-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Ditolak
                                </span>
                                @if(isset($payment->verifier))
                                    <div class="text-[10px] text-gray-muted mt-1 font-medium">Oleh: {{ $payment->verifier->teacherProfile->full_name ?? 'Admin' }}</div>
                                @endif
                            @endif
                        </td>
                        
                        <!-- Kolom Aksi -->
                        <td class="px-7 py-4 text-center">
                            @if($payment->payment_status === 'pending')
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Tombol Verify -->
                                    <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 shadow-sm active:scale-95 transition-all duration-200" title="Setujui Pembayaran">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            <span>Terima</span>
                                        </button>
                                    </form>
                                    
                                    <!-- Tombol Reject -->
                                    <button type="button" onclick="document.getElementById('rejectModal-{{ $payment->id }}').classList.remove('hidden')" class="bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 hover:border-transparent font-bold px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 shadow-sm active:scale-95 transition-all duration-200" title="Tolak Pembayaran">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        <span>Tolak</span>
                                    </button>

                                    <!-- Modal Reject Pembayaran -->
                                    <div id="rejectModal-{{ $payment->id }}" class="hidden fixed inset-0 z-50 bg-navy-dark/40 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
                                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center relative whitespace-normal border border-navy-light/20 animate-[scale-in_0.2s_ease-out]">
                                            <div class="p-6">
                                                <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl border border-rose-100 flex items-center justify-center mx-auto mb-4 shadow-sm">
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                </div>
                                                <h3 class="font-bold text-base text-navy-dark font-heading mb-1">Tolak Pembayaran?</h3>
                                                <p class="text-xs text-gray-muted leading-relaxed mb-6">Apakah Anda yakin ingin menolak bukti setoran dari <b>{{ $payment->student->studentProfile->full_name ?? $payment->student->email }}</b>?</p>
                                                
                                                <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" class="flex gap-3 justify-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <button type="button" onclick="document.getElementById('rejectModal-{{ $payment->id }}').classList.add('hidden')" class="px-5 py-2.5 bg-white-off text-navy-dark font-bold text-xs rounded-xl hover:bg-navy-light/20 border border-navy-light/30 transition-all">Batal</button>
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
                    <!-- Tampilan kalau tidak ada data pembayaran -->
                    <tr>
                        <td colspan="5" class="px-7 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-muted">
                                <div class="bg-white-off border border-navy-light/30 p-4 rounded-2xl mb-4 shadow-inner text-navy-base">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-base font-bold text-navy-dark font-heading mb-1">Belum Ada Setoran</h3>
                                <p class="text-xs text-gray-muted max-w-sm leading-relaxed">Saat ini belum terdapat catatan atau bukti pembayaran iuran yang cocok dengan filter pencarian.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Footer Tabel & Paginasi -->
        @if($payments->hasPages())
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-center gap-4 mt-auto">
            <span class="text-xs text-gray-muted font-medium">
                Menampilkan <b class="text-navy-dark">{{ $payments->firstItem() }}</b> - <b class="text-navy-dark">{{ $payments->lastItem() }}</b> dari <b class="text-navy-dark">{{ $payments->total() }}</b> transaksi
            </span>
            <div class="pagination-wrapper">
                {{ $payments->appends(request()->all())->links() }}
            </div>
        </div>
        @else
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex justify-between items-center text-xs text-gray-muted font-medium mt-auto">
            <span>Total Transaksi: <b class="text-navy-dark">{{ $payments->total() }}</b> data</span>
        </div>
        @endif
    </div>
@endsection

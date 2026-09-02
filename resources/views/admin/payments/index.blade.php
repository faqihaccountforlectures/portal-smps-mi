@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran Ekstrakurikuler')
@section('header', 'Verifikasi Pembayaran Ekstrakurikuler')

@section('content')
    <!-- Alert pesan sukses jika admin berhasil menyetujui/menolak pembayaran -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        
        <!-- Header Tabel & Form Filter -->
        <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/30 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-800">Daftar Setoran Pembayaran Ekstrakurikuler</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola dan verifikasi bukti pembayaran iuran ekstrakurikuler dari para siswa.</p>
            </div>
            
            <!-- Form Pencarian dan Filter -->
            <div class="flex flex-col sm:flex-row gap-3 items-center w-full sm:w-auto">
                <a href="{{ route('admin.payments.export.pdf', request()->all()) }}" target="_blank" class="w-full sm:w-auto bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-xl font-semibold shadow-md shadow-slate-800/20 transition-all flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Cetak PDF
                </a>
                
                <form action="{{ route('admin.payments.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 w-full">
                    <!-- Search -->
                    <div class="relative w-full sm:w-auto">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama siswa..." class="bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block w-full pl-9 p-2 transition-colors">
                    </div>
                    
                    <!-- Filter Status -->
                    <select name="status" class="bg-white border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 p-2 transition-colors w-full sm:w-auto" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="verifikasi" {{ ($statusFilter ?? '') === 'verifikasi' ? 'selected' : '' }}>Butuh Verifikasi</option>
                        <option value="belum_lunas" {{ ($statusFilter ?? '') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="lunas" {{ ($statusFilter ?? '') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="ditolak" {{ ($statusFilter ?? '') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    
                    <!-- Submit (hidden karena select sudah ada onchange, tapi butuh untuk search input) -->
                    <button type="submit" class="hidden">Cari</button>
                </form>
            </div>
        </div>
        
        <!-- Container untuk Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-4 font-semibold">Nama Siswa</th>
                        <th class="px-6 py-4 font-semibold">Bulan & Ekskul</th>
                        <th class="px-6 py-4 font-semibold">Nominal & Info</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-600">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        
                        <!-- Kolom Nama Siswa -->
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $payment->student->studentProfile->full_name ?? $payment->student->email }}
                            @if($payment->student->studentProfile && $payment->student->studentProfile->nisn)
                                <div class="text-xs text-slate-500 font-normal mt-0.5">NISN: {{ $payment->student->studentProfile->nisn }}</div>
                            @endif
                        </td>
                        
                        <!-- Kolom Ekskul & Bulan -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-indigo-50 border border-indigo-100 text-indigo-700 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase mb-1">
                                {{ $payment->extracurricular->name }}
                            </span>
                            <p class="font-bold text-slate-700 text-xs">{{ $payment->month }} {{ $payment->year }}</p>
                        </td>
                        
                        <!-- Kolom Info Pembayaran -->
                        <td class="px-6 py-4">
                            <p class="font-bold text-emerald-600">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                            @if($payment->payment_status !== 'unpaid')
                                <p class="text-[10px] text-slate-400 mt-0.5 font-medium uppercase">Metode: {{ $payment->payment_method }}</p>
                                @if($payment->proof_of_payment)
                                    <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-[10px] bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 px-2 py-1 rounded font-bold transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat Bukti Foto
                                    </a>
                                @endif
                            @endif
                        </td>
                        
                        <!-- Kolom Status -->
                        <td class="px-6 py-4 text-center">
                            @if($payment->payment_status === 'unpaid')
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-md text-xs font-bold border border-slate-300">Belum Dibayar</span>
                            @elseif($payment->payment_status === 'pending')
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-md text-xs font-bold border border-amber-200">Perlu Verifikasi</span>
                            @elseif($payment->payment_status === 'verified')
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-md text-xs font-bold border border-emerald-200">Disetujui</span>
                                <div class="text-[10px] text-slate-400 mt-1">Oleh: {{ $payment->verifier->teacherProfile->full_name ?? 'Admin' }}</div>
                            @elseif($payment->payment_status === 'rejected')
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-md text-xs font-bold border border-red-200">Ditolak</span>
                                <div class="text-[10px] text-slate-400 mt-1">Oleh: {{ $payment->verifier->teacherProfile->full_name ?? 'Admin' }}</div>
                            @endif
                        </td>
                        
                        <!-- Kolom Aksi -->
                        <td class="px-6 py-4 text-center">
                            @if($payment->payment_status === 'pending')
                                <div class="flex justify-center gap-2">
                                    <!-- Tombol Verify -->
                                    <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-lg shadow-sm hover:shadow-emerald-500/30 transition-all text-xs font-semibold flex items-center gap-1" title="Setujui">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Terima
                                        </button>
                                    </form>
                                    
                                    <!-- Tombol Reject -->
                                    <button type="button" onclick="document.getElementById('rejectModal-{{ $payment->id }}').classList.remove('hidden')" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg shadow-sm hover:shadow-red-500/30 transition-all text-xs font-semibold flex items-center gap-1" title="Tolak">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Tolak
                                    </button>

                                    <!-- Modal Reject Pembayaran -->
                                    <div id="rejectModal-{{ $payment->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
                                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center relative whitespace-normal">
                                            <div class="p-6">
                                                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                </div>
                                                <h3 class="font-bold text-lg text-slate-800 mb-1">Tolak Pembayaran?</h3>
                                                <p class="text-sm text-slate-500 mb-6">Tolak pembayaran ini? (Bisa jadi karena bukti transfer palsu atau gambar kurang jelas).</p>
                                                
                                                <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" class="flex gap-3 justify-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <button type="button" onclick="document.getElementById('rejectModal-{{ $payment->id }}').classList.add('hidden')" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-semibold text-sm rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                                                    <button type="submit" class="px-6 py-2.5 bg-red-600 text-white font-semibold text-sm rounded-xl hover:bg-red-700 hover:shadow-lg hover:shadow-red-600/20 active:scale-[0.98] transition-all duration-200">Ya, Tolak</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-slate-400 text-xs italic">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <!-- Tampilan kalau tidak ada pembayaran sama sekali -->
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4 border border-slate-100 shadow-inner">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-base font-bold text-slate-700 mb-1">Belum ada setoran</p>
                                <p class="text-sm text-slate-500">Saat ini belum ada bukti pembayaran yang diunggah oleh siswa.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Footer Tabel dengan Paginasi -->
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 text-xs text-slate-500">
            <span>Total Transaksi: <b class="text-slate-700">{{ $payments->total() }}</b></span>
            
            <div class="pagination-wrapper">
                {{ $payments->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
@endsection

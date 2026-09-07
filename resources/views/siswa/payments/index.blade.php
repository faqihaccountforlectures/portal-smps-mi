@extends('layouts.app')

@section('title', 'Riwayat Pembayaran Ekstrakurikuler')
@section('header', 'Riwayat Pembayaran Ekstrakurikuler')

@section('content')
    <!-- Alert Pesan Sukses / Error -->
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

    @if($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 px-5 py-4 rounded-xl mb-6 shadow-sm shadow-rose-500/10 flex flex-col gap-1.5 animate-[fade-in-down_0.5s_ease-out]">
            @foreach($errors->all() as $error)
                <span class="text-xs font-bold tracking-wide flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $error }}
                </span>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: Form Upload Pembayaran Baru -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 p-6 sticky top-6 group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <div class="flex items-center gap-3 mb-5 border-b border-navy-light/20 pb-4">
                    <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-navy-dark font-heading tracking-wide">Upload Pembayaran</h3>
                        <p class="text-[11px] text-gray-muted tracking-wide font-bold">Setor iuran ekstrakurikuler bulanan</p>
                    </div>
                </div>
                
                @if($approvedRegistrations->isEmpty())
                    <!-- Tampilan kalau siswa belum terdaftar di ekskul apa pun -->
                    <div class="bg-white-off border border-navy-light/30 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-muted leading-relaxed font-medium">Anda belum bergabung dengan ekstrakurikuler mana pun. Silakan mendaftar terlebih dahulu di menu <a href="{{ route('siswa.extracurriculars.index') }}" class="text-navy-base font-bold hover:underline">Katalog Ekstrakurikuler</a>.</p>
                    </div>
                @else
                    <form action="{{ route('siswa.payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <!-- Pilihan Ekstrakurikuler -->
                        <div>
                            <label class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-1.5">Ekstrakurikuler <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="extracurricular_id" class="w-full bg-white-off/50 border border-navy-light/40 text-navy-dark text-xs font-semibold rounded-xl pl-4 pr-9 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all appearance-none shadow-sm cursor-pointer" required>
                                    <option value="" disabled selected>Pilih Ekstrakurikuler</option>
                                    @foreach($approvedRegistrations as $reg)
                                        <option value="{{ $reg->extracurricular_id }}">{{ $reg->extracurricular->name }} (Rp {{ number_format($reg->extracurricular->fee, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-navy-base">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Periode Bulan & Tahun -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-1.5">Bulan <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="month" class="w-full bg-white-off/50 border border-navy-light/40 text-navy-dark text-xs font-semibold rounded-xl pl-3.5 pr-8 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all appearance-none shadow-sm cursor-pointer" required>
                                        <option value="Januari">Januari</option>
                                        <option value="Februari">Februari</option>
                                        <option value="Maret">Maret</option>
                                        <option value="April">April</option>
                                        <option value="Mei">Mei</option>
                                        <option value="Juni">Juni</option>
                                        <option value="Juli">Juli</option>
                                        <option value="Agustus">Agustus</option>
                                        <option value="September">September</option>
                                        <option value="Oktober">Oktober</option>
                                        <option value="November">November</option>
                                        <option value="Desember">Desember</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-navy-base">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-1.5">Tahun <span class="text-rose-500">*</span></label>
                                <input type="number" name="year" value="{{ date('Y') }}" min="2020" max="2099" class="w-full bg-white-off/50 border border-navy-light/40 text-navy-dark font-mono font-medium text-xs rounded-xl px-3.5 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all shadow-sm" required>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-1.5">Metode <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="payment_method" id="payment_method" class="w-full bg-white-off/50 border border-navy-light/40 text-navy-dark text-xs font-semibold rounded-xl pl-4 pr-9 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all appearance-none shadow-sm cursor-pointer" onchange="toggleProofField()" required>
                                    <option value="transfer" selected>Transfer Bank / e-Wallet</option>
                                    <option value="cash">Tunai (Bayar di Sekolah)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-navy-base">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Upload File Bukti Transfer -->
                        <div id="proof_field_container">
                            <label class="block text-xs font-bold text-navy-dark uppercase tracking-wider mb-1.5">Bukti Transfer (JPG/PNG) <span class="text-rose-500">*</span></label>
                            <input type="file" name="proof_of_payment" id="proof_of_payment" accept="image/jpeg,image/png,image/jpg" class="w-full text-xs text-gray-muted file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-navy-light/10 file:text-navy-base hover:file:bg-navy-light/20 transition-all border border-navy-light/40 rounded-xl bg-white-off/50">
                            <p class="text-[10px] text-gray-muted mt-1 font-medium">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="w-full mt-4 py-2.5 bg-navy-dark text-white-off font-bold text-xs rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 border border-transparent">
                            Kirim Bukti Pembayaran
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: Tabel Riwayat Pembayaran -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden flex flex-col group hover:-translate-y-1 hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
                <div class="px-7 py-5 border-b border-navy-light/30 bg-white-off/30">
                    <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Daftar Transaksi Pembayaran Ekstrakurikuler</h2>
                    <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-bold">Seluruh riwayat penyetoran iuran ekstrakurikuler yang telah diajukan.</p>
                </div>
                
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                                <th class="px-7 py-4 font-bold">Bulan & Ekstrakurikuler</th>
                                <th class="px-7 py-4 font-bold">Nominal & Metode</th>
                                <th class="px-7 py-4 font-bold text-center">Status</th>
                                <th class="px-7 py-4 font-bold text-center">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white-off text-sm text-navy-base">
                            @forelse($allTransactions as $payment)
                            <tr class="hover:bg-white-off/50 transition-colors group/row">
                                <!-- Info Ekskul dan Bulan -->
                                <td class="px-7 py-4">
                                    <p class="font-bold text-navy-dark text-base">{{ $payment->month }} {{ $payment->year }}</p>
                                    <span class="inline-flex items-center gap-1.5 mt-1 bg-navy-light/10 border border-navy-light/30 text-navy-dark px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                        {{ $payment->extracurricular->name }}
                                    </span>
                                </td>
                                
                                <!-- Nominal dan Metode -->
                                <td class="px-7 py-4">
                                    <p class="font-bold text-navy-base text-base">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                                    @if($payment->payment_status !== 'unpaid')
                                        <p class="text-[10px] text-gray-muted mt-0.5 font-bold uppercase tracking-wider">{{ $payment->payment_method }}</p>
                                    @endif
                                </td>
                                
                                <!-- Status Pembayaran -->
                                <td class="px-7 py-4 text-center">
                                    @if($payment->payment_status === 'unpaid')
                                        <span class="inline-flex items-center gap-1.5 bg-white-off border border-navy-light/40 text-navy-dark px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                            Belum Dibayar
                                        </span>
                                    @elseif($payment->payment_status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200/60 text-amber-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            <span class="relative flex h-2 w-2">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                            </span>
                                            Menunggu
                                        </span>
                                    @elseif($payment->payment_status === 'verified')
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200/60 text-emerald-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Terverifikasi
                                        </span>
                                        @if($payment->verifier)
                                            <p class="text-[10px] text-gray-muted mt-1 font-semibold">Oleh: {{ $payment->verifier->teacherProfile->full_name ?? $payment->verifier->email }}</p>
                                        @endif
                                    @elseif($payment->payment_status === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 bg-rose-50 border border-rose-200/60 text-rose-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Link File Bukti -->
                                <td class="px-7 py-4 text-center">
                                    @if($payment->payment_status === 'unpaid')
                                        <span class="text-xs italic text-gray-muted">-</span>
                                    @elseif($payment->proof_of_payment)
                                        <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank" class="inline-flex items-center justify-center p-2 bg-white-off hover:bg-navy-dark hover:text-white-off text-navy-base border border-navy-light/30 rounded-xl transition-all shadow-sm active:scale-95" title="Lihat Bukti Transfer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    @else
                                        <span class="text-xs italic text-gray-muted">- (Tunai)</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-7 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-muted">
                                        <div class="bg-white-off border border-navy-light/30 p-4 rounded-2xl mb-4 shadow-inner text-navy-base">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <h3 class="text-base font-bold text-navy-dark font-heading mb-1">Belum Ada Transaksi</h3>
                                        <p class="text-xs text-gray-muted max-w-sm leading-relaxed">Belum ada riwayat pembayaran yang pernah diajukan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Script toggle upload bukti berdasarkan metode pembayaran -->
    <script>
        function toggleProofField() {
            const method = document.getElementById('payment_method').value;
            const container = document.getElementById('proof_field_container');
            const input = document.getElementById('proof_of_payment');
            
            if (method === 'cash') {
                container.style.display = 'none';
                input.removeAttribute('required');
            } else {
                container.style.display = 'block';
                input.setAttribute('required', 'required');
            }
        }
    </script>
@endsection

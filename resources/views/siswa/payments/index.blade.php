@extends('layouts.app')

@section('title', 'Riwayat Pembayaran Ekstrakurikuler')
@section('header', 'Riwayat Pembayaran Ekstrakurikuler')

@section('content')
    <!-- Alert Pesan Sukses / Error -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex flex-col gap-1">
            @foreach($errors->all() as $error)
                <span class="text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $error }}
                </span>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri: Form Upload Pembayaran Baru -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Upload Pembayaran
                </h3>
                
                @if($approvedRegistrations->isEmpty())
                    <!-- Tampilan kalau siswa belum join ekskul apa-apa -->
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
                        <p class="text-sm text-slate-500">Anda belum memiliki ekstrakurikuler yang aktif. Silakan daftar terlebih dahulu di menu <a href="{{ route('siswa.extracurriculars.index') }}" class="text-blue-600 font-semibold hover:underline">Katalog Ekstrakurikuler</a>.</p>
                    </div>
                @else
                    <form action="{{ route('siswa.payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <!-- Pilihan Ekskul -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Ekstrakurikuler <span class="text-red-500">*</span></label>
                            <select name="extracurricular_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 p-2.5 transition-colors" required>
                                <option value="" disabled selected>Pilih Ekstrakurikuler</option>
                                @foreach($approvedRegistrations as $reg)
                                    <option value="{{ $reg->extracurricular_id }}">{{ $reg->extracurricular->name }} (Rp {{ number_format($reg->extracurricular->fee, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Periode Bulan & Tahun -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Bulan <span class="text-red-500">*</span></label>
                                <select name="month" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 p-2.5 transition-colors" required>
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
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Tahun <span class="text-red-500">*</span></label>
                                <input type="number" name="year" value="{{ date('Y') }}" min="2020" max="2099" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 p-2.5 transition-colors" required>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Metode <span class="text-red-500">*</span></label>
                            <select name="payment_method" id="payment_method" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 p-2.5 transition-colors" onchange="toggleProofField()" required>
                                <option value="transfer" selected>Transfer Bank / e-Wallet</option>
                                <option value="cash">Tunai (Bayar di Sekolah)</option>
                            </select>
                        </div>

                        <!-- Upload File Bukti -->
                        <div id="proof_field_container">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Bukti Transfer (JPG/PNG) <span class="text-red-500">*</span></label>
                            <input type="file" name="proof_of_payment" id="proof_of_payment" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all border border-slate-200 rounded-xl bg-slate-50">
                            <p class="text-[10px] text-slate-400 mt-1">Maksimal ukuran file: 2MB.</p>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="w-full mt-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/20 active:scale-[0.98] transition-all">
                            Kirim Bukti Pembayaran
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Tabel Riwayat Pembayaran -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/30">
                    <h2 class="text-base font-bold text-slate-800">Daftar Transaksi Pembayaran Ekstrakurikuler</h2>
                    <p class="text-xs text-slate-500 mt-1">Seluruh riwayat pembayaran yang pernah kamu unggah.</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                                <th class="px-6 py-4 font-semibold">Bulan & Ekskul</th>
                                <th class="px-6 py-4 font-semibold">Nominal & Metode</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm text-slate-600">
                            @forelse($allTransactions as $payment)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- Info Ekskul dan Bulan -->
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800">{{ $payment->month }} {{ $payment->year }}</p>
                                    <span class="inline-flex items-center gap-1.5 mt-1 bg-indigo-50 border border-indigo-100 text-indigo-700 px-2 py-0.5 rounded-md text-[10px] font-bold">
                                        {{ $payment->extracurricular->name }}
                                    </span>
                                </td>
                                
                                <!-- Nominal dan Metode -->
                                <td class="px-6 py-4">
                                    <p class="font-bold text-emerald-600">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                                    @if($payment->payment_status !== 'unpaid')
                                        <p class="text-xs text-slate-400 mt-0.5 font-medium uppercase">{{ $payment->payment_method }}</p>
                                    @endif
                                </td>
                                
                                <!-- Status Pembayaran -->
                                <td class="px-6 py-4">
                                    @if($payment->payment_status === 'unpaid')
                                        <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-md text-xs font-bold border border-slate-300">Belum Dibayar</span>
                                    @elseif($payment->payment_status === 'pending')
                                        <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-md text-xs font-bold border border-amber-200">Menunggu Verifikasi</span>
                                    @elseif($payment->payment_status === 'verified')
                                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-md text-xs font-bold border border-emerald-200">Terverifikasi</span>
                                        @if($payment->verifier)
                                            <p class="text-[10px] text-slate-400 mt-1 border-t border-slate-100 pt-1">Oleh: {{ $payment->verifier->teacherProfile->full_name ?? $payment->verifier->email }}</p>
                                        @endif
                                    @elseif($payment->payment_status === 'rejected')
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-md text-xs font-bold border border-red-200">Ditolak</span>
                                    @endif
                                </td>
                                
                                <!-- Link File Bukti -->
                                <td class="px-6 py-4">
                                    @if($payment->payment_status === 'unpaid')
                                        <span class="text-xs italic text-slate-400">-</span>
                                    @elseif($payment->proof_of_payment)
                                        <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank" class="inline-flex items-center justify-center p-2 bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 rounded-lg transition-colors group" title="Lihat Bukti">
                                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    @else
                                        <span class="text-xs italic text-slate-400">- (Tunai)</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500 text-sm">
                                    Belum ada riwayat pembayaran.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Script sederhana untuk hide/show field upload foto berdasarkan metode pembayaran -->
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

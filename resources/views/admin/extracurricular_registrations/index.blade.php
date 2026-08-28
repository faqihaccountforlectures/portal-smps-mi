@extends('layouts.app')

@section('title', 'Pendaftaran Ekstrakurikuler')
@section('header', 'Pendaftaran Ekstrakurikuler')

@section('content')
    <!-- Alert pesan sukses jika admin berhasil menyetujui/menolak pendaftaran -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        
        <!-- Header Tabel -->
        <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/30 flex justify-between items-center">
            <div>
                <h2 class="text-base font-bold text-slate-800">Daftar Pendaftaran Ekstrakurikuler</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola permohonan pendaftaran ekskul dari para siswa.</p>
            </div>
        </div>
        
        <!-- Container untuk Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-4 font-semibold">Nama Siswa</th>
                        <th class="px-6 py-4 font-semibold">Ekstrakurikuler</th>
                        <th class="px-6 py-4 font-semibold">Tanggal Daftar</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-600">
                    @forelse($registrations as $registration)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- Kolom Nama Siswa -->
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $registration->student->studentProfile->full_name ?? $registration->student->email }}
                            @if($registration->student->studentProfile && $registration->student->studentProfile->nisn)
                                <div class="text-xs text-slate-500 font-normal mt-0.5">NISN: {{ $registration->student->studentProfile->nisn }}</div>
                            @endif
                        </td>
                        
                        <!-- Kolom Ekstrakurikuler -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-indigo-50 border border-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                {{ $registration->extracurricular->name }}
                            </span>
                        </td>
                        
                        <!-- Kolom Tanggal Daftar -->
                        <td class="px-6 py-4">
                            {{ $registration->created_at->format('d M Y, H:i') }}
                        </td>
                        
                        <!-- Kolom Status -->
                        <td class="px-6 py-4 text-center">
                            @if($registration->status === 'pending')
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-md text-xs font-bold border border-amber-200">Menunggu</span>
                            @elseif($registration->status === 'approved')
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-md text-xs font-bold border border-emerald-200">Disetujui</span>
                            @elseif($registration->status === 'rejected')
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-md text-xs font-bold border border-red-200">Ditolak</span>
                            @else
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-md text-xs font-bold">{{ ucfirst($registration->status) }}</span>
                            @endif
                        </td>
                        
                        <!-- Kolom Aksi -->
                        <td class="px-6 py-4 text-center">
                            @if($registration->status === 'pending')
                                <div class="flex justify-center gap-2">
                                    <!-- Tombol Approve -->
                                    <form action="{{ route('extracurricular-registrations.approve', $registration->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-lg shadow-sm hover:shadow-emerald-500/30 transition-all text-xs font-semibold flex items-center gap-1" title="Setujui">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Terima
                                        </button>
                                    </form>
                                    
                                    <!-- Tombol Reject -->
                                    <button type="button" onclick="document.getElementById('rejectModal-{{ $registration->id }}').classList.remove('hidden')" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg shadow-sm hover:shadow-red-500/30 transition-all text-xs font-semibold flex items-center gap-1" title="Tolak">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Tolak
                                    </button>

                                    <!-- Modal Reject Pendaftaran -->
                                    <div id="rejectModal-{{ $registration->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
                                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center relative whitespace-normal">
                                            <div class="p-6">
                                                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                </div>
                                                <h3 class="font-bold text-lg text-slate-800 mb-1">Tolak Pendaftaran?</h3>
                                                <p class="text-sm text-slate-500 mb-6">Apakah Anda yakin ingin menolak pendaftaran <b>{{ $registration->student->studentProfile->full_name ?? $registration->student->email }}</b> untuk ekstrakurikuler <b>{{ $registration->extracurricular->name }}</b>?</p>
                                                
                                                <form action="{{ route('extracurricular-registrations.reject', $registration->id) }}" method="POST" class="flex gap-3 justify-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <button type="button" onclick="document.getElementById('rejectModal-{{ $registration->id }}').classList.add('hidden')" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-semibold text-sm rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
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
                    <!-- Tampilan kalau tidak ada pendaftaran -->
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4 border border-slate-100 shadow-inner">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-base font-bold text-slate-700 mb-1">Belum ada pendaftaran</p>
                                <p class="text-sm text-slate-500">Belum ada siswa yang mendaftar ekstrakurikuler saat ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Footer Tabel -->
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/50 flex justify-between items-center text-xs text-slate-500">
            <span>Total: <b class="text-slate-700">{{ $registrations->count() }}</b> pendaftar</span>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Data Guru')
@section('header', 'Edit Data Guru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
            
            <div class="px-6 py-5 border-b border-slate-50 flex items-center gap-3">
                <a href="{{ route('teachers.index') }}" class="p-2 rounded-lg hover:bg-slate-50 text-slate-400 hover:text-slate-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <!-- Kalo pas edit kita ubah teksnya biar ngerti kalo ini lagi ngubah data -->
                    <h2 class="text-lg font-bold text-slate-800">Ubah Profil Guru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui biodata atau sesuaikan email akun login guru yang bersangkutan.</p>
                </div>
            </div>
            
            <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT') <!-- Wajib dipake di Laravel kalo mau update data pake rute PUT -->
                
                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <ul class="list-disc list-inside text-sm font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    <!-- KOLOM KIRI: INFO AKUN -->
                    <div class="md:col-span-4 space-y-6">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 mb-1 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Informasi Akun
                            </h3>
                            <!-- Penjelasannya disesuain kalo misalnya email gurunya ganti -->
                            <p class="text-xs text-slate-500 mb-4">Ubah email ini jika guru terkait menggunakan akun belajar.id yang baru.</p>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Google <span class="text-red-500">*</span></label>
                                <!-- Nampilin data email yang udah ada sebelumnya -->
                                <input type="email" name="email" value="{{ old('email', $teacher->email) }}" placeholder="contoh@guru.smp.belajar.id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200">
                                
                                <div class="mt-2.5 bg-blue-50 border border-blue-100 rounded-lg p-3 flex gap-2">
                                    <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-[11px] text-blue-700 leading-relaxed font-medium">
                                        Ingat, sistem login menggunakan SSO secara otomatis ya pak.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: BIODATA -->
                    <div class="md:col-span-8">
                        <h3 class="text-sm font-bold text-slate-800 mb-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            Biodata & Penempatan
                        </h3>
                        <p class="text-xs text-slate-500 mb-4">Sesuaikan gelar, jabatan, atau No HP apabila ada perubahan terbaru.</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <!-- Nama Lengkap -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                                <!-- Ngambil value dari relasi teacherProfile -->
                                <input type="text" name="full_name" value="{{ old('full_name', $teacher->teacherProfile->full_name ?? '') }}" placeholder="Contoh: Bapak Budi Santoso, S.Pd., M.Si." required class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all duration-200 shadow-sm">
                            </div>

                            <!-- NIP -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIP / NUPTK <span class="text-slate-400 font-normal text-xs">(Opsional)</span></label>
                                <input type="text" name="nip" value="{{ old('nip', $teacher->teacherProfile->nip ?? '') }}" placeholder="Masukkan NIP (jika ada)" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all duration-200 shadow-sm">
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <!-- Nentuin pilihan jenis kelamin yang lagi aktif (ter-select) -->
                                    @php $currentGender = old('gender', $teacher->teacherProfile->gender ?? ''); @endphp
                                    <select name="gender" required class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl pl-4 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all duration-200 appearance-none shadow-sm">
                                        <option value="" disabled {{ $currentGender ? '' : 'selected' }}>Pilih jenis kelamin...</option>
                                        <option value="laki-laki" {{ $currentGender == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ $currentGender == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Jabatan (Ubah jadi select box juga) -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    @php $currentPosition = old('position', $teacher->teacherProfile->position ?? ''); @endphp
                                    <select name="position" required class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl pl-4 pr-10 py-2.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all duration-200 appearance-none shadow-sm">
                                        <option value="" disabled {{ $currentPosition ? '' : 'selected' }}>Pilih jabatan...</option>
                                        <option value="guru" {{ $currentPosition == 'guru' ? 'selected' : '' }}>Guru / Tenaga Pendidik</option>
                                        <option value="kepala_sekolah" {{ $currentPosition == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                        <option value="wakil_kepala_sekolah" {{ $currentPosition == 'wakil_kepala_sekolah' ? 'selected' : '' }}>Wakil Kepala Sekolah</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- No HP -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. WA / Telepon <span class="text-slate-400 font-normal text-xs">(Opsional)</span></label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $teacher->teacherProfile->phone_number ?? '') }}" placeholder="Contoh: 08123456789" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all duration-200 shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('teachers.index') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold text-sm rounded-xl hover:bg-slate-50 active:scale-[0.98] transition-all duration-200">
                        Batal
                    </a>
                    <!-- Tombol ini buat apply perubahannya -->
                    <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/20 active:scale-[0.98] transition-all duration-200 flex items-center gap-2">
                        <span>Simpan Perubahan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

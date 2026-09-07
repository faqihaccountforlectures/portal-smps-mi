@extends('layouts.app')

@section('title', 'Tambah Data Guru')
@section('header', 'Tambah Data Guru')

@section('content')
    <!-- Pake max-w-4xl biar formnya agak lebar buat nampung 2 kolom (Akun & Biodata) -->
    <div class="max-w-5xl mx-auto">
        <!-- Header Page -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('teachers.index') }}" class="p-2.5 bg-white rounded-xl shadow-sm border border-navy-light/30 text-gray-muted hover:text-navy-base hover:bg-navy-light/10 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-navy-dark font-heading tracking-wide">Registrasi Guru Baru</h2>
                <p class="text-[13px] text-gray-muted mt-0.5 tracking-wide">Lengkapi biodata dan daftarkan email asli untuk keperluan login sistem.</p>
            </div>
        </div>
        
        <!-- Nampilin pesan error kalo ada isian yang salah / kurang pas divalidasi -->
        @if($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl shadow-sm">
            <ul class="list-disc list-inside text-sm font-bold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('teachers.store') }}" method="POST">
            @csrf
            
            <!-- Grid buat misahin area Informasi Akun dan area Biodata -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                
                <!-- KOLOM KIRI: INFO AKUN (Lebar 4 kolom) -->
                <div class="md:col-span-4">
                    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden group hover:shadow-md hover:border-navy-base/30 transition-all duration-300 p-6">
                        <div class="mb-6">
                            <h3 class="text-base font-bold text-navy-dark mb-1 flex items-center gap-2 tracking-wide font-heading">
                                <svg class="w-5 h-5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Informasi Akun
                            </h3>
                            <p class="text-xs text-gray-muted leading-relaxed">Email ini sebagai "kunci" saat guru login via Google.</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-navy-dark mb-2 tracking-wide">Email Google <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@guru.smp.belajar.id" required class="w-full bg-white-off/50 border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium">
                            
                            <div class="mt-4 bg-navy-light/10 border border-navy-light/30 rounded-xl p-3.5 flex gap-3 shadow-sm">
                                <svg class="w-5 h-5 text-navy-base shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-xs text-navy-base leading-relaxed font-bold">
                                    Password tidak perlu dibuat secara manual karena sistem ini sudah mendukung otentikasi akun Google belajar sekolah.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: BIODATA (Lebar 8 kolom) -->
                <div class="md:col-span-8">
                    <div class="bg-white rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 overflow-hidden group hover:shadow-md hover:border-navy-base/30 transition-all duration-300 p-6 flex flex-col h-full">
                        <div class="mb-6 flex justify-between items-start">
                            <div>
                                <h3 class="text-base font-bold text-navy-dark mb-1 flex items-center gap-2 tracking-wide font-heading">
                                    <svg class="w-5 h-5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                    Biodata & Penempatan
                                </h3>
                                <p class="text-xs text-gray-muted leading-relaxed">Pastikan penulisan nama beserta gelarnya sudah lengkap.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 flex-grow">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                                <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Contoh: Budi Santoso, S.Pd., M.Si." required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium">
                            </div>

                            <!-- NIP -->
                            <div>
                                <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">NIP <span class="text-rose-500">*</span></label>
                                <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP" required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium">
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Jenis Kelamin <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="gender" required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl pl-4 pr-10 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 appearance-none shadow-sm font-medium cursor-pointer">
                                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Pilih jenis kelamin...</option>
                                        <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Jabatan -->
                            <div>
                                <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">Jabatan <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="position" required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl pl-4 pr-10 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 appearance-none shadow-sm font-medium cursor-pointer">
                                        <option value="" disabled {{ old('position') ? '' : 'selected' }}>Pilih jabatan...</option>
                                        <option value="guru" {{ old('position') == 'guru' ? 'selected' : '' }}>Guru / Tenaga Pendidik</option>
                                        <option value="kepala_sekolah" {{ old('position') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                        <option value="wakil_kepala_sekolah" {{ old('position') == 'wakil_kepala_sekolah' ? 'selected' : '' }}>Wakil Kepala Sekolah</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Nomor HP -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-bold text-navy-dark mb-1.5 tracking-wide">No. WA / Telepon <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="Contoh: 08123456789" required class="w-full bg-white border border-navy-light/40 text-navy-dark text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-navy-base focus:border-navy-base outline-none transition-all duration-200 shadow-sm font-medium">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Area Tombol Aksi di Kanan Bawah -->
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('teachers.index') }}" class="px-6 py-2.5 bg-white-off text-gray-muted font-bold text-sm rounded-xl hover:bg-navy-light/10 hover:text-navy-dark border border-navy-light/30 transition-colors shadow-sm active:scale-95">
                    Batal
                </a>
                <button type="submit" class="px-8 py-2.5 bg-navy-dark text-white-off font-bold text-sm rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 flex items-center gap-2 border border-transparent">
                    <span>Simpan Data Guru</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </div>
        </form>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran')
@section('header', 'Tahun Ajaran')

@section('content')
    <div class="max-w-2xl"> <!-- PENJELASAN: Membatasi lebar kotak form (max-w-2xl) agar tidak memanjang penuh di layar besar dan terlihat rapi proporsional -->
        <div class="bg-white p-7 rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 relative overflow-hidden group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            
            <div class="flex items-center gap-3 mb-6 border-b border-white-off pb-4">
                <div class="bg-white-off p-2.5 rounded-xl text-navy-dark group-hover:bg-navy-dark group-hover:text-white-off transition-colors duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Edit Data Tahun Ajaran</h2>
                    <p class="text-xs text-gray-muted mt-0.5">Ubah detail nama, semester, atau ubah status aktif.</p>
                </div>
            </div>
            
            <form action="{{ route('academic-years.update', $academicYear->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                <!-- PENJELASAN: Menggabungkan Nama dan Semester ke dalam grid 2 kolom agar form tidak terlalu panjang ke bawah -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted mb-1.5 uppercase tracking-wider">Nama Tahun Ajaran</label>
                        <input type="text" name="year_name" value="{{ $academicYear->year_name }}" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-300">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted mb-1.5 uppercase tracking-wider">Semester</label>
                        <div class="relative">
                            <select name="semester" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl pl-4 pr-10 py-3 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-300 appearance-none">
                                <option value="ganjil" {{ $academicYear->semester == 'ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                                <option value="genap" {{ $academicYear->semester == 'genap' ? 'selected' : '' }}>Semester Genap</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy-base">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-muted mb-1.5 uppercase tracking-wider">Status Aktif</label>
                    <div class="relative">
                        <select name="is_active" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl pl-4 pr-10 py-3 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-300 appearance-none">
                            <option value="1" {{ $academicYear->is_active ? 'selected' : '' }}>Aktif (Gunakan Sekarang)</option>
                            <option value="0" {{ !$academicYear->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2.5 flex items-start gap-2 text-slate-500 bg-amber-50/50 p-3 rounded-xl border border-amber-100">
                        <svg class="w-4 h-4 shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs leading-relaxed text-amber-800/80">Menyetel status ini ke <b>Aktif</b> akan menonaktifkan tahun ajaran lainnya secara otomatis.</p>
                    </div>
                </div>

                <div class="pt-6 flex gap-4 border-t border-white-off mt-6">
                    <a href="{{ route('academic-years.index') }}" class="px-6 py-3 bg-white border border-navy-light/50 text-gray-muted font-bold text-sm rounded-xl hover:bg-white-off hover:text-navy-base hover:border-transparent active:scale-[0.98] transition-all duration-300 text-center">Batal & Kembali</a>
                    <button type="submit" class="px-8 py-3 bg-navy-dark text-white-off font-bold text-sm rounded-xl hover:bg-navy-base hover:shadow-lg hover:shadow-navy-base/20 active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2">
                        <span>Simpan Perubahan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Data Tahun Ajaran')
@section('header', 'Tahun Ajaran')

@section('content')
    
    <!-- Pesan Sukses -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- PENJELASAN: Menambahkan pesan Error, berguna saat user mencoba menghapus Tahun Ajaran yang sedang Aktif -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-3 animate-[fade-in-down_0.5s_ease-out]">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri: Form Tambah Data -->
        <div class="xl:col-span-1">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow duration-300">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                
                <div class="flex items-center gap-2 mb-4 border-b border-slate-50 pb-3">
                    <div class="bg-blue-50 p-2 rounded-lg text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h2 class="text-sm font-bold text-slate-800">Tambah Tahun Ajaran</h2>
                </div>
                
                <form action="{{ route('academic-years.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <!-- PENJELASAN: Menambahkan komentar, mengganti 'name' menjadi 'year_name' karena di database menggunakan nama 'year_name' -->
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Tahun Ajaran</label>
                        <input type="text" name="year_name" placeholder="Contoh: 2026/2027" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg px-3 py-2 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Semester</label>
                        <!-- PENJELASAN: Menambahkan div relative dan ikon panah bawah agar jelas bahwa ini dropdown -->
                        <div class="relative">
                            <select name="semester" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg pl-3 pr-10 py-2 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200 appearance-none">
                                <option value="ganjil">Semester Ganjil</option>
                                <option value="genap">Semester Genap</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Aktif</label>
                        <div class="relative">
                            <select name="is_active" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-lg pl-3 pr-10 py-2 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200 appearance-none">
                                <option value="1">Aktif (Gunakan Sekarang)</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <div class="mt-2 flex items-start gap-1.5 text-slate-400 bg-slate-50/50 p-2 rounded border border-slate-100">
                            <svg class="w-3.5 h-3.5 shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-[10px] leading-relaxed">Jika diset <b>Aktif</b>, maka tahun ajaran yang sedang aktif sebelumnya akan otomatis dinonaktifkan.</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-semibold text-sm py-2 rounded-lg hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/20 active:scale-[0.98] transition-all duration-200 flex justify-center items-center gap-2 mt-2">
                        <span>Simpan Data</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Tabel Data -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full">
                <div class="px-5 py-4 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="bg-white p-1.5 rounded text-slate-400 shadow-sm border border-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Daftar Tahun Ajaran</h2>
                    </div>
                    <span class="text-xs font-medium text-slate-500 bg-white px-2 py-1 rounded-md border border-slate-200">{{ $academicYears->count() }} Data</span>
                </div>
                
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-400 text-[11px] uppercase tracking-wider border-b border-slate-100">
                                <th class="px-5 py-3 font-semibold">Tahun Ajaran</th>
                                <th class="px-5 py-3 font-semibold">Semester</th>
                                <th class="px-5 py-3 font-semibold">Status</th>
                                <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm text-slate-600">
                            @forelse($academicYears as $year)
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full {{ $year->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                                        <!-- PENJELASAN: Memanggil nama yang benar sesuai kolom database yaitu $year->year_name (bukan $year->name) -->
                                        <span class="font-bold text-slate-700">{{ $year->year_name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-1.5">
                                        @if($year->semester == 'ganjil')
                                            <span class="w-5 h-5 rounded flex items-center justify-center bg-amber-50 text-amber-600 text-xs font-bold">1</span>
                                        @else
                                            <span class="w-5 h-5 rounded flex items-center justify-center bg-indigo-50 text-indigo-600 text-xs font-bold">2</span>
                                        @endif
                                        <span class="capitalize text-xs font-medium">{{ $year->semester }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($year->is_active)
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 border border-emerald-100 text-emerald-600 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase">
                                            <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-slate-100 border border-slate-200 text-slate-500 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase">
                                            Non-aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex justify-end items-center gap-1">
                                        <!-- PENJELASAN: Tombol Edit sekarang diarahkan ke rute halaman Edit yang baru -->
                                        <a href="{{ route('academic-years.edit', $year->id) }}" class="text-slate-400 hover:text-blue-600 transition-colors p-1.5 rounded-md hover:bg-blue-50 block" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        <!-- PENJELASAN: Tombol untuk Hapus. Membuka modal konfirmasi hapus -->
                                        <button onclick="document.getElementById('deleteModal-{{ $year->id }}').classList.remove('hidden')" class="text-slate-400 hover:text-red-600 transition-colors p-1.5 rounded-md hover:bg-red-50" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- PENJELASAN: Memanggil potongan kodingan Modal Delete dari file terpisah agar rapi -->
                            @include('admin.academic-years.delete')
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-10 h-10 mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        <p class="text-sm font-medium">Belum ada data tahun ajaran.</p>
                                        <p class="text-xs mt-1">Silakan tambahkan data baru melalui form di samping.</p>
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
@endsection
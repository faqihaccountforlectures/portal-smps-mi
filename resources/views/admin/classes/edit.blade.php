@extends('layouts.app')

@section('title', 'Edit Data Kelas')
@section('header', 'Edit Data Kelas')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white p-7 rounded-2xl shadow-sm shadow-navy-base/5 border border-navy-light/30 relative overflow-hidden group hover:shadow-md hover:border-navy-base/30 transition-all duration-300">
            <div class="flex items-center gap-3 mb-5 border-b border-white-off pb-4 pb-3">
                <div class="bg-white-off p-2.5 rounded-xl text-navy-dark group-hover:bg-navy-dark group-hover:text-white-off transition-colors duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-navy-dark font-heading tracking-wide">Edit Data Kelas</h2>
                    <p class="text-xs text-gray-muted mt-0.5">Ubah detail tingkat, nama ruang kelas, atau wali kelas.</p>
                </div>
            </div>
            
            <form action="{{ route('classes.update', $classRoom->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted mb-1.5 uppercase tracking-wider">Tingkat Kelas</label>
                        <div class="relative">
                            <select name="grade_level" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl pl-4 pr-10 py-3 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-200 appearance-none">
                                <option value="7" {{ $classRoom->grade_level == '7' ? 'selected' : '' }}>Kelas 7 (VII)</option>
                                <option value="8" {{ $classRoom->grade_level == '8' ? 'selected' : '' }}>Kelas 8 (VIII)</option>
                                <option value="9" {{ $classRoom->grade_level == '9' ? 'selected' : '' }}>Kelas 9 (IX)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy-base">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-muted mb-1.5 uppercase tracking-wider">Nama Ruang/Kelas</label>
                        <input type="text" name="name" value="{{ $classRoom->name }}" required class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-200">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-muted mb-1.5 uppercase tracking-wider">Wali Kelas (Opsional)</label>
                    <div class="relative">
                        <select name="homeroom_teacher_id" class="w-full bg-white-off/50 border border-navy-light/50 text-navy-dark font-semibold text-sm rounded-xl pl-4 pr-10 py-3 focus:bg-white focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all duration-200 appearance-none">
                            <option value="">-- Kosongkan / Belum Ada --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $classRoom->homeroom_teacher_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->teacherProfile->full_name ?? $teacher->email }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex gap-4 border-t border-white-off mt-6">
                    <a href="{{ route('classes.index') }}" class="px-6 py-2 bg-white border border-navy-light/50 text-gray-muted font-semibold text-sm rounded-xl hover:bg-white-off hover:text-navy-base border border-transparent hover:border-navy-light/50 active:scale-[0.98] transition-all duration-200 text-center">Batal & Kembali</a>
                    <button type="submit" class="px-8 py-3 bg-navy-dark text-white-off font-bold text-sm rounded-xl hover:bg-navy-base hover:shadow-lg hover:shadow-navy-base/20 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Simpan Perubahan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection


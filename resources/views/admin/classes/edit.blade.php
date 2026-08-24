@extends('layouts.app')

@section('title', 'Edit Data Kelas')
@section('header', 'Edit Data Kelas')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
            
            <div class="flex items-center gap-3 mb-5 border-b border-slate-50 pb-3">
                <div class="bg-blue-50 p-2.5 rounded-lg text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Edit Data Kelas</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Ubah detail tingkat, nama ruang kelas, atau wali kelas.</p>
                </div>
            </div>
            
            <form action="{{ route('classes.update', $classRoom->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tingkat Kelas</label>
                        <div class="relative">
                            <select name="grade_level" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl pl-4 pr-10 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200 appearance-none">
                                <option value="7" {{ $classRoom->grade_level == '7' ? 'selected' : '' }}>Kelas 7 (VII)</option>
                                <option value="8" {{ $classRoom->grade_level == '8' ? 'selected' : '' }}>Kelas 8 (VIII)</option>
                                <option value="9" {{ $classRoom->grade_level == '9' ? 'selected' : '' }}>Kelas 9 (IX)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Ruang/Kelas</label>
                        <input type="text" name="name" value="{{ $classRoom->name }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Wali Kelas (Opsional)</label>
                    <div class="relative">
                        <select name="homeroom_teacher_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl pl-4 pr-10 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200 appearance-none">
                            <option value="">-- Kosongkan / Belum Ada --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $classRoom->homeroom_teacher_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->teacherProfile->full_name ?? $teacher->email }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex gap-4 border-t border-slate-50 mt-5">
                    <a href="{{ route('classes.index') }}" class="px-6 py-2 bg-white border border-slate-200 text-slate-700 font-semibold text-sm rounded-xl hover:bg-slate-50 active:scale-[0.98] transition-all duration-200 text-center">Batal & Kembali</a>
                    <button type="submit" class="px-8 py-2 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/20 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Simpan Perubahan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

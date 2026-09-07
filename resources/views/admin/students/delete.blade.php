<!-- Modal Hapus Data Siswa -->
<div id="deleteModal-{{ $student->id }}" class="hidden fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center relative border border-white-off">
        <div class="p-7">
            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-5 border border-rose-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="font-bold text-xl text-navy-dark font-heading tracking-wide mb-2">Hapus Data Siswa?</h3>
            
            <p class="text-[13px] text-gray-muted mb-6 leading-relaxed font-medium">Akun milik <b class="text-navy-base">{{ $student->studentProfile->full_name ?? $student->email }}</b> 
            akan dihapus secara permanen beserta datanya. Tindakan ini tidak dapat dibatalkan.</p>
            
            <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="flex gap-3 justify-center">
                @csrf
                @method('DELETE')
                
                <button type="button" onclick="document.getElementById('deleteModal-{{ $student->id }}').classList.add('hidden')" class="px-6 py-2.5 bg-white-off text-gray-muted font-bold text-sm rounded-xl hover:bg-navy-light/10 hover:text-navy-dark border border-navy-light/30 transition-colors shadow-sm active:scale-95">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white font-bold text-sm rounded-xl hover:bg-rose-700 hover:shadow-lg hover:shadow-rose-600/20 active:scale-95 transition-all duration-200 border border-transparent">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

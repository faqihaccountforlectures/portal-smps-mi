<!-- File ini adalah modal pop-up khusus buat konfirmasi pas admin ngeklik tombol tong sampah buat ngehapus data guru -->
<div id="deleteModal-{{ $teacher->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center relative border border-navy-light/30">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 rounded-full bg-rose-500/5 opacity-50 blur-2xl pointer-events-none"></div>

        <div class="p-7 relative z-10">
            <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-sm border border-rose-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="font-bold text-lg text-navy-dark mb-1.5 font-heading">Hapus Data Guru?</h3>
            
            <!-- Tulisannya sengaja nyebutin nama/email gurunya biar admin yakin 100% siapa yang mau dihapus (biar gak salah hapus) -->
            <p class="text-[13px] text-gray-muted mb-6 leading-relaxed">Akun milik <b class="text-navy-base">{{ $teacher->teacherProfile->full_name ?? $teacher->email }}</b> 
            akan dihapus secara permanen beserta profilnya. Tindakan ini tidak dapat dibatalkan.</p>
            
            <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" class="flex gap-3 justify-center">
                @csrf
                @method('DELETE') <!-- Wajib banget dipake di Laravel kalo mau action DELETE -->
                
                <button type="button" onclick="document.getElementById('deleteModal-{{ $teacher->id }}').classList.add('hidden')" class="px-5 py-2.5 bg-white-off text-gray-muted font-bold text-sm rounded-xl hover:bg-navy-light/10 hover:text-navy-dark transition-colors border border-navy-light/30 shadow-sm flex-1 active:scale-95">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-rose-600 text-white font-bold text-sm rounded-xl hover:bg-rose-700 hover:shadow-lg hover:shadow-rose-600/20 active:scale-95 transition-all duration-200 flex-1 border border-transparent">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

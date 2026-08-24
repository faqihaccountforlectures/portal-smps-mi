<!-- Catatan: File ini adalah modal pop-up khusus buat konfirmasi pas admin ngeklik tombol tong sampah buat ngehapus data guru -->
<div id="deleteModal-{{ $teacher->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center relative">
        <div class="p-6">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="font-bold text-lg text-slate-800 mb-1">Hapus Data Guru?</h3>
            
            <!-- Tulisannya sengaja nyebutin nama/email gurunya biar admin yakin 100% siapa yang mau dihapus (biar gak salah hapus) -->
            <p class="text-sm text-slate-500 mb-6">Akun milik <b>{{ $teacher->teacherProfile->full_name ?? $teacher->email }}</b> 
            akan dihapus secara permanen beserta profilnya. Tindakan ini tidak dapat dibatalkan.</p>
            
            <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" class="flex gap-3 justify-center">
                @csrf
                @method('DELETE') <!-- Wajib banget dipake di Laravel kalo mau action DELETE -->
                
                <button type="button" onclick="document.getElementById('deleteModal-{{ $teacher->id }}').classList.add('hidden')" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-semibold text-sm rounded-xl hover:bg-slate-200 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-red-600 text-white font-semibold text-sm rounded-xl hover:bg-red-700 hover:shadow-lg hover:shadow-red-600/20 active:scale-[0.98] transition-all duration-200">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

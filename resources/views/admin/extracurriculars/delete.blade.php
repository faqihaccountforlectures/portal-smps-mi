<div id="deleteModal-{{ $ekskul->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center relative border border-white-off">
        <div class="p-6">
            <div class="w-16 h-16 bg-rose-50 border border-rose-100 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="font-bold text-lg text-navy-dark font-heading mb-1">Hapus Data?</h3>
            <p class="text-sm text-gray-muted mb-6 leading-relaxed">Data <b>{{ $ekskul->name }}</b> akan dihapus secara permanen. Semua data pendaftar pada ekstrakurikuler ini juga terhapus dan tidak bisa dipulihkan kembali.</p>
            
            <form action="{{ route('extracurriculars.destroy', $ekskul->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3 justify-center">
                @csrf
                @method('DELETE')
                
                <button type="button" onclick="document.getElementById('deleteModal-{{ $ekskul->id }}').classList.add('hidden')" class="px-6 py-2.5 bg-white-off text-navy-dark border border-navy-light/40 font-bold text-sm rounded-xl hover:bg-navy-light/20 transition-colors w-full sm:w-auto">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white-off font-bold text-sm rounded-xl hover:bg-rose-700 hover:shadow-lg hover:shadow-rose-600/20 active:scale-95 transition-all duration-200 w-full sm:w-auto">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

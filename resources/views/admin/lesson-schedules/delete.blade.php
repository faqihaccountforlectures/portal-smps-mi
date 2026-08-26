<!-- Modal Hapus (Hidden by default) -->
<div id="deleteModal-{{ $schedule->id }}" class="fixed inset-0 z-[100] flex items-center justify-center hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
    
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
        <div class="absolute top-0 left-0 w-full h-1 bg-red-500"></div>
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2" id="modal-title">Konfirmasi Penghapusan</h3>
            <p class="text-sm text-slate-500 mb-6">
                Apakah Anda yakin ingin menghapus jadwal <b>{{ $schedule->teacherAssignment->subject->name }}</b> pada hari <b>{{ $schedule->day_of_week }}</b> ({{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }})? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="document.getElementById('deleteModal-{{ $schedule->id }}').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <form action="{{ route('lesson-schedules.destroy', $schedule->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 bg-red-500 text-white text-sm font-semibold rounded-xl hover:bg-red-600 hover:shadow-lg hover:shadow-red-500/20 active:scale-[0.98] transition-all">
                        Ya, Hapus Jadwal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

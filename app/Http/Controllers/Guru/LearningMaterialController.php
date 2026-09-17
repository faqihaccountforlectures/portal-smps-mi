<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LearningMaterialController extends Controller
{
    /**
     * FUNGSI KODE: Menampilkan halaman utama daftar materi pelajaran yang diunggah oleh guru yang sedang login.
     * Mengambil seluruh penugasan mengajar guru tersebut dan menyajikan materi dengan filter per kelas/mata pelajaran.
     */
    public function index(Request $request)
    {
        $teacherId = Auth::id();

        // Mengambil seluruh alokasi mengajar guru (relasi mapel, kelas, dan tahun ajaran)
        $assignments = TeacherAssignment::with(['subject', 'classRoom', 'academicYear'])
            ->where('teacher_id', $teacherId)
            ->get();

        // Mengambil ID penugasan yang dipilih dari dropdown filter (jika ada)
        $selectedAssignmentId = $request->query('assignment_id');

        // Menyiapkan kueri materi pelajaran khusus milik guru yang sedang login
        $materialsQuery = LearningMaterial::with(['teacherAssignment.subject', 'teacherAssignment.classRoom'])
            ->whereHas('teacherAssignment', function ($query) use ($teacherId, $selectedAssignmentId) {
                $query->where('teacher_id', $teacherId);
                if ($selectedAssignmentId) {
                    $query->where('id', $selectedAssignmentId);
                }
            })
            ->latest();

        // Menggunakan paginasi 10 item per halaman
        $materials = $materialsQuery->paginate(10)->withQueryString();

        // Menghitung statistik ringkas untuk dasbor materi guru
        $totalMaterials = LearningMaterial::whereHas('teacherAssignment', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })->count();

        $totalClasses = $assignments->pluck('class_room_id')->unique()->count();

        return view('guru.materials.index', compact(
            'materials',
            'assignments',
            'selectedAssignmentId',
            'totalMaterials',
            'totalClasses'
        ));
    }

    /**
     * FUNGSI KODE: Menampilkan formulir tambah materi pelajaran baru.
     * Memastikan guru memiliki penugasan aktif sebelum dapat mengunggah berkas ajar.
     */
    public function create()
    {
        $teacherId = Auth::id();

        // Mengambil daftar penugasan guru untuk pilihan opsi kelas & mata pelajaran
        $assignments = TeacherAssignment::with(['subject', 'classRoom', 'academicYear'])
            ->where('teacher_id', $teacherId)
            ->get();

        if ($assignments->isEmpty()) {
            return redirect()->route('guru.materials.index')
                ->with('error', 'Anda belum memiliki penugasan mengajar aktif. Silakan hubungi Administrator untuk mengatur alokasi kelas Anda.');
        }

        return view('guru.materials.create', compact('assignments'));
    }

    /**
     * FUNGSI KODE: Memvalidasi dan menyimpan materi pelajaran baru ke dalam database dan storage.
     */
    public function store(Request $request)
    {
        $teacherId = Auth::id();

        // 1. Validasi data formulir
        $validated = $request->validate([
            'teacher_assignment_id' => 'required|exists:teacher_assignments,id',
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'file'                  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png|max:20480', // Maks 20 MB
            'link_url'              => 'nullable|url|max:500',
        ], [
            'teacher_assignment_id.required' => 'Mata pelajaran dan kelas wajib dipilih.',
            'title.required'                 => 'Judul materi wajib diisi.',
            'title.max'                      => 'Judul materi maksimal 255 karakter.',
            'file.mimes'                     => 'Format berkas harus berupa PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR, atau Gambar.',
            'file.max'                       => 'Ukuran berkas maksimal adalah 20 MB.',
            'link_url.url'                   => 'Format tautan eksternal tidak valid (pastikan diawali http:// atau https://).',
        ]);

        // 2. Otorisasi kepemilikan penugasan: pastikan penugasan yang dipilih benar milik guru ini
        $assignment = TeacherAssignment::where('id', $validated['teacher_assignment_id'])
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        // Setidaknya salah satu dari berkas, tautan eksternal, atau deskripsi wajib diisi
        if (!$request->hasFile('file') && empty($validated['link_url']) && empty($validated['description'])) {
            return back()->withInput()->with('error', 'Mohon lampirkan berkas materi, tautan pembelajaran, atau catatan instruksi materi.');
        }

        // 3. Proses penyimpanan berkas fisik jika diunggah
        $filePath = null;
        $fileName = null;
        $fileSize = null;

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileName     = $uploadedFile->getClientOriginalName();
            $fileSize     = $this->formatBytes($uploadedFile->getSize());
            $filePath     = $uploadedFile->store('materials', 'public');
        }

        // 4. Simpan rekaman materi baru ke basis data
        LearningMaterial::create([
            'teacher_assignment_id' => $assignment->id,
            'title'                 => $validated['title'],
            'description'           => $validated['description'] ?? null,
            'file_path'             => $filePath,
            'file_name'             => $fileName,
            'file_size'             => $fileSize,
            'link_url'              => $validated['link_url'] ?? null,
        ]);

        return redirect()->route('guru.materials.index')
            ->with('success', 'Materi pelajaran baru berhasil ditambahkan dan dapat diakses siswa!');
    }

    /**
     * FUNGSI KODE: Menampilkan formulir untuk memperbarui data materi yang telah diunggah.
     */
    public function edit($id)
    {
        $teacherId = Auth::id();

        // Mencari materi dan memverifikasi kepemilikannya oleh guru bersangkutan
        $material = LearningMaterial::with('teacherAssignment')->findOrFail($id);

        if ($material->teacherAssignment->teacher_id !== $teacherId) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah materi ini.');
        }

        $assignments = TeacherAssignment::with(['subject', 'classRoom', 'academicYear'])
            ->where('teacher_id', $teacherId)
            ->get();

        return view('guru.materials.edit', compact('material', 'assignments'));
    }

    /**
     * FUNGSI KODE: Menyimpan pembaruan data materi ke basis data, termasuk penggantian berkas fisik jika ada.
     */
    public function update(Request $request, $id)
    {
        $teacherId = Auth::id();

        $material = LearningMaterial::with('teacherAssignment')->findOrFail($id);

        if ($material->teacherAssignment->teacher_id !== $teacherId) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah materi ini.');
        }

        $validated = $request->validate([
            'teacher_assignment_id' => 'required|exists:teacher_assignments,id',
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'file'                  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png|max:20480',
            'link_url'              => 'nullable|url|max:500',
        ], [
            'teacher_assignment_id.required' => 'Mata pelajaran dan kelas wajib dipilih.',
            'title.required'                 => 'Judul materi wajib diisi.',
            'file.mimes'                     => 'Format berkas harus berupa PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR, atau Gambar.',
            'file.max'                       => 'Ukuran berkas maksimal adalah 20 MB.',
            'link_url.url'                   => 'Format tautan eksternal tidak valid.',
        ]);

        // Verifikasi bahwa penugasan baru (jika dipindah) tetap milik guru ini
        TeacherAssignment::where('id', $validated['teacher_assignment_id'])
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        // Jika guru mengunggah berkas baru, hapus berkas lama dari storage terlebih dahulu
        if ($request->hasFile('file')) {
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $uploadedFile = $request->file('file');
            $material->file_name = $uploadedFile->getClientOriginalName();
            $material->file_size = $this->formatBytes($uploadedFile->getSize());
            $material->file_path = $uploadedFile->store('materials', 'public');
        }

        // Perbarui atribut lainnya
        $material->teacher_assignment_id = $validated['teacher_assignment_id'];
        $material->title                 = $validated['title'];
        $material->description           = $validated['description'] ?? null;
        $material->link_url              = $validated['link_url'] ?? null;
        $material->save();

        return redirect()->route('guru.materials.index')
            ->with('success', 'Materi pelajaran berhasil diperbarui!');
    }

    /**
     * FUNGSI KODE: Menghapus rekaman materi pelajaran beserta berkas fisik dari storage server.
     */
    public function destroy($id)
    {
        $teacherId = Auth::id();

        $material = LearningMaterial::with('teacherAssignment')->findOrFail($id);

        if ($material->teacherAssignment->teacher_id !== $teacherId) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus materi ini.');
        }

        // Hapus berkas fisik jika ada
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('guru.materials.index')
            ->with('success', 'Materi pelajaran berhasil dihapus secara permanen.');
    }

    /**
     * FUNGSI KODE: Mengunduh berkas materi pelajaran yang diunggah.
     */
    public function download($id)
    {
        $teacherId = Auth::id();

        $material = LearningMaterial::with('teacherAssignment')->findOrFail($id);

        if ($material->teacherAssignment->teacher_id !== $teacherId) {
            abort(403, 'Anda tidak memiliki izin untuk mengunduh berkas materi ini.');
        }

        if (!$material->file_path || !Storage::disk('public')->exists($material->file_path)) {
            return back()->with('error', 'Berkas fisik materi tidak ditemukan pada penyimpanan server.');
        }

        return Storage::disk('public')->download($material->file_path, $material->file_name);
    }

    /**
     * FUNGSI KODE: Fungsi pembantu (helper) untuk mengonversi ukuran byte ke satuan yang mudah dibaca (KB, MB).
     */
    private function formatBytes($bytes, $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

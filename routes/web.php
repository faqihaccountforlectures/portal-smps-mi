<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherAssignmentController;
use App\Http\Controllers\Admin\LessonScheduleController;
use App\Http\Controllers\Admin\ExtracurricularController;
use App\Http\Controllers\Admin\ExtracurricularRegistrationController;
use App\Http\Controllers\Siswa\StudentExtracurricularController;
use App\Http\Controllers\Siswa\StudentPaymentController;
use App\Http\Controllers\Admin\AdminPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah rute web untuk aplikasi didaftarkan. Seluruh rute
| dimuat oleh RouteServiceProvider dan tergabung dalam grup
| middleware "web".
|
*/

// Rute Halaman Beranda (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// Rute Halaman Autentikasi (Login)
Route::get('/login', function () {
    // Jika pengguna sudah login, arahkan langsung ke dasbor
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
})->name('login');

// Memproses formulir login manual (Email & Kata Sandi)
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

// Rute Autentikasi Google (SSO)
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// Memproses proses keluar (Logout)
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Rute Halaman Dasbor (Membutuhkan Autentikasi)
Route::get('/dashboard', function () {
    // Mengambil data hak akses (role) dari pengguna yang sedang masuk
    $role = Auth::user()->role;

    // Mengarahkan pengguna ke dasbor spesifik berdasarkan peran mereka
    if ($role === 'admin') {
        return app(\App\Http\Controllers\Admin\DashboardController::class)->index();
    } elseif ($role === 'guru') {
        return app(\App\Http\Controllers\Guru\DashboardController::class)->index();
    } elseif ($role === 'siswa') {
        return app(\App\Http\Controllers\Siswa\DashboardController::class)->index();
    }

    // Jika peran tidak valid, keluarkan pengguna dan arahkan kembali ke halaman login
    Auth::logout();
    return redirect('/login')->with('error', 'Hak akses tidak valid.');
})->middleware('auth')->name('dashboard');

// ==========================================
// GRUP RUTE ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {

// ==========================================
// RUTE MASTER DATA TAHUN AJARAN (ACADEMIC YEARS)
// ==========================================
// Menampilkan halaman utama daftar tahun ajaran
Route::get('/admin/academic-years', [AcademicYearController::class, 'index'])->name('academic-years.index');
// Menyimpan data tahun ajaran baru ke basis data
Route::post('/admin/academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store');
// Menampilkan formulir untuk mengubah data tahun ajaran
Route::get('/admin/academic-years/{id}/edit', [AcademicYearController::class, 'edit'])->name('academic-years.edit');
// Memperbarui data tahun ajaran pada basis data
Route::put('/admin/academic-years/{id}', [AcademicYearController::class, 'update'])->name('academic-years.update');
// Menghapus data tahun ajaran secara permanen
Route::delete('/admin/academic-years/{id}', [AcademicYearController::class, 'destroy'])->name('academic-years.destroy');

// ==========================================
// RUTE MASTER DATA KELAS (CLASS ROOMS)
// ==========================================
// Menampilkan daftar kelas beserta wali kelas terkait
Route::get('/admin/classes', [\App\Http\Controllers\Admin\ClassRoomController::class, 'index'])->name('classes.index');
// Menyimpan data kelas baru ke basis data
Route::post('/admin/classes', [\App\Http\Controllers\Admin\ClassRoomController::class, 'store'])->name('classes.store');
// Menampilkan formulir untuk mengubah data kelas
Route::get('/admin/classes/{id}/edit', [\App\Http\Controllers\Admin\ClassRoomController::class, 'edit'])->name('classes.edit');
// Memperbarui data kelas pada basis data
Route::put('/admin/classes/{id}', [\App\Http\Controllers\Admin\ClassRoomController::class, 'update'])->name('classes.update');
// Menghapus data kelas secara permanen
Route::delete('/admin/classes/{id}', [\App\Http\Controllers\Admin\ClassRoomController::class, 'destroy'])->name('classes.destroy');

// ==========================================
// RUTE MASTER DATA MATA PELAJARAN (SUBJECTS)
// ==========================================
// Menampilkan daftar lengkap mata pelajaran (Wajib & Muatan Lokal)
Route::get('/admin/subjects', [SubjectController::class, 'index'])->name('subjects.index');
// Menampilkan formulir untuk menambah mata pelajaran baru
Route::get('/admin/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
// Menyimpan data mata pelajaran baru ke basis data
Route::post('/admin/subjects', [SubjectController::class, 'store'])->name('subjects.store');
// Menampilkan formulir untuk mengubah mata pelajaran
Route::get('/admin/subjects/{id}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
// Memperbarui data mata pelajaran pada basis data
Route::put('/admin/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
// Menghapus data mata pelajaran secara permanen
Route::delete('/admin/subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

// ==========================================
// RUTE PENUGASAN GURU (TEACHER ASSIGNMENTS)
// ==========================================
// Menampilkan daftar penugasan guru (alokasi guru pada mata pelajaran)
Route::get('/teacher-assignments', [TeacherAssignmentController::class, 'index'])->name('teacher-assignments.index');
// Menampilkan formulir pendaftaran penugasan guru baru
Route::get('/teacher-assignments/create', [TeacherAssignmentController::class, 'create'])->name('teacher-assignments.create');
// Menyimpan data penugasan guru ke basis data
Route::post('/teacher-assignments', [TeacherAssignmentController::class, 'store'])->name('teacher-assignments.store');
// Menampilkan formulir ubah penugasan berdasarkan ID guru dan ID mata pelajaran
Route::get('/teacher-assignments/{teacher_id}/{subject_id}/edit', [TeacherAssignmentController::class, 'edit'])->name('teacher-assignments.edit');
// Memperbarui data penugasan guru pada basis data
Route::put('/teacher-assignments/{teacher_id}/{subject_id}', [TeacherAssignmentController::class, 'update'])->name('teacher-assignments.update');
// Menghapus data penugasan guru secara permanen
Route::delete('/teacher-assignments/{teacher_id}/{subject_id}', [TeacherAssignmentController::class, 'destroy'])->name('teacher-assignments.destroy');

// ==========================================
// RUTE MANAJEMEN JADWAL PELAJARAN (TIMETABLES)
// ==========================================
// Menginisialisasi rute sumber daya (resource) untuk jadwal pelajaran
Route::resource('lesson-schedules', LessonScheduleController::class);

// ==========================================
// RUTE EKSTRAKURIKULER (EXTRACURRICULARS)
// ==========================================
// Menginisialisasi rute sumber daya (resource) untuk ekstrakurikuler
Route::resource('admin/extracurriculars', ExtracurricularController::class)->names([
    'index' => 'extracurriculars.index',
    'create' => 'extracurriculars.create',
    'store' => 'extracurriculars.store',
    'edit' => 'extracurriculars.edit',
    'update' => 'extracurriculars.update',
    'destroy' => 'extracurriculars.destroy',
]);

// ==========================================
// RUTE PERSETUJUAN PENDAFTARAN EKSKUL (ADMIN)
// ==========================================
// Menampilkan daftar siswa yang mengajukan pendaftaran ekstrakurikuler
Route::get('/admin/extracurricular-registrations', [ExtracurricularRegistrationController::class, 'index'])->name('extracurricular-registrations.index');
// Memproses persetujuan atas pengajuan pendaftaran siswa
Route::patch('/admin/extracurricular-registrations/{id}/approve', [ExtracurricularRegistrationController::class, 'approve'])->name('extracurricular-registrations.approve');
// Memproses penolakan atas pengajuan pendaftaran siswa
Route::patch('/admin/extracurricular-registrations/{id}/reject', [ExtracurricularRegistrationController::class, 'reject'])->name('extracurricular-registrations.reject');

// ==========================================
// RUTE VERIFIKASI PEMBAYARAN EKSKUL (ADMIN)
// ==========================================
// Menampilkan riwayat pembayaran ekstrakurikuler dari seluruh siswa
Route::get('/admin/payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
// Menyetujui keabsahan bukti pembayaran yang diunggah siswa
Route::patch('/admin/payments/{id}/verify', [AdminPaymentController::class, 'verify'])->name('admin.payments.verify');
// Menolak keabsahan bukti pembayaran yang diunggah siswa
Route::patch('/admin/payments/{id}/reject', [AdminPaymentController::class, 'reject'])->name('admin.payments.reject');

// ==========================================
// RUTE MASTER DATA GURU (TEACHERS)
// ==========================================
// Menampilkan daftar keseluruhan data guru yang terdaftar
Route::get('/admin/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'index'])->name('teachers.index');
// Menampilkan formulir pendaftaran akun dan profil guru baru
Route::get('/admin/teachers/create', [\App\Http\Controllers\Admin\TeacherController::class, 'create'])->name('teachers.create');
// Menyimpan data akun dan profil guru ke basis data melalui transaksi DB
Route::post('/admin/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('teachers.store');
// Menampilkan formulir perubahan data profil guru
Route::get('/admin/teachers/{id}/edit', [\App\Http\Controllers\Admin\TeacherController::class, 'edit'])->name('teachers.edit');
// Memperbarui data akun dan profil guru pada basis data
Route::put('/admin/teachers/{id}', [\App\Http\Controllers\Admin\TeacherController::class, 'update'])->name('teachers.update');
// Menghapus akun dan profil guru secara permanen
Route::delete('/admin/teachers/{id}', [\App\Http\Controllers\Admin\TeacherController::class, 'destroy'])->name('teachers.destroy');

// ==========================================
// RUTE DATA SISWA (STUDENTS)
// ==========================================
// Menampilkan daftar keseluruhan data siswa (termasuk informasi orang tua)
Route::get('/admin/students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('students.index');
// Menampilkan formulir pendaftaran akun dan profil siswa baru
Route::get('/admin/students/create', [\App\Http\Controllers\Admin\StudentController::class, 'create'])->name('students.create');
// Menyimpan data akun dan profil siswa ke basis data melalui transaksi DB
Route::post('/admin/students', [\App\Http\Controllers\Admin\StudentController::class, 'store'])->name('students.store');
// Menampilkan formulir perubahan data profil siswa
Route::get('/admin/students/{id}/edit', [\App\Http\Controllers\Admin\StudentController::class, 'edit'])->name('students.edit');
// Memperbarui data akun dan profil siswa pada basis data
Route::put('/admin/students/{id}', [\App\Http\Controllers\Admin\StudentController::class, 'update'])->name('students.update');
// Menghapus akun dan profil siswa secara permanen
Route::delete('/admin/students/{id}', [\App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('students.destroy');

// ==========================================
// RUTE PEMBAGIAN KELAS (CLASS ENROLLMENTS)
// ==========================================
// Menampilkan daftar seluruh kelas pada tahun ajaran aktif beserta statistik jumlah siswa
Route::get('/admin/class-enrollments', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'index'])->name('class-enrollments.index');
// Menampilkan detail daftar siswa yang telah dialokasikan pada kelas tertentu
Route::get('/admin/class-enrollments/{class_id}', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'show'])->name('class-enrollments.show');
// Menampilkan formulir penambahan siswa (yang belum memiliki kelas) ke kelas tertentu
Route::get('/admin/class-enrollments/{class_id}/add-students', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'addStudents'])->name('class-enrollments.add-students');
// Memproses penyimpanan massal siswa ke dalam kelas tujuan
Route::post('/admin/class-enrollments/{class_id}', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'storeStudents'])->name('class-enrollments.store-students');
// Menghapus alokasi kelas seorang siswa (mengembalikan status menjadi belum memiliki kelas)
Route::delete('/admin/class-enrollments/{enrollment_id}', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'destroy'])->name('class-enrollments.destroy');
});

// ==========================================
// GRUP RUTE KHUSUS GURU
// ==========================================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->group(function () {
// Menampilkan halaman utama dasbor guru
Route::get('/dashboard', [\App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('guru.dashboard');
// Menampilkan daftar kelas yang ditugaskan sebagai wali kelas
Route::get('/classes', [\App\Http\Controllers\Guru\MyClassController::class, 'index'])->name('guru.classes.index');
// Menampilkan detail perwalian siswa di kelas yang bersangkutan
Route::get('/classes/{id}', [\App\Http\Controllers\Guru\MyClassController::class, 'show'])->name('guru.classes.show');
// Menampilkan jadwal mengajar mingguan guru
Route::get('/schedules', [\App\Http\Controllers\Guru\ScheduleController::class, 'index'])->name('guru.schedules.index');
// Menampilkan informasi profil akun guru
Route::get('/profile', [\App\Http\Controllers\Guru\ProfileController::class, 'index'])->name('guru.profile.index');
// Memperbarui informasi profil guru
Route::put('/profile', [\App\Http\Controllers\Guru\ProfileController::class, 'update'])->name('guru.profile.update');
// Menampilkan daftar ekstrakurikuler yang dibina oleh guru bersangkutan
Route::get('/extracurriculars', [\App\Http\Controllers\Guru\ExtracurricularController::class, 'index'])->name('guru.extracurriculars.index');
// Menampilkan daftar siswa yang tergabung dalam ekstrakurikuler binaan
Route::get('/extracurriculars/{id}', [\App\Http\Controllers\Guru\ExtracurricularController::class, 'show'])->name('guru.extracurriculars.show');
});

// ==========================================
// GRUP RUTE KHUSUS SISWA
// ==========================================
Route::middleware(['auth', 'role:siswa'])->group(function () {
// Menampilkan informasi profil akun siswa
Route::get('/siswa/profile', [\App\Http\Controllers\Siswa\ProfileController::class, 'index'])->name('siswa.profile.index');
// Memperbarui informasi profil siswa
Route::put('/siswa/profile', [\App\Http\Controllers\Siswa\ProfileController::class, 'update'])->name('siswa.profile.update');
// Menampilkan jadwal pelajaran mingguan sesuai kelas siswa
Route::get('/siswa/timetables', [\App\Http\Controllers\Siswa\TimetableController::class, 'index'])->name('siswa.timetables.index');
// Menampilkan katalog ekstrakurikuler yang dapat diikuti siswa
Route::get('/siswa/extracurriculars', [StudentExtracurricularController::class, 'index'])->name('siswa.extracurriculars.index');
// Mengirimkan pengajuan pendaftaran ekstrakurikuler
Route::post('/siswa/extracurricular-registrations', [ExtracurricularRegistrationController::class, 'store'])->name('extracurricular-registrations.store');
// Membatalkan atau keluar dari ekstrakurikuler yang diikuti
Route::delete('/siswa/extracurricular-registrations/{id}', [ExtracurricularRegistrationController::class, 'destroy'])->name('extracurricular-registrations.destroy');
// Menampilkan riwayat pembayaran iuran ekstrakurikuler
Route::get('/siswa/payments', [StudentPaymentController::class, 'index'])->name('siswa.payments.index');
// Mengunggah bukti pembayaran iuran ekstrakurikuler
Route::post('/siswa/payments', [StudentPaymentController::class, 'store'])->name('siswa.payments.store');
});
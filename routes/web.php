<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssessmentCriteriaController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InternshipAssessmentController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\InternshipGroupController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Models\AcademicYear;
use App\Models\Activity;
use App\Models\AssessmentCriteria;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Route yang bisa diakses SEMUA role (setelah login)
    // ---------------------------------------------------
    Route::resource('internship-groups', InternshipGroupController::class);
    Route::resource('internships', InternshipController::class);
    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('admins', AdminController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('class-rooms', ClassRoomController::class);
    Route::resource('assessment-criteria', AssessmentCriteriaController::class);
    Route::post('/academic-years/{academicYear}/select', [AcademicYearController::class, 'select'])->name('academic-years.select');
    Route::post('/academic-years/{academicYear}/set-active', [AcademicYearController::class, 'setActive'])
        ->name('academic-years.set-active')
        ->middleware('can:admin');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/students/{student}/attendances', [StudentController::class, 'showAttendances'])->name('students.attendances.show');

    // --- Route untuk Print ---
    Route::get('/attendances/print/{internship}', [AttendanceController::class, 'print'])->name('attendances.print');
Route::get('/internship-groups/print/{internshipGroup}', [InternshipGroupController::class, 'print'])->name('internship-group.print');
    Route::get('/attendances/{internship}', [AttendanceController::class, 'show'])->name('attendances.show');
    Route::get('/activities/{activity}/print', [ActivityController::class, 'print'])->name('activities.print');
    Route::get('/internship-assessments/{assessment}/print', [InternshipAssessmentController::class, 'print'])->name('internship-assessments.print');

    // =========================================================================================
    // == ROUTE YANG DILINDUNGI: HANYA BISA DIAKSES JIKA SISWA PUNYA MAGANG AKTIF ==
    // =========================================================================================
    Route::middleware('student.has_internship')->group(function () {
        Route::resource('attendances', AttendanceController::class);
        Route::patch('/attendance/{id}/verify', [AttendanceController::class, 'verify'])->name('attendance.verify');

        Route::resource('activities', ActivityController::class);

        Route::resource('internship-assessments', InternshipAssessmentController::class);
    });
    // =========================================================================================
    // =========================================================================================

});

// Internship Group Management
require __DIR__ . '/auth.php';

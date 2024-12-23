<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('download-template', [SubmissionController::class, 'downloadTemplate'])->name('download.template');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    
    Route::get('submissions/add', [SubmissionController::class, 'add'])->name('submissions.add');
    Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/dashboard', [SubmissionController::class, 'index'])->name('dashboards.dashboardMhs');
    
    

    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.schedule');

    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboardAdm', [SubmissionController::class, 'indexAdm'])->name('dashboards.dashboardAdm');
        Route::get('/students', [StudentController::class, 'index'])->name('students.student');
        Route::get('form-student', [StudentController::class, 'showStudentForm'])->name('studentForm');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.detail');
        Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');

        Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.schedule');
        Route::get('form-schedule', [ScheduleController::class, 'showScheduleForm'])->name('scheduleForm');
        Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{id}', [ScheduleController::class, 'show'])->name('schedules.detail');
        Route::put('/schedules/{id}', [ScheduleController::class, 'update'])->name('schedules.update');

        Route::get('/submissions/{id}', [SubmissionController::class, 'show'])->name('submission.detail');
        Route::put('/submissions/{id}/approve', [SubmissionController::class, 'approve'])->name('submissions.approve');
        Route::put('/submissions/{id}/reject', [SubmissionController::class, 'reject'])->name('submissions.reject');
        Route::put('/submissions/{id}/status', [SubmissionController::class, 'updateStatus'])->name('submissions.updateStatus');
    });
});

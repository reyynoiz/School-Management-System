<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Semua user boleh melihat dan mengunduh
    Route::get('students/export-pdf', [StudentController::class, 'exportPdf'])->name('students.export-pdf');
    Route::get('teachers/export-pdf', [TeacherController::class, 'exportPdf'])->name('teachers.export-pdf');

    Route::get('students/data', [StudentController::class, 'data'])->name('students.data');
    Route::get('teachers/data', [TeacherController::class, 'data'])->name('teachers.data');
    Route::get('classes/data', [ClassController::class, 'data'])->name('classes.data');
    Route::get('subjects/data', [SubjectController::class, 'data'])->name('subjects.data');

    Route::resource('students', StudentController::class)->only(['index']);
    Route::resource('teachers', TeacherController::class)->only(['index']);
    Route::resource('classes', ClassController::class)->only(['index']);
    Route::resource('subjects', SubjectController::class)->only(['index']);
});

// Hanya admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('students/import-excel', [StudentController::class, 'importExcel'])->name('students.import-excel');
    Route::post('teachers/import-excel', [TeacherController::class, 'importExcel'])->name('teachers.import-excel');

    Route::resource('students', StudentController::class)->except(['index']);
    Route::resource('teachers', TeacherController::class)->except(['index']);
    Route::resource('classes', ClassController::class)->except(['index']);
    Route::resource('subjects', SubjectController::class)->except(['index']);

    Route::get('archives', [ArchiveController::class, 'index'])->name('archives.index');
    Route::get('archives/data', [ArchiveController::class, 'data'])->name('archives.data');
    Route::post('archives/{type}/{id}/restore', [ArchiveController::class, 'restore'])->name('archives.restore');
    Route::delete('archives/{type}/{id}', [ArchiveController::class, 'destroy'])->name('archives.destroy');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/data', [UserController::class, 'data'])->name('users.data');
    Route::post('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
});

require __DIR__.'/auth.php';
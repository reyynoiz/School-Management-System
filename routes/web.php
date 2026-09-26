<?php

use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
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

    // All users can access the index and data routes
    Route::get('students/data', [StudentController::class, 'data'])->name('students.data');
    Route::get('teachers/data', [TeacherController::class, 'data'])->name('teachers.data');
    Route::get('classes/data', [ClassController::class, 'data'])->name('classes.data');
    Route::get('subjects/data', [SubjectController::class, 'data'])->name('subjects.data');

    Route::resource('students', StudentController::class)->only(['index']);
    Route::resource('teachers', TeacherController::class)->only(['index']);
    Route::resource('classes', ClassController::class)->only(['index']);
    Route::resource('subjects', SubjectController::class)->only(['index']);
});

// Only admin can access the following routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('students', StudentController::class)->except(['index']);
    Route::resource('teachers', TeacherController::class)->except(['index']);
    Route::resource('classes', ClassController::class)->except(['index']);
    Route::resource('subjects', SubjectController::class)->except(['index']);
});

require __DIR__.'/auth.php';
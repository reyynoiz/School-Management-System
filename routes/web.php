<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassController;
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
    Route::get('students/data', [StudentController::class, 'data'])->name('students.data');
    Route::resource('students', StudentController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('teachers/data', [TeacherController::class, 'data'])->name('teachers.data');
    Route::resource('teachers', TeacherController::class);
    Route::get('classes/data', [ClassController::class, 'data'])->name('classes.data');
    Route::resource('classes', ClassController::class);

});

require __DIR__.'/auth.php';

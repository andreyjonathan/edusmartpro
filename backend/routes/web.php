<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('students', \App\Http\Controllers\StudentController::class);
    Route::resource('teachers', \App\Http\Controllers\TeacherController::class);
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    Route::resource('subjects', \App\Http\Controllers\SubjectController::class);
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
    Route::resource('schedules', \App\Http\Controllers\ScheduleController::class);
    Route::resource('classrooms', \App\Http\Controllers\ClassroomController::class);
    Route::resource('payments', \App\Http\Controllers\PaymentController::class);
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});

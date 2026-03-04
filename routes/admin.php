<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\HealthRecordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DataRecordController;
use App\Http\Controllers\Admin\PrescriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Appointments Management
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::patch('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Patient Records (lists patients; detail shows their health records)
    // NOTE: specific routes MUST come before wildcard {record} routes to avoid conflicts
    Route::get('/patient-records', [HealthRecordController::class, 'index'])->name('patient-records.index');
    Route::get('/patient-records/user/{user}', [HealthRecordController::class, 'showPatient'])->name('patient-records.patient');
    Route::post('/patient-records', [HealthRecordController::class, 'store'])->name('patient-records.store');
    Route::patch('/patient-records/{record}', [HealthRecordController::class, 'update'])->name('patient-records.update');
    Route::delete('/patient-records/{record}', [HealthRecordController::class, 'destroy'])->name('patient-records.destroy');

    // Data Records (Analytics)
    Route::get('/data-records', [DataRecordController::class, 'index'])->name('data-records.index');
    Route::post('/data-records/monthly-data', [DataRecordController::class, 'getMonthlyData'])->name('data-records.monthly-data');

    // Prescription Requests
    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::post('/prescriptions/{id}/approve', [PrescriptionController::class, 'approve'])->name('prescriptions.approve');
    Route::post('/prescriptions/{id}/reject', [PrescriptionController::class, 'reject'])->name('prescriptions.reject');

    // Users Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
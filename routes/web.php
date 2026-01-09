<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AdminController;

// Guest routes (unauthenticated users)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Forgot password routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot.password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot.password.send');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password/{token}', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset.submit');

    // Manual reset (development only)
    Route::post('/manual-reset-password', [ForgotPasswordController::class, 'manualReset'])->name('password.manual.reset');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/absensi', [AuthController::class, 'absensi'])->name('absensi');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Report routes
    Route::get('/report', [AuthController::class, 'showReportForm'])->name('report');
    Route::post('/report/generate', [AuthController::class, 'generateReport'])->name('report.generate');
    Route::post('/report/save-draft', [AuthController::class, 'saveDraft'])->name('report.saveDraft');
    Route::post('/report/upload-photo', [AuthController::class, 'uploadPhoto'])->name('report.uploadPhoto');
    Route::delete('/report/delete-photo', [AuthController::class, 'deletePhoto'])->name('report.deletePhoto');
    Route::delete('/report/delete-draft/{id}', [AuthController::class, 'deleteDraft'])->name('report.deleteDraft');
    Route::get('/report/history', [AuthController::class, 'showReportHistory'])->name('report.history');
    Route::get('/report/drafts', [AuthController::class, 'showDrafts'])->name('report.drafts');

    // API routes for attendance
    Route::post('/api/check-in', [AuthController::class, 'checkIn']);
    Route::post('/api/check-out', [AuthController::class, 'checkOut']);
    Route::get('/api/attendance-status', [AuthController::class, 'getAttendanceStatus']);
    Route::get('/api/attendance-history', [AuthController::class, 'getAttendanceHistory']);
    Route::post('/api/clear-cache', [AuthController::class, 'clearUserCache']);

    // API routes for izin
    Route::post('/api/submit-izin', [AuthController::class, 'submitIzin']);
    Route::get('/api/user/izin-history', [AuthController::class, 'getUserIzinHistory']);

    // Calendar routes
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');
    Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
    Route::put('/calendar/{id}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');

    // Admin routes (admin only)
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::post('/admin/users/{id}/reset-link', [AdminController::class, 'generateResetLink'])->name('admin.users.reset-link');

        Route::get('/admin/absensi', [AdminController::class, 'absensi'])->name('admin.absensi');
        Route::delete('/admin/absensi/{id}', [AdminController::class, 'deleteAbsensi'])->name('admin.absensi.delete');

        // API routes for izin management
        Route::get('/api/admin/izin-list', [AdminController::class, 'getIzinList']);
        Route::get('/api/admin/izin-detail/{id}', [AdminController::class, 'getIzinDetail']);
        Route::post('/api/admin/approve-izin', [AdminController::class, 'approveIzin']);
        Route::post('/api/admin/reject-izin', [AdminController::class, 'rejectIzin']);

        // API routes for calendar attendance
        Route::get('/api/admin/attendance-weekly', [AdminController::class, 'getAttendanceWeekly']);
        Route::get('/api/admin/attendance-detail', [AdminController::class, 'getAttendanceDetail']);
        Route::get('/api/admin/user-attendance-chart', [AdminController::class, 'getUserAttendanceChart']);

        Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
        Route::get('/admin/reports/{id}', [AdminController::class, 'showReport'])->name('admin.reports.show');
        Route::delete('/admin/reports/{id}', [AdminController::class, 'deleteReport'])->name('admin.reports.delete');
        Route::get('/admin/visit-schedules', [AdminController::class, 'visitSchedules'])->name('admin.visit-schedules');
        Route::get('/admin/visit-schedules/calendar', [AdminController::class, 'getVisitSchedulesCalendar'])->name('admin.visit-schedules.calendar');
        Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/admin/clear-cache', [AdminController::class, 'clearCache'])->name('admin.clear-cache');
    });
});

// Redirect root to login or absensi
Route::get('/', function () {
    if (session('user')) {
        return redirect('/absensi');
    }
    return redirect('/login');
});

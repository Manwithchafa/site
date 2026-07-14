<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\VisitorExportController;
use App\Http\Controllers\Public\VisitorRegistrationController;
use App\Livewire\Admin\DashboardHome;
use App\Livewire\Admin\VisitorIndex;
use App\Livewire\Admin\VisitorShow;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('visitor-registration.create', 'welcome-service');
});

Route::get('/qr/{code}', [VisitorRegistrationController::class, 'create'])
    ->name('visitor-registration.create');

Route::get('/registration/success/{registration:public_uuid}', [VisitorRegistrationController::class, 'success'])
    ->name('visitor-registration.success');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', DashboardHome::class)->name('dashboard');
    Route::get('/visitors', VisitorIndex::class)->name('visitors.index');
    Route::get('/visitors/export.csv', VisitorExportController::class)->name('visitors.export');
    Route::get('/visitors/{visitor}', VisitorShow::class)->name('visitors.show');
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/data', [\App\Http\Controllers\Admin\ReportsController::class, 'data'])->name('reports.data');
    Route::get('/reports/export.csv', [\App\Http\Controllers\Admin\ReportsController::class, 'exportCsv'])->name('reports.export.csv');
    Route::get('/reports/export.pdf', [\App\Http\Controllers\Admin\ReportsController::class, 'exportPdf'])->name('reports.export.pdf');
});

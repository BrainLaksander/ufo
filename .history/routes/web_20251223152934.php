<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Kemahasiswaan\KemahasiswaanController;
use App\Http\Controllers\Pengurus\PengurusController;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK MAHASISWA (TANPA LOGIN)
|--------------------------------------------------------------------------
*/

// Main mahasiswa landing page
Route::view('/', 'pages.mahasiswa.dashboard')->name('home');

// Mahasiswa pages
Route::prefix('mahasiswa')->group(function () {
    Route::view('/', 'pages.mahasiswa.dashboard')->name('mahasiswa.dashboard');
    Route::view('/organisasi', 'pages.mahasiswa.organisasi')->name('mahasiswa.organisasi');
    Route::view('/event', 'pages.mahasiswa.event')->name('mahasiswa.event');
});

/*
|--------------------------------------------------------------------------
| PORTAL INTERNAL (LOGIN + ROLE SELECTION)
|--------------------------------------------------------------------------
*/

// Portal login page
Route::view('/portal/login', 'pages.portal.login')->name('portal.login');

// Pengurus portal pages (frontend only)
Route::prefix('/portal/pengurus')->group(function () {
    Route::view('/', 'pages.portal.pengurus.dashboard')->name('portal.pengurus.dashboard');
});

// Admin portal pages (frontend only)
Route::prefix('/portal/admin')->group(function () {
    Route::view('/', 'pages.portal.admin.dashboard')->name('portal.admin.dashboard');
});

// Kemahasiswaan portal pages (frontend only)
Route::prefix('/portal/kemahasiswaan')->group(function () {
    Route::view('/', 'pages.portal.kemahasiswaan.dashboard')->name('portal.kemahasiswaan.dashboard');
});

/*
|--------------------------------------------------------------------------
| LEGACY AUTH & DASHBOARD ROUTES (UNTUK BACKWARD COMPATIBILITY)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->middleware('role:admin')->name('dashboard.admin');
    Route::get('/kemahasiswaan', [KemahasiswaanController::class, 'index'])->middleware('role:kemahasiswaan')->name('dashboard.kemahasiswaan');
    Route::get('/pengurus', [PengurusController::class, 'index'])->middleware('role:pengurus')->name('dashboard.pengurus');
});

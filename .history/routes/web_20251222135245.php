<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Kemahasiswaan\KemahasiswaanController;
use App\Http\Controllers\Pengurus\PengurusController;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK (MAHASISWA - TANPA LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/organisasi', [PublicController::class, 'organisasi'])->name('organisasi.index');
Route::get('/kegiatan', [PublicController::class, 'kegiatan'])->name('kegiatan.index');

/*
|--------------------------------------------------------------------------
| LOGIN (HANYA UNTUK INTERNAL)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD INTERNAL (SETELAH LOGIN) - PROTECTED BY AUTH & ROLE
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->middleware('role:admin')->name('dashboard.admin');
    Route::get('/kemahasiswaan', [KemahasiswaanController::class, 'index'])->middleware('role:kemahasiswaan')->name('dashboard.kemahasiswaan');
    Route::get('/pengurus', [PengurusController::class, 'index'])->middleware('role:pengurus')->name('dashboard.pengurus');
});

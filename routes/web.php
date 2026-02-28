<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\DashboardOrganisasiController;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK MAHASISWA (TANPA LOGIN)
|--------------------------------------------------------------------------
*/

// Mahasiswa homepage
Route::view('/', 'pages.mahasiswa.dashboard')->name('mahasiswa.dashboard');

// Mahasiswa public pages
Route::prefix('mahasiswa')->group(function () {
    Route::view('/', 'pages.mahasiswa.dashboard')->name('mahasiswa.home');
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('mahasiswa.pengumuman.index');
    Route::get('/pengumuman/{id}', [PengumumanController::class, 'detail'])->name('mahasiswa.pengumuman.detail');
    Route::get('/organisasi', [OrganisasiController::class, 'index'])->name('mahasiswa.organisasi.index');
    Route::get('/organisasi/{id}', [OrganisasiController::class, 'show'])->name('mahasiswa.organisasi.show');
    Route::view('/event', 'pages.mahasiswa.event')->name('mahasiswa.event');
    Route::get('/lost-found', [LostFoundController::class, 'index'])->name('mahasiswa.lost-found');
    Route::view('/tentang', 'mahasiswa.tentang')->name('mahasiswa.tentang');
});

// Direct routes (tanpa /mahasiswa prefix)
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
Route::get('/organisasi', [OrganisasiController::class, 'index'])->name('organisasi.index');
Route::get('/organisasi/{id}', [OrganisasiController::class, 'show'])->name('organisasi.show');
Route::get('/lost-found', [LostFoundController::class, 'index'])->name('lost-found.index');
Route::view('/tentang', 'mahasiswa.tentang')->name('tentang');

// API endpoints for detail views
Route::prefix('api')->group(function () {
    Route::get('/pengumuman/{id}', [PengumumanController::class, 'detail'])->name('api.pengumuman.detail');
    Route::get('/lost-found/{id}', [LostFoundController::class, 'detail'])->name('api.lost-found.detail');
});

/*
|--------------------------------------------------------------------------
| PORTAL SISTEM INTERNAL (DUMMY LOGIN)
|--------------------------------------------------------------------------
*/

// Portal login (role selector)
Route::view('/portal/login', 'pages.portal.login')->name('portal.login');
Route::view('/portal', 'pages.portal.login')->name('portal.index');

// Note: dedicated internal-login route removed — use the canonical /portal/login (route name: portal.login) to avoid duplicate public endpoints.

/*
|--------------------------------------------------------------------------
| PENGURUS PORTAL
|--------------------------------------------------------------------------
*/

Route::prefix('/portal/pengurus')->group(function () {
    Route::view('/', 'portal.pengurus.dashboard')->name('portal.pengurus.dashboard');
    Route::view('/events', 'portal.pengurus.events')->name('portal.pengurus.events');
    Route::view('/announcements', 'portal.pengurus.announcements')->name('portal.pengurus.announcements');
    Route::view('/lostandfound', 'portal.pengurus.lostandfound')->name('portal.pengurus.lostandfound');
    Route::view('/proposals', 'portal.pengurus.proposals')->name('portal.pengurus.proposals');
    Route::view('/members', 'portal.pengurus.members')->name('portal.pengurus.members');
    Route::view('/applications', 'portal.pengurus.applications')->name('portal.pengurus.applications');
});

/*
|--------------------------------------------------------------------------
| ADMIN PORTAL
|--------------------------------------------------------------------------
*/

Route::prefix('/portal/admin')->group(function () {
    Route::view('/', 'pages.admin.dashboard')->name('portal.admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| KEMAHASISWAAN PORTAL
|--------------------------------------------------------------------------
*/

Route::prefix('/portal/kemahasiswaan')->group(function () {
    Route::view('/', 'pages.kemahasiswaan.dashboard')->name('portal.kemahasiswaan.dashboard');
});

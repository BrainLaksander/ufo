<?php

use Illuminate\Support\Facades\Route;

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
    Route::view('/organisasi', 'pages.mahasiswa.organisasi')->name('mahasiswa.organisasi');
    Route::view('/event', 'pages.mahasiswa.event')->name('mahasiswa.event');
});

/*
|--------------------------------------------------------------------------
| PORTAL SISTEM INTERNAL (DUMMY LOGIN)
|--------------------------------------------------------------------------
*/

// Portal login (role selector)
Route::view('/portal/login', 'pages.portal.login')->name('portal.login');
Route::view('/portal', 'pages.portal.login')->name('portal.index');

/*
|--------------------------------------------------------------------------
| PENGURUS PORTAL
|--------------------------------------------------------------------------
*/

Route::prefix('/portal/pengurus')->group(function () {
    Route::view('/', 'pages.pengurus.dashboard')->name('portal.pengurus.dashboard');
    Route::view('/profil', 'pages.pengurus.profil-organisasi')->name('portal.pengurus.profil');
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

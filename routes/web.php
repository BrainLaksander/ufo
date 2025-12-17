<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK (MAHASISWA - TANPA LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('mahasiswa.beranda');
});

Route::get('/organisasi', function () {
    return view('mahasiswa.organisasi');
});

Route::get('/kegiatan', function () {
    return view('mahasiswa.kegiatan');
});

/*
|--------------------------------------------------------------------------
| LOGIN (HANYA UNTUK INTERNAL)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| DASHBOARD INTERNAL (SETELAH LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard/admin', fn () => view('dashboard.admin'));
Route::get('/dashboard/kemahasiswaan', fn () => view('dashboard.kemahasiswaan'));
Route::get('/dashboard/pengurus', fn () => view('dashboard.pengurus'));

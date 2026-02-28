<?php

use Illuminate\Support\Facades\Route;

// Dummy authentication routes placeholder.
// Project belum menggunakan sistem login, jadi rute ini hanya
// memberikan view kosong agar `require` tidak error jika dikembalikan.

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// you can expand later when auth is implemented

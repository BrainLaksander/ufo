<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardOrganisasiController;
use App\Http\Controllers\ProfilOrganisasiController;
use App\Http\Controllers\EventOrganisasiController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\ContactController;
use App\Http\Middleware\EnsureUserRole;

/**
 * Dashboard Organisasi Kampus Routes
 * 
 * Arsitektur:
 * - Role-based access control
 * - Server-side rendering dengan Blade
 * - RESTful resource controllers
 */

// ========== PUBLIC ROUTES ==========

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// ========== DASHBOARD ROUTES ==========
// Note: authentication middleware temporarily removed because
// project belum menggunakan sistem login. Semua route dapat diakses
// langsung untuk pengembangan tampilan.

// ======== DASHBOARD (basic) ========
Route::get('/dashboard', [DashboardOrganisasiController::class, 'index'])
    ->name('dashboard');

// ======== ADMIN & PENGURUS ========
Route::middleware([EnsureUserRole::class . ':admin,pengurus'])->group(function () {
    // PROFIL ORGANISASI
    Route::get('/profil', [ProfilOrganisasiController::class, 'show'])
        ->name('profil.show');
    Route::get('/profil/edit', [ProfilOrganisasiController::class, 'edit'])
        ->name('profil.edit');
    Route::put('/profil', [ProfilOrganisasiController::class, 'update'])
        ->name('profil.update');
    Route::post('/profil/logo', [ProfilOrganisasiController::class, 'uploadLogo'])
        ->name('profil.uploadLogo');

    // EVENT ORGANISASI
    Route::resource('events', EventOrganisasiController::class)
        ->names([
            'index' => 'events.index',
            'create' => 'events.create',
            'store' => 'events.store',
            'show' => 'events.show',
            'edit' => 'events.edit',
            'update' => 'events.update',
            'destroy' => 'events.destroy',
        ]);
    Route::post('/events/{event}/publish', [EventOrganisasiController::class, 'publish'])
        ->name('events.publish');
    Route::post('/events/{event}/poster', [EventOrganisasiController::class, 'uploadPoster'])
        ->name('events.uploadPoster');

    // PENGUMUMAN
    Route::resource('announcements', AnnouncementController::class)
        ->names([
            'index' => 'announcements.index',
            'create' => 'announcements.create',
            'store' => 'announcements.store',
            'show' => 'announcements.show',
            'edit' => 'announcements.edit',
            'update' => 'announcements.update',
            'destroy' => 'announcements.destroy',
        ]);
    Route::post('/announcements/{announcement}/publish', [AnnouncementController::class, 'publish'])
        ->name('announcements.publish');

    // PENGAJUAN / PROPOSAL
    Route::resource('proposals', ProposalController::class)
        ->names([
            'index' => 'proposals.index',
            'create' => 'proposals.create',
            'store' => 'proposals.store',
            'show' => 'proposals.show',
            'edit' => 'proposals.edit',
            'update' => 'proposals.update',
            'destroy' => 'proposals.destroy',
        ]);
    Route::post('/proposals/{proposal}/submit', [ProposalController::class, 'submit'])
        ->name('proposals.submit');

    // LOST & FOUND
    Route::resource('lost-found', LostFoundController::class)
        ->names([
            'index' => 'lostfound.index',
            'create' => 'lostfound.create',
            'store' => 'lostfound.store',
            'show' => 'lostfound.show',
            'edit' => 'lostfound.edit',
            'update' => 'lostfound.update',
            'destroy' => 'lostfound.destroy',
        ]);
    Route::post('/lost-found/{item}/claim', [LostFoundController::class, 'claim'])
        ->name('lostfound.claim');
    Route::post('/lost-found/{item}/mark-found', [LostFoundController::class, 'markFound'])
        ->name('lostfound.markFound');
});

// ======== ADMIN ONLY ========
Route::middleware([EnsureUserRole::class . ':admin'])->group(function () {
    // APPROVAL ANNOUNCEMENT
    Route::post('/announcements/{announcement}/approve', [AnnouncementController::class, 'approve'])
        ->name('announcements.approve');
    Route::post('/announcements/{announcement}/reject', [AnnouncementController::class, 'reject'])
        ->name('announcements.reject');

    // APPROVAL PROPOSAL
    Route::post('/proposals/{proposal}/approve', [ProposalController::class, 'approve'])
        ->name('proposals.approve');
    Route::post('/proposals/{proposal}/reject', [ProposalController::class, 'reject'])
        ->name('proposals.reject');

    // CONTACT MESSAGES
    Route::get('/messages', [ContactController::class, 'index'])
        ->name('messages.index');
    Route::get('/messages/{message}', [ContactController::class, 'show'])
        ->name('messages.show');
    Route::post('/messages/{message}/reply', [ContactController::class, 'reply'])
        ->name('messages.reply');
});

// No auth.php require; auth middleware intentionally omitted for now


<?php

/**
 * Rute Web untuk Aplikasi Motospon
 * 
 * File ini berisi semua rute web untuk aplikasi Motospon.
 * Rute-rute ini dimuat oleh RouteServiceProvider dalam sebuah grup
 * yang berisi middleware "web".
 */

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\{
    LoginController,
    NewsController,
    GoogleController,
    RegisterController,
    MyProfileDashboardController,
    ProposalController,
    AdminCategoryController,
    NewsDashboardController,
    DashboardController,
    SubmitProposalController,
    SponsorshipDashboardController,
    SponsorshipController
};

// ===================================================
// Grup Rute dan Definisi Middleware
// ===================================================

/*
|--------------------------------------------------------------------------
| Rute Publik
|--------------------------------------------------------------------------
*/

// Halaman Utama
Route::get('/', fn () => view('home', [
    'title' => 'Home',
    'active' => 'home',
]));

// Sponsorship
// Menampilkan semua sponsorship yang tersedia
Route::get('/sponsorships', [SponsorshipController::class, 'index']);
// Menampilkan detail sponsorship berdasarkan slug
Route::get('/sponsorships/{sponsorship:slug}', [SponsorshipController::class, 'show']);

// Berita
// Menampilkan semua artikel berita
Route::get('/news', [NewsController::class, 'index']);
// Menampilkan detail artikel berita berdasarkan slug
Route::get('/news/{news:slug}', [NewsController::class, 'show']);

// Kategori
// Menampilkan semua kategori sponsor
Route::get('/categories', fn () => view('categories', [
    'title' => 'Sponsor Categories',
    'categories' => Category::all()
]));

/*
|--------------------------------------------------------------------------
| Rute Autentikasi (Hanya untuk Tamu)
|--------------------------------------------------------------------------
*/
// Grup rute yang hanya bisa diakses oleh tamu (pengguna yang belum login)
Route::middleware('guest')->group(function () {
    // Masuk
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    // Pendaftaran
    Route::get('/register', [RegisterController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'store']);
});

// Keluar
// Menangani proses logout pengguna
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rute Google OAuth
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])
    ->name('google.redirect');

Route::get('/login/google/callback', [GoogleController::class, 'handleGoogleCallback'])
    ->name('google.callback');

/*
|--------------------------------------------------------------------------
| Rute yang Membutuhkan Autentikasi
|--------------------------------------------------------------------------
*/
// Grup rute yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    // Pemilihan Tipe Akun
    Route::get('/choose-account-type', [GoogleController::class, 'chooseAccountType'])
        ->name('choose.account.type');
    Route::post('/choose-account-type', [GoogleController::class, 'storeAccountType'])
        ->name('store.account.type');

    // Dashboard
    Route::get('/dashboard', function () {
        if (auth()->user()->is_company) {
            return redirect()->route('dashboard.company');
        } else {
            return redirect()->route('dashboard.community');
        }
    })->name('dashboard');

    // Dashboard Routes by Role
    Route::get('/dashboard/company', [DashboardController::class, 'dashboard'])
        ->middleware('account.type')
        ->name('dashboard.company')
        ->defaults('accountType', 'company');
        
    Route::get('/dashboard/community', [DashboardController::class, 'dashboard'])
        ->middleware('account.type')
        ->name('dashboard.community')
        ->defaults('accountType', 'community');

    // Proposal
    // Grup rute untuk manajemen proposal
Route::prefix('proposals')->middleware('account.type')->group(function () {
        Route::get('/', [ProposalController::class, 'index'])->name('proposals.index');
        Route::get('/hidden', [ProposalController::class, 'hidden'])->name('proposals.hidden');
        Route::get('/export-pdf', [ProposalController::class, 'exportPdf'])->name('proposals.export-pdf');
        Route::put('/{id}/update-status', [ProposalController::class, 'updateStatus'])->name('proposals.updateStatus');
        Route::post('/{id}/accept', [ProposalController::class, 'acceptProposal'])->name('proposals.accept');
        Route::post('/{id}/reject', [ProposalController::class, 'rejectProposal'])->name('proposals.reject');
        Route::put('/{id}/hide-from-company', [ProposalController::class, 'hideFromCompany'])->name('proposal.hideFromCompany');
        Route::put('/{id}/unhide-from-company', [ProposalController::class, 'unhideFromCompany'])->name('proposal.unhideFromCompany');
    });

    // Operasi Proposal Tunggal
    // Grup rute untuk operasi pada satu proposal
Route::prefix('proposal')->middleware('account.type')->group(function () {
        Route::get('/preview/{id}', [ProposalController::class, 'previewFromDatabase'])->name('proposal.preview');
        Route::get('/edit/{id}', [ProposalController::class, 'edit'])->name('proposal.edit');
        Route::post('/update/{id}', [ProposalController::class, 'update'])->name('proposal.update');
        Route::delete('/{id}', [ProposalController::class, 'destroy'])->name('proposal.delete');
        Route::post('/{id}/approve', [ProposalController::class, 'approve'])->name('proposal.approve');
        Route::post('/{id}/reject', [ProposalController::class, 'reject'])->name('proposal.reject');
    });

    // Rute Dashboard
    // Grup rute yang terkait dengan dashboard
Route::prefix('dashboard')->group(function () {
        // Dashboard index
        Route::get('/index', [ProposalController::class, 'showUserProposals'])->name('dashboard.index');
        
        // Proposals
        Route::post('/proposals/submit', [SubmitProposalController::class, 'store'])->name('proposals.submit');
        
        // Proposal preview
        Route::get('/preview-proposal', [SubmitProposalController::class, 'showPreview'])->name('dashboard.previewProposal.show');
        Route::post('/preview-proposal', [ProposalController::class, 'previewProposal'])->name('dashboard.previewProposal');
        
        // Submit Proposal
        Route::get('/submitproposal/{sponsorship?}', [SubmitProposalController::class, 'create'])->name('submitproposal');
        Route::post('/submitproposal', [SubmitProposalController::class, 'store'])->name('proposals.store');
        
        // Sponsorships Management (for companies)
        Route::get('/sponsorships/checkSlug', [SponsorshipDashboardController::class, 'checkSlug']);
        Route::resource('/sponsorships', SponsorshipDashboardController::class);
        Route::post('/sponsorships/take', [SubmitProposalController::class, 'take'])->name('sponsorships.take');
        
        // Community Sponsorships (for browsing and viewing)
        Route::get('/community/sponsorships', [SponsorshipDashboardController::class, 'communityIndex'])
            ->name('community.sponsorships.index');
        Route::get('/community/sponsorships/{sponsorship:slug}', [SponsorshipDashboardController::class, 'communityShow'])
            ->name('community.sponsorships.show');
        
        // News
        Route::resource('/news', NewsDashboardController::class);
        Route::get('/news/pdf', [NewsDashboardController::class, 'pdfReport'])->name('pdfReport');
        
        // Profile
        Route::get('/myprofile', [MyProfileDashboardController::class, 'index'])->name('myprofile.index');
        Route::get('/profile/edit', [MyProfileDashboardController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [MyProfileDashboardController::class, 'update'])->name('profile.update');
        
        // Student Management
        Route::get('/student', [MyProfileDashboardController::class, 'studentList'])->name('studentList');
        Route::get('/student/pdfstudent', [MyProfileDashboardController::class, 'pdfStudent'])->name('pdfStudent');
        Route::delete('/student/{userId}', [MyProfileDashboardController::class, 'deleteUser'])->name('deleteUser');
    })->middleware('account.type');
});

/*
|--------------------------------------------------------------------------
| Rute Admin (Hanya Admin)
|--------------------------------------------------------------------------
*/
// Grup rute khusus admin (membutuhkan autentikasi dan hak akses admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('/dashboard/categories', AdminCategoryController::class)->except('show');
});

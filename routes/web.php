<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\{
    LoginController,
    NewsController,
    GoogleController,
    SponsorController,
    RegisterController,
    MyProfileController,
    ProposalController,
    AdminCategoryController,
    NewsDashboardController,
    DashboardSponsorsController,
    CompanyDashboardController,
    DashboardSponsorshipController
};
use App\Models\Proposal;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('home', [
    'title' => 'Home',
    'active' => 'home',
]));

Route::get('/sponsors', [SponsorController::class, 'index']);
Route::get('/sponsors/{sponsor:slug}', [SponsorController::class, 'show']);

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{news:slug}', [NewsController::class, 'show']);

Route::get('/categories', fn () => view('categories', [
    'title' => 'Sponsor Categories',
    'categories' => Category::all()
]));

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    Route::get('/register', [RegisterController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Google OAuth Routes
|--------------------------------------------------------------------------
*/

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/choose-account-type', [GoogleController::class, 'chooseAccountType'])->name('choose.account.type');
Route::post('/choose-account-type', [GoogleController::class, 'storeAccountType'])->name('store.account.type');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (User)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard redirector (checks account type)
    // Hilangkan middleware account.type kalau tidak diperlukan
    Route::get('/dashboard/{accountType?}', [DashboardSponsorsController::class, 'dashboard'])
    ->where('accountType', '^(company|community)?$') // Cuma izinkan ini
    ->name('dashboard');



    // Dashboard Routes by Role
    Route::get('/dashboard/company', [GoogleController::class, 'redirectToDashboard'])->name('dashboard.company');
    Route::get('/dashboard/community', [GoogleController::class, 'redirectToDashboard'])->name('dashboard.community');

    /*
    |--------------------------------------------------------------------------
    | Proposal Management
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/proposals', [ProposalController::class, 'index'])->name('proposals.index');
    Route::put('/dashboard/proposals/{id}/update-status', [ProposalController::class, 'updateStatus'])->name('proposals.updateStatus');

    Route::get('/dashboard/index', [ProposalController::class, 'showUserProposals'])->name('dashboard.index');
    Route::post('/dashboard/proposals/submit', [ProposalController::class, 'submitProposal'])->name('proposals.submit');

    Route::get('/proposal/preview/{id}', [ProposalController::class, 'previewFromDatabase'])->name('proposal.preview');
    Route::get('/dashboard/preview-proposal', [DashboardSponsorsController::class, 'showPreview'])->name('dashboard.previewProposal.show');
    Route::post('/dashboard/preview-proposal', [ProposalController::class, 'previewProposal'])->name('dashboard.previewProposal');

    Route::get('/proposal/edit/{id}', [ProposalController::class, 'edit'])->name('proposal.edit');
    Route::post('/proposal/update/{id}', [ProposalController::class, 'update'])->name('proposal.update');
    Route::delete('/proposal/{id}', [ProposalController::class, 'destroy'])->name('proposal.delete');
    Route::put('/proposals/{id}/hide-from-company', [ProposalController::class, 'hideFromCompany'])->name('proposal.hideFromCompany');



    Route::post('/proposal/{id}/approve', [ProposalController::class, 'approve'])->name('proposal.approve');
    Route::post('/proposal/{id}/reject', [ProposalController::class, 'reject'])->name('proposal.reject');
    Route::post('/proposal/{id}/status', [ProposalController::class, 'updateStatus'])->name('proposal.updateStatus');

    Route::post('/proposals/{id}/accept', [ProposalController::class, 'acceptProposal'])->name('proposals.accept');
    Route::post('/proposals/{id}/reject', [ProposalController::class, 'rejectProposal'])->name('proposals.reject');

    /*
    |--------------------------------------------------------------------------
    | Sponsor Management
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/addsponsor', [DashboardSponsorsController::class, 'addsponsor'])->name('addsponsor');
    Route::post('/dashboard/addsponsor', [DashboardSponsorsController::class, 'storeSponsor'])->name('sponsors.store');
    Route::get('/sponsor/{id}/take', [SponsorController::class, 'take'])->name('sponsor.take');
    Route::get('/sponsor/{id}/complete', [SponsorController::class, 'complete'])->name('sponsor.complete');
    Route::post('/sponsors/take', [DashboardSponsorsController::class, 'takeSponsor'])->name('sponsors.take');

    Route::get('/dashboard/sponsors/checkSlug', [DashboardSponsorsController::class, 'checkSlug']);
    Route::resource('/dashboard/sponsors', DashboardSponsorsController::class);

    /*
    |--------------------------------------------------------------------------
    | News Management
    |--------------------------------------------------------------------------
    */

    Route::resource('/dashboard/news', NewsDashboardController::class);
    Route::get('/dashboard/news/pdf', [NewsDashboardController::class, 'pdfReport'])->name('pdfReport');

    /*
    |--------------------------------------------------------------------------
    | Profile & User Management
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/myprofile', [MyProfileController::class, 'index'])->name('myprofile.index');
    Route::get('/dashboard/editprofile', [MyProfileController::class, 'edit'])->name('editprofile.edit');
    Route::put('/dashboard/myprofile', [MyProfileController::class, 'update'])->name('editprofile.update');

    Route::get('/dashboard/student', [MyProfileController::class, 'studentList'])->name('studentList');
    Route::get('/dashboard/student/pdfstudent', [MyProfileController::class, 'pdfStudent'])->name('pdfStudent');
    Route::delete('/dashboard/student/{userId}', [MyProfileController::class, 'deleteUser'])->name('deleteUser');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Admin Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['admin'])->group(function () {
    Route::resource('/dashboard/categories', AdminCategoryController::class)->except('show');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\NewsDashboardController;
use App\Http\Controllers\DashboardSponsorsController;
use App\Http\Controllers\DashboardSponsorshipController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home', [
        "title" => "Home",
        "active" => "home"
    ]);
});

Route::get('/sponsors', [SponsorController::class, 'index']);
Route::get('/sponsors/{sponsor:slug}', [SponsorController::class, 'show']);

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{news:slug}', [NewsController::class, 'show']);

Route::get('/categories', function () {
    return view('categories', [
        'title' => 'Sponsor Categories',
        'categories' => Category::all()
    ]);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    Route::get('/register', [RegisterController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'logout']);

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/choose-account-type', [GoogleController::class, 'chooseAccountType'])->name('choose.account.type');
Route::post('/choose-account-type', [GoogleController::class, 'storeAccountType'])->name('store.account.type');

Route::middleware(['auth'])->group(function () {

    /** ======================
     *  DASHBOARD USER
     *  ====================== */

    // Dashboard utama user — beri nama route 'dashboard'
    Route::get('/dashboard', [DashboardSponsorsController::class, 'dashboard'])
        ->middleware('account.type')
        ->name('dashboard');

        Route::get('/dashboard/proposals', [ProposalController::class, 'index'])->name('proposals.index');
Route::put('/dashboard/proposals/{id}/update-status', [ProposalController::class, 'updateStatus'])->name('proposals.updateStatus');

    // Halaman index mahasiswa setelah submit proposal
    Route::get('/dashboard/index', [DashboardSponsorsController::class, 'showUserProposals'])->name('dashboard.index');

    // Form tambah sponsor
    Route::get('/dashboard/addsponsor', [DashboardSponsorsController::class, 'addsponsor'])->name('addsponsor');
    Route::post('/dashboard/addsponsor', [DashboardSponsorsController::class, 'storeSponsor'])->name('sponsors.store');

    // Proposal Preview (sebelum submit)
    Route::get('/dashboard/preview-proposal', [DashboardSponsorsController::class, 'showPreview'])->name('dashboard.previewProposal.show');
    Route::post('/dashboard/preview-proposal', [DashboardSponsorsController::class, 'previewProposal'])->name('dashboard.previewProposal');

    // Submit proposal sesudah preview
    Route::post('/dashboard/proposals/submit', [ProposalController::class, 'submitProposal'])->name('proposals.submit');

    /** ======================
     *  PROPOSAL CRUD & STATUS UPDATE
     *  ====================== */

    // CRUD Proposal
    Route::post('/proposals', [ProposalController::class, 'store'])->name('proposals.store');
    Route::get('/proposal/{id}', [ProposalController::class, 'show'])->name('proposal.show');
    Route::delete('/proposal/{id}', [ProposalController::class, 'destroy'])->name('proposal.destroy');

    // Status update (approve/reject)
    Route::post('/proposal/{id}/approve', [ProposalController::class, 'approve'])->name('proposal.approve');
    Route::post('/proposal/{id}/reject', [ProposalController::class, 'reject'])->name('proposal.reject');
    Route::post('/proposal/{id}/status', [ProposalController::class, 'updateStatus'])->name('proposal.updateStatus');

    // Dashboard company (perusahaan)
    Route::get('/dashboard/company', [ProposalController::class, 'showSubmittedProposals'])->name('dashboard.company');

    // Accept / Reject proposal dari perusahaan
    Route::post('/proposals/{id}/accept', [ProposalController::class, 'acceptProposal'])->name('proposals.accept');
    Route::post('/proposals/{id}/reject', [ProposalController::class, 'rejectProposal'])->name('proposals.reject');

    /** ======================
     *  PROFILE & MISC
     *  ====================== */

    Route::get('/dashboard/myprofile', [MyProfileController::class, 'index'])->name('myprofile.index');
    Route::get('/dashboard/editprofile', [MyProfileController::class, 'edit'])->name('editprofile.edit');
    Route::put('/dashboard/myprofile', [MyProfileController::class, 'update'])->name('editprofile.update');

    Route::get('/dashboard/student', [MyProfileController::class, 'studentList'])->name('studentList');
    Route::delete('/dashboard/student/{userId}', [MyProfileController::class, 'deleteUser'])->name('deleteUser');
    Route::get('/dashboard/student/pdfstudent', [MyProfileController::class, 'pdfStudent'])->name('pdfStudent');

    Route::get('/dashboard/news/pdf', [NewsDashboardController::class, 'pdfReport'])->name('pdfReport');

    Route::post('/sponsors/take', [DashboardSponsorsController::class, 'takeSponsor'])->name('sponsors.take');
    Route::get('/sponsor/{id}/take', [SponsorController::class, 'take'])->name('sponsor.take');
    Route::get('/sponsor/{id}/complete', [SponsorController::class, 'complete'])->name('sponsor.complete');

    Route::get('/dashboard/sponsors/checkSlug', [DashboardSponsorsController::class, 'checkSlug']);

    Route::resource('/dashboard/sponsors', DashboardSponsorsController::class);
    Route::resource('/dashboard/news', NewsDashboardController::class);
});

Route::middleware(['admin'])->group(function () {
    Route::resource('/dashboard/categories', AdminCategoryController::class)->except('show');
});

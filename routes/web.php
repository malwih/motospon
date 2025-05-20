<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\NewsDashboardController;
use App\Http\Controllers\DashboardSponsorsController;
use App\Http\Controllers\DashboardSponsorshipController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Ini adalah route web utama aplikasi Anda.
|--------------------------------------------------------------------------
*/

/** ================================
 *  Public Pages (Tanpa Login)
 *  ================================ */
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

/** ================================
 *  Authentication
 *  ================================ */
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    Route::get('/register', [RegisterController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'logout']);

/** ================================
 *  Google Login & Account Type
 *  ================================ */
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/choose-account-type', [GoogleController::class, 'chooseAccountType'])->name('choose.account.type');
Route::post('/choose-account-type', [GoogleController::class, 'storeAccountType'])->name('store.account.type');

/** ================================
 *  Dashboard (Login Required)
 *  ================================ */
Route::middleware(['auth'])->group(function () {

    // Dashboard utama
    Route::get('/dashboard', [DashboardSponsorsController::class, 'dashboard'])
        ->middleware('account.type')
        ->name('dashboard');

    // // Sponsorship page
    // Route::get('/dashboard/sponsorship', [DashboardSponsorshipController::class, 'index'])->name('dashboard.sponsorship');

    // Tambah Sponsor
    Route::get('/dashboard/addsponsor', [DashboardSponsorsController::class, 'addsponsor'])->name('addsponsor');
    Route::post('/dashboard/addsponsor', [DashboardSponsorsController::class, 'storeSponsor'])->name('sponsors.store');
    Route::post('/sponsors/take', [DashboardSponsorsController::class, 'takeSponsor'])->name('sponsors.take');

    // Ambil sponsor (langsung dari halaman sponsor)
    Route::get('/sponsor/{id}/take', [SponsorController::class, 'take'])->name('sponsor.take');
    Route::get('/sponsor/{id}/complete', [SponsorController::class, 'complete'])->name('sponsor.complete');

    // My Profile
    Route::get('/dashboard/myprofile', [MyProfileController::class, 'index'])->name('myprofile.index');
    Route::get('/dashboard/editprofile', [MyProfileController::class, 'edit'])->name('editprofile.edit');
    Route::put('/dashboard/myprofile', [MyProfileController::class, 'update'])->name('editprofile.update');

    // Student Management
    Route::get('/dashboard/student', [MyProfileController::class, 'studentList'])->name('studentList');
    Route::delete('/dashboard/student/{userId}', [MyProfileController::class, 'deleteUser'])->name('deleteUser');
    Route::get('/dashboard/student/pdfstudent', [MyProfileController::class, 'pdfStudent'])->name('pdfStudent');

    // Generate PDF untuk berita
    Route::get('/dashboard/news/pdf', [NewsDashboardController::class, 'pdfReport'])->name('pdfReport');

    // Cek slug sponsor
    Route::get('/dashboard/sponsors/checkSlug', [DashboardSponsorsController::class, 'checkSlug']);

    // Preview Proposal
    Route::post('/dashboard/sponsors/preview-proposal', [DashboardSponsorsController::class, 'previewProposal'])->name('sponsors.previewProposal');

});

/** ================================
 *  Resource Controllers
 *  ================================ */
Route::middleware(['auth'])->group(function () {
    Route::resource('/dashboard/sponsors', DashboardSponsorsController::class);
    Route::resource('/dashboard/news', NewsDashboardController::class);
});

Route::middleware(['admin'])->group(function () {
    Route::resource('/dashboard/categories', AdminCategoryController::class)->except('show');
});

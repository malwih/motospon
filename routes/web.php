<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\NewsDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardProductshipController;

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

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product:slug}', [ProductController::class, 'show']);

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{news:slug}', [NewsController::class, 'show']);

Route::get('/categories', function () {
    return view('categories', [
        'title' => 'Product Categories',
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
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('account.type')
        ->name('dashboard');

    // // Productship page
    // Route::get('/dashboard/productship', [DashboardProductshipController::class, 'index'])->name('dashboard.productship');

    // Tambah Product
    Route::get('/dashboard/addproduct', [DashboardController::class, 'addproduct'])->name('addproduct');
    Route::post('/dashboard/addproduct', [DashboardController::class, 'storeProduct'])->name('products.store');
    Route::post('/products/take', [DashboardController::class, 'takeProduct'])->name('products.take');

    // Ambil product (langsung dari halaman product)
    Route::get('/product/{id}/take', [ProductController::class, 'take'])->name('product.take');
    Route::get('/product/{id}/complete', [ProductController::class, 'complete'])->name('product.complete');

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

    // Cek slug product
    Route::get('/dashboard/products/checkSlug', [DashboardController::class, 'checkSlug']);
});

/** ================================
 *  Resource Controllers
 *  ================================ */
Route::middleware(['auth'])->group(function () {
    Route::resource('/dashboard/products', DashboardController::class);
    Route::resource('/dashboard/news', NewsDashboardController::class);
});

Route::middleware(['admin'])->group(function () {
    Route::resource('/dashboard/categories', AdminCategoryController::class)->except('show');
});

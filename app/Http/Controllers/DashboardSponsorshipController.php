<?php

namespace App\Http\Controllers;

use App\Models\User;  // Mengimpor model User

class DashboardSponsorshipController extends Controller
{
    /**
     * Menampilkan daftar perusahaan sponsor
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil data semua user dengan tipe perusahaan (is_company = 1)
        $companies = User::where('is_company', 1)->get();

        // Mengembalikan view dengan data perusahaan
        return view('dashboard.sponsorship.index', compact('companies'));
    }
}

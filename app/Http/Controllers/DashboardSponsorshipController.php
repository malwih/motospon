<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardSponsorshipController extends Controller
{
    public function index()
{
    $companies = User::where('is_company', 1)->get();

    return view('dashboard.sponsorship.index', compact('companies'));
}

}

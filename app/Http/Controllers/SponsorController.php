<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Sponsor;
use App\Models\Category;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index()
    {
        $title = '';
        if (request('category')) {
            $category = Category::firstWhere('slug', request('category'));
            $title = ' in ' . $category->name;
        }

        return view('sponsors', [
            "title" => "All Sponsor" . $title,
            "active" => 'sponsors',
            "sponsors" => Sponsor::latest()->filter(request(['search', 'category']))->paginate(7)->withQueryString()
        ]);
    }

    public function show(Sponsor $sponsor)
    {
        return view('sponsor', [
            "title" => "$sponsor->title",
            "active" => 'sponsors',
            "sponsor" => $sponsor
        ]);
    }
}

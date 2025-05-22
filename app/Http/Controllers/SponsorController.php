<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Models\Category;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    /**
     * Menampilkan daftar sponsor, bisa difilter berdasarkan kategori atau pencarian.
     */
    public function index(Request $request)
    {
        $title = '';
        $active = 'sponsors';

        // Tambahkan judul jika kategori digunakan sebagai filter
        if ($request->has('category')) {
            $category = Category::firstWhere('slug', $request->category);
            if ($category) {
                $title = ' in ' . $category->name;
            }
        }

        // Ambil sponsor dengan filter dan pagination
        $sponsors = Sponsor::latest()
            ->filter($request->only(['search', 'category']))
            ->paginate(7)
            ->withQueryString();

        return view('sponsors', compact('sponsors', 'active') + [
            'title' => 'All Sponsor' . $title
        ]);
    }

    /**
     * Menampilkan detail dari satu sponsor.
     */
    public function show(Sponsor $sponsor)
    {
        return view('sponsor', [
            'title' => $sponsor->title,
            'active' => 'sponsors',
            'sponsor' => $sponsor
        ]);
    }
}

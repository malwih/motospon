<?php

namespace App\Http\Controllers;

use App\Models\Sponsorship;
use App\Models\Category;
use Illuminate\Http\Request;

class SponsorshipController extends Controller
{
    /**
     * Menampilkan daftar sponsorship dengan filter dan pencarian
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Inisialisasi variabel
        $title = '';
        $active = 'sponsorships';

        // Cek jika ada parameter category pada request
        if ($request->has('category')) {
            // Cari kategori berdasarkan slug
            $category = Category::firstWhere('slug', $request->category);
            if ($category) {
                // Set judul berdasarkan nama kategori
                $title = ' in ' . $category->name;
            }
        }

        // Ambil daftar kategori unik dari sponsorship
        $categories = Sponsorship::select('category')
            ->distinct()                    // Hapus duplikat
            ->orderBy('category')           // Urutkan berdasarkan nama kategori
            ->pluck('category')             // Ambil hanya kolom category
            ->filter()                      // Hapus nilai null/kosong
            ->toArray();                    // Konversi ke array

        // Query untuk mendapatkan daftar sponsorship
        $sponsorships = Sponsorship::withCount('proposals')  // Hitung jumlah proposal
            ->latest()                       // Urutkan terbaru
            ->filter($request->only([        // Terapkan filter
                'search', 
                'category'
            ]))
            ->paginate(7)                    // Paginasi 7 item per halaman
            ->withQueryString();             // Pertahankan parameter URL

        // Tampilkan view dengan data yang diperlukan
        return view('sponsorships', 
            compact('sponsorships', 'active', 'categories') + [
                'title' => 'Sponsorship' . $title
            ]
        );
    }

    
    /**
     * Menampilkan detail dari satu sponsorship
     * 
     * @param Sponsorship $sponsorship
     * @return \Illuminate\View\View
     */
    public function show(Sponsorship $sponsorship)
    {
        // Tampilkan halaman detail sponsorship
        return view('sponsorship', [
            'title' => $sponsorship->title,  // Judul halaman
            'active' => 'sponsorships',      // Menu aktif
            'sponsorship' => $sponsorship     // Data sponsorship
        ]);
    }
}
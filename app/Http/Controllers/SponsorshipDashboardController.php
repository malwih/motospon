<?php

namespace App\Http\Controllers;

use App\Models\Sponsorship;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Cviebrock\EloquentSluggable\Services\SlugService;

class SponsorshipDashboardController extends Controller
{
    /**
     * Display a listing of sponsorships for community users
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function communityIndex(Request $request)
    {
        $query = Sponsorship::with(['author', 'proposals'])
            ->withCount('proposals')
            ->latest();

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('body', 'like', $searchTerm)
                  ->orWhere('category', 'like', $searchTerm);
            });
        }

        // Apply category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        
        // Get paginated results
        $perPage = $request->input('per_page', 9);
        $sponsorships = $query->paginate($perPage);
        
        // Get unique categories for filter
        $categories = Sponsorship::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->toArray();

        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson() || $request->has('ajax')) {
            // If this is a browser back/forward navigation, redirect to the clean URL
            $referer = $request->header('referer');
            if ($referer && (strpos($referer, 'sponsorships/') !== false || strpos($referer, 'detail') !== false)) {
                $cleanUrl = url()->current() . ($request->getQueryString() ? '?' . $request->getQueryString() : '');
                return redirect($cleanUrl);
            }

            $request->headers->set('X-Requested-With', 'XMLHttpRequest');
            
            $formattedSponsorships = $sponsorships->getCollection()->map(function($sponsorship) {
                return [
                    'id' => $sponsorship->id,
                    'title' => $sponsorship->title,
                    'slug' => $sponsorship->slug,
                    'body' => strip_tags($sponsorship->body),
                    'category' => $sponsorship->category,
                    'author_name' => $sponsorship->author ? $sponsorship->author->name : 'Unknown',
                    'proposals_count' => $sponsorship->proposals_count,
                    'posted_on' => $sponsorship->created_at->format('M d, Y'),
                    'image' => $sponsorship->image ? asset('storage/' . $sponsorship->image) : asset('images/default-sponsorship.jpg')
                ];
            });

            return response()
                ->json([
                    'success' => true,
                    'data' => $formattedSponsorships,
                    'pagination' => [
                        'current_page' => $sponsorships->currentPage(),
                        'last_page' => $sponsorships->lastPage(),
                        'per_page' => $sponsorships->perPage(),
                        'total' => $sponsorships->total(),
                        'next_page_url' => $sponsorships->nextPageUrl(),
                        'prev_page_url' => $sponsorships->previousPageUrl(),
                        'from' => $sponsorships->firstItem(),
                        'to' => $sponsorships->lastItem()
                    ],
                    'first_item' => $sponsorships->firstItem(),
                    'last_item' => $sponsorships->lastItem(),
                    'links' => $sponsorships->links()->toHtml()
                ])
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        return view('dashboard.sponsorshipscommunity.searchsponsorship', [
            'sponsorships' => $sponsorships,
            'categories' => $categories,
            'active' => 'sponsorships'
        ]);
    }

    /**
     * Display the specified sponsorship for community users
     *
     * @param  \App\Models\Sponsorship  $sponsorship
     * @return \Illuminate\View\View
     */
    public function communityShow(Sponsorship $sponsorship)
    {
        // Load the sponsorship with its relationships
        $sponsorship->load(['author', 'proposals']);

        // Check if views column exists before incrementing
        if (Schema::hasColumn('sponsorships', 'views')) {
            $sponsorship->increment('views');
        } else {
            // Set default view count if column doesn't exist
            $sponsorship->views = 0;
        }

        return view('dashboard.sponsorshipscommunity.detailsponsorship', [
            'sponsorship' => $sponsorship,
            'active' => 'sponsorships'
        ]);
    }

    /**
    /**
     * Menampilkan daftar sponsorship milik user yang login
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    /**
     * Display a listing of the user's sponsorships
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Check if user has selected account type
        $user = Auth::user();
        if (!$user->is_company && !$user->is_community) {
            return redirect()->route('choose.account.type');
        }
        
        // Get sponsorships created by the authenticated user
        return view('dashboard.sponsorships.index', [
            'sponsorships' => Sponsorship::where('user_id', $user->id)
                ->withCount('proposals')
                ->latest()
                ->get(),
            'active' => 'my-sponsorships'
        ]);
    }

    /**
     * Menampilkan form untuk membuat sponsorship baru
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.sponsorships.create');
    }

    /**
     * Menampilkan form edit sponsorship
     * 
     * @param Sponsorship $sponsorship
     * @return \Illuminate\View\View
     */
    public function edit(Sponsorship $sponsorship)
    {
        return view('dashboard.sponsorships.edit', [
            'sponsorship' => $sponsorship
        ]);
    }

    /**
     * Menampilkan detail sponsorship
     * 
     * @param Sponsorship $sponsorship
     * @return \Illuminate\View\View
     */
    public function show(Sponsorship $sponsorship)
    {
        return view('dashboard.sponsorships.show', [
            'sponsorship' => $sponsorship
        ]);
    }

    /**
     * Menyimpan data sponsorship baru
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:sponsorships',
            'image' => 'image|file|max:51200', // Maksimal 50MB
            'body' => 'required',
            'category' => 'required|array',
            'category.*' => 'string|max:255',
            'event' => 'required|array',
            'event.*' => 'string|max:255',
        ]);

        // Simpan gambar jika diupload
        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('sponsorship-images');
        }

        // Konversi array ke string untuk disimpan di database
        $validatedData['category'] = implode(', ', $request->category);
        $validatedData['event'] = implode(', ', $request->event);

        // Tambahkan data tambahan
        $validatedData['user_id'] = auth()->id();
        $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 100);

        // Simpan data ke database
        Sponsorship::create($validatedData);

        return redirect('/dashboard/sponsorships')
            ->with('success', 'New sponsorship has been created!');
    }

    /**
     * Memperbarui data sponsorship
     * 
     * @param Request $request
     * @param Sponsorship $sponsorship
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Sponsorship $sponsorship)
    {
        // Aturan validasi dasar
        $rules = [
            'title' => 'required|max:255',
            'image' => 'image|file|max:51200', // Maksimal 50MB
            'body' => 'required',
            'category' => 'required|array|min:1',
            'category.*' => 'string|max:255',
            'event' => 'required|array|min:1',
            'event.*' => 'string|max:255',
        ];

        // Validasi slug unik jika berubah
        if ($request->slug != $sponsorship->slug) {
            $rules['slug'] = 'required|unique:sponsorships';
        }

        // Validasi input
        $validatedData = $request->validate($rules);

        // Update gambar jika diupload
        if ($request->file('image')) {
            // Hapus gambar lama jika ada
            if ($sponsorship->image) {
                \Storage::delete($sponsorship->image);
            }
            $validatedData['image'] = $request->file('image')->store('sponsorship-images');
        } elseif ($request->has('oldImage')) {
            // Jika tidak ada gambar baru diupload, gunakan gambar lama
            $validatedData['image'] = $request->oldImage;
        }

        // Bersihkan array dari nilai yang kosong
        $categories = array_filter($request->category, function($value) {
            return !empty(trim($value));
        });
        
        $events = array_filter($request->event, function($value) {
            return !empty(trim($value));
        });

        // Pastikan setidaknya ada satu kategori dan event
        if (empty($categories) || empty($events)) {
            return back()->withErrors([
                'category' => empty($categories) ? 'At least one category is required' : null,
                'event' => empty($events) ? 'At least one event is required' : null,
            ])->withInput();
        }

        // Konversi array ke string untuk disimpan di database
        $validatedData['category'] = implode(', ', $categories);
        $validatedData['event'] = implode(', ', $events);
        
        // Update data tambahan
        $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 200);

        // Update data di database
        $sponsorship->update($validatedData);

        return redirect('/dashboard/sponsorships')
            ->with('success', 'Sponsorship has been updated!');
    }

    /**
     * Menghapus data sponsorship
     * 
     * @param Sponsorship $sponsorship
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Sponsorship $sponsorship)
    {
        // Hapus gambar dari storage jika ada
        if ($sponsorship->image) {
            \Storage::delete($sponsorship->image);
        }

        // Hapus data dari database
        $sponsorship->delete();

        return redirect('/dashboard/sponsorships')
            ->with('success', 'Sponsorship has been deleted!');
    }

    /**
     * Mengecek dan membuat slug otomatis
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Sponsorship::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }
}

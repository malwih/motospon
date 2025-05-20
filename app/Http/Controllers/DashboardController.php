<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Sponsor;
use App\Models\SponsorUser;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use \Cviebrock\EloquentSluggable\Services\SlugService;


class DashboardController extends Controller
{

    public function index()
    {

        $user = Auth::user();

    if (!$user->is_company && !$user->is_community) {
        return redirect()->route('choose.account.type');
    }
    
        return view('dashboard.sponsors.index', [
            'sponsors' => Sponsor::where('user_id', auth()->user()->id)->get()
        ]);
    }

    public function dashboard()
    {
        $user = Auth::user();
        $sponsors = $user->sponsors; // Mengambil daftar sponsor yang sudah diambil oleh user

        return view('dashboard.index', [
            'user' => $user,
            'sponsors' => $sponsors,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        // Tidak perlu lagi melewati informasi kategori ke view
        return view('dashboard.sponsors.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:sponsors',
            'image' => 'image|file|max:51200',
            'body' => 'required',
        ]);

        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('sponsor-images');
        }

        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 100);

        Sponsor::create($validatedData);

        return redirect('/dashboard/sponsors')->with('success', 'New sponsor has been added!');
    }

    public function show(Sponsor $sponsor)
    {
        return view('dashboard.sponsors.show', [
            'sponsor' => $sponsor
        ]);
    }

    public function edit(Sponsor $sponsor)
    {
        return view('dashboard.sponsors.edit', [
            'sponsor' => $sponsor
        ]);
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $rules = [
            'title' => 'required|max:255',
            'image' => 'image|file|max:51200',
            'body' => 'required'
        ];

        if ($request->slug != $sponsor->slug) {
            $rules['slug'] = 'required|unique:sponsors';
        }

        $validatedData = $request->validate($rules);

        if ($request->file('image')) {
            // Sesuaikan pengelolaan gambar sesuai kebutuhan Anda
            // Jangan lupa untuk menghapus gambar yang lama jika diperlukan
            // Contoh: Storage::delete($sponsor->image);
            $validatedData['image'] = $request->file('image')->store('sponsor-images');
        }

        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 200);

        $sponsor->update($validatedData);

        return redirect('/dashboard/sponsors')->with('success', 'Class has been updated!');
    }

    public function destroy(Sponsor $sponsor)
    {
        // Sesuaikan penghapusan file gambar jika diperlukan
        // Contoh: if ($sponsor->image) { Storage::delete($sponsor->image); }

        $sponsor->delete();

        return redirect('/dashboard/sponsors')->with('success', 'Class has been deleted!');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Sponsor::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }

    // public function addsponsor(Sponsor $sponsor)
    // {
    //     return view('dashboard.addsponsor', [
    //         'sponsor' => $sponsor
    //     ]);
    // }

    public function addsponsor()
    {
        $sponsors = Sponsor::all();

        return view('dashboard.addsponsor', [
            'sponsors' => $sponsors
        ]);
    }

    public function takeSponsor(Request $request)
    {
        $validatedData = $request->validate([
            'sponsor_id' => 'required|exists:sponsors,id',
            'schedule' => 'required',
            'price' => 'required',
        ]);

        $user = auth()->user();

        // Periksa apakah pengguna memiliki kursus yang aktif pada SponsorUser
        $activeSponsor = SponsorUser::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($activeSponsor) {
            return back()->with('error', 'Anda masih memiliki kursus yang aktif lainnya.');
        }

        // Simpan data ke pivot table sponsor_user
        $sponsorUser = SponsorUser::create([
            'sponsor_id' => $validatedData['sponsor_id'],
            'user_id' => $user->id,
            'is_active' => true,
            'is_completed' => false,
        ]);

        if ($sponsorUser) {
            return redirect()->route('dashboard')->with('success', 'Sponsor added successfully.');
        } else {
            return back()->with('error', 'Failed to add sponsor.');
        }
    }


    public function storeSponsor(Request $request)
    {
        $validatedData = $request->validate([
            'sponsor_id' => 'required|exists:sponsors,id',
            'schedule' => 'required',
            'price' => 'required',
        ]);

        $user = auth()->user();

        // Simpan data ke pivot table sponsor_user
        $sponsorUser = SponsorUser::create([
            'sponsor_id' => $validatedData['sponsor_id'],
            'user_id' => $user->id,
            'is_active' => true, // Atur status aktif ke true jika baru ditambahkan
            'is_completed' => false, // Default nilai is_completed ke false
            // ... (menambahkan field lainnya)
        ]);

        if ($sponsorUser) {
            return back()->with('success', 'Sponsor added successfully.');
        } else {
            return back()->with('error', 'Failed to add sponsor.');
        }
    }
}

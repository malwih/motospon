<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Sponsor;
use App\Models\Proposal;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use \Cviebrock\EloquentSluggable\Services\SlugService;


class DashboardSponsorsController extends Controller
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

    public function dashboard($accountType = null)
{
    $user = Auth::user();

    if (!$accountType) {
        // Otomatis arahkan sesuai tipe user
        if ($user->is_admin) {
            return redirect('/dashboard/admin');
        } elseif ($user->is_company) {
            return redirect('/dashboard/company');
        } elseif ($user->is_community) {
            return redirect('/dashboard/community');
        } else {
            return redirect()->route('choose.account.type');
        }
    }

    // Validasi tipe akun
    if (!in_array($accountType, ['admin', 'company', 'community'])) {
        abort(404); // atau redirect ke default
    }

    $sponsors = $user->sponsors;
    $proposals = Proposal::where('user_id', $user->id)->get();

    return view('dashboard.' . $accountType, [
        'user' => $user,
        'sponsors' => $sponsors,
        'proposals' => $proposals,
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
        'category' => 'required|array',
        'category.*' => 'string|max:255',
        'event' => 'required|array',
        'event.*' => 'string|max:255',
    ]);

    // Simpan gambar jika ada
    if ($request->file('image')) {
        $validatedData['image'] = $request->file('image')->store('sponsor-images');
    }

    // Konversi array menjadi string agar bisa disimpan ke database (kolom string)
    $validatedData['category'] = implode(', ', $request->category);
    $validatedData['event'] = implode(', ', $request->event);

    // Tambahan data
    $validatedData['user_id'] = auth()->user()->id;
    $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 100);

    // Simpan sponsor
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
            'category' => 'required',
            'event' => 'required',
        ]);

        $user = auth()->user();

        // Periksa apakah pengguna memiliki kursus yang aktif pada Proposal
        $activeSponsor = Proposal::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($activeSponsor) {
            return back()->with('error', 'Anda masih memiliki kursus yang aktif lainnya.');
        }

        // Simpan data ke pivot table sponsor_user
        $sponsorUser = Proposal::create([
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
        'category' => 'required',
        'event' => 'required',
    ]);

    $user = auth()->user();

    $proposal = Proposal::create([
        'sponsor_id' => $validatedData['sponsor_id'],
        'user_id' => $user->id,
        'category' => $validatedData['category'],
        'event' => $validatedData['event'],
        'is_active' => true,
        'is_completed' => false,
        'is_reject' => false,
    ]);

    return redirect()->route('dashboard')->with('success', 'Proposal berhasil disubmit!');
}







    public function showPreview()
{
    // Optional: cegah akses langsung tanpa POST
    return redirect()->route('dashboard'); // Atau tampilkan view default
}

}

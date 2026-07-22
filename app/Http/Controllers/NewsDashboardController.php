<?php

namespace App\Http\Controllers;

use TCPDF;
use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class NewsDashboardController extends Controller
{
    /**
     * Menampilkan daftar berita milik pengguna dengan pencarian dan sorting.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort') === 'desc' ? 'desc' : 'asc';
        $search = $request->query('search');

        $news = News::where('user_id', auth()->id())
            ->when($search, function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->orderBy('title', $sort)
            ->get();

        return view('dashboard.news.index', compact('news'));
    }

    /**
     * Halaman dashboard utama.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $news = $user->news;

        return view('dashboard.index', compact('user', 'news'));
    }

    /**
     * Form tambah berita baru.
     */
    public function create()
    {
        return view('dashboard.news.create');
    }

    /**
     * Simpan berita baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:news',
            'image' => 'nullable|image|file|max:51200',
            'body' => 'required',
        ]);

        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('news-images');
        }

        $validatedData['user_id'] = auth()->id();
        $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 100);

        News::create($validatedData);

        return redirect('/dashboard/news')->with('success', 'News has been added!');
    }

    /**
     * Tampilkan detail satu berita.
     */
    public function show(News $news)
    {
        return view('dashboard.news.show', compact('news'));
    }

    /**
     * Form edit berita.
     */
    public function edit(News $news)
    {
        return view('dashboard.news.edit', compact('news'));
    }

    /**
     * Update berita ke database.
     */
    public function update(Request $request, News $news)
    {
        $rules = [
            'title' => 'required|max:255',
            'image' => 'nullable|image|file|max:51200',
            'body' => 'required',
        ];

        if ($request->slug !== $news->slug) {
            $rules['slug'] = 'required|unique:news';
        }

        $validatedData = $request->validate($rules);

        if ($request->file('image')) {
            if ($news->image) {
                Storage::delete($news->image);
            }
            $validatedData['image'] = $request->file('image')->store('news-images');
        }

        $validatedData['user_id'] = auth()->id();
        $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 200);

        $news->update($validatedData);

        return redirect('/dashboard/news')->with('success', 'News has been updated!');
    }

    /**
     * Hapus berita dari database.
     */
    public function destroy(News $news)
    {
        if ($news->image) {
            Storage::delete($news->image);
        }

        $news->delete();

        return redirect('/dashboard/news')->with('success', 'News has been deleted!');
    }

    /**
     * Generate slug otomatis berdasarkan judul.
     */
    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(News::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }

    /**
     * Cetak laporan PDF berita.
     */
    public function pdfReport()
    {
        $news = News::where('user_id', auth()->id())->get();

        $pdf = new TCPDF();
        $pdf->SetHeaderData('', 0, 'News Report', '');
        $pdf->AddPage();
        $pdf->writeHTML(View::make('dashboard.news.pdf', compact('news'))->render());

        return new Response($pdf->Output('news_report.pdf', 'D'), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductUser;
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
    
        return view('dashboard.products.index', [
            'products' => Product::where('user_id', auth()->user()->id)->get()
        ]);
    }

    public function dashboard()
    {
        $user = Auth::user();
        $products = $user->products; // Mengambil daftar product yang sudah diambil oleh user

        return view('dashboard.index', [
            'user' => $user,
            'products' => $products,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        // Tidak perlu lagi melewati informasi kategori ke view
        return view('dashboard.products.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:products',
            'image' => 'image|file|max:51200',
            'body' => 'required',
        ]);

        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('product-images');
        }

        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 100);

        Product::create($validatedData);

        return redirect('/dashboard/products')->with('success', 'New product has been added!');
    }

    public function show(Product $product)
    {
        return view('dashboard.products.show', [
            'product' => $product
        ]);
    }

    public function edit(Product $product)
    {
        return view('dashboard.products.edit', [
            'product' => $product
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $rules = [
            'title' => 'required|max:255',
            'image' => 'image|file|max:51200',
            'body' => 'required'
        ];

        if ($request->slug != $product->slug) {
            $rules['slug'] = 'required|unique:products';
        }

        $validatedData = $request->validate($rules);

        if ($request->file('image')) {
            // Sesuaikan pengelolaan gambar sesuai kebutuhan Anda
            // Jangan lupa untuk menghapus gambar yang lama jika diperlukan
            // Contoh: Storage::delete($product->image);
            $validatedData['image'] = $request->file('image')->store('product-images');
        }

        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 200);

        $product->update($validatedData);

        return redirect('/dashboard/products')->with('success', 'Class has been updated!');
    }

    public function destroy(Product $product)
    {
        // Sesuaikan penghapusan file gambar jika diperlukan
        // Contoh: if ($product->image) { Storage::delete($product->image); }

        $product->delete();

        return redirect('/dashboard/products')->with('success', 'Class has been deleted!');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Product::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }

    // public function addproduct(Product $product)
    // {
    //     return view('dashboard.addproduct', [
    //         'product' => $product
    //     ]);
    // }

    public function addproduct()
    {
        $products = Product::all();

        return view('dashboard.addproduct', [
            'products' => $products
        ]);
    }

    public function takeProduct(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'schedule' => 'required',
            'price' => 'required',
        ]);

        $user = auth()->user();

        // Periksa apakah pengguna memiliki kursus yang aktif pada ProductUser
        $activeProduct = ProductUser::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($activeProduct) {
            return back()->with('error', 'Anda masih memiliki kursus yang aktif lainnya.');
        }

        // Simpan data ke pivot table product_user
        $productUser = ProductUser::create([
            'product_id' => $validatedData['product_id'],
            'user_id' => $user->id,
            'is_active' => true,
            'is_completed' => false,
        ]);

        if ($productUser) {
            return redirect()->route('dashboard')->with('success', 'Product added successfully.');
        } else {
            return back()->with('error', 'Failed to add product.');
        }
    }


    public function storeProduct(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'schedule' => 'required',
            'price' => 'required',
        ]);

        $user = auth()->user();

        // Simpan data ke pivot table product_user
        $productUser = ProductUser::create([
            'product_id' => $validatedData['product_id'],
            'user_id' => $user->id,
            'is_active' => true, // Atur status aktif ke true jika baru ditambahkan
            'is_completed' => false, // Default nilai is_completed ke false
            // ... (menambahkan field lainnya)
        ]);

        if ($productUser) {
            return back()->with('success', 'Product added successfully.');
        } else {
            return back()->with('error', 'Failed to add product.');
        }
    }
}

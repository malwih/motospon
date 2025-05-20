<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $title = '';
        if (request('category')) {
            $category = Category::firstWhere('slug', request('category'));
            $title = ' in ' . $category->name;
        }

        return view('products', [
            "title" => "All Product" . $title,
            "active" => 'products',
            "products" => Product::latest()->filter(request(['search', 'category']))->paginate(7)->withQueryString()
        ]);
    }

    public function show(Product $product)
    {
        return view('product', [
            "title" => "$product->title",
            "active" => 'products',
            "product" => $product
        ]);
    }
}

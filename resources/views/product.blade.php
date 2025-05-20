@extends('layouts.main')

@section('container')

<div class="container">
    <div class="container mx-auto px-20">
        <div class="item-center content-center justify-center col-md-8">
            <h1 class="text-3xl font-bold text-black mb-2 mt-8">{{ $product->title }}</h1>
            
            @if($product->image)
                <div style="max-height: 500px; overflow:hidden;">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="img-fluid">
                </div>
                @else
                <img src="https://source.unsplash.com/1200x400?{{ $product->title }}" alt="{{ $product->title }}" class="img-fluid mt-6">
                @endif
            
            <article class="my-3 fs-5">
            {!! $product->body !!}
            </article>
            <a href="/products" type="button" class="text-white bg-orange-500 hover:bg-orange-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Back to Sponsors</a>
            <a href="/register" type="button" class="text-white bg-green-500 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Register Class</a>

        </div>
    </div>
</div>

@endsection
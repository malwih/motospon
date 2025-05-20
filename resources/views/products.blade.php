@extends('layouts.main')

@section('container')
    <!-- Header -->
    <div class="bg-gradient-to-tr from-orange-300 to-orange-200 pt-24 pb-6">
        <h2 class="text-center text-3xl font-bold text-orange-600">Daftar Sponsorship</h2>
    </div>

    <!-- Content -->
    <div class="min-h-screen bg-gradient-to-tr from-gray-200 to-gray-100 px-6 py-12">
        <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 max-w-7xl mx-auto">
            @forelse ($products as $product)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:scale-105 transition-transform duration-300">
                    <div class="relative">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
                             class="w-full h-48 object-cover">
                        <div class="absolute top-0 left-0 bg-yellow-300 text-gray-900 font-semibold py-1 px-3 rounded-br-xl">
                            Rp{{ number_format($product->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $product->title }}</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p>{{ $product->body }}</p>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p>{{ $product->slug }}</p>
                            </div>
                        </div>
                        <a href="/products/{{ $product->slug }}">
                            <button
                                class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-xl shadow-md transition duration-300">
                                Lihat Detail
                            </button>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">
                    <p>Tidak ada sponsorship yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

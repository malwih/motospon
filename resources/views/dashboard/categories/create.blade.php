{{-- Extend layout utama dashboard --}}
@extends('dashboard.layouts.main')

@section('title', 'Create Categories - Motospon')

{{-- Section untuk konten utama --}}
@section('container')
    {{-- Container utama dengan padding dan margin --}}
    <div class="p-4 sm:ml-64">
        {{-- Card container --}}
        <div class="p-6 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-20 bg-white dark:bg-gray-800">
            {{-- Header card --}}
            <div class="flex justify-between items-center pb-4 mb-6 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Category</h1>
            </div>

            {{-- Form container --}}
            <div class="w-full max-w-2xl">
                {{-- Form untuk membuat kategori baru --}}
                <form method="post" action="/dashboard/categories" class="space-y-6">
                    @csrf
                    
                    {{-- Input field untuk nama kategori --}}
                    <div>
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Category Name
                        </label>
                        <input 
                            type="text" 
                            id="category" 
                            name="category" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('category') border-red-500 @enderror"
                            required 
                            autofocus 
                            value="{{ old('category') }}"
                            placeholder="Enter category name"
                        >
                        {{-- Menampilkan pesan error jika validasi gagal --}}
                        @error('category')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tombol submit --}}
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-blue-800 transition-all duration-200"
                        >
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
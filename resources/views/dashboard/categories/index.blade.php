{{-- Extend layout utama dashboard --}}
@extends('dashboard.layouts.main')

@section('title', 'Categories - Motospon')

{{-- Section untuk konten utama --}}
@section('container')
    {{-- Container utama dengan padding dan margin --}}
    <div class="p-4 sm:ml-64">
        {{-- Card container --}}
        <div class="p-6 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-20 bg-white dark:bg-gray-800">
            {{-- Header section --}}
            <div class="flex justify-between items-center pb-4 mb-6 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Category Course</h1>
                <a 
                    href="/dashboard/categories/create" 
                    class="text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200"
                >
                    Create New Category
                </a>
            </div>

            {{-- Notification alert --}}
            @if(session()->has('success'))
                <div class="flex items-center p-4 mb-6 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-1" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Table container --}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    {{-- Table header --}}
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Category Name</th>
                            <th scope="col" class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    {{-- Table body --}}
                    <tbody>
                        @foreach ($categories as $category)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            {{-- Nomor urut --}}
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $loop->iteration }}
                            </td>
                            {{-- Nama kategori --}}
                            <td class="px-6 py-4">
                                {{ $category->name }}
                            </td>
                            {{-- Tombol aksi --}}
                            <td class="px-6 py-4 flex items-center space-x-2">
                                {{-- Tombol view --}}
                                <a 
                                    href="/dashboard/categories/{{ $category->slug }}" 
                                    class="p-2 text-blue-600 hover:text-white border border-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200"
                                    title="View"
                                >
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </a>
                                {{-- Tombol edit --}}
                                <a 
                                    href="/dashboard/categories/{{ $category->slug }}/edit" 
                                    class="p-2 text-yellow-600 hover:text-white border border-yellow-600 hover:bg-yellow-700 rounded-lg transition-colors duration-200"
                                    title="Edit"
                                >
                                    <i data-feather="edit" class="w-4 h-4"></i>
                                </a>
                                {{-- Tombol delete --}}
                                <form 
                                    action="/dashboard/categories/{{ $category->slug }}" 
                                    method="post" 
                                    class="inline"
                                >
                                    @method('delete')
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="p-2 text-red-600 hover:text-white border border-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200"
                                        onclick="return confirm('Are you sure?')"
                                        title="Delete"
                                    >
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Inisialisasi Feather Icons --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
@endsection
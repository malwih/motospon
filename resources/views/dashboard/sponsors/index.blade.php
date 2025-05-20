@extends('dashboard.layouts.main')

@php
    use Illuminate\Support\Str;
@endphp

@section('container')

<div class="p-10 sm:ml-80">
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-6xl mx-auto mt-20">

        <div class="flex justify-between items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Sponsors</h1>
            <a href="/dashboard/sponsors/create" class="text-white bg-orange-500 hover:bg-orange-600 font-medium rounded-lg text-sm px-5 py-2.5">
                + Create New Sponsor
            </a>
        </div>

        @if(session()->has('success'))
        <div class="flex items-center p-4 mt-6 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
            <svg class="w-5 h-5 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            {{ session('success') }}
            <button type="button" class="ml-auto bg-transparent border-0 text-green-800 hover:text-green-900" data-bs-dismiss="alert" aria-label="Close">&times;</button>
        </div>
        @endif

        <div class="overflow-x-auto mt-8">
            <table class="min-w-full text-base text-left text-gray-900">
                <thead class="text-sm text-white uppercase bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Sponsor Name</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sponsors as $sponsor)
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 font-semibold">
                            {{ $sponsor->title }}
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            {{ Str::words(strip_tags($sponsor->body), 10, '...') }}
                        </td>
                        <td class="px-6 py-4 flex space-x-2">
                            <a href="/dashboard/sponsors/{{ $sponsor->slug }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-2 rounded" title="View">
                                <span data-feather="eye"></span>
                            </a>
                            <a href="/dashboard/sponsors/{{ $sponsor->slug }}/edit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded" title="Edit">
                                <span data-feather="edit"></span>
                            </a>
                            <form action="/dashboard/sponsors/{{ $sponsor->slug }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @method('delete')
                                @csrf
                                <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" title="Delete">
                                    <span data-feather="x-circle"></span>
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

@endsection

@extends('dashboard.layouts.main')

@section('container')

<div class="p-10 sm:ml-80">
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white  mx-auto mt-20">

        <div class="flex justify-between items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Detail Sponsor</h1>

            <div class="flex items-center space-x-2">
                <a href="/dashboard/sponsors" class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded flex items-center justify-center">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                </a>
                <a href="/dashboard/sponsors/{{ $sponsor->slug }}/edit" class="bg-yellow-600 hover:bg-yellow-700 text-white p-2 rounded flex items-center justify-center">
                    <i data-feather="edit" class="w-4 h-4"></i>
                </a>
                <form action="/dashboard/sponsors/{{ $sponsor->slug }}" method="post" onsubmit="return confirm('Are you sure?')" class="inline">
                    @method('delete')
                    @csrf
                    <button class="bg-red-600 hover:bg-red-700 text-white p-2 rounded flex items-center justify-center">
                        <i data-feather="x-circle" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>

        <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">{{ $sponsor->title }}</h2>

        @if($sponsor->image)
        <div class="overflow-hidden rounded-lg shadow-sm mb-6 max-h-[350px]">
            <img src="{{ asset('storage/' . $sponsor->image) }}" alt="{{ $sponsor->title }}" class="w-full object-cover">
        </div>
        @endif

        <article class="prose prose-lg max-w-none text-gray-800">
            {!! $sponsor->body !!}
        </article>

    </div>
</div>

<script>
    feather.replace();
</script>

@endsection

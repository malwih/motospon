@extends('layouts.main')

@section('container')

<div class="pt-28 pb-12 bg-gray-50 min-h-screen w-full">
    <div class="w-full max-w-screen-xl mx-auto px-6 sm:px-12">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8">{{ $sponsor->title }}</h1>

        @if($sponsor->image)
            <div class="overflow-hidden rounded-lg shadow-lg mb-8 max-h-[500px] w-full">
                <img src="{{ asset('storage/' . $sponsor->image) }}" alt="{{ $sponsor->title }}" class="w-full h-auto object-cover object-center">
            </div>
        @else
            <img src="https://source.unsplash.com/1200x400?{{ $sponsor->title }}" alt="{{ $sponsor->title }}" class="w-full rounded-lg shadow-lg mb-8 object-cover object-center h-auto">
        @endif

        <article class="prose max-w-none text-gray-700 mb-10">
            {!! $sponsor->body !!}
        </article>

        <div class="flex flex-wrap gap-4">
            <a href="/sponsors"
   class="inline-flex items-center bg-orange-500 hover:bg-orange-700 text-white font-semibold rounded-lg px-6 py-3 transition duration-300 text-center">
   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
       <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
   </svg>
   Back to Sponsors
</a>


            <a href="/register"
               class="inline-block bg-green-500 hover:bg-green-700 text-white font-semibold rounded-lg px-6 py-3 transition duration-300 text-center">
               Register Class
            </a>
        </div>
    </div>
</div>

@endsection

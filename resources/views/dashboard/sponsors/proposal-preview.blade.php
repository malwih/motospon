@extends('dashboard.layouts.main')

@section('container')
<div class="p-10 sm:ml-80">
  <div class="bg-white shadow-lg rounded-lg p-8 max-w-4xl mx-auto mt-24 font-sans text-gray-800 leading-relaxed">
    <h1 class="text-3xl font-semibold mb-8 border-b pb-4 border-gray-300">Preview Proposal</h1>
    
    <div class="prose max-w-none text-gray-900">
      {!! $proposal !!}
    </div>

    <a href="{{ url()->previous() }}"
       class="mt-10 inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition duration-300">
      Back
    </a>
  </div>
</div>
@endsection

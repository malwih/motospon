@extends('dashboard.layouts.main')

@section('container')
<div class="p-10 sm:ml-80">
  <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-7xl mx-auto mt-20">

    <div class="flex justify-between flex-wrap items-center pb-4 border-b border-gray-300">
      <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    </div>

    @if(session()->has('error'))
      <div class="flex items-center p-4 mt-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-900 dark:text-red-400 dark:border-red-800" role="alert">
        {{ session('error') }}
      </div>
    @endif

    @if(session()->has('success'))
      <div class="flex items-center p-4 mt-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-900 dark:text-green-400 dark:border-green-800" role="alert">
        {{ session('success') }}
      </div>
    @endif

    <section class="mb-10 mt-6">
      <div class="flex items-center justify-between mb-4">
  <h2 class="text-2xl font-semibold text-gray-800">My Sponsorships</h2>
  <a href="{{ route('addsponsor') }}">
    <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg shadow">
      + Add Sponsor
    </button>
  </a>
</div>


      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($sponsors as $sponsor)
          <div class="bg-white shadow-md rounded-lg border-t-4 {{ $sponsor->pivot->is_active ? 'border-green-400' : 'border-gray-400' }} p-5">
            
            <p class="text-sm font-medium text-gray-500 uppercase">Kelas</p>
            <p class="text-xl font-semibold text-gray-800">{{ $sponsor->title }}</p>

            <ul class="mt-6 space-y-3">
              <li class="flex items-center text-gray-600">
                <svg class="w-5 h-5 mr-2 fill-current {{ $sponsor->pivot->is_active ? 'text-green-400' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM371.8 211.8l-128 128C238.3 345.3 231.2 348 224 348s-14.34-2.719-19.81-8.188l-64-64c-10.91-10.94-10.91-28.69 0-39.63c10.94-10.94 28.69-10.94 39.63 0L224 280.4l108.2-108.2c10.94-10.94 28.69-10.94 39.63 0C382.7 183.1 382.7 200.9 371.8 211.8z"/>
                </svg>
                {{ $sponsor->schedule }}
              </li>

              <li class="flex items-center text-gray-600">
                <svg class="w-5 h-5 mr-2 fill-current {{ $sponsor->pivot->is_active ? 'text-green-400' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM371.8 211.8l-128 128C238.3 345.3 231.2 348 224 348s-14.34-2.719-19.81-8.188l-64-64c-10.91-10.94-10.91-28.69 0-39.63c10.94-10.94 28.69-10.94 39.63 0L224 280.4l108.2-108.2c10.94-10.94 28.69-10.94 39.63 0C382.7 183.1 382.7 200.9 371.8 211.8z"/>
                </svg>
                {{ $sponsor->pivot->is_active ? 'Active' : 'Inactive' }}
              </li>

              <li class="flex items-center">
                <span class="{{ $sponsor->pivot->is_completed ? 'text-green-500 font-semibold' : 'text-gray-400' }}">
                  {{ $sponsor->pivot->is_completed ? 'Completed' : 'Not Complete' }}
                </span>
              </li>
            </ul>

            <div class="mt-6">
              <a href="{{ $sponsor->link_payment }}">
                <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-lg font-semibold transition duration-300">
                  Payment Now
                </button>
              </a>
            </div>
          </div>
        @empty
          <p class="text-gray-500">No sponsors found.</p>
        @endforelse
      </div>
    </section>

  </div>
</div>
@endsection

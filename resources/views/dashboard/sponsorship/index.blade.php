@extends('dashboard.layouts.main')

@section('container')

<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Daftar Sponsor (Company)</h1>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($companies as $company)
        <div class="bg-white shadow-md rounded-2xl p-4 flex flex-col items-center text-center">
            <img src="{{ $company->profile_photo_url ?? asset('default-avatar.png') }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover mb-4">
            <h2 class="text-lg font-semibold">{{ $company->name }}</h2>
            <p class="text-gray-600">{{ $company->email }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection
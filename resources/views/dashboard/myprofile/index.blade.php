@extends('dashboard.layouts.main')
@php use Illuminate\Support\Str; @endphp
@section('container')

<div class="p-10 sm:ml-64">
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-3xl mx-auto mt-20">
        
        <div class="flex justify-between flex-wrap items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        </div>

        @if(session()->has('success'))
        <div class="flex items-center p-4 mt-4 mb-6 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-900 dark:text-green-400 dark:border-green-800" role="alert">
            <svg class="w-5 h-5 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            {{ session('success') }}
            <button type="button" class="ml-auto bg-transparent border-0 text-green-800 hover:text-green-900" data-bs-dismiss="alert" aria-label="Close">&times;</button>
        </div>
        @endif

        <div class="flex justify-center mb-8 mt-8">
            @php $avatar = $user->avatar ?? null; @endphp

            @if($avatar && Str::startsWith($avatar, 'http'))
                <img class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" src="{{ $avatar }}" alt="Google Profile Photo">
            @elseif(!empty($avatar))
                <img class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" src="{{ asset('storage/' . $avatar) }}" alt="Profile Photo">
            @else
                <img class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" src="{{ asset('default-avatar.png') }}" alt="Default Profile Photo">
            @endif
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 max-w-3xl mx-auto">
            <div class="px-6 py-6">
                <dl class="divide-y divide-gray-200">
                    <div class="py-4 grid grid-cols-3 gap-6">
                        <dt class="text-base font-semibold text-gray-600">Full name</dt>
                        <dd class="text-base text-gray-900 col-span-2">{{ $user->name }}</dd>
                    </div>
                    <div class="py-4 grid grid-cols-3 gap-6">
                        <dt class="text-base font-semibold text-gray-600">Username</dt>
                        <dd class="text-base text-gray-900 col-span-2">{{ $user->username }}</dd>
                    </div>
                    <div class="py-4 grid grid-cols-3 gap-6">
                        <dt class="text-base font-semibold text-gray-600">Email</dt>
                        <dd class="text-base text-gray-900 col-span-2">{{ $user->email }}</dd>
                    </div>
                    <div class="py-4 grid grid-cols-3 gap-6">
                        <dt class="text-base font-semibold text-gray-600">Course Taken</dt>
                        <dd class="text-base text-gray-900 col-span-2">
                            @forelse ($user->sponsors as $sponsor)
                                {{ $sponsor->title }}@if(!$loop->last), @endif
                            @empty
                                No sponsors taken yet.
                            @endforelse
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <a href="/dashboard/editprofile">
            <button type="button" class="block w-full bg-orange-500 mt-8 py-3 rounded-2xl text-white font-semibold hover:bg-orange-600 transition duration-300">
                Edit Profile
            </button>
        </a>
    </div>
</div>

@endsection

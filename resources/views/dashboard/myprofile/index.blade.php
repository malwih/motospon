@extends('dashboard.layouts.main')
@php use Illuminate\Support\Str; @endphp
@section('container')

<script src="https://unpkg.com/alpinejs" defer></script>


<div class="p-10 sm:ml-64">
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-3xl mx-auto mt-20">
        
        <div class="flex justify-between flex-wrap items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        </div>

        @if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 3000)" 
        x-show="show" 
        x-transition 
        class="relative p-4 pr-10 mt-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50"
        role="alert"
    >
        {{ session('success') }}
        
        <button 
            @click="show = false" 
            type="button" 
            class="absolute top-1/2 right-3 transform -translate-y-1/2 text-xl text-green-800 hover:text-green-900 focus:outline-none"
            aria-label="Close"
        >
            &times;
        </button>
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

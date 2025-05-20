@extends('dashboard.layouts.main')
@php use Illuminate\Support\Str; @endphp
@section('container')

<div class="p-10 sm:ml-64">
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-3xl mx-auto mt-20">

        <div class="flex justify-between items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
        </div>

        @if(session()->has('success'))
        <div class="flex items-center p-4 mt-4 mb-6 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            {{ session('success') }}
            <button type="button" class="ml-auto text-green-800 hover:text-green-900">&times;</button>
        </div>
        @endif

        <form action="{{ route('editprofile.update') }}" method="POST" enctype="multipart/form-data" class="mt-6">
            @csrf
            @method('PUT')

            {{-- Profile Picture --}}
            <div class="flex justify-center mb-8">
                <div class="relative w-28 h-28 group cursor-pointer">
                    @php $avatar = $user->avatar; @endphp

                    @if($avatar && Str::startsWith($avatar, 'http'))
                        <img src="{{ $avatar }}" class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" alt="Google Photo">
                    @elseif($avatar)
                        <img src="{{ asset('storage/' . $avatar) }}" class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" alt="Profile Photo">
                    @else
                        <img src="{{ asset('img/user.png') }}" class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" alt="Default Photo">
                    @endif

                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center text-white font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                        Ubah
                    </div>
                    <input type="file" name="avatar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 rounded-full cursor-pointer">
                </div>
            </div>
            @error('avatar')
                <p class="text-sm text-red-600 text-center mt-1">{{ $message }}</p>
            @enderror

            {{-- Form Fields --}}
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="px-6 py-6">
                    <dl class="divide-y divide-gray-200">

                        @foreach (['name' => 'Full Name', 'username' => 'Username', 'email' => 'Email'] as $field => $label)
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">{{ $label }}</dt>
                            <dd class="col-span-2">
                                <input type="{{ $field === 'email' ? 'email' : 'text' }}"
                                       name="{{ $field }}"
                                       id="{{ $field }}"
                                       value="{{ old($field, $user->$field) }}"
                                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error($field) border-red-500 @enderror"
                                       required>
                                @error($field)
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>
                        @endforeach

                        {{-- New Password --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">New Password</dt>
                            <dd class="col-span-2">
                                <input type="password" name="password" id="password"
                                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('password') border-red-500 @enderror"
                                       placeholder="(Optional)">
                                @error('password')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">Confirm Password</dt>
                            <dd class="col-span-2">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500"
                                       placeholder="(Optional)">
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>

            {{-- Buttons --}}
            <button type="submit"
                class="block w-full bg-orange-500 mt-6 py-3 rounded-2xl text-white font-semibold hover:bg-orange-600 transition duration-300">
                Update Profile
            </button>

            <a href="/dashboard/myprofile"
                class="block w-full mt-4 py-3 rounded-2xl text-center bg-blue-500 text-white font-semibold hover:bg-blue-700 transition duration-300">
                Back
            </a>
        </form>
    </div>
</div>

@endsection

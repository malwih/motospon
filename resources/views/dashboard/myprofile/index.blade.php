@extends('dashboard.layouts.main')

@section('title', 'My Profile - Motospon')

{{-- 
    Menggunakan class Str untuk manipulasi string
    - Digunakan untuk mengecek format URL gambar profil
--}}
@php use Illuminate\Support\Str; @endphp

{{-- ============================================================ --}}
{{-- SECTION KONTEN UTAMA --}}
{{-- ============================================================ --}}
@section('container')
{{-- 
    Memuat library AlpineJS untuk interaktivitas
    - Digunakan untuk komponen interaktif seperti notifikasi
--}}
<script src="https://unpkg.com/alpinejs" defer></script>

{{-- ============================================================ --}}
{{-- KONTEN UTAMA --}}
{{-- ============================================================ --}}
{{-- 
    Container utama dengan padding dan margin
    - Padding responsif untuk mobile dan desktop
    - Margin kiri menyesuaikan lebar sidebar
--}}
<div class="w-full p-10 sm:ml-64">
    {{-- 
        Card utama untuk menampilkan profil pengguna
        - Border dan shadow untuk efek ketinggian
        - Lebar maksimum untuk tampilan yang rapi
    --}}
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-3xl mx-auto mt-20">
        {{-- 
            Header card dengan judul halaman
            - Border bawah untuk pemisah visual
        --}}
        <div class="flex justify-between flex-wrap items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        </div>

        {{-- ============================================================ --}}
        {{-- NOTIFIKASI --}}
        {{-- ============================================================ --}}
        {{-- 
            Notifikasi sukses menggunakan AlpineJS
            - Muncul ketika ada session 'success'
            - Menghilang otomatis setelah 3 detik
            - Dapat ditutup manual oleh pengguna
        --}}
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
            
            {{-- Tombol untuk menutup notifikasi --}}
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

        {{-- ============================================================ --}}
        {{-- FOTO PROFIL --}}
        {{-- ============================================================ --}}
        {{-- 
            Section untuk menampilkan foto profil pengguna
            - Prioritas tampilan: URL eksternal > local storage > default
            - Border oranye untuk highlight visual
            - Ukuran dan bentuk konsisten
        --}}
        <div class="flex justify-center mb-8 mt-8">
            @php $avatar = $user->avatar ?? null; @endphp

            {{-- Cek sumber avatar dan tampilkan yang sesuai --}}
            @if($avatar && Str::startsWith($avatar, 'http'))
                {{-- Tampilkan gambar dari URL eksternal (Google) --}}
                <img class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" 
                     src="{{ $avatar }}" 
                     alt="Google Profile Photo">
            @elseif(!empty($avatar))
                {{-- Tampilkan gambar dari local storage --}}
                <img class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" 
                     src="{{ asset('storage/' . $avatar) }}" 
                     alt="Profile Photo">
            @else
                {{-- Tampilkan gambar default jika tidak ada avatar --}}
                <img class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" 
                     src="{{ asset('storage/default-avatar.png') }}" 
                     alt="Default Profile Photo">
            @endif
        </div>

        {{-- ============================================================ --}}
        {{-- INFORMASI PROFIL --}}
        {{-- ============================================================ --}}
        {{-- 
            Container untuk menampilkan detail informasi profil
            - Menggunakan grid untuk tata letak yang rapi
            - Garis pemisah antar item
        --}}
        <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 max-w-3xl mx-auto">
            <div class="px-6 py-6">
                <dl class="divide-y divide-gray-200">
                    {{-- Nama Lengkap --}}
                    <div class="py-4 grid grid-cols-3 gap-6">
                        <dt class="text-base font-semibold text-gray-600">Full name</dt>
                        <dd class="text-base text-gray-900 col-span-2">{{ $user->name }}</dd>
                    </div>
                    
                    {{-- Username --}}
                    <div class="py-4 grid grid-cols-3 gap-6">
                        <dt class="text-base font-semibold text-gray-600">Username</dt>
                        <dd class="text-base text-gray-900 col-span-2">{{ $user->username }}</dd>
                    </div>
                    
                    {{-- Email --}}
                    <div class="py-4 grid grid-cols-3 gap-6">
                        <dt class="text-base font-semibold text-gray-600">Email</dt>
                        <dd class="text-base text-gray-900 col-span-2">{{ $user->email }}</dd>
                    </div>
                    
                    {{-- Nomor WhatsApp --}}
                    <div class="py-4 grid grid-cols-3 gap-6">
                        <dt class="text-base font-semibold text-gray-600">WhatsApp</dt>
                        <dd class="text-base text-gray-900 col-span-2">
                            @if($user->whatsapp_number)
                                {{ $user->whatsapp_number }}
                            @else
                                <span class="text-yellow-600">Not yet set</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TOMBOL AKSI --}}
        {{-- ============================================================ --}}
        {{-- 
            Tombol untuk menuju halaman edit profil
            - Warna oranye yang konsisten dengan tema
            - Efek hover untuk interaktivitas
            - Lebar penuh pada tampilan mobile
        --}}
        <a href="{{ route('profile.edit') }}" class="block mt-8">
            <button type="button" class="w-full bg-orange-500 py-3 rounded-2xl text-white font-semibold hover:bg-orange-600 transition duration-300">
                Edit Profile
            </button>
        </a>
    </div>
</div>
@endsection

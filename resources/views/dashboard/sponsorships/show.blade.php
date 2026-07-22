@extends('dashboard.layouts.main')

@section('title', 'Preview Sponsorship - Motospon')

@section('container')

{{-- ============================================================ --}}
{{-- KONTEN UTAMA --}}
{{-- ============================================================ --}}
{{-- 
    Kontainer utama dengan padding dan margin
    - Padding responsif untuk mobile dan desktop
    - Margin kiri otomatis untuk sidebar yang bisa disembunyikan
--}}
<div class="w-full p-10 sm:ml-80">
    {{-- 
        Card untuk menampilkan detail sponsor
        - Shadow dan border untuk efek kedalaman
        - Margin top 20 untuk jarak dari navbar
        - Lebar otomatis dengan margin horizontal otomatis
    --}}
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white mx-auto mt-20">
        {{-- 
            Header dengan judul dan tombol aksi
            - Menggunakan flexbox untuk tata letak yang rapi
            - Border bottom sebagai pemisah visual
            - Padding bottom untuk jarak yang cukup
        --}}
        <div class="flex justify-between items-center pb-4 border-b border-gray-300">
            {{-- Judul halaman --}}
            <h1 class="text-3xl font-bold text-gray-900">Preview Sponsorship</h1>

            {{-- 
                Container untuk tombol aksi
                - Menggunakan flexbox dengan jarak antar elemen
                - Spasi konsisten antar tombol
            --}}
            <div class="flex items-center space-x-2">
                {{-- Tombol kembali ke daftar sponsor --}}
                <a href="/dashboard/sponsorships" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg flex items-center space-x-1 transition duration-300">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                    <span>Back</span>
                </a>
                {{-- Tombol untuk mengedit sponsor --}}
                <a href="/dashboard/sponsorships/{{ $sponsorship->slug }}/edit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg flex items-center space-x-1 transition duration-300">
                    <i data-feather="edit" class="w-4 h-4"></i>
                    <span>Edit</span>
                </a>
                {{-- Form untuk menghapus sponsor --}}
                <form action="/dashboard/sponsorships/{{ $sponsorship->slug }}" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sponsorship ini?')" class="inline">
                    @method('delete') {{-- Method Spoofing untuk delete --}}
                    @csrf {{-- CSRF Token untuk keamanan --}}
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg flex items-center space-x-1 transition duration-300">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                        <span>Delete</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Judul sponsor --}}
        <h2 class="text-2xl font-semibold text-gray-800 mt-6 mb-4">{{ $sponsorship->title }}</h2>

        {{-- 
            Menampilkan gambar sponsor jika ada
            - Memeriksa apakah ada gambar yang diunggah
            - Menggunakan container dengan ukuran tetap
        --}}
        @if($sponsorship->image)
        <div class="mb-6 rounded-lg shadow-sm overflow-hidden bg-white">
            {{-- 
                Container untuk gambar dengan ukuran tetap
                - Tinggi 24rem (h-96) untuk konsistensi
                - Flexbox untuk penempatan tengah
            --}}
            <div class="flex items-center justify-center w-full h-96">
                <div class="relative w-full h-full">
                    {{-- 
                        Tag gambar dengan lazy loading dan efek fade in
                        - Object-contain untuk mempertahankan aspek rasio
                        - Efek transisi untuk loading yang halus
                        - Lazy loading untuk performa yang lebih baik
                    --}}
                    <img 
                        src="{{ asset('storage/' . $sponsorship->image) }}" 
                        alt="{{ $sponsorship->title }}" 
                        class="absolute inset-0 w-full h-full object-contain m-auto p-4"
                        style="max-width: 100%; max-height: 100%; object-fit: contain;"
                        onload="this.style.opacity=1"
                        style="opacity:0; transition:opacity 0.3s"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
        @endif

        {{-- 
            Konten artikel sponsor
            - Menggunakan class prose untuk styling otomatis
            - Max-w-none untuk lebar penuh
            - Warna teks abu-abu gelap untuk keterbacaan
        --}}
        <article class="prose prose-lg max-w-none text-gray-800">
            {{-- 
                Menampilkan konten body dengan format HTML
                - Menggunakan {!! !!} untuk menampilkan HTML mentah
                - Konten sudah di-sanitize di controller
            --}}
            {!! $sponsorship->body !!}
        </article>

    </div>
</div>

{{-- ============================================================ --}}
{{-- SCRIPT UNTUK INTERAKTIVITAS --}}
{{-- ============================================================ --}}
{{-- 
    Inisialisasi Feather Icons
    - Mengganti elemen dengan atribut data-feather dengan ikon SVG
    - Di-load setelah DOM selesai dimuat
--}}
<script>
    // Mengganti elemen dengan atribut data-feather dengan ikon yang sesuai
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>

{{-- Penutup section container --}}
@endsection

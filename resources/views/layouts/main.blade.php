<!doctype html>
<html lang="en">

<head>
    {{-- ============================================================ --}}
    {{-- META TAGS & ASSETS --}}
    {{-- ============================================================ --}}
    {{--
        Konfigurasi dasar halaman dan pemuatan aset
        - Encoding karakter UTF-8
        - Viewport untuk tampilan responsif
        - CSS eksternal dan internal
        - Judul halaman dinamis
    --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- CSS Eksternal --}}
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.15.1/css/pro.min.css" />
    
    {{-- CSS Utama melalui Vite --}}
    @vite('resources/css/app.css')

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('storage/img/logo.png') }}">
    
    {{-- Judul Halaman Dinamis --}}
    <title>Motospon | {{ $title }}</title>
</head>

<body>
    {{-- ============================================================ --}}
    {{-- STRUKTUR UTAMA LAYOUT --}}
    {{-- ============================================================ --}}
    {{--
        Struktur utama halaman
        - Navbar di bagian atas
        - Konten utama di tengah
        - Footer di bagian bawah
    --}}

    {{-- ============================================================ --}}
    {{-- NAVBAR --}}
    {{-- ============================================================ --}}
    @include('partials.navbar')

    {{-- ============================================================ --}}
    {{-- KONTEN UTAMA --}}
    {{-- ============================================================ --}}
    {{--
        Container untuk konten utama
        - Lebar penuh dengan margin otomatis
        - Menggunakan yield untuk konten dinamis
    --}}
    <div class="mx-auto w-full">
        @yield('container')
    </div>

    {{-- ============================================================ --}}
    {{-- SCRIPT DAN FOOTER --}}
    {{-- ============================================================ --}}
    {{--
        Script dan komponen footer
        - Flowbite untuk komponen UI interaktif
        - File JavaScript utama melalui Vite
        - Stack untuk script tambahan
        - Komponen footer
    --}}
    <script src="https://unpkg.com/flowbite@1.5.1/dist/flowbite.js"></script>
    @vite(['resources/js/app.js'])
    @stack('scripts')
    @include('partials.footer')
</body>
</html>
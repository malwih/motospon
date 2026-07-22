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
        - CSS utama melalui Vite
        - Feather Icons untuk ikon
        - Trix Editor untuk input tebal kaya
    --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Motospon')</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('storage/img/logo.png') }}">
    
    {{-- Memuat CSS utama --}}
    @vite('resources/css/app.css')
    
    {{-- File CSS tambahan --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    {{-- Feather Icons --}}
    <script src="https://unpkg.com/feather-icons"></script>
    
    {{-- Trix Editor untuk input teks kaya --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    
    {{-- ============================================================ --}}
    {{-- STYLE KUSTOM --}}
    {{-- ============================================================ --}}
    <style>
        /* 
            Menyembunyikan tombol upload file di Trix Editor
            - Menonaktifkan fitur upload file bawaan
            - Digunakan untuk mencegah unggahan file yang tidak diinginkan
        */
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }
        
        /* 
            Style untuk menu aktif di sidebar
            - Warna latar oranye (#f97316)
            - Teks putih untuk kontras
        */
        body[data-dashboard-active="true"] .dashboard-menu-item {
            background-color: #f97316;
            color: white;
        }
        
        /* 
            Style untuk ikon di menu aktif
            - Memastikan ikon berwarna putih saat menu aktif
            - Meningkatkan keterlihatan
        */
        body[data-dashboard-active="true"] .dashboard-menu-item svg {
            color: white;
        }
    </style>
</head>

<body>
    {{-- ============================================================ --}}
    {{-- STRUKTUR UTAMA LAYOUT --}}
    {{-- ============================================================ --}}
    {{--
        Struktur utama halaman dashboard
        - Header: Berisi navigasi utama
        - Sidebar: Menu navigasi samping
        - Konten Utama: Area konten dinamis
    --}}
    
    {{-- Memasukkan komponen header --}}
    @include('dashboard.layouts.header')

    {{-- ============================================================ --}}
    {{-- KONTAINER UTAMA --}}
    {{-- ============================================================ --}}
    <div class="container">
        <div class="row">
            {{-- ================================================== --}}
            {{-- SIDEBAR --}}
            {{-- ================================================== --}}
            @include('dashboard.layouts.sidebar')
            
            {{-- ================================================== --}}
            {{-- KONTEN UTAMA --}}
            {{-- ================================================== --}}
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                {{-- ========================================== --}}
                {{-- NOTIFIKASI SUKSES --}}
                {{-- ========================================== --}}
                {{-- 
                    Menampilkan pesan sukses
                    - Muncul setelah operasi berhasil (create, update, delete)
                    - Warna hijau untuk indikasi sukses
                --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                {{-- ========================================== --}}
                {{-- NOTIFIKASI ERROR --}}
                {{-- ========================================== --}}
                {{-- 
                    Menampilkan pesan error validasi
                    - Muncul ketika ada kesalahan input form
                    - Warna merah untuk indikasi error
                --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- ========================================== --}}
                {{-- KONTEN DINAMIS --}}
                {{-- ========================================== --}}
                {{-- 
                    Area konten dinamis
                    - Diisi oleh view yang meng-extend layout ini
                    - Menggunakan @yield('container')
                --}}
                @yield('container')
            </main>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SCRIPT EKSTERNAL --}}
    {{-- ============================================================ --}}
    {{-- 
        Inisialisasi komponen JavaScript
        - Feather Icons untuk mengganti ikon
        - Flowbite untuk komponen UI interaktif
    --}}
    <script>
        // Inisialisasi Feather Icons
        // Mengganti elemen dengan atribut data-feather dengan ikon yang sesuai
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
    
    {{-- Flowbite JS untuk komponen UI interaktif --}}
    <script src="https://unpkg.com/flowbite@1.5.1/dist/flowbite.js"></script>
    
    {{-- Stack untuk script tambahan dari view --}}
    @stack('scripts')
</body>

</html>
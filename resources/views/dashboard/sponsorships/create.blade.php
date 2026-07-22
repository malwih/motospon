@extends('dashboard.layouts.main')

@section('title', 'Create New Sponsorship - Motospon')

@section('container')

{{-- ============================================================ --}}
{{-- LIBRARY YANG DIPERLUKAN --}}
{{-- ============================================================ --}}
{{-- 
    Library yang digunakan:
    1. TOM Select - untuk komponen dropdown yang lebih interaktif
    2. SweetAlert2 - untuk tampilan notifikasi yang lebih baik
--}}

{{-- TOM Select CDN --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
{{-- SweetAlert2 CDN --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        Card untuk form pembuatan sponsorship
        - Shadow dan border untuk efek kedalaman
        - Lebar maksimum 5xl untuk tampilan yang optimal
        - Margin top 20 untuk jarak dari navbar
    --}}
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-5xl mx-auto mt-20">
        {{-- 
            Header card dengan judul halaman
            - Border bottom sebagai pemisah visual
            - Margin bottom 6 untuk jarak dengan konten form
        --}}
        <div class="pb-4 border-b border-gray-300 mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Create New Sponsorship</h1>
        </div>

        {{-- 
            Form untuk membuat sponsorship baru
            - Menggunakan method POST untuk mengirim data
            - enctype="multipart/form-data" untuk mengunggah file
            - CSRF token untuk keamanan form
        --}}
        <form id="submitForm" method="post" action="/dashboard/sponsorships" class="mt-6" enctype="multipart/form-data">
            @csrf {{-- CSRF token untuk keamanan --}}

            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="px-6 py-6">
                    {{-- Daftar field menggunakan description list --}}
                    <dl class="divide-y divide-gray-200">

                        {{-- 
                            Field: Title
                            - Input teks untuk title
                            - Validasi sisi klien dengan required
                            - Menampilkan pesan error jika validasi gagal
                        --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">Title</dt>
                            <dd class="col-span-2">
                                <input type="text" 
                                       name="title" 
                                       id="title"
                                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('title') border-red-500 @enderror"
                                       value="{{ old('title') }}" 
                                       placeholder="Yamaha Indonesia" 
                                       required 
                                       autofocus>
                                @error('title')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>

                        {{-- Field: Slug (tersembunyi, di-generate otomatis) --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center hidden">
                            <dt class="text-sm font-medium text-gray-600">Slug</dt>
                            <dd class="col-span-2">
                                <input type="text" name="slug" id="slug"
                                    class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('slug') border-red-500 @enderror"
                                    value="{{ old('slug') }}" required>
                                @error('slug')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>

                        {{-- 
                            Field: Kategori Sponsorship
                            - Menggunakan Alpine.js untuk dropdown interaktif
                            - Memungkinkan pemilihan multiple kategori
                            - Tampilan yang bersih dan mudah digunakan
                        --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-start">
                            <dt class="text-sm font-medium text-gray-600 pt-2">Category</dt>
                            <dd class="col-span-2" x-data="{ open: false, selected: [] }">
                                <div class="relative">
                                    {{-- 
                                        Tombol toggle untuk dropdown kategori
                                        - Menampilkan kategori yang dipilih
                                        - Ikon panah yang bisa berputar
                                    --}}
                                    <button @click="open = !open" type="button"
                                        class="w-full flex justify-between items-center border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white text-left text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        <span x-text="selected.length > 0 ? selected.join(', ') : 'Choose Category'"></span>
                                        <svg :class="{ 'rotate-180': open }"
                                            class="h-5 w-5 text-gray-500 transition-transform duration-200" 
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" 
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.177l3.71-3.946a.75.75 0 011.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>

            <div x-show="open" @click.away="open = false"
                class="absolute z-10 mt-2 w-full rounded-md bg-white shadow-lg border border-gray-300 p-3 space-y-2 max-h-60 overflow-y-auto">
                @php
                    $categories = ['Spareparts', 'Apparels', 'Electric', 'Oil', 'Other'];
                    $oldCategory = old('category', []);
                @endphp
                @foreach ($categories as $cat)
                    <label class="flex items-center space-x-2 text-sm text-gray-700">
                        <input type="checkbox" name="category[]" value="{{ $cat }}"
                            x-model="selected"
                            class="text-orange-500 border-gray-300 rounded shadow-sm focus:ring-orange-500"
                            {{ is_array($oldCategory) && in_array($cat, $oldCategory) ? 'checked' : '' }}>
                        <span>{{ $cat }}</span>
                    </label>
                @endforeach
            </div>

            @error('category')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </dd>
</div>





                        {{-- Field: Jenis Event --}}
<div class="py-4 grid grid-cols-3 gap-4 items-start">
    <dt class="text-sm font-medium text-gray-600 pt-2">Event</dt>
    <dd class="col-span-2" x-data="{ open: false, selectedEvents: [] }">
        <div class="relative">
            <button @click="open = !open" type="button"
                class="w-full flex justify-between items-center border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white text-left text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <span x-text="selectedEvents.length > 0 ? selectedEvents.join(', ') : 'Choose Event'"></span>
                <svg :class="{ 'rotate-180': open }"
                    class="h-5 w-5 text-gray-500 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.177l3.71-3.946a.75.75 0 011.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="open" @click.away="open = false"
                class="absolute z-10 mt-2 w-full rounded-md bg-white shadow-lg border border-gray-300 p-3 space-y-2 max-h-60 overflow-y-auto">
                @php
                    $events = ['Sunmori', 'Nightride', 'Contest', 'Anniversary', 'Other'];
                    $oldEvents = old('event', []);
                @endphp
                @foreach ($events as $evt)
                    <label class="flex items-center space-x-2 text-sm text-gray-700">
                        <input type="checkbox" name="event[]" value="{{ $evt }}"
                            x-model="selectedEvents"
                            class="text-orange-500 border-gray-300 rounded shadow-sm focus:ring-orange-500"
                            {{ is_array($oldEvents) && in_array($evt, $oldEvents) ? 'checked' : '' }}>
                        <span>{{ $evt }}</span>
                    </label>
                @endforeach
            </div>

            @error('event')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </dd>
</div>



                        {{-- Field: Upload Gambar --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">Upload Image</dt>
                            <dd class="col-span-2">
                                <img class="img-preview mb-3 rounded-lg border border-gray-300 max-h-56 hidden" alt="Image Preview">
                                <input type="file" id="image" name="image" accept="image/*"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none @error('image') border-red-500 @enderror"
                                    onchange="previewImage()">
                                @error('image')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>

                        {{-- Field: Deskripsi Sponsorship --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-start">
                            <dt class="text-sm font-medium text-gray-600 pt-2">Description</dt>
                            <dd class="col-span-2">
                                @error('body')
                                <div class="text-red-600 text-sm mb-2">{{ $message }}</div>
                                @enderror
                                <input id="body" type="hidden" name="body" value="{{ old('body') }}">
                                <trix-editor input="body" class="trix-content rounded-md border border-gray-300 shadow-sm" placeholder="PT Yamaha Indonesia Motor Manufacturing adalah sebuah perusahaan yang memproduksi sepeda motor..."></trix-editor>
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <button type="submit"
                class="block w-full bg-orange-500 mt-6 py-3 rounded-2xl text-white font-semibold hover:bg-orange-600 transition duration-300">
                Create Sponsorship
            </button>

            {{-- Tombol Kembali --}}
            <a href="/dashboard/sponsorships" 
                class="block w-full mt-4 py-3 rounded-2xl text-center bg-blue-500 text-white font-semibold hover:bg-blue-700 transition duration-300">
                Back
            </a>

        </form>
    </div>
</div>

{{-- ============================================================ --}}
{{-- SCRIPT UNTUK INTERAKTIVITAS --}}
{{-- ============================================================ --}}
<script>
    // Auto-generate slug dari judul
    // Mendapatkan elemen input judul dan slug
    const title = document.querySelector('#title');
    const slug = document.querySelector('#slug');

    // Event listener untuk mengupdate slug saat judul diubah
    title.addEventListener('change', function () {
        fetch('/dashboard/sponsorships/checkSlug?title=' + encodeURIComponent(title.value))
            .then(response => response.json())
            .then(data => slug.value = data.slug)
    });

    // Mencegah upload file melalui trix editor
    document.addEventListener('trix-file-accept', function (e) {
        e.preventDefault();
    });

    /**
     * Menampilkan preview gambar yang diupload
     */
    function previewImage() {
        const image = document.querySelector('#image');
        const imgPreview = document.querySelector('.img-preview');

        // Tampilkan container preview
        imgPreview.style.display = 'block';
        imgPreview.classList.remove('hidden');

        // Baca file gambar yang dipilih
        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        // Set source gambar preview saat selesai dibaca
        oFReader.onload = function (oFREvent) {
            imgPreview.src = oFREvent.target.result;
        };
    }


    // Konfirmasi sebelum submit form
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('submitForm');

        // Event listener untuk form submission
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah form submit default

            // Tampilkan konfirmasi SweetAlert2
            Swal.fire({
                title: 'Create Sponsorship?',
                text: "Please ensure all data is correct before creating.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                didOpen: () => {
                    // Styling tombol konfirmasi
                    Swal.getConfirmButton().style.background = '#16a34a';
                    Swal.getCancelButton().style.background = '#d33';
                    Swal.getConfirmButton().style.color = '#fff';
                    Swal.getCancelButton().style.color = '#fff';
                }
            }).then((result) => {
                // Jika user mengkonfirmasi, submit form
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
</script>


<script src="//unpkg.com/alpinejs" defer></script>

@endsection

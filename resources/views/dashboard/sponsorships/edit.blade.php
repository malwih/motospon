@extends('dashboard.layouts.main')

@section('title', 'Edit Sponsorship - Motospon')

@section('container')

{{-- ============================================================ --}}
{{-- LIBRARY YANG DIPERLUKAN --}}
{{-- ============================================================ --}}
{{-- 
    Library yang digunakan:
    1. SweetAlert2 - untuk tampilan notifikasi yang lebih baik
--}}

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
        Card untuk form edit sponsorship
        - Shadow dan border untuk efek kedalaman
        - Lebar maksimum 5xl untuk tampilan yang optimal
        - Margin top 20 untuk jarak dari navbar
    --}}
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-5xl mx-auto mt-20">
        {{-- 
            Header card dengan judul halaman
            - Menggunakan flexbox untuk tata letak yang rapi
            - Border bottom sebagai pemisah visual
        --}}
        <div class="flex justify-between items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Edit Sponsorship</h1>
        </div>

        {{-- 
            Notifikasi sukses
            - Muncul ketika ada data yang berhasil diupdate
            - Menampilkan pesan dari session
            - Bisa ditutup oleh pengguna
        --}}
        @if(session()->has('success')) 
        <div class="flex items-center p-4 mt-4 mb-6 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
            {{-- Ikon centang untuk notifikasi sukses --}}
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            {{ session('success') }}
            {{-- Tombol untuk menutup notifikasi --}}
            <button type="button" class="ml-auto text-green-800 hover:text-green-900">&times;</button>
        </div>
        @endif

        {{-- 
            Form untuk mengupdate sponsorship
            - Menggunakan method PUT untuk update data
            - enctype="multipart/form-data" untuk mengunggah file
            - CSRF token untuk keamanan form
        --}}
        <form id="updateForm" method="post" action="/dashboard/sponsorships/{{ $sponsorship->slug }}" enctype="multipart/form-data" class="mt-6">
            @method('put') {{-- Method Spoofing untuk update --}}
            @csrf {{-- CSRF Token untuk keamanan --}}

            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="px-6 py-6">
                    {{-- Daftar field menggunakan description list --}}
                    <dl class="divide-y divide-gray-200">

                        {{-- 
                            Field: Judul Sponsorship
                            - Input teks untuk judul sponsorship
                            - Nilai default diambil dari data yang sudah ada
                            - Validasi sisi klien dengan required
                        --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">Title</dt>
                            <dd class="col-span-2">
                                <input type="text" 
                                       name="title" 
                                       id="title"
                                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('title') border-red-500 @enderror"
                                       value="{{ old('title', $sponsorship->title) }}" 
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
                                    value="{{ old('slug', $sponsorship->slug) }}" required>
                                @error('slug')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>

                        {{-- 
                            Field: Upload Gambar
                            - Menampilkan gambar yang sudah ada (jika ada)
                            - Memungkinkan penggantian gambar
                            - Preview gambar sebelum diupload
                        --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-center">
                            <dt class="text-sm font-medium text-gray-600">Change Image</dt>
                            <dd class="col-span-2">
                                {{-- Menyimpan path gambar lama untuk dihapus jika diganti --}}
                                <input type="hidden" name="oldImage" value="{{ $sponsorship->image }}">
                                {{-- Menampilkan gambar yang sudah ada --}}
                                @if($sponsorship->image)
                                <img src="{{ asset('storage/' . $sponsorship->image) }}" 
                                     alt="Sponsor Image"
                                     class="img-preview mb-3 rounded-lg border border-gray-300 max-h-56 block">
                                @else
                                <img class="img-preview mb-3 rounded-lg border border-gray-300 max-h-56 hidden" 
                                     alt="Image Preview">
                                @endif
                                {{-- Input file untuk mengunggah gambar baru --}}
                                <input type="file" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none @error('image') border-red-500 @enderror"
                                       onchange="previewImage()">
                                @error('image')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </dd>
                        </div>

                        {{-- Field: Kategori Sponsorship --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-start">
                            <dt class="text-sm font-medium text-gray-600 pt-2">Category</dt>
                            <dd class="col-span-2" x-data="{ open: false, selected: {{ json_encode(old('category', $sponsorship->category ? explode(', ', $sponsorship->category) : [])) }} }">
                                <div class="relative">
                                    <button @click="open = !open" type="button"
                                        class="w-full flex justify-between items-center border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white text-left text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        <span x-text="selected.length > 0 ? selected.join(', ') : 'Choose Category'"></span>
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
                                            $categories = ['Spareparts', 'Apparels', 'Electric', 'Oil', 'Other'];
                                            $oldCategory = old('category', $sponsorship->category ? explode(', ', $sponsorship->category) : []);
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
                            <dd class="col-span-2" x-data="{ open: false, selectedEvents: {{ json_encode(old('event', $sponsorship->event ? explode(', ', $sponsorship->event) : [])) }} }">
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
                                            $oldEvent = old('event', $sponsorship->event ? explode(', ', $sponsorship->event) : []);
                                        @endphp
                                        @foreach ($events as $evt)
                                            <label class="flex items-center space-x-2 text-sm text-gray-700">
                                                <input type="checkbox" name="event[]" value="{{ $evt }}"
                                                    x-model="selectedEvents"
                                                    class="text-orange-500 border-gray-300 rounded shadow-sm focus:ring-orange-500"
                                                    {{ is_array($oldEvent) && in_array($evt, $oldEvent) ? 'checked' : '' }}>
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

                        {{-- Field: Deskripsi Sponsorship --}}
                        <div class="py-4 grid grid-cols-3 gap-4 items-start">
                            <dt class="text-sm font-medium text-gray-600 pt-2">Description</dt>
                            <dd class="col-span-2">
                                @error('body')
                                <div class="text-red-600 text-sm mb-2">{{ $message }}</div>
                                @enderror
                                <input id="body" type="hidden" name="body" value="{{ old('body', $sponsorship->body) }}">
                                <trix-editor input="body" class="trix-content rounded-md border border-gray-300 shadow-sm"></trix-editor>
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>

            {{-- Tombol aksi --}}
            <div class="flex justify-between mt-6 space-x-4">
                {{-- Tombol kembali ke halaman daftar sponsorship --}}
                <a href="/dashboard/sponsorships"
                    class="w-1/2 py-2.5 rounded-2xl text-center bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition duration-300">
                    Back
                </a>
                {{-- Tombol untuk submit form update --}}
                <button type="submit"
                    class="w-1/2 py-2.5 rounded-2xl text-center bg-orange-500 text-white font-semibold hover:bg-orange-700 transition duration-300">
                    Update Sponsorship
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================================ --}}
{{-- SCRIPT UNTUK INTERAKTIVITAS --}}
{{-- ============================================================ --}}
<script>
    /**
     * Menampilkan preview gambar yang dipilih
     * - Membaca file gambar yang dipilih
     * - Menampilkan preview gambar sebelum diupload
     * - Memperbarui tampilan preview jika gambar diganti
     */
    function previewImage() {
        // Ambil elemen input file dan preview gambar
        const image = document.querySelector('#image');
        const imgPreview = document.querySelector('.img-preview');
        
        // Tampilkan preview hanya jika file dipilih
        if (image.files && image.files[0]) {
            // Membuat pembaca file
            const reader = new FileReader();
            
            // Setelah file selesai dibaca
            reader.onload = function(e) {
                // Update source gambar preview
                imgPreview.src = e.target.result;
                // Tampilkan elemen preview
                imgPreview.classList.remove('hidden');
                imgPreview.classList.add('block');
            }
            
            // Baca file sebagai URL data
            reader.readAsDataURL(image.files[0]);
        }
    }
</script>

{{-- ============================================================ --}}
{{-- SCRIPT UNTUK AUTO-GENERATE SLUG --}}
{{-- ============================================================ --}}
<script>
    // Auto-generate slug dari judul
    const title = document.querySelector('#title');
    const slug = document.querySelector('#slug');

    // Jika elemen title dan slug ada di halaman
    if (title && slug) {
        // Event listener untuk mengupdate slug saat judul diubah
        title.addEventListener('change', function() {
            // Panggil endpoint untuk generate slug
            fetch('/dashboard/sponsorships/checkSlug?title=' + encodeURIComponent(title.value))
                .then(response => response.json())
                .then(data => slug.value = data.slug);
        });
    }


    // Konfirmasi sebelum submit form
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('updateForm');

        // Pastikan form ada di halaman
        if (form) {
            // Event listener untuk form submission
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Mencegah form submit default

                // Tampilkan konfirmasi SweetAlert2
                Swal.fire({
                    title: 'Update Sponsorship?',
                    text: "Please ensure all data is correct before updating.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    // Custom styling untuk tombol konfirmasi
                    didOpen: () => {
                        // Styling tombol konfirmasi (hijau)
                        Swal.getConfirmButton().style.background = '#16a34a';
                        // Styling tombol batal (merah)
                        Swal.getCancelButton().style.background = '#d33';
                        // Pastikan teks tombol tetap putih
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
        }
    });
</script>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection

{{-- Menggunakan layout utama dashboard --}}
@extends('dashboard.layouts.main')

@section('title', 'Sponsorship - Motospon')

{{-- Menggunakan helper Str untuk memanipulasi string --}}
@php
    use Illuminate\Support\Str;
@endphp

{{-- Section untuk konten utama --}}
@section('container')
{{-- Mengimpor library yang diperlukan --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Kontainer utama dengan padding dan margin --}}
{{-- ============================================================ --}}
{{-- KONTEN UTAMA --}}
{{-- ============================================================ --}}
<div class="w-full p-10 sm:ml-80">
    {{-- 
        Card utama yang berisi daftar sponsorship
        - Menggunakan shadow dan border untuk memberikan kedalaman
        - Lebar maksimum 7xl untuk tampilan yang optimal
        - Margin top 20 untuk memberi jarak dari navbar
    --}}
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-7xl mx-auto mt-20">
        {{-- 
            Header section dengan judul dan tombol tambah
            - Menggunakan flexbox untuk tata letak yang responsif
            - Border bottom untuk pemisah visual
        --}}
        <div class="flex justify-between flex-wrap items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Sponsorship</h1>
            {{-- Tombol untuk menambah sponsorship baru --}}
            <a href="/dashboard/sponsorships/create">
                <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg shadow">
                    + Create New Sponsorship
                </button>
            </a>
        </div>

        {{-- 
            Notifikasi sukses menggunakan Alpine.js
            - Akan hilang otomatis setelah 3 detik
            - Menggunakan transisi untuk efek halus
            - Posisi relative untuk menempatkan tombol close
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
            {{-- Menampilkan pesan sukses dari session --}}
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

        {{-- 
            Section untuk menampilkan daftar sponsorship dalam bentuk tabel
            - Menggunakan overflow-x-auto untuk memungkinkan scroll horizontal pada mobile
            - Margin top 6 untuk jarak dari header
        --}}
        <section class="mt-6">
            {{-- 
                Container untuk tabel dengan scroll horizontal
                - Berguna untuk tampilan mobile agar tabel bisa discroll
            --}}
            <div class="overflow-x-auto">
                {{-- Tabel daftar sponsorship --}}
                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                    {{-- Header tabel --}}
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Title</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Category</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Event</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    {{-- Body tabel --}}
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Looping data sponsorship --}}
                        @forelse ($sponsorships as $index => $sponsorship)
                        <tr>
                            {{-- Nomor urut --}}
                            <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                            {{-- Judul sponsor --}}
                            <td class="px-6 py-4 text-center">{{ $sponsorship->title }}</td>
                            {{-- Kategori (dibatasi 3 kata) --}}
                            <td class="px-6 py-4 text-center">{{ Str::words(strip_tags($sponsorship->category), 3, '...') }}</td>
                            {{-- Event (dibatasi 3 kata) --}}
                            <td class="px-6 py-4 text-center">{{ Str::words(strip_tags($sponsorship->event), 3, '...') }}</td>
                            {{-- 
                                Kolom untuk tombol aksi
                                - Menggunakan dropdown untuk menghemat ruang
                                - Posisi relatif untuk menu dropdown
                            --}}
                            <td class="px-4 py-3 text-center">
                                {{-- 
                                    Dropdown menu untuk aksi-aksi yang tersedia
                                    - Menggunakan Alpine.js untuk toggle
                                    - Posisi absolute di kanan
                                --}}
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    {{-- 
                                        Tombol toggle dropdown
                                        - Menggunakan ikon setting dan panah
                                        - Efek hover untuk interaktivitas
                                    --}}
                                    <button @click="open = !open"
                                            class="inline-flex items-center px-3 py-2 bg-gray-200 hover:bg-gray-300 border border-gray-400 shadow-sm text-sm font-medium text-gray-700 focus:outline-none">
                                        <svg class="w-5 h-5 text-gray-700 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19.14,12.94a7.92,7.92,0,0,0,.06-1l2.12-1.65a.5.5,0,0,0,.13-.56l-2-3.46a.5.5,0,0,0-.54-.24l-2.49,1a7.75,7.75,0,0,0-1.73-1L14.24,3.1a.5.5,0,0,0-.49-.1L9.79,4.17a.5.5,0,0,0-.3.44l-.3,2.88a7.62,7.62,0,0,0-1.73,1l-2.49-1a.5.5,0,0,0-.54.24l-2,3.46a.5.5,0,0,0,.13.56L4.8,12a8.36,8.36,0,0,0,0,2l-2.12,1.65a.5.5,0,0,0-.13.56l2,3.46a.5.5,0,0,0,.54.24l2.49-1a7.75,7.75,0,0,0,1.73,1l.3,2.88a.5.5,0,0,0,.3.44l3.96,1.17a.5.5,0,0,0,.49-.1l1.53-2.65a7.62,7.62,0,0,0,1.73-1l2.49,1a.5.5,0,0,0,.54-.24l2-3.46a.5.5,0,0,0-.13-.56ZM12,15.5A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/>
                                        </svg>
                                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    {{-- Menu dropdown --}}
                                    <div x-show="open" @click.away="open = false"
                                        class="absolute right-0 z-10 mt-2 w-36 origin-top-right bg-white border border-gray-200 shadow-lg">
                                        {{-- Tombol preview --}}
                                        <a href="/dashboard/sponsorships/{{ $sponsorship->slug }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Preview</a>
                                        {{-- Tombol edit --}}
                                        <a href="/dashboard/sponsorships/{{ $sponsorship->slug }}/edit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                        {{-- Form untuk hapus --}}
                                        <form action="/dashboard/sponsorships/{{ $sponsorship->slug }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        {{-- Tampilan jika data kosong --}}
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                                Sponsorship not found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<script>
/**
 * Konfirmasi sebelum menghapus data sponsorship
 */
document.addEventListener('DOMContentLoaded', function () {
    // Cari semua form dengan class 'delete-form'
    document.querySelectorAll('.delete-form').forEach(form => {
        // Tambahkan event listener untuk submit form
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah form submit default
            
            // Tampilkan konfirmasi menggunakan SweetAlert2
            Swal.fire({
                title: 'Delete Sponsorship?',
                text: "Deleted data cannot be recovered.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                // Custom styling untuk tombol
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
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>
@endsection

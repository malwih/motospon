<?php
$activePage = 'dashboard';
?>

@extends('dashboard.layouts.main')

@section('title', 'Preview Proposal - Motospon')

{{-- ============================================================ --}}
{{-- KONTEN UTAMA --}}
{{-- ============================================================ --}}
{{-- 
    Section utama yang akan diisi dengan konten halaman
    - Menggunakan container untuk tata letak yang konsisten
--}}
@section('container')

{{-- ============================================================ --}}
{{-- LIBRARY EKSTERNAL --}}
{{-- ============================================================ --}}
{{-- 
    Memuat library SweetAlert2 untuk notifikasi yang lebih interaktif
    - Digunakan untuk menampilkan konfirmasi sebelum submit
--}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ============================================================ --}}
{{-- KONTEN UTAMA --}}
{{-- ============================================================ --}}
{{-- 
    Container utama dengan padding dan margin
    - Padding responsif untuk mobile dan desktop
    - Margin kiri menyesuaikan lebar sidebar
--}}
<div class="w-full p-10 sm:ml-80">
    {{-- 
        Card untuk menampilkan preview proposal
        - Shadow dan border untuk efek kedalaman
        - Lebar maksimum 4xl untuk keterbacaan yang optimal
        - Margin top 24 untuk jarak dari navbar
    --}}
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-4xl mx-auto mt-24 font-sans text-gray-800 leading-relaxed">
        {{-- 
            Judul halaman
            - Ukuran teks besar dengan ketebalan semi-bold
            - Margin bottom 8 untuk jarak dengan konten
            - Garis bawah sebagai pemisah visual
        --}}
        <h1 class="text-3xl font-semibold mb-8 border-b pb-4 border-gray-300">Preview Proposal</h1>

        {{-- 
            Konten proposal yang akan ditampilkan
            - Menggunakan format HTML yang dihasilkan dari controller
            - Unescaped untuk menampilkan HTML dengan benar
        --}}
        <div class="prose max-w-none">
            {!! $proposal !!}
        </div>

        {{-- 
            Form untuk submit proposal
            - Menggunakan method POST dengan route proposals.submit
            - ID submitForm digunakan untuk event listener JavaScript
        --}}
        <form id="submitForm" action="{{ route('proposals.submit') }}" method="POST">
            @csrf {{-- Token CSRF untuk keamanan --}}

            {{-- 
                Input tersembunyi untuk data proposal
                - Menyimpan data mentah proposal untuk diproses di server
                - Nilai diambil dari variabel $raw_proposal
            --}}
            <input type="hidden" name="proposal" value="{{ $raw_proposal }}">
            {{-- 
                Input tersembunyi untuk data tambahan
                - Menyimpan ID sponsor dan detail terkait
                - Data ini akan digunakan untuk verifikasi di server
            --}}
            <input type="hidden" name="sponsorship_id" value="{{ $sponsorship_id }}">
            <input type="hidden" name="category" value="{{ $category }}">
            <input type="hidden" name="event" value="{{ $event }}">
            <input type="hidden" name="name_community" value="{{ $name_community }}">
            <input type="hidden" name="name_event" value="{{ $name_event }}">
            <input type="hidden" name="location" value="{{ $location }}">
            <input type="hidden" name="date_event" value="{{ $date_event }}">
            <input type="hidden" name="feedback_benefit" value="{{ $feedback_benefit }}">

            {{-- 
                Input tersembunyi untuk item anggaran
                - Menyimpan data item anggaran dalam bentuk array
                - Setiap item memiliki nama, deskripsi, dan biaya
                - Data di-loop dari array $budget_items
            --}}
            @for($i = 0; $i < count($budget_items); $i++)
                <input type="hidden" name="budget_items[]" value="{{ $budget_items[$i] }}">
                <input type="hidden" name="budget_descriptions[]" value="{{ $budget_descriptions[$i] }}">
                <input type="hidden" name="budget_costs[]" value="{{ $budget_costs[$i] }}">
            @endfor

            {{-- 
                Input tersembunyi untuk jadwal acara (rundown)
                - Menyimpan waktu dan aktivitas dalam bentuk array
                - Data di-loop dari array $rundown_times
                - Setiap waktu memiliki aktivitas terkait
            --}}
            @foreach ($rundown_times as $index => $time)
                <input type="hidden" name="rundown_times[]" value="{{ $time }}">
                <input type="hidden" name="rundown_activities[]" value="{{ $rundown_activities[$index] }}">
            @endforeach

            {{-- 
                Input tersembunyi untuk dokumentasi acara
                - Menyimpan data file yang diunggah
                - Data di-loop dari array $event_documentations
            --}}
            @if(isset($event_documentations) && count($event_documentations) > 0)
                @foreach($event_documentations as $index => $doc)
                    @php
                        // Ensure the file path is correct and remove 'public/' if present
                        $filePath = $doc['file_path'] ?? '';
                        // Remove any 'public/' prefix and ensure no double slashes
                        $filePath = ltrim(str_replace('public/', '', $filePath), '/');
                        // Generate the correct URL with proper slashes
                        $imageUrl = asset('storage/' . $filePath);
                    @endphp
                    <input type="hidden" name="event_documentations[{{ $index }}][file_path]" value="{{ $filePath }}">
                    <input type="hidden" name="event_documentations[{{ $index }}][original_name]" value="{{ $doc['original_name'] ?? 'documentation.jpg' }}">
                    <input type="hidden" name="event_documentations[{{ $index }}][mime_type]" value="{{ $doc['mime_type'] ?? '' }}">
                    <input type="hidden" name="event_documentations[{{ $index }}][size]" value="{{ $doc['size'] ?? 0 }}">
                    <input type="hidden" name="event_documentations[{{ $index }}][image_url]" value="{{ $imageUrl }}">
                @endforeach
            @endif

        </form>

        {{-- ============================================================ --}}
        {{-- TOMBOL AKSI --}}
        {{-- ============================================================ --}}
        {{-- 
            Container untuk tombol aksi
            - Menggunakan flexbox untuk tata letak yang responsif
            - Jarak antar tombol 4 (space-x-4)
            - Margin top 6 untuk jarak dari konten di atas
        --}}
        <div class="flex justify-between mt-6 space-x-4">
            {{-- 
                Tombol kembali ke halaman sebelumnya
                - Menggunakan warna kuning untuk aksi sekunder
                - Transisi halus saat hover
                - Lebar 1/2 container
            --}}
            <a href="{{ url()->previous() }}" 
               class="w-1/2 py-2.5 rounded-2xl text-center bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition duration-300">
                Back
            </a>
            {{-- 
                Tombol submit proposal
                - Menggunakan warna oranye untuk aksi utama
                - Mengarah ke form dengan ID submitForm
                - Transisi halus saat hover
            --}}
            <button type="submit" form="submitForm" 
                    class="w-1/2 py-2.5 rounded-2xl text-center bg-orange-500 text-white font-semibold hover:bg-orange-700 transition duration-300">
                Submit Proposal
            </button>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- SCRIPT UNTUK INTERAKTIVITAS --}}
{{-- ============================================================ --}}
{{-- 
    Script untuk menangani konfirmasi sebelum submit
    - Menggunakan SweetAlert2 untuk UI yang lebih baik
    - Mencegah pengiriman form yang tidak disengaja
--}}
<script>
    /**
     * Menunggu dokumen selesai dimuat sebelum mengeksekusi script
     * - Memastikan semua elemen DOM sudah tersedia
     */
    document.addEventListener('DOMContentLoaded', function () {
        // Dapatkan form submit berdasarkan ID
        const form = document.getElementById('submitForm');

        // Tambahkan event listener untuk form submit
        form.addEventListener('submit', function (e) {
            // Mencegah form submit default
            e.preventDefault();

            // Tampilkan konfirmasi menggunakan SweetAlert2
            Swal.fire({
                title: 'Submit Proposal?',
                text: "Please ensure all data is correct before submitting.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                // Mengatur style tombol konfirmasi
                didOpen: () => {
                    // Warna hijau untuk tombol konfirmasi
                    Swal.getConfirmButton().style.background = '#16a34a';
                    // Warna merah untuk tombol batal
                    Swal.getCancelButton().style.background = '#d33';
                    // Warna teks putih untuk keterbacaan yang lebih baik
                    Swal.getConfirmButton().style.color = '#fff';
                    Swal.getCancelButton().style.color = '#fff';
                }
            }).then((result) => {
                // Jika pengguna mengklik 'Yes', submit form
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

@endsection

@push('scripts')
<script>
    // Add smooth scrolling to top when clicking the back to top button
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.createElement('button');
        backToTopButton.className = 'fixed bottom-8 right-8 p-3 bg-orange-500 text-white rounded-full shadow-lg hover:bg-orange-600 transition-colors duration-200 opacity-0 invisible z-50';
        backToTopButton.innerHTML = `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
        `;
        backToTopButton.title = 'Back to top';
        document.body.appendChild(backToTopButton);

        // Show/hide back to top button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.remove('opacity-100', 'visible');
                backToTopButton.classList.add('opacity-0', 'invisible');
            }
        });

        // Smooth scroll to top when clicked
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>
@endpush

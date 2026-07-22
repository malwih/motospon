@php
    // Menetapkan variabel activePage untuk navigasi aktif
    // Digunakan untuk menyorot menu yang sedang aktif di sidebar
    $activePage = 'dashboard';
@endphp

@extends('dashboard.layouts.main')

@section('title', 'Preview Proposal - Motospon')

{{-- ============================================================ --}}
{{-- LIBRARY YANG DIPERLUKAN --}}
{{-- ============================================================ --}}
{{-- 
    Library JavaScript yang digunakan:
    1. SweetAlert2 - untuk tampilan notifikasi yang lebih baik
--}}
@section('container')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ============================================================ --}}
{{-- KONTEN UTAMA --}}
{{-- ============================================================ --}}
{{-- 
    Kontainer utama halaman preview proposal
    - Padding responsif untuk mobile dan desktop
    - Margin kiri otomatis untuk sidebar yang bisa disembunyikan
--}}
<div class="w-full p-10 sm:ml-80">
    {{-- 
        Card untuk menampilkan preview proposal
        - Shadow dan border untuk efek kedalaman
        - Lebar maksimum 4xl untuk tampilan yang optimal
        - Margin top 24 untuk jarak dari navbar
    --}}
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-4xl mx-auto mt-24 font-sans text-gray-800 leading-relaxed">
        {{-- 
            Header halaman preview
            - Border bottom sebagai pemisah visual
            - Margin bottom 8 untuk jarak dengan konten
        --}}
        <div class="flex justify-between items-center mb-8 border-b pb-4 border-gray-300">
            <h1 class="text-3xl font-semibold">Preview Proposal</h1>
            <div class="flex gap-3">
            <form id="exportPdfForm" action="{{ route('proposals.export-pdf') }}" method="GET" class="inline">
                @csrf
                <input type="hidden" name="sponsorship_id" value="{{ $sponsorship_id }}">
                <input type="hidden" name="name_community" value="{{ $name_community }}">
                <input type="hidden" name="name_event" value="{{ $name_event }}">
                <input type="hidden" name="location" value="{{ $location }}">
                <input type="hidden" name="date_event" value="{{ $date_event }}">
                <input type="hidden" name="feedback_benefit" value="{{ $feedback_benefit }}">
                
                @foreach ($budget_items as $index => $item)
                    <input type="hidden" name="budget_items[]" value="{{ $item }}">
                    <input type="hidden" name="budget_descriptions[]" value="{{ $budget_descriptions[$index] ?? '' }}">
                    <input type="hidden" name="budget_costs[]" value="{{ $budget_costs[$index] ?? '' }}">
                @endforeach
                
                @foreach ($rundown_times as $index => $time)
                    <input type="hidden" name="rundown_times[]" value="{{ $time }}">
                    <input type="hidden" name="rundown_activities[]" value="{{ $rundown_activities[$index] ?? '' }}">
                @endforeach
                
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export PDF
                </button>
            </form>
            </div>
        </div>
        
        <div id="proposal-content" class="prose max-w-none text-gray-900">
            {!! $proposal !!}
        </div>

        {{-- 
            Form untuk submit proposal
            - Menggunakan method POST dengan route 'proposals.submit'
            - CSRF token untuk keamanan form
        --}}
        <form id="submitForm" action="{{ route('proposals.submit') }}" method="POST">
            @csrf {{-- Token CSRF untuk keamanan --}}

            {{-- 
                Input tersembunyi untuk menyimpan data proposal
                - Menyimpan data mentah proposal yang sudah diformat
                - Menyimpan ID sponsor yang dipilih
                - Menyimpan kategori dan event terkait
                - Menyimpan detail komunitas dan acara
            --}}
            <input type="hidden" name="proposal" value="{{ $raw_proposal }}">
            <input type="hidden" name="sponsorship_id" value="{{ $sponsorship_id }}">
            <input type="hidden" name="category" value="{{ $category }}">
            <input type="hidden" name="event" value="{{ $event }}">
            @if(isset($id))
                <input type="hidden" name="id" value="{{ $id }}">
            @endif
            <input type="hidden" name="name_community" value="{{ $name_community }}">
            <input type="hidden" name="name_event" value="{{ $name_event }}">
            <input type="hidden" name="location" value="{{ $location }}">
            <input type="hidden" name="date_event" value="{{ $date_event }}">
            <input type="hidden" name="feedback_benefit" value="{{ $feedback_benefit }}">

            {{-- 
                Input tersembunyi untuk item anggaran
                - Menyimpan daftar item anggaran
                - Menyimpan deskripsi untuk setiap item
                - Menyimpan biaya untuk setiap item
                - Menggunakan array untuk multiple values
            --}}
            @foreach ($budget_items as $index => $item)
                <input type="hidden" name="budget_items[]" value="{{ $item }}">
                <input type="hidden" name="budget_descriptions[]" value="{{ $budget_descriptions[$index] }}">
                <input type="hidden" name="budget_costs[]" value="{{ $budget_costs[$index] }}">
            @endforeach

            {{-- 
                Input tersembunyi untuk jadwal acara (rundown)
                - Menyimpan daftar waktu acara
                - Menyimpan daftar aktivitas untuk setiap waktu
                - Menggunakan array untuk multiple values
            --}}
            @foreach ($rundown_times as $index => $time)
                <input type="hidden" name="rundown_times[]" value="{{ $time }}">
                <input type="hidden" name="rundown_activities[]" value="{{ $rundown_activities[$index] }}">
            @endforeach

            {{-- 
                Input tersembunyi untuk dokumentasi acara
                - Menyimpan data file yang diunggah
                - Data diambil dari database berdasarkan event_id
            --}}
            @php
                // Initialize variables
                $uniqueIndex = 0;
                $processedFiles = [];
                
                // Get event documentations from either variable (for backward compatibility)
                $docs = $event_documentations ?? ($eventDocumentations ?? []);
            @endphp
            
            @if(!empty($docs) && count($docs) > 0)
                @foreach($docs as $doc)
                    @php
                        // Get document data
                        $docData = is_array($doc) ? $doc : (is_object($doc) ? $doc->toArray() : []);
                        
                        // Skip if no file path
                        if (empty($docData['file_path'])) {
                            continue;
                        }
                        
                        // Clean up file path
                        $filePath = ltrim($docData['file_path'], '/');
                        
                        // If the path is a full URL, extract just the filename
                        if (str_starts_with($filePath, 'http')) {
                            $filePath = 'event_documentations/' . ($id ?? 'temp') . '/' . basename(parse_url($filePath, PHP_URL_PATH));
                        }
                        
                        // Ensure the path is in the correct format
                        if (!str_starts_with($filePath, 'event_documentations/')) {
                            $filePath = 'event_documentations/' . ($id ?? 'temp') . '/' . basename($filePath);
                        }
                        
                        // Create a unique key for this file
                        $fileKey = md5($filePath);
                        
                        // Skip if we've already processed this file
                        if (in_array($fileKey, $processedFiles)) {
                            continue;
                        }
                        $processedFiles[] = $fileKey;
                        
                        // Generate the full URL for the image
                        $imageUrl = asset('storage/' . $filePath);
                    @endphp
                    
                    <input type="hidden" name="event_documentations[{{ $uniqueIndex }}][file_path]" value="{{ $filePath }}">
                    <input type="hidden" name="event_documentations[{{ $uniqueIndex }}][original_name]" value="{{ $docData['original_name'] ?? basename($filePath) }}">
                    <input type="hidden" name="event_documentations[{{ $uniqueIndex }}][mime_type]" value="{{ $docData['mime_type'] ?? 'image/jpeg' }}">
                    <input type="hidden" name="event_documentations[{{ $uniqueIndex }}][size]" value="{{ $docData['size'] ?? 0 }}">
                    @if(isset($docData['id']) && !empty($docData['id']))
                        <input type="hidden" name="event_documentations[{{ $uniqueIndex }}][id]" value="{{ $docData['id'] }}">
                    @endif
                    
                    @php $uniqueIndex++; @endphp
                @endforeach
            @endif
        </form>

        {{-- 
            Tombol kembali ke halaman sebelumnya
            - Menggunakan url()->previous() untuk kembali ke halaman sebelumnya
            - Styling dengan warna kuning dan efek hover
            - Transisi halus untuk efek hover
        --}}
        <div class="flex justify-center mt-6">
            <a href="{{ url()->previous() }}" 
               class="w-1/2 py-2.5 rounded-2xl text-center bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition duration-300">
                Back
            </a>
        </div>
    </div>
    
    <!-- Image Preview Modal -->
    <div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
      <div class="relative bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-2">
          <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-[85vh] mx-auto">
        </div>
      </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- SCRIPT UNTUK INTERAKTIVITAS --}}
{{-- ============================================================ --}}
<script>
    // Image Preview Modal Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('imagePreviewModal');
        const modalImg = document.getElementById('modalImage');
        
        // Make all images with class 'previewable' clickable to show in modal
        document.addEventListener('click', function(e) {
            // Check if clicked element is an image with previewable class
            const img = e.target.closest('img.previewable');
            if (img) {
                e.preventDefault();
                modalImg.src = img.src;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        });
        
        // Close modal when clicking outside the image
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    });
</script>
<script>
    /**
     * Menangani pengiriman form dengan konfirmasi
     * - Mencegah pengiriman form default
     * - Menampilkan dialog konfirmasi menggunakan SweetAlert2
     * - Hanya mengirim form jika pengguna mengkonfirmasi
     */
    document.addEventListener('DOMContentLoaded', function () {
        // Dapatkan elemen form
        const form = document.getElementById('submitForm');

        // Tambahkan event listener untuk form submit
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah pengiriman form default

            // Tampilkan dialog konfirmasi
            Swal.fire({
                title: 'Submit Proposal?',
                text: "Please ensure all data is correct before submitting.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                // Kustomisasi tampilan tombol
                didOpen: () => {
                    // Styling tombol konfirmasi (hijau)
                    Swal.getConfirmButton().style.background = '#16a34a';
                    // Styling tombol batal (merah)
                    Swal.getCancelButton().style.background = '#d33';
                    // Pastikan teks tombol berwarna putih
                    Swal.getConfirmButton().style.color = '#fff';
                    Swal.getCancelButton().style.color = '#fff';
                }
            }).then((result) => {
                // Jika user mengklik 'Yes', kirim form
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
        backToTopButton.className = 'fixed bottom-8 right-8 p-3 bg-orange-500 text-white rounded-full shadow-lg hover:bg-orange-600 transition-colors duration-200 opacity-0 invisible';
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

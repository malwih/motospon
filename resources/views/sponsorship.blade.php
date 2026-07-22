@extends('layouts.main')

@section('container')

{{-- 
    Container utama halaman detail
    - Mengatur padding dan background color
    - Memastikan konten dapat diakses dengan baik
--}}
<div class="pt-28 pb-12 bg-gray-50 min-h-screen w-full">
    <div class="w-full max-w-screen-xl mx-auto px-6 sm:px-12">
        {{-- 
            Header informasi sponsorship
            - Menampilkan tanggal posting dan penulis
            - Menampilkan kategori sponsorship
        --}}
        <div class="mb-6">
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <svg class="h-4 w-4 mr-1 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Posted on {{ $sponsorship->created_at->format('M d, Y') }}</span>
                @if($sponsorship->user)
                    <span class="mx-2">•</span>
                    <span>By {{ $sponsorship->user->name }}</span>
                @endif
            </div>
            @if($sponsorship->category)
                <div class="inline-block">
                    <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ ucfirst($sponsorship->category) }}
                    </span>
                </div>
            @endif
        </div>
        {{-- 
            Judul utama sponsorship
            - Menggunakan ukuran teks besar dan tebal
            - Warna gelap untuk kontras yang baik
        --}}
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8">{{ $sponsorship->title }}</h1>

        {{-- 
            Menampilkan gambar utama
            - Jika ada gambar unggahan, tampilkan gambar tersebut
            - Jika tidak ada, gunakan gambar placeholder dari Unsplash
        --}}
        @if($sponsorship->image)
            <div class="mb-6 rounded-lg shadow-sm overflow-hidden bg-white">
                <div class="flex items-center justify-center w-full h-96">
                    <div class="relative w-full h-full">
                        <img 
                            src="{{ asset('storage/' . $sponsorship->image) }}" 
                            alt="{{ $sponsorship->title }}" 
                            class="absolute inset-0 w-full h-full object-contain m-auto p-4"
                            style="max-width: 100%; max-height: 100%; object-fit: contain;"
                            onload="this.style.opacity=1"
                            style="opacity:0; transition:opacity 0.3s"
                        >
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6 rounded-lg shadow-sm overflow-hidden bg-white">
                <div class="flex items-center justify-center w-full h-96 bg-gray-100">
                    <img 
                        src="https://source.unsplash.com/1200x400?{{ $sponsorship->title }}" 
                        alt="{{ $sponsorship->title }}" 
                        class="w-full h-full object-contain p-4"
                        onload="this.style.opacity=1"
                        style="opacity:0; transition:opacity 0.3s"
                    >
                </div>
            </div>
        @endif

        {{-- 
            Konten utama artikel
            - Menggunakan class prose untuk styling otomatis
            - Warna teks abu-abu untuk keterbacaan yang baik
        --}}
        <article class="prose max-w-none text-gray-700 mb-10">
            {!! $sponsorship->body !!}
        </article>

        {{-- 
            Tombol aksi
            - Tombol kembali ke halaman daftar
            - Tombol submit proposal (hanya untuk user yang login dan bukan company)
            - Tombol login untuk user yang belum login
        --}}
        <div class="flex flex-wrap gap-4 mt-8">
            <a href="/sponsorships"
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
               </svg>
               Back to Sponsorship
            </a>

            @auth
                @if(!auth()->user()->is_company)
                    <a href="{{ route('submitproposal', ['sponsorship' => $sponsorship->id]) }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                       </svg>
                       Submit Proposal
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                   </svg>
                   Login to Submit Proposal
                </a>
            @endauth
        </div>
    </div>
</div>

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

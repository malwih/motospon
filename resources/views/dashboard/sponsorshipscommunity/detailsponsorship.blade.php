@extends('dashboard.layouts.main')

@section('title', 'Sponsorship Details - Motospon')

@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp

{{-- 
    Sponsorship Detail Page for Community Users
    - Displays detailed information about a specific sponsorship
    - Consistent with dashboard layout and styling
--}}
@section('container')
<div class="w-full p-10 sm:ml-80">
    {{-- 
        Card utama yang berisi detail sponsorship
        - Menggunakan shadow dan border untuk memberikan kedalaman
        - Lebar maksimum 7xl untuk tampilan yang optimal
        - Margin top 20 untuk memberi jarak dari navbar
    --}}
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-7xl mx-auto mt-20">
        <!-- Back Button -->
        <a href="{{ route('community.sponsorships.index') }}" 
           class="inline-flex items-center text-orange-500 hover:text-orange-700 mb-6 transition-colors duration-200">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Sponsorships
        </a>
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Sponsorship Details</h1>
        
        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Image Section -->
            <div class="relative h-96 w-full">
                <img src="{{ asset('storage/' . $sponsorship->image) }}" 
                     alt="{{ $sponsorship->title }}"
                     class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                    <div class="flex items-center mb-2">
                        <span class="bg-orange-500 text-white text-sm font-semibold px-3 py-1 rounded-full">
                            {{ ucfirst($sponsorship->category) }}
                        </span>
                        <span class="ml-4 text-white text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $sponsorship->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <h2 class="text-3xl font-bold text-white">{{ $sponsorship->title }}</h2>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-white mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-white text-sm">By {{ $sponsorship->author->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6 md:p-8">
                <!-- Author Info -->
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center">
                        <img src="{{ $sponsorship->author->avatar ? asset('storage/' . $sponsorship->author->avatar) : asset('storage/default-avatar.png') }}" 
                             alt="{{ $sponsorship->author->name }}" 
                             class="w-10 h-10 rounded-full object-cover mr-3">
                        <div>
                            <p class="font-medium text-gray-900">{{ $sponsorship->author->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-500">Posted on {{ $sponsorship->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        {{ $sponsorship->proposals_count }} {{ Str::plural('Proposal', $sponsorship->proposals_count) }} Submitted
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Description</h3>
                    <div class="prose max-w-none text-gray-700">
                        {!! $sponsorship->body !!}
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                <a href="{{ route('community.sponsorships.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Sponsorships
                    </a>
                    <a href="{{ route('submitproposal', ['sponsorship' => $sponsorship->id]) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Submit Proposal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .prose {
        color: #374151;
        line-height: 1.75;
    }
    .prose p {
        margin-top: 1.25em;
        margin-bottom: 1.25em;
    }
    .prose h2 {
        color: #111827;
        font-weight: 600;
        font-size: 1.5em;
        margin-top: 2em;
        margin-bottom: 1em;
        line-height: 1.3333333;
    }
    .prose h3 {
        color: #111827;
        font-weight: 600;
        font-size: 1.25em;
        margin-top: 1.6em;
        margin-bottom: 0.6em;
        line-height: 1.6;
    }
    .prose a {
        color: #ea580c;
        text-decoration: none;
        font-weight: 500;
    }
    .prose a:hover {
        text-decoration: underline;
    }
    .prose ul {
        margin-top: 1.25em;
        margin-bottom: 1.25em;
        padding-left: 1.625em;
    }
    .prose li {
        margin-top: 0.5em;
        margin-bottom: 0.5em;
    }
</style>
@endpush

@push('scripts')
<script>
    // Smooth scrolling for anchor links
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100, // Offset for fixed header
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add copy to clipboard functionality for code blocks
        document.querySelectorAll('pre code').forEach((block) => {
            const pre = block.parentNode;
            if (!pre.classList.contains('code-block')) {
                pre.classList.add('code-block', 'relative', 'bg-gray-50', 'p-4', 'rounded-lg', 'overflow-x-auto');
                
                const button = document.createElement('button');
                button.className = 'copy-button absolute top-2 right-2 p-1.5 rounded-md bg-white/50 hover:bg-white/80 transition-colors duration-200';
                button.innerHTML = `
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                    </svg>
                `;
                button.title = 'Copy to clipboard';
                
                pre.style.position = 'relative';
                pre.insertBefore(button, block);
                
                button.addEventListener('click', () => {
                    navigator.clipboard.writeText(block.textContent).then(() => {
                        const originalTitle = button.title;
                        button.innerHTML = `
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        `;
                        button.title = 'Copied!';
                        
                        setTimeout(() => {
                            button.innerHTML = `
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                </svg>
                            `;
                            button.title = originalTitle;
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy text: ', err);
                    });
                });
            }
        });

        // Add smooth scrolling to top when clicking the back to top button
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

@endsection

@extends('dashboard.layouts.main')

@section('title', 'Sponsorships - Motospon')

@php
    use Illuminate\Support\Str;
@endphp

{{-- 
    Main Content for Sponsorships List
    - Displays a searchable and filterable list of sponsorships
    - Consistent with dashboard layout and styling
--}}
@section('container')
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
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Sponsorships</h1>
        </div>

        {{-- Search and Filter Section --}}
        <form action="{{ route('community.sponsorships.index') }}" method="GET" class="mb-8">
            <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                <div class="w-full md:w-1/3 relative">
                    <div class="relative">
                        <input type="text" 
                               name="search"
                               id="searchInput"
                               value="{{ request('search') }}"
                               placeholder="Search sponsorships..." 
                               class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               autocomplete="off">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div id="searchLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                            <svg class="animate-spin h-4 w-4 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    <div id="searchResults" class="hidden absolute z-50 w-full mt-1 bg-white rounded-md shadow-lg max-h-96 overflow-auto border border-gray-200 divide-y divide-gray-100">
                        <div class="p-3 text-sm text-gray-500 border-b">
                            Type to search for sponsorships...
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/3">
                    <select name="category" id="categoryFilter" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        {{-- Sponsorships Grid --}}
        <div id="sponsorshipsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($sponsorships as $sponsorship)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
                    <div class="relative">
                        <img src="{{ asset('storage/' . $sponsorship->image) }}" 
                             alt="{{ $sponsorship->title }}"
                             class="w-full h-48 object-cover hover:opacity-90 transition-opacity duration-300">
                        <div class="absolute top-0 left-0 bg-orange-500 text-white font-semibold py-1 px-3 rounded-br-lg">
                            {{ $sponsorship->category }}
                        </div>
                    </div>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <svg class="h-4 w-4 mr-1 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Posted on {{ $sponsorship->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2">{{ $sponsorship->title }}</h3>
                        
                        <p class="text-gray-600 mb-4 flex-grow line-clamp-3">
                            {{ Str::limit(strip_tags($sponsorship->body), 150) }}
                        </p>
                        
                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-orange-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-sm text-gray-600">{{ $sponsorship->author->name ?? 'Unknown' }}</span>
                            </div>
                            <span class="text-sm font-semibold text-orange-600">
                                {{ $sponsorship->proposals_count }} {{ Str::plural('Proposal', $sponsorship->proposals_count) }} Submitted
                            </span>
                        </div>
                        
                        <a href="{{ route('community.sponsorships.show', $sponsorship) }}" 
                           class="mt-6 block w-full bg-orange-500 hover:bg-orange-600 text-white text-center font-medium py-2 px-4 rounded-lg transition duration-200 transform hover:-translate-y-0.5">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-orange-100 mb-4">
                        <svg class="w-8 h-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No sponsorships available</h3>
                    <p class="text-gray-500">Please check back later for new sponsorship opportunities</p>
                </div>
            @endforelse
        </div>
        
        {{-- Pagination --}}
        <div id="paginationContainer" class="mt-10">
            <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    @if ($sponsorships->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                            Previous
                        </span>
                    @else
                        <a href="{{ $sponsorships->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                    @endif
                    
                    @if ($sponsorships->hasMorePages())
                        <a href="{{ $sponsorships->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    @else
                        <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                            Next
                        </span>
                    @endif
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $sponsorships->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $sponsorships->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $sponsorships->total() }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            {{-- Previous Page Link --}}
                            @if ($sponsorships->onFirstPage())
                                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $sponsorships->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                                $currentPage = $sponsorships->currentPage();
                                $lastPage = $sponsorships->lastPage();
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($lastPage, $currentPage + 2);
                                
                                // Adjust if we're near the start
                                if ($endPage - $startPage < 4 && $endPage < $lastPage) {
                                    $endPage = min($lastPage, $startPage + 4);
                                }
                                
                                // Adjust if we're near the end
                                if ($endPage - $startPage < 4 && $startPage > 1) {
                                    $startPage = max(1, $endPage - 4);
                                }
                            @endphp
                            
                            @if($startPage > 1)
                                <a href="{{ $sponsorships->url(1) }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">1</a>
                                @if($startPage > 2)
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                                @endif
                            @endif
                            
                            @for ($i = $startPage; $i <= $endPage; $i++)
                                @if ($i == $sponsorships->currentPage())
                                    <span aria-current="page" class="z-10 bg-orange-50 border-orange-500 text-orange-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        {{ $i }}
                                    </span>
                                @else
                                    <a href="{{ $sponsorships->url($i) }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor
                            
                            @if($endPage < $lastPage)
                                @if($endPage < $lastPage - 1)
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                                @endif
                                <a href="{{ $sponsorships->url($lastPage) }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">{{ $lastPage }}</a>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($sponsorships->hasMorePages())
                                <a href="{{ $sponsorships->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM fully loaded');
        
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const searchResults = document.getElementById('searchResults');
        const sponsorshipsGrid = document.getElementById('sponsorshipsGrid');
        let timeoutId;

        // Function to fetch sponsorships based on search and filter
        function fetchSponsorships(search = '', category = '') {
            console.log('Fetching sponsorships with search:', search, 'category:', category);
            
            // Build the URL with query parameters
            let url = '{{ route("community.sponsorships.index") }}';
            const params = new URLSearchParams();
            
            if (search) params.append('search', search);
            if (category) params.append('category', category);
            
            // Always include the AJAX flag
            params.append('ajax', '1');
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            console.log('Request URL:', url);
            
            // Show loading state
            if (sponsorshipsGrid) {
                sponsorshipsGrid.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-orange-100 mb-4">
                            <svg class="w-8 h-8 text-orange-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600">Loading sponsorships...</p>
                    </div>
                `;
            }

            // Make the AJAX request with cache-busting parameter
            const timestamp = new Date().getTime();
            const separator = url.includes('?') ? '&' : '?';
            const cacheBustedUrl = `${url}${separator}_=${timestamp}`;
            
            fetch(cacheBustedUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                },
                credentials: 'same-origin',
                cache: 'no-store'
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response data:', data);
                
                // Handle redirect response
                if (data && data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }
                
                if (data && data.success) {
                    // Update the sponsorships grid
                    updateSponsorshipsGrid(data);
                    
                    // Create a clean URL without the ajax parameter
                    const cleanParams = new URLSearchParams(params);
                    cleanParams.delete('ajax');
                    const cleanQuery = cleanParams.toString();
                    const newUrl = window.location.pathname + (cleanQuery ? '?' + cleanQuery : '');
                    
                    // Update the URL without triggering a page reload
                    if (window.history && window.history.pushState) {
                        window.history.pushState({}, document.title, newUrl);
                    }
                    
                    // Hide pagination completely
                    const paginationContainer = document.getElementById('paginationContainer');
                    if (paginationContainer) {
                        paginationContainer.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching sponsorships:', error);
                if (sponsorshipsGrid) {
                    sponsorshipsGrid.innerHTML = `
                        <div class="col-span-full text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                                <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">Error loading sponsorships</h3>
                            <p class="text-gray-500">Please try again later or contact support if the problem persists</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching sponsorships:', error);
                if (sponsorshipsGrid) {
                    sponsorshipsGrid.innerHTML = `
                        <div class="col-span-full text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                                <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">Error loading sponsorships</h3>
                            <p class="text-gray-500">Please try again later or contact support if the problem persists</p>
                        </div>
                    `;
                }
            });
        }
        
        // Function to update the sponsorships grid with new data
        function updateSponsorshipsGrid(data) {
            if (!data || !data.data || data.data.length === 0) {
                sponsorshipsGrid.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-orange-100 mb-4">
                            <svg class="w-8 h-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No sponsorships found</h3>
                        <p class="text-gray-500">Try adjusting your search or filter criteria</p>
                    </div>
                `;
                
                // Hide pagination when no results
                const paginationContainer = document.getElementById('paginationContainer');
                if (paginationContainer) {
                    paginationContainer.style.display = 'none';
                }
                
                return;
            }

            const sponsorships = data.data;
            const pagination = data.pagination;

            const html = sponsorships.map(sponsorship => `
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
                    <div class="relative">
                        <img src="${sponsorship.image}" 
                             alt="${sponsorship.title}"
                             class="w-full h-48 object-cover hover:opacity-90 transition-opacity duration-300">
                        <div class="absolute top-0 left-0 bg-orange-500 text-white font-semibold py-1 px-3 rounded-br-lg">
                            ${sponsorship.category}
                        </div>
                    </div>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <svg class="h-4 w-4 mr-1 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Posted on ${sponsorship.posted_on}</span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2">${sponsorship.title}</h3>
                        
                        <p class="text-gray-600 mb-4 flex-grow line-clamp-3">
                            ${sponsorship.body.substring(0, 150)}${sponsorship.body.length > 150 ? '...' : ''}
                        </p>
                        
                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-orange-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-sm text-gray-600">${sponsorship.author_name}</span>
                            </div>
                            <a href="/dashboard/community/sponsorships/${sponsorship.slug}" class="inline-flex items-center px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 transition-colors duration-200">
                                View Details
                                <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            `).join('');

            sponsorshipsGrid.innerHTML = html;
            
            // Update pagination
            const paginationContainer = document.getElementById('paginationContainer');
            if (paginationContainer) {
                // Show pagination only if there are multiple pages
                if (data.pagination && data.pagination.last_page > 1) {
                    paginationContainer.style.display = 'block';
                    
                    // Update pagination info
                    const paginationInfo = paginationContainer.querySelector('.text-sm.text-gray-700');
                    if (paginationInfo) {
                        paginationInfo.innerHTML = `
                            Showing
                            <span class="font-medium">${data.pagination.from || 0}</span>
                            to
                            <span class="font-medium">${data.pagination.to || 0}</span>
                            of
                            <span class="font-medium">${data.pagination.total || 0}</span>
                            results
                        `;
                    }
                    
                    // Update pagination links
                    const paginationNav = paginationContainer.querySelector('nav');
                    if (paginationNav) {
                        // Remove existing active page
                        const activePage = paginationNav.querySelector('.z-10.bg-orange-50');
                        if (activePage) {
                            activePage.classList.remove('z-10', 'bg-orange-50', 'border-orange-500', 'text-orange-600');
                            activePage.classList.add('bg-white', 'border-gray-300', 'text-gray-500', 'hover:bg-gray-50');
                        }
                        
                        // Update page numbers
                        const pageLinks = paginationNav.querySelectorAll('a[href*="page="]');
                        pageLinks.forEach(link => {
                            // Update active state if this is the current page
                            if (link.getAttribute('href').includes(`page=${data.pagination.current_page}`)) {
                                link.classList.remove('bg-white', 'border-gray-300', 'text-gray-500', 'hover:bg-gray-50');
                                link.classList.add('z-10', 'bg-orange-50', 'border-orange-500', 'text-orange-600');
                            }
                        });
                        
                        // Update previous/next buttons
                        const prevButton = paginationNav.querySelector('a[rel="prev"]');
                        const nextButton = paginationNav.querySelector('a[rel="next"]');
                        
                        if (data.pagination.current_page === 1) {
                            // Disable previous button on first page
                            if (prevButton) {
                                prevButton.outerHTML = `
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                `;
                            }
                        } else if (prevButton) {
                            prevButton.outerHTML = `
                                <a href="${data.pagination.prev_page_url}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            `;
                        }
                        
                        if (data.pagination.current_page === data.pagination.last_page) {
                            // Disable next button on last page
                            if (nextButton) {
                                nextButton.outerHTML = `
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                `;
                            }
                        } else if (nextButton) {
                            nextButton.outerHTML = `
                                <a href="${data.pagination.next_page_url}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            `;
                        }
                    }
                } else {
                    paginationContainer.style.display = 'none';
                }
                
                // Add event listeners to pagination links
                const paginationLinks = paginationContainer.querySelectorAll('a[href]');
                paginationLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const searchParams = new URLSearchParams(url.search);
                        
                        // Get current search and filter values
                        const search = searchInput ? searchInput.value.trim() : '';
                        const category = categoryFilter ? categoryFilter.value : '';
                        
                        // Update URL with search and filter parameters
                        if (search) searchParams.set('search', search);
                        if (category) searchParams.set('category', category);
                        
                        // Add ajax flag
                        searchParams.set('ajax', '1');
                        
                        // Update URL without page reload
                        const newUrl = `${url.pathname}?${searchParams.toString()}`;
                        window.history.pushState({ path: newUrl }, '', newUrl);
                        
                        // Fetch the page
                        fetch(newUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            updateSponsorshipsGrid(data);
                            // Scroll to top of results
                            window.scrollTo({
                                top: sponsorshipsGrid.offsetTop - 100,
                                behavior: 'smooth'
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching page:', error);
                        });
                    });
                });
            }
        }
        
        // Event listener for search input
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    const searchTerm = this.value.trim();
                    const category = categoryFilter ? categoryFilter.value : '';
                    fetchSponsorships(searchTerm, category);
                }, 500); // 500ms debounce
            });
        }
        
        // Event listener for category filter
        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                const searchTerm = searchInput ? searchInput.value.trim() : '';
                const category = this.value;
                fetchSponsorships(searchTerm, category);
            });
        }
        
        // Function to load sponsorships based on URL parameters
        function loadSponsorshipsFromURL() {
            const searchParams = new URLSearchParams(window.location.search);
            const search = searchParams.get('search') || '';
            const category = searchParams.get('category') || '';
            const page = searchParams.get('page') || 1;
            
            if (searchInput) searchInput.value = search;
            if (categoryFilter) categoryFilter.value = category;
            
            // Only fetch if we have search or filter parameters
            if (search || category || page > 1) {
                fetchSponsorships(search, category, page);
            } else if (sponsorshipsGrid) {
                // If no search/filter, ensure we're showing the initial content
                const initialContent = sponsorshipsGrid.getAttribute('data-initial-content');
                if (initialContent) {
                    sponsorshipsGrid.innerHTML = initialContent;
                }
            }
        }

        // Save initial content for restoring
        if (sponsorshipsGrid) {
            sponsorshipsGrid.setAttribute('data-initial-content', sponsorshipsGrid.innerHTML);
        }

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
            // If we have state data, use it to restore the page
            if (event.state && event.state.path) {
                window.location.href = event.state.path;
            } else {
                // Otherwise, force a full page reload
                window.location.reload();
            }
        });

        // Initial load from URL parameters
        loadSponsorshipsFromURL();
    });
</script>
@endpush

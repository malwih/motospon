@extends('layouts.main')

@section('container')
    {{-- 
        Header dengan gradient dan latar belakang pattern
        - Menampilkan judul dan deskripsi halaman
        - Terdapat tombol aksi untuk register/login
    --}}
    <div class="relative bg-gradient-to-r from-orange-500 to-orange-600 py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgcGF0dGVyblRyYW5zZm9ybT0icm90YXRlKDQUpIj48cGF0aCBkPSJNMCAwSDEwMFYxMDBIMHoiIGZpbGw9Im5vbmUiLz48cGF0aCBkPSJNMCwwSDEwMFYxMDBIMHoiIGZpbGw9IiNmZmZmZmYwMyIgZmlsbC1vcGFjaXR5PSIwLjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjcGF0dGVybikiLz48L3N2Zz4=')] opacity-20"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-sm mb-4">
                    <span class="text-sm font-medium text-white">Sponsorship Platform</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                    Discover Amazing <span class="text-yellow-300">Sponsorship</span> Opportunities
                </h1>
                <p class="text-lg md:text-xl text-orange-100 max-w-2xl mx-auto leading-relaxed">
                    Connect with top brands and find the perfect sponsorship for your next event or project.
                </p>
                @guest
                    <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                        <a href="/register" class="px-6 py-3 bg-white text-orange-600 font-semibold rounded-lg shadow-md hover:bg-gray-100 transition duration-200 transform hover:-translate-y-0.5">
                            Register
                        </a>
                        <a href="/login" class="px-6 py-3 border-2 border-white/30 text-white font-medium rounded-lg hover:bg-white/10 transition duration-200">
                            Login
                        </a>
                    </div>
                @endguest
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </div>

    {{-- 
        Konten utama halaman sponsorships
        - Berisi form pencarian dan filter
        - Menampilkan grid daftar sponsorship
    --}}
    <div class="bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            {{-- 
                Section pencarian dan filter
                - Input pencarian dengan autocomplete
                - Dropdown filter kategori
            --}}
            <div class="mb-8">
                <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                    <div class="w-full md:w-1/3 relative">
                        <div class="relative">
                            <input type="text" 
                                   id="searchInput"
                                   placeholder="Search sponsorship..." 
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
                        <select id="categoryFilter" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 
                Grid untuk menampilkan daftar sponsorship
                - Responsif dengan 1-3 kolom tergantung ukuran layar
                - Setiap card menampilkan informasi singkat sponsorship
            --}}
            <div id="sponsorshipsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php use Illuminate\Support\Str; @endphp
                @forelse ($sponsorships as $sponsorship)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $sponsorship->image) }}" 
                                 alt="{{ $sponsorship->title }}"
                                 class="w-full h-48 object-cover hover:opacity-90 transition-opacity duration-300">
                            <div class="absolute top-0 left-0 bg-orange-500 text-white font-semibold py-1 px-3 rounded-br-lg">
                                {{ ucfirst($sponsorship->category) }}
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
                                {{ Str::words(strip_tags($sponsorship->body), 20, '...') }}
                            </p>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-orange-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $sponsorship->author->name ?? 'Unknown' }}</span>
                                </div>
                                <span class="text-sm font-semibold text-orange-600">
                                    {{ $sponsorship->proposals_count }} Proposals Submitted
                                </span>
                            </div>
                            
                            <a href="/sponsorships/{{ $sponsorship->slug }}" 
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
                        <p class="text-gray-500">Check back later for new sponsorship opportunities</p>
                    </div>
                @endforelse
            </div>

            {{-- 
                Navigasi pagination
                - Tombol previous/next
                - Daftar nomor halaman
            --}}
            @if($sponsorships->hasPages())
                <div class="mt-10 flex justify-center">
                    <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-orange-50 text-sm font-medium text-orange-600 hover:bg-orange-100">
                            1
                        </a>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            2
                        </a>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            3
                        </a>
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            ...
                        </span>
                        <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            8
                        </a>
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </nav>
                </div>
            @endif
        </div>
    </div>

    {{-- 
        JavaScript untuk fitur pencarian dan filter
        - Menggunakan vanilla JavaScript
        - Menangani interaksi pengguna secara real-time
    --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded');
            
            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            const searchLoading = document.getElementById('searchLoading');
            const sponsorshipsGrid = document.getElementById('sponsorshipsGrid');
            let timeoutId;
            
            if (!searchInput || !searchResults || !sponsorshipsGrid) {
                console.error('Required elements not found');
                return;
            }
            
            // Store all sponsorships data
            const sponsorships = [
                @foreach($sponsorships as $sponsorship)
                {
                    id: {{ $sponsorship->id }},
                    title: `{{ $sponsorship->title }}`,
                    slug: `{{ $sponsorship->slug }}`,
                    body: `{!! str_replace(["\r\n", "\r", "\n"], " ", strip_tags($sponsorship->body)) !!}`,
                    category: `{{ $sponsorship->category }}`,
                    author_name: `{{ $sponsorship->author->name ?? 'Unknown' }}`,
                    proposals_count: {{ $sponsorship->proposals_count ?? 0 }},
                    location: `{{ $sponsorship->location ?? 'Online' }}`, // Keeping for backward compatibility
                    created_at: '{{ $sponsorship->created_at->format('M d, Y') }}',
                    posted_on: 'Posted on {{ $sponsorship->created_at->format('M d, Y') }}',
                    image: `{{ $sponsorship->image ? asset('storage/' . $sponsorship->image) : 'https://source.unsplash.com/random/400x300/?event' }}`,
                    view_details: 'View Details'
                }{{ !$loop->last ? ',' : '' }}
                @endforeach
            ];
            
            console.log('Loaded', sponsorships.length, 'sponsorships');

            /**
             * Fungsi untuk memfilter sponsorship berdasarkan query pencarian
             * - Mencari di judul, deskripsi, kategori, dan lokasi
             * - Case insensitive search
             * @param {string} query - Kata kunci pencarian
             * @returns {Array} Daftar sponsorship yang sesuai dengan query
             */
            function filterSponsorships(query) {
                console.log('Filtering with query:', query);
                if (!query || !query.trim()) {
                    console.log('Empty query, returning all sponsorships');
                    return sponsorships;
                }
                
                const lowerQuery = query.toLowerCase().trim();
                console.log('Searching for:', lowerQuery);
                
                const results = sponsorships.filter(sponsorship => {
                    try {
                        return (
                            (sponsorship.title && sponsorship.title.toLowerCase().includes(lowerQuery)) ||
                            (sponsorship.body && sponsorship.body.toLowerCase().includes(lowerQuery)) ||
                            (sponsorship.category && sponsorship.category.toLowerCase().includes(lowerQuery)) ||
                            (sponsorship.location && sponsorship.location.toLowerCase().includes(lowerQuery))
                        );
                    } catch (error) {
                        console.error('Error filtering:', error, sponsorship);
                        return false;
                    }
                });
                
                console.log('Found', results.length, 'results');
                return results;
            }

            /**
             * Fungsi untuk merender hasil pencarian
             * - Menampilkan maksimal 5 hasil
             * - Setiap hasil menampilkan gambar, judul, dan info singkat
             * @param {Array} results - Daftar hasil pencarian
             * @returns {string} HTML untuk menampilkan hasil pencarian
             */
            function renderSearchResults(results) {
                console.log('Rendering search results:', results);
                if (!results || results.length === 0) {
                    return `
                        <div class="p-4 text-center">
                            <div class="text-gray-500 mb-2">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p>No results found</p>
                            </div>
                            <p class="text-xs text-gray-400">Try different keywords</p>
                        </div>`;
                }

                const items = results.slice(0, 5).map(sponsorship => `
                    <a href="/sponsorships/${sponsorship.slug}" class="block px-4 py-3 hover:bg-orange-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <img src="${sponsorship.image}" alt="${sponsorship.title}" 
                                     class="w-12 h-12 rounded-md object-cover border border-gray-200">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">${sponsorship.title}</h4>
                                <div class="flex items-center text-xs text-gray-500 mt-1">
                                    <span class="inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        ${sponsorship.category}
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span class="truncate">
                                        <svg class="w-3 h-3 inline mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        ${sponsorship.author_name}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 ml-2">
                                <span class="inline-block bg-orange-100 text-orange-800 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    ${sponsorship.proposals_count} Proposals
                                </span>
                            </div>
                        </div>
                    </a>
                `).join('');
                
                return items;
            }

            /**
             * Fungsi untuk merender daftar sponsorship
             * - Menampilkan card untuk setiap sponsorship
             * - Responsif dengan grid layout
             * - Menampilkan pesan jika tidak ada hasil
             * @param {Array} sponsorshipsToRender - Daftar sponsorship yang akan dirender
             * @returns {string} HTML untuk menampilkan daftar sponsorship
             */
            function renderSponsorships(sponsorshipsToRender) {
                if (sponsorshipsToRender.length === 0) {
                    return `
                        <div class="col-span-full text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-orange-100 mb-4">
                                <svg class="w-8 h-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No matching sponsorships found</h3>
                            <p class="text-gray-500">Try different keywords or browse all sponsorships</p>
                        </div>`;
                }

                return sponsorshipsToRender.map(sponsorship => `
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
                                <span>${sponsorship.posted_on}</span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2">${sponsorship.title}</h3>
                            
                            <p class="text-gray-600 mb-4 flex-grow line-clamp-3">
                                ${sponsorship.body.substring(0, 100)}${sponsorship.body.length > 100 ? '...' : ''}
                            </p>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-orange-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-sm text-gray-600">${sponsorship.author_name}</span>
                                </div>
                                <span class="text-sm font-semibold text-orange-600">
                                    ${sponsorship.proposals_count} Proposals Submitted
                                </span>
                            </div>
                            
                            <a href="/sponsorships/${sponsorship.slug}" 
                               class="mt-6 block w-full bg-orange-500 hover:bg-orange-600 text-white text-center font-medium py-2 px-4 rounded-lg transition duration-200 transform hover:-translate-y-0.5">
                                ${sponsorship.view_details}
                            </a>
                        </div>
                    </div>
                `).join('');
            }

            /**
             * Menangani input pencarian
             * - Menampilkan loading indicator saat mencari
             * - Menggunakan debounce untuk optimasi performa
             * - Memperbarui hasil pencarian secara real-time
             */
            function handleSearch() {
                try {
                    clearTimeout(timeoutId);
                    const query = searchInput.value.trim();
                    console.log('Search input:', query);
                    
                    if (query.length === 0) {
                        console.log('Empty search, showing all');
                        searchResults.classList.add('hidden');
                        // Show all sponsorships
                        sponsorshipsGrid.innerHTML = renderSponsorships(sponsorships);
                        return;
                    }


                    searchLoading.classList.remove('hidden');
                    searchResults.classList.remove('hidden');

                    timeoutId = setTimeout(() => {
                        try {
                            const results = filterSponsorships(query);
                            console.log('Rendering results:', results);
                            
                            const searchResultsHTML = renderSearchResults(results);
                            const sponsorshipsHTML = renderSponsorships(results);
                            
                            searchResults.innerHTML = searchResultsHTML;
                            searchLoading.classList.add('hidden');
                            
                            // Update main grid with filtered results
                            sponsorshipsGrid.innerHTML = sponsorshipsHTML;
                            
                            console.log('Search results updated');
                        } catch (error) {
                            console.error('Error in search:', error);
                            searchResults.innerHTML = '<div class="p-4 text-red-500">Error performing search. Please try again.</div>';
                            searchLoading.classList.add('hidden');
                        }
                    }, 300);
                } catch (error) {
                    console.error('Error in search handler:', error);
                    searchResults.innerHTML = '<div class="p-4 text-red-500">An error occurred. Please refresh the page and try again.</div>';
                    searchLoading.classList.add('hidden');
                }
            }

            // Menambahkan event listeners untuk interaksi pengguna
            searchInput.addEventListener('input', handleSearch);
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    handleSearch();
                }
            });

            /**
             * Menangani perubahan filter kategori
             * - Memfilter daftar sponsorship berdasarkan kategori
             * - Memperbarui URL dengan parameter filter
             * - Tidak me-refresh halaman saat filter berubah
             */
            const categoryFilter = document.getElementById('categoryFilter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', function() {
                    const category = this.value;
                    let filtered = sponsorships;
                    
                    if (category) {
                        filtered = filtered.filter(sponsorship => 
                            sponsorship.category.toLowerCase() === category.toLowerCase()
                        );
                    }
                    
                    // Update the grid with filtered results
                    sponsorshipsGrid.innerHTML = renderSponsorships(filtered);
                    
                    // Update URL without page reload
                    const url = new URL(window.location);
                    if (category) {
                        url.searchParams.set('category', category);
                    } else {
                        url.searchParams.delete('category');
                    }
                    window.history.pushState({}, '', url);
                });
            }

            // Menutup hasil pencarian saat mengklik di luar area pencarian
            document.addEventListener('click', function(event) {
                if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                    searchResults.classList.add('hidden');
                }
            });

            // Inisialisasi dengan menampilkan semua sponsorship
            sponsorshipsGrid.innerHTML = renderSponsorships(sponsorships);
            
            // Menerapkan filter kategori dari URL saat halaman dimuat
            const urlParams = new URLSearchParams(window.location.search);
            const categoryParam = urlParams.get('category');
            if (categoryParam && categoryFilter) {
                categoryFilter.value = categoryParam;
                const event = new Event('change');
                categoryFilter.dispatchEvent(event);
            }
        });
    </script>
    @endpush
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

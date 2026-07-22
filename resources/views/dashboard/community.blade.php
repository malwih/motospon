@extends('dashboard.layouts.main')

@section('title', 'Community - Motospon')

@section('container')

{{-- ============================================================
    INCLUDE LIBRARY YANG DIPERLUKAN
    ============================================================ --}}
{{-- Library untuk date picker --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

{{-- Styling untuk sweetalert2 --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

{{-- Framework JavaScript minimalis untuk interaktivitas --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>

{{-- Library untuk menampilkan alert yang lebih interaktif --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Versi Alpine.js --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>



{{-- ============================================================
    KONTEN UTAMA
    ============================================================ --}}
<div class="w-full p-10 sm:ml-80">
    {{-- Container utama untuk konten --}}
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white w-full max-w-[95vw] mx-auto mt-20">
        {{-- Header halaman --}}
        {{-- Bagian header halaman yang menampilkan judul dashboard --}}
        <div class="flex justify-between flex-wrap items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        </div>

        {{-- ============================================================
    MENAMPILKAN PESAN FLASH SESSION
    ============================================================ --}}
        {{-- 
            Bagian ini menangani tampilan notifikasi flash message.
            Terdapat 3 jenis notifikasi:
            1. Success - untuk operasi yang berhasil
            2. Error - untuk operasi yang gagal
            3. Warning - untuk peringatan
        --}}
        @if(session('success'))
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        @endif
        
        {{-- Menampilkan pesan error --}}
        {{-- Jika ada pesan error, maka tampilkan alert dengan tipe error --}}
        @if(session('error'))
            <x-alert type="error">
                {{ session('error') }}
            </x-alert>
        @endif
        
        {{-- Menampilkan pesan peringatan --}}
        {{-- Jika ada pesan peringatan, maka tampilkan alert dengan tipe warning --}}
        @if(session('warning'))
            <x-alert type="warning">
                {{ session('warning') }}
            </x-alert>
        @endif


        {{-- ============================================================
    TOMBOL SUBMIT PROPOSAL
    ============================================================ --}}
        {{-- 
            Tombol untuk mengarahkan ke halaman pengajuan proposal baru.
            Menggunakan warna oranye yang konsisten dengan tema aplikasi.
            Ditempatkan di bagian kanan atas tabel.
        --}}
        <div class="block justify-end mb-6 mt-6">
            <a href="{{ route('submitproposal') }}" class="inline-block">
                <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 ease-in-out transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>Submit Proposal
                </button>
            </a>
        </div>

        {{-- ============================================================
    SEARCH & FILTER
    ============================================================ --}}
        {{-- 
            Bagian pencarian dan filter untuk memudahkan pengguna 
            dalam menemukan proposal yang diinginkan.
            Menggunakan grid yang responsif untuk tampilan mobile dan desktop.
        --}}
        <section class="mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-file-alt mr-2"></i>Proposal Submitted
            </h2>

            {{-- Container untuk form filter --}}
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <form method="GET" action="{{ route('dashboard.community') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- 
                        Field tersembunyi untuk menyimpan nilai sorting saat ini.
                        Digunakan untuk mempertahankan state sorting saat melakukan filter.
                    --}}
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <input type="hidden" name="order" value="{{ request('order') }}">
                    <!-- Sponsor Filter -->
                    <div>
                        <label for="sponsor" class="block text-sm font-medium text-gray-700 mb-1">Sponsor</label>
                        <input type="text" name="sponsor" id="sponsor" value="{{ request('sponsor') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Search sponsor...">
                    </div>
                    
                    <!-- Name Event Filter -->
                    <div>
                        <label for="event" class="block text-sm font-medium text-gray-700 mb-1">Name Event</label>
                        <input type="text" name="event" id="event" value="{{ request('event') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Search name event...">
                    </div>
                    
                    <!-- Date Filter -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date Event</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <!-- Submit and Reset Buttons -->
                    <div class="flex items-end space-x-2 md:col-span-4">
                        <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Apply Filter
                        </button>
                        @php
                            $resetUrl = route('dashboard.community');
                            $sort = request('sort');
                            $order = request('order');
                            
                            if ($sort && $order) {
                                $resetUrl .= "?sort=$sort&order=$order";
                            }
                        @endphp
                        <a href="{{ $resetUrl }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabel daftar proposal --}}
            <div class="w-full overflow-x-auto">
                <table class="w-full table-auto divide-y divide-gray-200 border rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center cursor-pointer hover:bg-gray-200" onclick="sortTable('sponsor')">
                                <div class="flex items-center justify-center">
                                    Sponsor
                                    @if(request('sort') == 'sponsor' && request('order') == 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    @elseif(request('sort') == 'sponsor' && request('order') == 'desc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center cursor-pointer hover:bg-gray-200" onclick="sortTable('event')">
                                <div class="flex items-center justify-center">
                                    Name Event
                                    @if(request('sort') == 'event' && request('order') == 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    @elseif(request('sort') == 'event' && request('order') == 'desc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center cursor-pointer hover:bg-gray-200" onclick="sortTable('date')">
                                <div class="flex items-center justify-center">
                                    Date Event
                                    @if(request('sort') == 'date' && request('order') == 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    @elseif(request('sort') == 'date' && request('order') == 'desc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Status</th>
                            <th class="px-4 py-3 text-center"></th>
                        </tr>
                    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($proposals as $index => $proposal)
        <tr>
            <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
            <td class="px-6 py-4 text-center max-w-xs truncate" title="{{ $proposal->sponsorship->title ?? 'N/A' }}">
                {{ Str::limit($proposal->sponsorship->title ?? 'N/A', 20, '...') }}
            </td>
            <td class="px-6 py-4 text-center max-w-xs truncate" title="{{ $proposal->name_event }}">
                {{ Str::limit($proposal->name_event, 25, '...') }}
            </td>
            <td class="px-6 py-4 text-center whitespace-nowrap">
                {{ \Carbon\Carbon::parse($proposal->date_event)->format('d M Y') }}
            </td>
            <td class="px-6 py-4 text-center">
                @if ($proposal->is_accept)
                    <span class="text-green-600 font-semibold">Accepted</span>
                @elseif ($proposal->is_reject)
                    <span class="text-red-600 font-semibold">Rejected</span>
                @elseif ($proposal->is_active)
                    <span class="text-yellow-500 font-semibold">Active</span>
                @else
                    <span class="text-gray-500 font-semibold">Inactive</span>
                @endif
            </td>

            <!-- Dropdown Gear Icon with Flat Rectangle Button -->
<td class="px-4 py-3 text-center">
    <div class="relative inline-block text-left" x-data="{ open: false }">
        <button @click="open = !open"
                class="inline-flex items-center px-3 py-2 bg-gray-200 hover:bg-gray-300 border border-gray-400 shadow-sm text-sm font-medium text-gray-700 focus:outline-none">
            <!-- More 3D Gear Icon -->
            <svg class="w-5 h-5 text-gray-700 mr-1" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19.14,12.94a7.92,7.92,0,0,0,.06-1l2.12-1.65a.5.5,0,0,0,.13-.56l-2-3.46a.5.5,0,0,0-.54-.24l-2.49,1a7.75,7.75,0,0,0-1.73-1L14.24,3.1a.5.5,0,0,0-.49-.1L9.79,4.17a.5.5,0,0,0-.3.44l-.3,2.88a7.62,7.62,0,0,0-1.73,1l-2.49-1a.5.5,0,0,0-.54.24l-2,3.46a.5.5,0,0,0,.13.56L4.8,12a8.36,8.36,0,0,0,0,2l-2.12,1.65a.5.5,0,0,0-.13.56l2,3.46a.5.5,0,0,0,.54.24l2.49-1a7.75,7.75,0,0,0,1.73,1l.3,2.88a.5.5,0,0,0,.3.44l3.96,1.17a.5.5,0,0,0,.49-.1l1.53-2.65a7.62,7.62,0,0,0,1.73-1l2.49,1a.5.5,0,0,0,.54-.24l2-3.46a.5.5,0,0,0-.13-.56ZM12,15.5A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/>
            </svg>
            <!-- Down Arrow -->
            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown Content -->
        <div x-show="open" @click.away="open = false"
            class="absolute right-0 z-10 w-40 origin-top-right bg-white border border-gray-200 rounded-md shadow-lg">
            @if($proposal->is_accept || $proposal->is_reject)
                <!-- Show Preview and Feedback for accepted/rejected proposals -->
                <a href="{{ route('proposal.preview', $proposal->id) }}" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-eye mr-2 w-4 text-center"></i> Preview
                </a>
                
                <!-- Feedback Section -->
                <div class="border-t border-gray-100">
                    <div class="px-4 py-2 text-xs text-gray-500">
                        @if($proposal->is_accept)
                            <div class="text-green-600 font-medium">
                                <i class="fas fa-check-circle mr-1"></i> Diterima
                            </div>
                        @else
                            <div class="text-red-600 font-medium">
                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                            </div>
                        @endif
                        
                        @if($proposal->feedback)
                            <div class="mt-2 p-2 bg-gray-50 rounded border border-gray-200">
                                <div class="font-medium text-gray-700 mb-1">Feedback Sponsor:</div>
                                <div class="text-gray-600">{{ $proposal->feedback }}</div>
                            </div>
                        @else
                            <div class="mt-1 text-gray-500 italic">No feedback</div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Show all actions for other proposals -->
                <a href="{{ route('proposal.preview', $proposal->id) }}" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-eye mr-2 w-4 text-center"></i> Preview
                </a>
                
                @if ($proposal->is_active)
                    <a href="{{ route('proposal.edit', $proposal->id) }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-edit mr-2 w-4 text-center"></i> Edit
                    </a>
                    
                    <form action="{{ route('proposal.delete', $proposal->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            <i class="fas fa-trash-alt mr-2 w-4 text-center"></i> Delete
                        </button>
                    </form>
                @else
                    <div class="px-4 py-2 text-xs text-gray-500 border-t border-gray-100">
                        <span class="text-gray-500"><i class="fas fa-clock mr-1"></i> Inactive</span>
                    </div>
                @endif
            @endif
        </div>
    </div>
</td>

        </tr>
        @empty
    <tr>
        <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">
            Proposal not found.
        </td>
    </tr>
        @endforelse
    </tbody>
</table>

                {{-- ============================================================
    PAGINATION
    ============================================================ --}}
                {{-- 
                    Komponen pagination untuk navigasi halaman.
                    Menampilkan informasi halaman saat ini dan total data.
                    Responsif dengan tampilan yang berbeda untuk mobile dan desktop.
                --}}
                <div class="mt-4 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($proposals->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                                Previous
                            </span>
                        @else
                            <a href="{{ $proposals->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                        @endif
                        
                        @if ($proposals->hasMorePages())
                            <a href="{{ $proposals->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                                <span class="font-medium">{{ $proposals->firstItem() }}</span>
                                to
                                <span class="font-medium">{{ $proposals->lastItem() }}</span>
                                of
                                <span class="font-medium">{{ $proposals->total() }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                {{-- Previous Page Link --}}
                                @if ($proposals->onFirstPage())
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $proposals->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($proposals->getUrlRange(1, $proposals->lastPage()) as $page => $url)
                                    @if ($page == $proposals->currentPage())
                                        <span aria-current="page" class="z-10 bg-orange-50 border-orange-500 text-orange-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($proposals->hasMorePages())
                                    <a href="{{ $proposals->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
        </section>
    </div>
</div>
{{-- ============================================================
    SCRIPT UNTUK SORTING DAN INTERAKTIVITAS
    ============================================================ --}}
{{-- ============================================================
    SCRIPT UNTUK SORTING DAN INTERAKTIVITAS
    ============================================================ --}}
{{-- 
    Script untuk menangani:
    1. Fungsi sorting kolom tabel
    2. Konfirmasi aksi (hapus, dll)
    3. Inisialisasi komponen interaktif
--}}
<script>
    // Fungsi untuk mengurutkan tabel berdasarkan kolom
    function sortTable(column) {
        let order = 'asc';
        // Jika kolom yang sama diklik, ubah urutan
        if ('{{ request('sort') }}' === column && '{{ request('order') }}' === 'asc') {
            order = 'desc';
        }
        
        // Update form dengan parameter sorting baru
        const form = document.querySelector('form[method="GET"]');
        const sortInput = form.querySelector('input[name="sort"]');
        const orderInput = form.querySelector('input[name="order"]');
        
        sortInput.value = column;
        orderInput.value = order;
        
        // Submit form
        form.submit();
    }
    
    // Inisialisasi komponen interaktif setelah DOM selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi tooltip jika menggunakan library tooltip
        if (typeof tippy === 'function') {
            tippy('[data-tippy-content]');
        }
        
        // Inisialisasi datepicker jika ada
        const dateInput = document.getElementById('date');
        if (dateInput) {
            flatpickr(dateInput, {
                dateFormat: 'Y-m-d',
                allowInput: true
            });
        }
    });
/**
 * Fungsi untuk mengurutkan tabel berdasarkan kolom
 * @param {string} column - Nama kolom yang akan diurutkan
 */
function sortTable(column) {
    // Membuat objek URL dari URL saat ini
    const url = new URL(window.location.href);
    
    // Mendapatkan parameter sort dan order saat ini
    const sort = url.searchParams.get('sort');
    const order = url.searchParams.get('order');
    
    // Jika mengklik kolom yang sama, toggle antara asc dan desc
    if (sort === column) {
        url.searchParams.set('order', order === 'asc' ? 'desc' : 'asc');
    } else {
        // Jika kolom baru, set default ke ascending
        url.searchParams.set('sort', column);
        url.searchParams.set('order', 'asc');
    }
    
    // Menyimpan parameter query yang ada
    const params = new URLSearchParams(window.location.search);
    ['sponsor', 'event', 'date', 'status'].forEach(param => {
        if (params.has(param) && !url.searchParams.has(param)) {
            url.searchParams.set(param, params.get(param));
        }
    });
    
    // Redirect ke URL baru dengan parameter yang sudah diupdate
    window.location.href = url.toString();
}

// Menjalankan script setelah DOM selesai dimuat
document.addEventListener('DOMContentLoaded', function () {
    // ============================================================
    // KONFIRMASI HAPUS DATA
    // ============================================================
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            // Mencegah form submit default
            e.preventDefault();
            
            // Menampilkan konfirmasi menggunakan SweetAlert2
            Swal.fire({
                title: 'Delete Proposal?',
                text: "Please ensure the data is no longer needed before deleting.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                // Kustomisasi tampilan tombol
                didOpen: () => {
                    Swal.getConfirmButton().style.background = '#16a34a';
                    Swal.getCancelButton().style.background = '#d33';
                    Swal.getConfirmButton().style.color = '#fff';
                    Swal.getCancelButton().style.color = '#fff';
                }
            }).then((result) => {
                // Jika user mengkonfirmasi, submit form
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // Konfirmasi terima/tolak proposal
    document.querySelectorAll('.confirm-action').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            let action = form.querySelector('input[name="action"]').value;
            let text = action === 'accept' ? 'Accept this proposal?' : 'Reject this proposal?';
            let title = action === 'accept' ? 'Accept Proposal' : 'Reject Proposal';

            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                didOpen: () => {
                    Swal.getConfirmButton().style.background = '#16a34a';
                    Swal.getCancelButton().style.background = '#d33';
                    Swal.getConfirmButton().style.color = '#fff';
                    Swal.getCancelButton().style.color = '#fff';
                }
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>



@endsection
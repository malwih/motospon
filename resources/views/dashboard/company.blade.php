@extends('dashboard.layouts.main')

@section('title', 'Company - Motospon')

@section('container')
{{-- ============================================================
    LIBRARY YANG DIBUTUHKAN
    ============================================================ --}}
{{-- Library untuk date picker --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
{{-- Styling untuk alert --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
{{-- Library untuk alert interaktif --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Framework JavaScript minimalis --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>



{{-- ============================================================
    KONTEN UTAMA
    ============================================================ --}}
<div class="w-full p-10 sm:ml-80">
    {{-- Container utama --}}
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white w-full max-w-[95vw] mx-auto mt-20">
        {{-- Header halaman dengan judul dashboard --}}
        <div class="flex justify-between flex-wrap items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        </div>

        {{-- ============================================================
        PESAN FLASH SESSION
        ============================================================ --}}
        {{-- Menampilkan pesan sukses --}}
        @if(session('success'))
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        @endif
        
        {{-- Menampilkan pesan error --}}
        @if(session('error'))
            <x-alert type="error">
                {{ session('error') }}
            </x-alert>
        @endif
        
        {{-- Menampilkan pesan peringatan --}}
        @if(session('warning'))
            <x-alert type="warning">
                {{ session('warning') }}
            </x-alert>
        @endif


        {{-- ============================================================
        SECTION DAFTAR PROPOSAL
        ============================================================ --}}
        <section class="mb-10">
            {{-- Header section dengan judul dan tombol aksi --}}
            <div class="flex justify-between items-center mb-4 mt-4">
                <h2 class="text-2xl font-semibold text-gray-800">Incoming Proposals</h2>
                <div>
                    {{-- Tombol untuk melihat daftar proposal yang disembunyikan --}}
                    <a href="{{ route('proposals.hidden') }}" class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm transition duration-200">
                        Hidden Proposals
                    </a>
                </div>
            </div>

            {{-- ============================================================
            FORM FILTER DATA
            ============================================================ --}}
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <form method="GET" action="{{ route('dashboard.company') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    {{-- Field tersembunyi untuk menyimpan pengaturan sorting --}}
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <input type="hidden" name="order" value="{{ request('order') }}">
                    
                    {{-- Filter berdasarkan Sponsor --}}
                    <div>
                        <label for="sponsor" class="block text-sm font-medium text-gray-700 mb-1">Sponsor</label>
                        <input type="text" name="sponsor" id="sponsor" value="{{ request('sponsor') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Search sponsor...">
                    </div>
                    
                    {{-- Filter berdasarkan Komunitas --}}
                    <div>
                        <label for="community" class="block text-sm font-medium text-gray-700 mb-1">Community</label>
                        <input type="text" name="community" id="community" value="{{ request('community') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Search community...">
                    </div>
                    
                    {{-- Filter berdasarkan Nama Event --}}
                    <div>
                        <label for="event" class="block text-sm font-medium text-gray-700 mb-1">Name Event</label>
                        <input type="text" name="event" id="event" value="{{ request('event') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Search name event...">
                    </div>
                    
                    {{-- Filter berdasarkan Lokasi --}}
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" id="location" value="{{ request('location') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Search location...">
                    </div>
                    
                    {{-- Filter berdasarkan Tanggal Event --}}
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date Event</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    
                    {{-- Filter berdasarkan Status Proposal --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    {{-- ============================================================
                    TOMBOL FILTER DAN RESET
                    ============================================================ --}}
                    <div class="flex items-end space-x-2 md:col-span-6">
                        <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Apply Filter
                        </button>
                        @php
                            $resetUrl = route('dashboard.company');
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

            {{-- ============================================================
            TABEL DAFTAR PROPOSAL
            ============================================================ --}}
            <div class="w-full overflow-x-auto">
                <table class="w-full table-auto divide-y divide-gray-200 border rounded-lg">
                    {{-- Header kolom tabel --}}
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Sponsor</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center cursor-pointer hover:bg-gray-200" onclick="sortTable('community')">
                                <div class="flex items-center justify-center">
                                    Community
                                    @if(request('sort') == 'community' && request('order') == 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    @elseif(request('sort') == 'community' && request('order') == 'desc')
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
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center cursor-pointer hover:bg-gray-200" onclick="sortTable('location')">
                                <div class="flex items-center justify-center">
                                    Location
                                    @if(request('sort') == 'location' && request('order') == 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    @elseif(request('sort') == 'location' && request('order') == 'desc')
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
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Actions</th>
                            <th class="px-4 py-3 text-center"></th>
                        </tr>
                    </thead>
    {{-- Isi data proposal --}}
    <tbody class="bg-white divide-y divide-gray-200">
    @php
        // Filter hanya proposal yang tidak dihide
        $visibleProposals = $proposals->filter(fn($proposal) => !$proposal->hidden_from_company);
    @endphp

    {{-- Cek apakah ada proposal yang ditampilkan --}}
    @if ($visibleProposals->isEmpty())
        <tr>
            <td colspan="10" class="px-6 py-4 text-center text-gray-500 italic">
                Proposal not found.
            </td>
        </tr>
    @else
        {{-- Inisialisasi counter untuk nomor urut --}}
        @php $counter = 1; @endphp
        @foreach ($visibleProposals as $index => $proposal)
        <tr>
            <td class="px-6 py-4 text-center">{{ $counter++ }}</td>
            <td class="px-6 py-4 text-center">
                @if($proposal->sponsorship)
                    {{ $proposal->sponsorship->title }}
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </td>
            <td class="px-6 py-4 text-center max-w-xs truncate" title="{{ $proposal->name_community }}">
                {{ Str::limit($proposal->name_community, 20, '...') }}
            </td>
            <td class="px-6 py-4 text-center max-w-xs truncate" title="{{ $proposal->name_event }}">
                                {{ Str::limit($proposal->name_event, 25, '...') }}
                            </td>
            <td class="px-6 py-4 text-center max-w-xs truncate" title="{{ $proposal->location }}">
                                {{ Str::limit($proposal->location, 30, '...') }}
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
            {{-- Kolom aksi berdasarkan status proposal --}}
            <td class="px-6 py-4 text-center">
                @if ($proposal->is_accept)
                    {{-- Jika proposal diterima, tampilkan tombol WhatsApp --}}
                    @php
                        // Format nomor WhatsApp dengan menghilangkan karakter non-angka
                        $whatsappNumber = preg_replace('/[^0-9]/', '', $proposal->user->whatsapp_number);
                        $whatsappLink = 'https://wa.me/' . $whatsappNumber;
                    @endphp
                    <a href="{{ $whatsappLink }}" target="_blank" 
                       class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm"
                       title="Hubungi via WhatsApp">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.966-.273-.099-.471-.148-.67.15-.197.297-.767.963-.94 1.16-.173.199-.347.222-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.795-1.484-1.781-1.66-2.079-.173-.297-.018-.458.13-.606.136-.133.296-.346.445-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.549 4.142 1.595 5.945L0 24l6.335-1.652a11.882 11.882 0 005.723 1.467h.005c6.554 0 11.89-5.335 11.89-11.893 0-3.18-1.262-6.171-3.555-8.417"/>
                        </svg>
                        WhatsApp
                    </a>
                @elseif ($proposal->is_active && !$proposal->is_accept && !$proposal->is_reject)
                    {{-- Jika proposal aktif dan belum diproses, tampilkan tombol Accept/Reject --}}
                    <div class="flex justify-center space-x-2">
                        {{-- Form untuk menerima proposal --}}
                        <form action="{{ route('proposals.updateStatus', $proposal->id) }}" method="POST" class="confirm-action">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm">
                                Accept
                            </button>
                        </form>
                        
                        {{-- Form untuk menolak proposal --}}
                        <form action="{{ route('proposals.updateStatus', $proposal->id) }}" method="POST" class="confirm-action">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm">
                                Reject
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Jika tidak ada aksi yang tersedia --}}
                    <span class="text-gray-500 text-sm">No Action</span>
                @endif
            </td>
            {{-- Kolom aksi tambahan (dropdown) --}}
            <td class="px-4 py-3 text-center">
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    {{-- Tombol dropdown --}}
                    <button @click="open = !open"
                        class="inline-flex items-center px-3 py-2 bg-gray-200 hover:bg-gray-300 border border-gray-400 shadow-sm text-sm font-medium text-gray-700 focus:outline-none"
                        aria-haspopup="true"
                        :aria-expanded="open">
                        <svg class="w-5 h-5 text-gray-700 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.14,12.94a7.92,7.92,0,0,0,.06-1l2.12-1.65a.5.5,0,0,0,.13-.56l-2-3.46a.5.5,0,0,0-.54-.24l-2.49,1a7.75,7.75,0,0,0-1.73-1L14.24,3.1a.5.5,0,0,0-.49-.1L9.79,4.17a.5.5,0,0,0-.3.44l-.3,2.88a7.62,7.62,0,0,0-1.73,1l-2.49-1a.5.5,0,0,0-.54.24l-2,3.46a.5.5,0,0,0,.13.56L4.8,12a8.36,8.36,0,0,0,0,2l-2.12,1.65a.5.5,0,0,0-.13.56l2,3.46a.5.5,0,0,0,.54.24l2.49-1a7.75,7.75,0,0,0,1.73,1l.3,2.88a.5.5,0,0,0,.3.44l3.96,1.17a.5.5,0,0,0,.49-.1l1.53-2.65a7.62,7.62,0,0,0,1.73-1l2.49,1a.5.5,0,0,0,.54-.24l2-3.46a.5.5,0,0,0-.13-.56ZM12,15.5A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/>
                        </svg>
                        <svg class="w-4 h-4 text-gray-700 transition-transform duration-200 transform" 
                             :class="{ 'rotate-180': open }"
                             fill="none" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    {{-- Menu dropdown --}}
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 z-10 mt-2 w-36 origin-top-right bg-white border border-gray-200 shadow-lg rounded-md">
                        {{-- Tombol Preview --}}
                        <a href="{{ route('proposal.preview', $proposal->id) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Preview
                        </a>
                        
                        {{-- Form untuk menyembunyikan proposal --}}
                        <form action="{{ route('proposal.hideFromCompany', $proposal->id) }}" 
                              method="POST" 
                              class="hide-form border-t border-gray-100">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Hide
                            </button>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    @endif
</tbody>


</table>

                <!-- Pagination -->
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
    SCRIPT JAVASCRIPT
    ============================================================ --}}
<script>
    /**
     * Fungsi untuk mengurutkan tabel berdasarkan kolom yang dipilih
     * @param {string} column - Nama kolom yang akan diurutkan
     */
    function sortTable(column) {
        const url = new URL(window.location.href);
        const sort = url.searchParams.get('sort');
        const order = url.searchParams.get('order');
        
        // Toggle order if clicking the same column
        if (sort === column) {
            url.searchParams.set('order', order === 'asc' ? 'desc' : 'asc');
        } else {
            // Default to ascending for new column
            url.searchParams.set('sort', column);
            url.searchParams.set('order', 'asc');
        }
        
        // Preserve other query parameters
        const params = new URLSearchParams(window.location.search);
        ['community', 'event', 'location', 'date', 'status'].forEach(param => {
            if (params.has(param) && !url.searchParams.has(param)) {
                url.searchParams.set(param, params.get(param));
            }
        });
        
        window.location.href = url.toString();
    }

    // Menjalankan script setelah DOM selesai dimuat
    document.addEventListener('DOMContentLoaded', function () {
        // Konfirmasi hide proposal
        document.querySelectorAll('.hide-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hide Proposal?',
                    text: "This proposal will be hidden from the dashboard.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
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

        // Konfirmasi terima/tolak proposal dengan feedback
        document.querySelectorAll('.confirm-action').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                let action = form.querySelector('input[name="action"]').value;
                let actionText = action === 'accept' ? 'Accept' : 'Reject';
                let title = action === 'accept' ? 'Accept Proposal' : 'Reject Proposal';
                let confirmButtonText = action === 'accept' ? 'Accept' : 'Reject';
                let confirmButtonColor = action === 'accept' ? '#16a34a' : '#d33';

                Swal.fire({
                    title: title,
                    html: `
                        <form id="feedbackForm">
                            <div class="mb-4">
                                <label for="feedback" class="block text-gray-700 text-sm font-bold mb-2">
                                    Add Feedback (Optional)
                                </label>
                                <textarea id="feedback" name="feedback" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                    rows="3" placeholder="Enter your feedback here..."></textarea>
                            </div>
                        </form>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: 'Cancel',
                    focusConfirm: false,
                    preConfirm: () => {
                        let feedback = document.getElementById('feedback').value;
                        let feedbackInput = document.createElement('input');
                        feedbackInput.type = 'hidden';
                        feedbackInput.name = 'feedback';
                        feedbackInput.value = feedback;
                        form.appendChild(feedbackInput);
                        return true;
                    },
                    didOpen: () => {
                        Swal.getConfirmButton().style.background = confirmButtonColor;
                        Swal.getCancelButton().style.background = '#6c757d';
                        Swal.getConfirmButton().style.color = '#fff';
                        Swal.getCancelButton().style.color = '#fff';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
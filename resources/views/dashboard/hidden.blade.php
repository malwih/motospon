@extends('dashboard.layouts.main')

@section('title', 'Hidden Proposals - Motospon')

@section('container')
{{-- ============================================================
    LIBRARY YANG DIBUTUHKAN
    ============================================================ --}}
{{-- Library untuk date picker --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
{{-- Library untuk alert interaktif --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
{{-- Framework JavaScript minimalis --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>
{{-- Library untuk alert interaktif --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Framework JavaScript minimalis (versi 3) --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


{{-- ============================================================ --}}
{{-- KONTEN UTAMA --}}
{{-- ============================================================ --}}
<div class="w-full p-10 sm:ml-80">
    {{-- Container utama --}}
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-7xl mx-auto mt-20">
        {{-- Header halaman dengan judul dan tombol kembali --}}
        <div class="flex justify-between items-center pb-4 border-b border-gray-300">
            <h1 class="text-2xl font-bold text-gray-800">Hidden Proposals</h1>
            <div class="flex items-center space-x-2">
                <a href="{{ route('dashboard.company') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg flex items-center space-x-1 transition duration-300">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- PESAN FLASH SESSION --}}
        {{-- ============================================================ --}}
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

        {{-- ============================================================ --}}
        {{-- TABEL DAFTAR PROPOSAL TERSEMBUNYI --}}
        {{-- ============================================================ --}}
        <div class="overflow-x-auto mt-6">
            <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                {{-- Header kolom tabel --}}
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Sponsor</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Community</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Event</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Location</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Date</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Actions</th>
                        <th class="px-4 py-3 text-center"></th>
                    </tr>
                </thead>
                {{-- Isi data proposal --}}
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($proposals as $index => $proposal)
                        <tr>
                            {{-- Nomor urut --}}
                            <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                            {{-- Nama sponsor --}}
                            <td class="px-6 py-4 text-center">
                                @if($proposal->sponsorship)
                                    {{ $proposal->sponsorship->title }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            {{-- Nama komunitas dengan tooltip --}}
                            <td class="px-6 py-4 text-center max-w-xs truncate" title="{{ $proposal->name_community }}">
                                {{ Str::limit($proposal->name_community, 20, '...') }}
                            </td>
                            {{-- Nama event dengan tooltip --}}
                            <td class="px-6 py-4 text-center max-w-xs truncate" title="{{ $proposal->name_event }}">
                                {{ Str::limit($proposal->name_event, 25, '...') }}
                            </td>
                            {{-- Lokasi dengan tooltip --}}
                            <td class="px-6 py-4 text-center max-w-xs truncate" title="{{ $proposal->location }}">
                                {{ Str::limit($proposal->location, 30, '...') }}
                            </td>
                            {{-- Tanggal event yang diformat --}}
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($proposal->date_event)->format('d M Y') }}
                            </td>
            {{-- Status proposal dengan warna yang sesuai --}}
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
            {{-- Tombol aksi untuk proposal --}}
            <td class="px-6 py-4 text-center">
                @if ($proposal->is_active && !$proposal->is_accept && !$proposal->is_reject)
                    {{-- Tampilkan tombol Accept/Reject untuk proposal aktif yang belum diproses --}}
                    <div class="flex justify-center space-x-2">
                        {{-- Form untuk menerima proposal --}}
                        <form action="{{ route('proposals.updateStatus', $proposal->id) }}" method="POST" class="confirm-action">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm">Accept</button>
                        </form>
                        {{-- Form untuk menolak proposal --}}
                        <form action="{{ route('proposals.updateStatus', $proposal->id) }}" method="POST" class="confirm-action">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm">Reject</button>
                        </form>
                    </div>
                @else
                    {{-- Tampilkan pesan jika tidak ada aksi yang tersedia --}}
                    <span class="text-gray-500 text-sm">No Action</span>
                @endif
            </td>
            {{-- Dropdown menu untuk aksi tambahan --}}
            <td class="px-4 py-3 text-center">
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    {{-- Tombol toggle dropdown --}}
                    <button @click="open = !open"
                        class="inline-flex items-center px-3 py-2 bg-gray-200 hover:bg-gray-300 border border-gray-400 shadow-sm text-sm font-medium text-gray-700 focus:outline-none"
                        aria-expanded="true"
                        aria-haspopup="true">
                        <svg class="w-5 h-5 text-gray-700 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.14,12.94a7.92,7.92,0,0,0,.06-1l2.12-1.65a.5.5,0,0,0,.13-.56l-2-3.46a.5.5,0,0,0-.54-.24l-2.49,1a7.75,7.75,0,0,0-1.73-1L14.24,3.1a.5.5,0,0,0-.49-.1L9.79,4.17a.5.5,0,0,0-.3.44l-.3,2.88a7.62,7.62,0,0,0-1.73,1l-2.49-1a.5.5,0,0,0-.54.24l-2,3.46a.5.5,0,0,0,.13.56L4.8,12a8.36,8.36,0,0,0,0,2l-2.12,1.65a.5.5,0,0,0-.13.56l2,3.46a.5.5,0,0,0,.54.24l2.49-1a7.75,7.75,0,0,0,1.73,1l.3,2.88a.5.5,0,0,0,.3.44l3.96,1.17a.5.5,0,0,0,.49-.1l1.53-2.65a7.62,7.62,0,0,0,1.73-1l2.49,1a.5.5,0,0,0,.54-.24l2-3.46a.5.5,0,0,0-.13-.56ZM12,15.5A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/>
                        </svg>
                        {{-- Ikon panah dropdown dengan animasi rotasi --}}
                        <svg class="w-4 h-4 text-gray-700 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        {{-- Tombol Unhide --}}
                        <form action="{{ route('proposal.unhideFromCompany', $proposal->id) }}" method="POST" class="unhide-form">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100">
                                Unhide
                            </button>
                        </form>
                    </div>
                </div>
            </td>
                        </tr>
                    @empty
                        {{-- Tampilan jika tidak ada proposal yang tersembunyi --}}
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-500 italic">No hidden proposals found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{-- ============================================================ --}}
            {{-- PAGINATION --}}
            {{-- ============================================================ --}}
            <div class="mt-4 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                {{-- Tampilan mobile --}}
                <div class="flex-1 flex justify-between sm:hidden">
                    {{-- Tombol Previous untuk mobile --}}
                    @if ($proposals->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                            Previous
                        </span>
                    @else
                        <a href="{{ $proposals->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                    @endif
                    
                    {{-- Tombol Next untuk mobile --}}
                    @if ($proposals->hasMorePages())
                        <a href="{{ $proposals->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    @else
                        <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                        <span>Next</span>
                    </span>
                @endif
            </div>
            
            {{-- Tampilan desktop --}}
            <div class="hidden sm:block">
                {{ $proposals->links() }}
            </div>
            </div>
        </div>
    </div>
</div>

<script>
// Konfirmasi Unhide Proposal
document.addEventListener('DOMContentLoaded', function() {
    // Handle form unhide
    const unhideForms = document.querySelectorAll('.unhide-form');
    unhideForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Unhide Proposal?',
                text: "This proposal will be shown in the dashboard.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                buttonsStyling: true,
                customClass: {
                    confirmButton: 'px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700',
                    cancelButton: 'px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 ml-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Handle accept/reject actions
    const actionForms = document.querySelectorAll('.confirm-action');
    actionForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const action = form.querySelector('input[name="action"]').value;
            const isAccept = action === 'accept';
            
            Swal.fire({
                title: isAccept ? 'Accept Proposal?' : 'Reject Proposal?',
                text: isAccept ? 'Are you sure you want to accept this proposal?' : 'Are you sure you want to reject this proposal?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: isAccept ? 'Accept' : 'Reject',
                cancelButtonText: 'Cancel',
                buttonsStyling: true,
                customClass: {
                    confirmButton: isAccept 
                        ? 'px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700' 
                        : 'px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700',
                    cancelButton: 'px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 ml-2'
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
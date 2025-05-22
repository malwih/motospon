@extends('dashboard.layouts.main')

@php
    use Illuminate\Support\Str;
@endphp

@section('container')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="p-10 sm:ml-80">
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-7xl mx-auto mt-20">

        <div class="flex justify-between flex-wrap items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Sponsors</h1>
            <a href="/dashboard/sponsors/create">
                <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg shadow">
                    + Create New Sponsor
                </button>
            </a>
        </div>

        {{-- Success Notification --}}
        @if(session('success'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show" 
            x-transition 
            class="relative p-4 pr-10 mt-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50"
            role="alert"
        >
            {{ session('success') }}
            
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

        <section class="mt-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Name Sponsor</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Category</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Event</th>
                            <th class="px-4 py-3 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($sponsors as $index => $sponsor)
                        <tr>
                            <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-center">{{ $sponsor->title }}</td>
                            <td class="px-6 py-4 text-center">{{ Str::words(strip_tags($sponsor->category), 3, '...') }}</td>
                            <td class="px-6 py-4 text-center">{{ Str::words(strip_tags($sponsor->event), 3, '...') }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    <button @click="open = !open"
                                            class="inline-flex items-center px-3 py-2 bg-gray-200 hover:bg-gray-300 border border-gray-400 shadow-sm text-sm font-medium text-gray-700 focus:outline-none">
                                        <svg class="w-5 h-5 text-gray-700 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19.14,12.94a7.92,7.92,0,0,0,.06-1l2.12-1.65a.5.5,0,0,0,.13-.56l-2-3.46a.5.5,0,0,0-.54-.24l-2.49,1a7.75,7.75,0,0,0-1.73-1L14.24,3.1a.5.5,0,0,0-.49-.1L9.79,4.17a.5.5,0,0,0-.3.44l-.3,2.88a7.62,7.62,0,0,0-1.73,1l-2.49-1a.5.5,0,0,0-.54.24l-2,3.46a.5.5,0,0,0,.13.56L4.8,12a8.36,8.36,0,0,0,0,2l-2.12,1.65a.5.5,0,0,0-.13.56l2,3.46a.5.5,0,0,0,.54.24l2.49-1a7.75,7.75,0,0,0,1.73,1l.3,2.88a.5.5,0,0,0,.3.44l3.96,1.17a.5.5,0,0,0,.49-.1l1.53-2.65a7.62,7.62,0,0,0,1.73-1l2.49,1a.5.5,0,0,0,.54-.24l2-3.46a.5.5,0,0,0-.13-.56ZM12,15.5A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/>
                                        </svg>
                                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false"
                                        class="absolute right-0 z-10 mt-2 w-36 origin-top-right bg-white border border-gray-200 shadow-lg">
                                        <a href="/dashboard/sponsors/{{ $sponsor->slug }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Preview</a>
                                        <a href="/dashboard/sponsors/{{ $sponsor->slug }}/edit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                        <form action="/dashboard/sponsors/{{ $sponsor->slug }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Sponsor?',
                text: "Data yang dihapus tidak dapat dikembalikan.",
                icon: 'warning',
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

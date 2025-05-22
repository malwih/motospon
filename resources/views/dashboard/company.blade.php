@extends('dashboard.layouts.main')

@section('container')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>



<div class="p-10 sm:ml-80">
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-7xl mx-auto mt-20">
        <div class="flex justify-between flex-wrap items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        </div>

        @if(session('status'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 3000)" 
        x-show="show" 
        x-transition 
        class="relative p-4 pr-10 mt-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50"
        role="alert"
    >
        {{ session('status') }}
        
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


        <section class="mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 mt-4">Incoming Proposals</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Sponsor</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Name Community</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Name Event</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Location</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Date Event</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Status</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Actions</th>
            <th class="px-4 py-3 text-center"></th> <!-- Dropdown icon -->
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
    @foreach ($proposals as $index => $proposal)
        @if (!$proposal->hidden_from_company)
        <tr>
            <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
            <td class="px-6 py-4 text-center">{{ $proposal->name_community }}</td>
            <td class="px-6 py-4 text-center">{{ $proposal->name_event }}</td>
            <td class="px-6 py-4 text-center">{{ $proposal->location }}</td>
            <td class="px-6 py-4 text-center">{{ $proposal->date_event }}</td>
            <td class="px-6 py-4 text-center">
                @if ($proposal->is_completed)
                    <span class="text-green-600 font-semibold">Accepted</span>
                @elseif ($proposal->is_reject)
                    <span class="text-red-600 font-semibold">Rejected</span>
                @elseif ($proposal->is_active)
                    <span class="text-yellow-500 font-semibold">Active</span>
                @else
                    <span class="text-gray-500 font-semibold">Inactive</span>
                @endif
            </td>
            <td class="px-6 py-4 text-center">
                @if ($proposal->is_active && !$proposal->is_completed && !$proposal->is_reject)
                    <div class="flex justify-center space-x-2">
                        <form action="{{ route('proposals.updateStatus', $proposal->id) }}" method="POST" class="confirm-action">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm">Accept</button>
                        </form>
                        <form action="{{ route('proposals.updateStatus', $proposal->id) }}" method="POST" class="confirm-action">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm">Reject</button>
                        </form>
                    </div>
                @else
                    <span class="text-gray-500 text-sm">No Action</span>
                @endif
            </td>
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
                        <a href="{{ route('proposal.preview', $proposal->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Preview</a>
                        <form action="{{ route('proposal.hideFromCompany', $proposal->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
        @endif
    @endforeach
</tbody>

</table>

            </div>
        </section>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Proposal?',
                text: "Pastikan data sudah tidak diperlukan sebelum dihapus.",
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

    // Accept/Reject confirmation
    document.querySelectorAll('.confirm-action').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            let action = form.querySelector('input[name="action"]').value;
            let text = action === 'accept' ? 'Terima proposal ini?' : 'Tolak proposal ini?';
            let title = action === 'accept' ? 'Terima Proposal' : 'Tolak Proposal';

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

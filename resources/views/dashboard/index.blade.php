@extends('dashboard.layouts.main')

@section('container')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="p-10 sm:ml-80">
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-7xl mx-auto mt-20">
        <div class="flex justify-between flex-wrap items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        </div>

        @if(session('status'))
            <div class="p-4 mt-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
                {{ session('status') }}
            </div>
        @endif

        {{-- Add Sponsor Button --}}
        <div class="block justify-end mb-6 mt-6">
            <a href="{{ route('addsponsor') }}">
                <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg shadow">
                    + Add Sponsor
                </button>
            </a>
        </div>

        <section class="mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Submitted Proposals</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sponsor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name Community</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($proposals as $index => $proposal)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proposal->sponsor->title ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proposal->name_community }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proposal->name_event }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proposal->location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proposal->date_event }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{-- Accept / Reject buttons --}}
                                    @if ($proposal->is_active && !$proposal->is_completed && !$proposal->is_reject)
                                        <form action="{{ route('proposals.updateStatus', $proposal->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                                Accept
                                            </button>
                                        </form>

                                        <form action="{{ route('proposals.updateStatus', $proposal->id) }}" method="POST" class="inline-block ml-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                                Reject
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Preview Button --}}
                                    <div class="mt-2">
                                        <a href="{{ route('proposal.preview', $proposal->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium inline-block">
                                            Preview
                                        </a>
                                    </div>

                                    {{-- Edit Button --}}
                                    <div class="mt-2">
                                        <a href="{{ route('proposal.edit', $proposal->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium inline-block">
                                            Edit
                                        </a>
                                    </div>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('proposal.destroy', $proposal->id) }}"  method="POST" class="mt-2 inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No proposals found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form.delete-form');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Hapus Proposal?',
                text: "Pastikan data sudah tidak diperlukan sebelum dihapus.",
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
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>

@endsection

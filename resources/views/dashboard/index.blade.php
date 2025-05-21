@extends('dashboard.layouts.main')

@section('container')
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($proposals as $index => $proposal)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proposal->sponsor->title ?? 'N/A' }}</td>
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
                                    @else
                                        <span class="text-gray-400">No Action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No proposals found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection

@extends('dashboard.layouts.main')

@section('container')
<div class="pt-28 px-6 sm:ml-80">
  <h1 class="text-3xl font-bold mb-6 text-gray-800">Company Dashboard</h1>

  <div class="overflow-x-auto">
    <table class="min-w-full bg-white shadow-md rounded-xl overflow-hidden">
      <thead>
        <tr class="bg-gray-200 text-gray-700 text-left text-sm uppercase">
          <th class="p-4">No</th>
          <th class="p-4">Community</th>
          <th class="p-4">Event</th>
          <th class="p-4">Date</th>
          <th class="p-4">Action</th>
        </tr>
      </thead>
      <tbody class="text-gray-800 text-sm">
        @foreach($proposals as $proposal)
        <tr class="border-b hover:bg-gray-100">
          <td class="p-4">{{ $loop->iteration }}</td>
          <td class="p-4">{{ $proposal->name_community }}</td>
          <td class="p-4">{{ $proposal->name_event }}</td>
          <td class="p-4">{{ $proposal->date_event }}</td>
          <td class="p-4 space-x-2">
            <a href="{{ route('proposal.show', $proposal->id) }}" class="text-blue-600 hover:underline">View</a>

            <form action="{{ route('proposal.destroy', $proposal->id) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button type="submit" class="text-red-600 hover:underline">Delete</button>
            </form>

            <form action="{{ route('proposal.approve', $proposal->id) }}" method="POST" class="inline">
              @csrf
              <button type="submit" class="text-green-600 hover:underline">Approve</button>
            </form>

            <form action="{{ route('proposal.reject', $proposal->id) }}" method="POST" class="inline">
              @csrf
              <button type="submit" class="text-yellow-600 hover:underline">Reject</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

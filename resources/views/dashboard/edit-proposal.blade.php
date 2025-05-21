@extends('dashboard.layouts.main')

@section('container')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<div class="p-10 sm:ml-80">
  <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-5xl mx-auto mt-20">
    <div class="pb-4 border-b border-gray-300 mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Edit Proposal</h1>
    </div>

    <form id="updateForm" method="post" action="{{ route('proposal.update', $proposal->id) }}" enctype="multipart/form-data">
      @csrf
      

      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
        <div class="px-6 py-6">
          <dl class="divide-y divide-gray-200">

            {{-- Select Sponsor --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Select Sponsor</dt>
              <dd class="col-span-2">
                <select name="sponsor_id" id="sponsor" class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('sponsor_id') border-red-500 @enderror" required>
                  <option value="">Select a Sponsor</option>
                  @foreach ($sponsors as $sponsor)
                  <option value="{{ $sponsor->id }}" data-category="{{ $sponsor->category }}" data-event="{{ $sponsor->event }}" {{ $sponsor->id == old('sponsor_id', $proposal->sponsor_id) ? 'selected' : '' }}>
                    {{ $sponsor->title }}
                  </option>
                  @endforeach
                </select>
                @error('sponsor_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Category --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Category</dt>
              <dd class="col-span-2">
                <input type="text" name="category" id="category" value="{{ old('category', $proposal->sponsor->category ?? '') }}" readonly class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm">
              </dd>
            </div>

            {{-- Event --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Event</dt>
              <dd class="col-span-2">
                <input type="text" name="event" id="event" value="{{ old('event', $proposal->sponsor->event ?? '') }}" readonly class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm">
              </dd>
            </div>

            {{-- Name Community --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Name Community</dt>
              <dd class="col-span-2">
                <input type="text" name="name_community" value="{{ old('name_community', $proposal->name_community) }}" class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('name_community') border-red-500 @enderror" required>
                @error('name_community')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Name Event --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Name Event</dt>
              <dd class="col-span-2">
                <input type="text" name="name_event" value="{{ old('name_event', $proposal->name_event) }}" class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('name_event') border-red-500 @enderror" required>
                @error('name_event')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Location --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Location</dt>
              <dd class="col-span-2">
                <input type="text" name="location" value="{{ old('location', $proposal->location) }}" class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('location') border-red-500 @enderror" required>
                @error('location')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Date --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Date</dt>
              <dd class="col-span-2">
                <input type="text" name="date_event" id="date" value="{{ old('date_event', $proposal->date_event) }}" class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('date') border-red-500 @enderror" required>
                @error('date_event')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Feedback / Benefit --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-start">
              <dt class="text-sm font-medium text-gray-600">Feedback / Benefit Sponsor</dt>
              <dd class="col-span-2">
                <textarea name="feedback_benefit" rows="3" class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('feedback_benefit') border-red-500 @enderror" required>{{ old('feedback_benefit', $proposal->feedback_benefit) }}</textarea>
              </dd>
            </div>

            {{-- Budget Estimate Plan --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-start">
              <dt class="text-sm font-medium text-gray-600">Budget Estimate Plan</dt>
              <dd class="col-span-2">
                <table class="w-full border border-gray-300 mb-2" id="budget-table">
                  <thead>
                    <tr class="bg-gray-100">
                      <th class="border px-2 py-1 text-sm">Item</th>
                      <th class="border px-2 py-1 text-sm">Description</th>
                      <th class="border px-2 py-1 text-sm">Estimated Cost</th>
                      <th class="border px-2 py-1 text-sm text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($budget_items as $i => $item)
                    <tr>
                      <td><input type="text" name="budget_items[]" value="{{ $item }}" class="border rounded px-2 py-1 w-full" required></td>
                      <td><input type="text" name="budget_descriptions[]" value="{{ $budget_descriptions[$i] ?? '' }}" class="border rounded px-2 py-1 w-full" required></td>
                      <td><input type="number" name="budget_costs[]" value="{{ $budget_costs[$i] ?? '' }}" class="border rounded px-2 py-1 w-full" required></td>
                      <td class="text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 font-bold">&times;</button></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <button type="button" onclick="addBudgetRow()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">+ Add Row</button>
              </dd>
            </div>

            {{-- Rundown Event --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-start">
              <dt class="text-sm font-medium text-gray-600">Rundown Event</dt>
              <dd class="col-span-2">
                <table class="w-full border border-gray-300 mb-2" id="rundown-table">
                  <thead>
                    <tr class="bg-gray-100">
                      <th class="border px-2 py-1 text-sm">Time</th>
                      <th class="border px-2 py-1 text-sm">Activity</th>
                      <th class="border px-2 py-1 text-sm text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($rundown_times as $i => $time)
                    <tr>
                      <td><input type="text" name="rundown_times[]" value="{{ $time }}" class="border rounded px-2 py-1 w-full" required></td>
                      <td><input type="text" name="rundown_activities[]" value="{{ $rundown_activities[$i] ?? '' }}" class="border rounded px-2 py-1 w-full" required></td>
                      <td class="text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 font-bold">&times;</button></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <button type="button" onclick="addRundownRow()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">+ Add Row</button>
              </dd>
            </div>

          </dl>
        </div>
      </div>

      <button type="submit" class="block w-full mt-4 py-3 rounded-2xl text-center bg-orange-500 text-white font-semibold hover:bg-orange-700 transition duration-300">
        Update
      </button>

      <a href="/dashboard/sponsors" class="block w-full mt-4 py-3 rounded-2xl text-center bg-blue-500 text-white font-semibold hover:bg-blue-700 transition duration-300">
        Back
      </a>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#date", {
      altInput: true,
      altFormat: "l, d F Y",
      dateFormat: "Y-m-d",
      locale: "id"
    });

    const sponsorSelect = document.getElementById('sponsor');
    const categoryInput = document.getElementById('category');
    const eventInput = document.getElementById('event');

    sponsorSelect.addEventListener('change', function () {
      const selected = sponsorSelect.options[sponsorSelect.selectedIndex];
      categoryInput.value = selected.getAttribute('data-category') || '';
      eventInput.value = selected.getAttribute('data-event') || '';
    });
  });

  function addBudgetRow() {
    const tbody = document.querySelector('#budget-table tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
      <td><input type="text" name="budget_items[]" class="border rounded px-2 py-1 w-full" required></td>
      <td><input type="text" name="budget_descriptions[]" class="border rounded px-2 py-1 w-full" required></td>
      <td><input type="number" name="budget_costs[]" class="border rounded px-2 py-1 w-full" required></td>
      <td class="text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 font-bold">&times;</button></td>
    `;
    tbody.appendChild(row);
  }

  function addRundownRow() {
    const tbody = document.querySelector('#rundown-table tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
      <td><input type="text" name="rundown_times[]" class="border rounded px-2 py-1 w-full" required></td>
      <td><input type="text" name="rundown_activities[]" class="border rounded px-2 py-1 w-full" required></td>
      <td class="text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 font-bold">&times;</button></td>
    `;
    tbody.appendChild(row);
  }

  function removeRow(btn) {
    const row = btn.closest('tr');
    if (row && row.parentNode.rows.length > 1) {
      row.remove();
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('updateForm');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Update Proposal?',
                text: "Pastikan semua data sudah benar sebelum diupdate.",
                icon: 'question',
                showCancelButton: true,
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
</script>
@endsection

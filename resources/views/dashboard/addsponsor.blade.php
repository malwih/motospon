@extends('dashboard.layouts.main')

@section('container')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="p-10 sm:ml-80">
  <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-5xl mx-auto mt-20">
    <div class="pb-4 border-b border-gray-300 mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Add Sponsor</h1>
    </div>

    <form method="post" action="{{ route('sponsors.take') }}" enctype="multipart/form-data">
      @csrf

      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
        <div class="px-6 py-6">
          <dl class="divide-y divide-gray-200">

            {{-- Select Sponsor --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Select Sponsor</dt>
              <dd class="col-span-2">
                <select name="sponsor_id" id="sponsor"
                  class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('sponsor_id') border-red-500 @enderror"
                  required>
                  <option value="">Select a Sponsor</option>
                  @foreach ($sponsors as $sponsor)
                  <option value="{{ $sponsor->id }}" data-category="{{ $sponsor->category }}" data-event="{{ $sponsor->event }}">{{ $sponsor->title }}</option>
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
                <input type="text" name="category" id="category"
                  class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('category') border-red-500 @enderror"
                  readonly>
                @error('category')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Event --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Event</dt>
              <dd class="col-span-2">
                <input type="text" name="event" id="event"
                  class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('event') border-red-500 @enderror"
                  readonly>
                @error('event')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Name Community --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Name Community</dt>
              <dd class="col-span-2">
                <input type="text" name="name_community" id="name_community"
                  placeholder="Nama komunitas yang mengajukan sponsorship"
                  class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('name_community') border-red-500 @enderror"
                  required>
                @error('name_community')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Name Event --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Name Event</dt>
              <dd class="col-span-2">
                <input type="text" name="name_event" id="name_event"
                  placeholder="Nama event seperti Anniversary atau lainnya"
                  class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('name_event') border-red-500 @enderror"
                  required>
                @error('name_event')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Location --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Location</dt>
              <dd class="col-span-2">
                <input type="text" name="location" id="location"
                  placeholder="Lokasi event seperti Rest Area 72 Lembang"
                  class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('location') border-red-500 @enderror"
                  required>
                @error('location')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Date --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Date</dt>
              <dd class="col-span-2">
                <input type="text" name="date" id="date"
                  class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('date') border-red-500 @enderror"
                  required>
                @error('date')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Feedback / Benefit --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-start">
              <dt class="text-sm font-medium text-gray-600">Feedback / Benefit Sponsor</dt>
              <dd class="col-span-2">
                <textarea name="feedback_benefit" id="feedback_benefit" rows="3"
                  placeholder="Feedback atau benefit untuk sponsor"
                  class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('feedback_benefit') border-red-500 @enderror"
                  required></textarea>
                @error('feedback_benefit')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Budget Estimate Plan --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-start">
              <dt class="text-sm font-medium text-gray-600">Budget Estimate Plan</dt>
              <dd class="col-span-2">
                <table class="w-full border border-gray-300 mb-2" id="budget-table">
                  <thead>
                    <tr class="bg-gray-100">
                      <th class="border border-gray-300 px-2 py-1 text-left text-sm">Item</th>
                      <th class="border border-gray-300 px-2 py-1 text-left text-sm">Description</th>
                      <th class="border border-gray-300 px-2 py-1 text-left text-sm">Estimated Cost</th>
                      <th class="border border-gray-300 px-2 py-1 text-center text-sm">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="text" name="budget_items[]" class="border border-gray-300 rounded px-2 py-1 w-full" required></td>
                      <td><input type="text" name="budget_descriptions[]" class="border border-gray-300 rounded px-2 py-1 w-full" required></td>
                      <td><input type="number" name="budget_costs[]" class="border border-gray-300 rounded px-2 py-1 w-full" min="0" required></td>
                      <td class="text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800 font-bold">&times;</button></td>
                    </tr>
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
                      <th class="border border-gray-300 px-2 py-1 text-left text-sm">Time</th>
                      <th class="border border-gray-300 px-2 py-1 text-left text-sm">Activity</th>
                      <th class="border border-gray-300 px-2 py-1 text-center text-sm">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="text" name="rundown_times[]" class="border border-gray-300 rounded px-2 py-1 w-full" placeholder="Jam mulai - jam selesai" required></td>
                      <td><input type="text" name="rundown_activities[]" class="border border-gray-300 rounded px-2 py-1 w-full" required></td>
                      <td class="text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800 font-bold">&times;</button></td>
                    </tr>
                  </tbody>
                </table>
                <button type="button" onclick="addRundownRow()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">+ Add Row</button>
              </dd>
            </div>

          </dl>
        </div>
      </div>

      <button type="submit" formaction="{{ route('dashboard.previewProposal') }}" class="block w-full mt-4 py-3 rounded-2xl text-center bg-orange-500 text-white font-semibold hover:bg-orange-700 transition duration-300">
        Next
      </button>

      <a href="/dashboard/sponsors" class="block w-full mt-4 py-3 rounded-2xl text-center bg-blue-500 text-white font-semibold hover:bg-blue-700 transition duration-300">
        Back
      </a>
    </form>
  </div>
</div>

<!-- Flatpickr Scripts -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#date", {
      altInput: true,
      altFormat: "l, d F Y", // Tampilkan
      dateFormat: "Y-m-d",   // Format kirim ke DB
      locale: "id",
      monthSelectorType: "dropdown",
      yearSelectorType: "dropdown",
      allowInput: true
    });

    const sponsorSelect = document.getElementById('sponsor');
    const categoryInput = document.getElementById('category');
    const eventInput = document.getElementById('event');

    sponsorSelect.addEventListener('change', function () {
      const selectedOption = sponsorSelect.options[sponsorSelect.selectedIndex];
      categoryInput.value = selectedOption.getAttribute('data-category') || '';
      eventInput.value = selectedOption.getAttribute('data-event') || '';
    });
  });

  function addBudgetRow() {
    const tbody = document.querySelector('#budget-table tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
      <td><input type="text" name="budget_items[]" class="border border-gray-300 rounded px-2 py-1 w-full" required></td>
      <td><input type="text" name="budget_descriptions[]" class="border border-gray-300 rounded px-2 py-1 w-full" required></td>
      <td><input type="number" name="budget_costs[]" class="border border-gray-300 rounded px-2 py-1 w-full" min="0" required></td>
      <td class="text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800 font-bold">&times;</button></td>
    `;
    tbody.appendChild(newRow);
  }

  function addRundownRow() {
    const tbody = document.querySelector('#rundown-table tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
      <td><input type="text" name="rundown_times[]" class="border border-gray-300 rounded px-2 py-1 w-full" placeholder="Jam mulai - jam selesai" required></td>
      <td><input type="text" name="rundown_activities[]" class="border border-gray-300 rounded px-2 py-1 w-full" required></td>
      <td class="text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800 font-bold">&times;</button></td>
    `;
    tbody.appendChild(newRow);
  }

  function removeRow(button) {
    const row = button.closest('tr');
    if (row.parentNode.rows.length > 1) {
      row.remove();
    }
  }
</script>
@endsection

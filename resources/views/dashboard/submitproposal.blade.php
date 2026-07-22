@extends('dashboard.layouts.main')

@section('title', 'Submit Proposal - Motospon')

@section('container')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">

<div class="w-full p-10 sm:ml-80">
  <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-5xl mx-auto mt-20">
    <div class="pb-4 border-b border-gray-300 mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Submit Proposal</h1>
    </div>

    <form id="proposalForm" method="post" action="{{ route('proposals.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
        <div class="px-6 py-6">
          <dl class="divide-y divide-gray-200">

            {{-- Select Sponsor --}}
            @if($selectedSponsorship)
                <input type="hidden" name="sponsorship_id" value="{{ $selectedSponsorship->id }}">
                <div class="py-4 grid grid-cols-3 gap-4 items-center">
                    <dt class="text-sm font-medium text-gray-600">Sponsor</dt>
                    <dd class="col-span-2">
                        <input type="text" value="{{ $selectedSponsorship->title }}" 
                               class="border border-gray-300 bg-gray-50 rounded-md shadow-sm block w-full py-2 px-3 text-sm" readonly>
                    </dd>
                </div>
                <input type="hidden" id="selected-category" value="{{ $selectedSponsorship->category }}">
                <input type="hidden" id="selected-event" value="{{ $selectedSponsorship->event }}">
            @else
                <div class="py-4 grid grid-cols-3 gap-4 items-center">
                    <dt class="text-sm font-medium text-gray-600">Select Sponsor</dt>
                    <dd class="col-span-2">
                        <select name="sponsorship_id" id="sponsorship"
                                class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('sponsor_id') border-red-500 @enderror"
                                required>
                            <option value="">Select a Sponsor</option>
                            @foreach ($sponsorships as $sponsorship)
                                <option value="{{ $sponsorship->id }}" data-category="{{ $sponsorship->category }}" data-event="{{ $sponsorship->event }}">{{ $sponsorship->title }}</option>
                            @endforeach
                        </select>
                        @error('sponsorship_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </dd>
                </div>
            @endif

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
                  placeholder="Pasukan Aerox"
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
                  placeholder="Sunmori"
                  class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('name_event') border-red-500 @enderror"
                  required>
                @error('name_event')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- Location --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-start">
              <dt class="text-sm font-medium text-gray-600">Location</dt>
              <dd class="col-span-2 space-y-2">
                <input type="text" name="location" id="location"
                  placeholder="Rest Area 72 Lembang"
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
                <input type="text" name="date" id="date" value="{{ old('date') }}" class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('date') border-red-500 @enderror" required>
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
                  placeholder="Logo sponsor akan di tampilkan di pamflet"
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
                      <td>
                        <div class="flex space-x-1">
                          <input type="text" name="rundown_start_times[]" class="timepicker border border-gray-300 rounded px-2 py-1 w-1/2" placeholder="Mulai" required>
                          <input type="text" name="rundown_end_times[]" class="timepicker border border-gray-300 rounded px-2 py-1 w-1/2" placeholder="Selesai" required>
                        </div>
                        <input type="hidden" name="rundown_times[]" class="time-range">
                      </td>
                      <td><input type="text" name="rundown_activities[]" class="border border-gray-300 rounded px-2 py-1 w-full" required></td>
                      <td class="text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800 font-bold">&times;</button></td>
                    </tr>
                  </tbody>
                </table>
                <button type="button" onclick="addRundownRow()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">+ Add Row</button>
              </dd>
            </div>

            {{-- Event Documentations --}}
            <div class="py-4 grid grid-cols-3 gap-4 items-start">
              <dt class="text-sm font-medium text-gray-600">Event Documentations</dt>
              <dd class="col-span-2">
                <div class="space-y-4">
                  <div class="flex items-center justify-center w-full">
                    <label for="event_documentations" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                      <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500 mt-2">PNG, JPG, JPEG, maks. 5MB per file</p>
                      </div>
                      <input id="event_documentations" name="event_documentations[]" type="file" class="hidden" multiple accept="image/*" />
                    </label>
                  </div>
                  <div id="file-preview" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                    <!-- Preview akan muncul di sini -->
                  </div>
                  <p class="text-xs text-gray-500 mt-2">Upload foto dokumentasi acara (opsional)</p>
                </div>
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <div class="flex justify-between mt-6 space-x-4">
        <a href="{{ url()->previous() }}" class="w-1/2 py-2.5 rounded-2xl text-center bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition duration-300">
          Back
        </a>
        <button type="button" onclick="saveFormData(); document.getElementById('proposalForm').setAttribute('action', '{{ route('dashboard.previewProposal') }}'); document.getElementById('proposalForm').submit();" class="w-1/2 py-2.5 rounded-2xl text-center bg-orange-500 text-white font-semibold hover:bg-orange-600 transition duration-300">
          Next
        </button>
        <button type="submit" id="submitBtn" class="hidden">Submit</button>
      </div>
    </form>
    
    <!-- Image Preview Modal -->
    <div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
      <div class="relative bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <button type="button" id="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        <div class="p-2 h-full flex items-center justify-center">
          <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-[80vh] mx-auto object-contain">
        </div>
      </div>
    </div>

{{-- ============================================================ --}}
{{-- LIBRARY YANG DIPERLUKAN --}}
{{-- ============================================================ --}}
{{-- Library Flatpickr untuk date & time picker --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- Lokalisasi Flatpickr ke Bahasa Indonesia --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

{{-- ============================================================ --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================ --}}
<script>
  // Fungsi untuk menyimpan data form ke sessionStorage
  function saveFormData() {
    // Simpan data form dasar
    const formData = {
      // Simpan data sponsor jika ada
      sponsorship_id: document.getElementById('sponsorship')?.value || '',
      name_community: document.getElementById('name_community').value,
      name_event: document.getElementById('name_event').value,
      location: document.getElementById('location').value,
      date: document.getElementById('date').value,
      feedback_benefit: document.getElementById('feedback_benefit').value,
      // Simpan data budget estimate plan
      budget_rows: [],
      // Simpan data rundown event
      rundown_rows: []
    };

    // Simpan data budget estimate plan
    document.querySelectorAll('#budget-table tbody tr').forEach(row => {
      const item = row.querySelector('input[name="budget_items[]"]')?.value || '';
      const desc = row.querySelector('input[name="budget_descriptions[]"]')?.value || '';
      const cost = row.querySelector('input[name="budget_costs[]"]')?.value || '';
      if (item || desc || cost) {
        formData.budget_rows.push({ item, desc, cost });
      }
    });

    // Simpan data rundown event
    document.querySelectorAll('#rundown-table tbody tr').forEach(row => {
      const startTime = row.querySelector('input[name="rundown_start_times[]"]')?.value || '';
      const endTime = row.querySelector('input[name="rundown_end_times[]"]')?.value || '';
      const activity = row.querySelector('input[name="rundown_activities[]"]')?.value || '';
      if (startTime || endTime || activity) {
        formData.rundown_rows.push({ startTime, endTime, activity });
      }
    });

    sessionStorage.setItem('proposalFormData', JSON.stringify(formData));
  }

  // Fungsi untuk memulihkan data form dari sessionStorage
  function restoreFormData() {
    const savedData = sessionStorage.getItem('proposalFormData');
    if (savedData) {
      const formData = JSON.parse(savedData);
      
      // Pulihkan data form dasar
      Object.keys(formData).filter(key => !['budget_rows', 'rundown_rows', 'sponsorship_id'].includes(key)).forEach(key => {
        const element = document.getElementById(key);
        if (element) {
          element.value = formData[key];
        }
      });

      // Pulihkan data sponsor
      if (formData.sponsorship_id) {
        const sponsorSelect = document.getElementById('sponsorship');
        if (sponsorSelect) {
          sponsorSelect.value = formData.sponsorship_id;
          
          // Trigger change event to update category and event fields
          const event = new Event('change');
          sponsorSelect.dispatchEvent(event);
        }
      }

      // Pulihkan data budget estimate plan
      if (formData.budget_rows && formData.budget_rows.length > 0) {
        // Hapus baris yang sudah ada kecuali yang pertama
        const budgetTbody = document.querySelector('#budget-table tbody');
        while (budgetTbody.rows.length > 1) {
          budgetTbody.deleteRow(1);
        }
        
        // Isi baris pertama dengan data yang ada
        const firstRow = budgetTbody.rows[0];
        if (firstRow && formData.budget_rows[0]) {
          firstRow.querySelector('input[name="budget_items[]"]').value = formData.budget_rows[0].item || '';
          firstRow.querySelector('input[name="budget_descriptions[]"]').value = formData.budget_rows[0].desc || '';
          firstRow.querySelector('input[name="budget_costs[]"]').value = formData.budget_rows[0].cost || '';
        }

        // Tambahkan baris tambahan jika ada
        for (let i = 1; i < formData.budget_rows.length; i++) {
          addBudgetRow();
          const row = budgetTbody.rows[budgetTbody.rows.length - 1];
          row.querySelector('input[name="budget_items[]"]').value = formData.budget_rows[i].item || '';
          row.querySelector('input[name="budget_descriptions[]"]').value = formData.budget_rows[i].desc || '';
          row.querySelector('input[name="budget_costs[]"]').value = formData.budget_rows[i].cost || '';
        }
      }

      // Pulihkan data rundown event
      if (formData.rundown_rows && formData.rundown_rows.length > 0) {
        // Hapus baris yang sudah ada kecuali yang pertama
        const rundownTbody = document.querySelector('#rundown-table tbody');
        while (rundownTbody.rows.length > 1) {
          rundownTbody.deleteRow(1);
        }
        
        // Isi baris pertama dengan data yang ada
        const firstRow = rundownTbody.rows[0];
        if (firstRow && formData.rundown_rows[0]) {
          firstRow.querySelector('input[name="rundown_start_times[]"]').value = formData.rundown_rows[0].startTime || '';
          firstRow.querySelector('input[name="rundown_end_times[]"]').value = formData.rundown_rows[0].endTime || '';
          firstRow.querySelector('input[name="rundown_activities[]"]').value = formData.rundown_rows[0].activity || '';
          
          // Update time range
          const startTime = formData.rundown_rows[0].startTime || '';
          const endTime = formData.rundown_rows[0].endTime || '';
          const timeRangeInput = firstRow.querySelector('.time-range');
          if (timeRangeInput && startTime && endTime) {
            timeRangeInput.value = `${startTime} - ${endTime}`;
          }
        }

        // Tambahkan baris tambahan jika ada
        for (let i = 1; i < formData.rundown_rows.length; i++) {
          addRundownRow();
          const row = rundownTbody.rows[rundownTbody.rows.length - 1];
          const rowData = formData.rundown_rows[i];
          
          row.querySelector('input[name="rundown_start_times[]"]').value = rowData.startTime || '';
          row.querySelector('input[name="rundown_end_times[]"]').value = rowData.endTime || '';
          row.querySelector('input[name="rundown_activities[]"]').value = rowData.activity || '';
          
          // Update time range
          const timeRangeInput = row.querySelector('.time-range');
          if (timeRangeInput && rowData.startTime && rowData.endTime) {
            timeRangeInput.value = `${rowData.startTime} - ${rowData.endTime}`;
          }
        }
      }

      // Hapus data yang sudah dimuat
      sessionStorage.removeItem('proposalFormData');
    }
  }

  // Fungsi untuk mengisi otomatis kategori dan event berdasarkan sponsorship yang dipilih
  document.addEventListener('DOMContentLoaded', function() {
    console.log('Halaman selesai dimuat');
    
    // Pulihkan data form jika ada
    restoreFormData();
    
    // Fungsi untuk mengisi field kategori dan event
    function fillCategoryAndEvent(category, event) {
      const categoryField = document.getElementById('category');
      const eventField = document.getElementById('event');
      
      if (categoryField) categoryField.value = category || '';
      if (eventField) eventField.value = event || '';
      
      console.log('Set category to:', category);
      console.log('Set event to:', event);
    }
    
    // Jika ada sponsorship yang dipilih dari halaman sebelumnya
    const selectedCategory = document.getElementById('selected-category');
    const selectedEvent = document.getElementById('selected-event');
    
    console.log('Selected Category:', selectedCategory);
    console.log('Selected Event:', selectedEvent);
    
    if (selectedCategory && selectedEvent) {
      fillCategoryAndEvent(selectedCategory.value, selectedEvent.value);
    }
    
    // Handle pemilihan sponsor secara manual
    const sponsorshipSelect = document.getElementById('sponsorship');
    if (sponsorshipSelect) {
      sponsorshipSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const category = selectedOption.getAttribute('data-category');
        const event = selectedOption.getAttribute('data-event');
        
        if (category && event) {
          fillCategoryAndEvent(category, event);
        }
      });
    }
  });
  
  // Initialize time pickers
  function initTimePickers(container = document) {
    flatpickr(container.querySelectorAll('.timepicker'), {
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      minuteIncrement: 30,
      onChange: function(selectedDates, dateStr, instance) {
        // Update the hidden input with the time range
        const row = instance.element.closest('tr');
        const startTime = row.querySelector('input[name="rundown_start_times[]"]').value;
        const endTime = row.querySelector('input[name="rundown_end_times[]"]').value;
        const timeRangeInput = row.querySelector('.time-range');
        
        if (startTime && endTime) {
          timeRangeInput.value = `${startTime} - ${endTime}`;
        }
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    // Date picker for event date
    flatpickr("#date", {
      altInput: true,
      altFormat: "l, d F Y",
      dateFormat: "Y-m-d",
      locale: "id"
    });
    
    // Inisialisasi time picker untuk baris yang sudah ada
    initTimePickers();
  });

    // Menangani perubahan pilihan sponsor
    const sponsorshipSelect = document.getElementById('sponsorship');
    if (sponsorshipSelect) {
        sponsorshipSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('category').value = selectedOption.dataset.category || '';
            document.getElementById('event').value = selectedOption.dataset.event || '';
        });
    }

    // Menambahkan baris baru pada tabel anggaran
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

    // Menambahkan baris baru pada tabel rundown acara
    function addRundownRow() {
        const tbody = document.querySelector('#rundown-table tbody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
              <div class="flex space-x-1">
                <input type="text" name="rundown_start_times[]" class="timepicker border border-gray-300 rounded px-2 py-1 w-1/2" placeholder="Mulai" required>
                <input type="text" name="rundown_end_times[]" class="timepicker border border-gray-300 rounded px-2 py-1 w-1/2" placeholder="Selesai" required>
              </div>
              <input type="hidden" name="rundown_times[]" class="time-range">
            </td>
            <td><input type="text" name="rundown_activities[]" class="border border-gray-300 rounded px-2 py-1 w-full" required></td>
            <td class="text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800 font-bold">&times;</button></td>
        `;
        tbody.appendChild(newRow);
        
        // Initialize timepicker for the new row
        initTimePickers(newRow);
    }

    // Menghapus baris dari tabel
    function removeRow(button) {
        const row = button.closest('tr');
        if (row && row.parentNode.rows.length > 1) {
            row.remove();
        }
    }

  // Image Preview Modal Functionality
  const modal = document.getElementById('imagePreviewModal');
  const modalImg = document.getElementById('modalImage');
  const closeModal = document.getElementById('closeModal');
  
  // Close modal when clicking the close button
  closeModal.addEventListener('click', function() {
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto'; // Re-enable scrolling
  });
  
  // Close modal when clicking outside the image
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto'; // Re-enable scrolling
    }
  });
  
  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto'; // Re-enable scrolling
    }
  });

  // Handle file upload preview
  const fileInput = document.getElementById('event_documentations');
  const filePreview = document.getElementById('file-preview');
  const previewBtn = document.getElementById('previewBtn');
  const submitBtn = document.getElementById('submitBtn');
  const proposalForm = document.getElementById('proposalForm');
  let filesToUpload = [];

  // Function to create a preview element for an uploaded file
  function createPreviewElement(file, fileUrl) {
    const previewId = 'file-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
    const preview = document.createElement('div');
    preview.id = previewId;
    preview.className = 'relative group';
    
    preview.innerHTML = `
      <div class="relative h-40 overflow-hidden rounded-lg border border-gray-200 group-hover:border-orange-500 transition-colors">
        <img src="${fileUrl}" alt="Preview ${file.name}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
          <button type="button" class="view-image p-2 bg-white bg-opacity-80 rounded-full text-gray-700 hover:bg-opacity-100 transition-all" data-image-src="${fileUrl}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
          </button>
          <button type="button" class="remove-image p-2 bg-white bg-opacity-80 rounded-full text-red-600 hover:bg-opacity-100 transition-all" data-preview-id="${previewId}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 truncate">
          ${file.name}
        </div>
      </div>
    `;
    
    // Store file data in the preview element
    preview._fileData = {
      name: file.name,
      size: file.size,
      lastModified: file.lastModified,
      file: file
    };
    
    return preview;
  }

  // Function to update the file input with current files
  function updateFileInput() {
    const dataTransfer = new DataTransfer();
    filesToUpload.forEach(file => {
      dataTransfer.items.add(file);
    });
    fileInput.files = dataTransfer.files;
    console.log('Updated file input with', filesToUpload.length, 'files');
  }

  // Handle file selection
  if (fileInput) {
    fileInput.addEventListener('change', function(e) {
      if (!e.target.files.length) return;
      
      const newFiles = Array.from(e.target.files);
      
      newFiles.forEach(file => {
        // Check if file is already in the filesToUpload array
        const fileExists = filesToUpload.some(
          f => f.name === file.name && 
               f.size === file.size && 
               f.lastModified === file.lastModified
        );
        
        if (fileExists) {
          console.log('File already exists:', file.name);
          return;
        }
        
        if (!file.type.match('image.*')) {
          alert(`File ${file.name} bukan gambar. Hanya file gambar yang diperbolehkan.`);
          return;
        }
        
        if (file.size > 5 * 1024 * 1000) { // 5MB
          alert(`File ${file.name} melebihi ukuran maksimal 5MB.`);
          return;
        }
        
        // Add file to filesToUpload array
        filesToUpload.push(file);
        
        // Create preview
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = createPreviewElement(file, e.target.result);
          filePreview.appendChild(preview);
          console.log('Added preview for file:', file.name);
        };
        reader.readAsDataURL(file);
      });
      
      // Reset the file input to allow selecting the same file again
      this.value = '';
      
      // Update the file input
      updateFileInput();
    });
  }
  
  // Handle remove image button click
  // Handle remove image button click
  document.addEventListener('click', function(e) {
    const removeBtn = e.target.closest('.remove-image');
    if (removeBtn) {
      e.preventDefault();
      e.stopPropagation();
      
      const previewId = removeBtn.dataset.previewId;
      const previewElement = document.getElementById(previewId);
      
      if (!previewElement || !previewElement._fileData) {
        console.error('Preview element or file data not found');
        return;
      }
      
      const { name, size, lastModified } = previewElement._fileData;
      
      console.log('Removing file:', { name, size, lastModified });
      
      // Remove from filesToUpload array
      const initialCount = filesToUpload.length;
      filesToUpload = filesToUpload.filter(
        file => !(file.name === name && file.size === size && file.lastModified === lastModified)
      );
      
      console.log(`Removed ${initialCount - filesToUpload.length} file(s)`);
      
      // Remove preview element
      if (previewElement && previewElement.parentNode) {
        previewElement.remove();
        console.log('Removed preview element');
      }
      
      // Update the file input
      updateFileInput();
      return; // Exit early after handling remove
    }
    
    // Handle view image button click
    const viewBtn = e.target.closest('.view-image');
    if (viewBtn) {
      e.preventDefault();
      e.stopPropagation();
      
      const imgSrc = viewBtn.getAttribute('data-image-src');
      const modal = document.getElementById('imagePreviewModal');
      const modalImg = document.getElementById('modalImage');
      
      if (modal && modalImg) {
        modalImg.src = imgSrc;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }
      return;
    }
    
    // Handle modal close
    const closeModal = e.target.closest('#closeModal');
    if (closeModal) {
      e.preventDefault();
      const modal = document.getElementById('imagePreviewModal');
      if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }
      return;
    }
    
    // Close modal when clicking outside the image
    const modal = document.getElementById('imagePreviewModal');
    const modalContent = document.querySelector('#imagePreviewModal > div');
    if (modal && !modal.classList.contains('hidden') && !modalContent.contains(e.target)) {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }
  });
  
  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      const modal = document.getElementById('imagePreviewModal');
      if (modal && !modal.classList.contains('hidden')) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }
    }
  });
  
  // Handle form submission
  if (proposalForm) {
    proposalForm.addEventListener('submit', function(e) {
      // If it's a preview submission, handle it specially
      if (this.getAttribute('action') === '{{ route('dashboard.previewProposal') }}') {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Add all files to FormData
        filesToUpload.forEach((file, index) => {
          formData.append('event_documentations[]', file);
        });
        
        // The form will be submitted normally with the updated FormData
        // We don't need to prevent default or return false here
      }
    });
    
    // Handle preview button click
    if (previewBtn) {
      previewBtn.addEventListener('click', function() {
        // Change form action to preview route
        proposalForm.setAttribute('action', '{{ route('dashboard.previewProposal') }}');
      });
    }
  }
</script>
@endsection

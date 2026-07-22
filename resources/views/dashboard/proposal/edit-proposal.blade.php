@php
    $activePage = 'dashboard';
@endphp

@extends('dashboard.layouts.main')

@section('title', 'Edit Proposal - Motospon')

{{-- 
    Menambahkan script khusus untuk halaman ini
    - Script ini akan dimuat di bagian head dokumen
    - Mengatur atribut data-dashboard-active untuk styling khusus
--}}
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.body.setAttribute('data-dashboard-active', 'true');
  });
</script>
@endpush

{{-- ============================================================ --}}
{{-- KONTEN UTAMA --}}
{{-- ============================================================ --}}
{{-- 
    Bagian utama dari halaman edit proposal
    - Menggunakan padding dan margin yang responsif
    - Menyesuaikan lebar konten untuk tampilan yang optimal
--}}
@section('container')
{{-- 
    Memasukkan CSS yang diperlukan
    - Flatpickr untuk date/time picker
    - SweetAlert2 untuk notifikasi yang lebih baik
--}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">

{{-- Memasukkan library SweetAlert2 untuk notifikasi --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


{{-- 
    Kontainer utama halaman edit proposal
    - Menggunakan lebar penuh dengan padding responsif
    - Margin kiri menyesuaikan lebar sidebar
--}}
<div class="w-full p-10 sm:ml-80">
  <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-5xl mx-auto mt-20">
    {{-- 
    Header halaman
    - Menampilkan judul halaman
    - Garis bawah sebagai pemisah visual
--}}
    <div class="pb-4 border-b border-gray-300 mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Edit Proposal</h1>
    </div>

    {{-- 
    Form untuk mengedit proposal
    - Menggunakan method POST dengan route ke proposal.update
    - Mendukung upload file dengan enctype multipart/form-data
--}}
    <form id="updateForm" method="post" action="{{ route('proposal.update', $proposal->id) }}" enctype="multipart/form-data">
      @csrf
      
      {{-- 
    Kontainer utama form
    - Background putih dengan bayangan dan border
    - Border radius untuk tampilan yang lebih lembut
--}}
      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
        <div class="px-6 py-6">
          {{-- 
    Daftar field form
    - Menggunakan description list untuk tata letak yang rapi
    - Setiap field dipisahkan dengan garis horizontal
--}}
          <dl class="divide-y divide-gray-200">

            {{-- 
    Field: Pilih Sponsor
    - Dropdown untuk memilih sponsor yang sudah ada
    - Nilai default diambil dari data proposal yang sedang diedit
--}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Select Sponsor</dt>
              <dd class="col-span-2">
                {{-- 
    Dropdown untuk memilih sponsor
    - Menampilkan daftar sponsor yang tersedia
    - Nilai default sesuai dengan sponsor yang dipilih sebelumnya
--}}
                <select name="sponsorship_id" id="sponsorship" class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('sponsorship_id') border-red-500 @enderror" readonly required disabled>
                  <option value="">Select a Sponsor</option>
                  @foreach ($sponsorships as $sponsorship)
                  {{-- 
    Opsi sponsor
    - Setiap opsi menyimpan data category dan event sebagai atribut data
    - Data ini akan digunakan untuk mengisi field terkait
--}}
                  <option value="{{ $sponsorship->id }}" 
                          data-category="{{ $sponsorship->category }}" 
                          data-event="{{ $sponsorship->event }}" 
                          {{ $sponsorship->id == old('sponsorship_id', $proposal->sponsorship_id) ? 'selected' : '' }}>
                    {{ $sponsorship->title }}
                  </option>
                  @endforeach
                </select>
                @error('sponsorship_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- 
    Field: Kategori Sponsor
    - Menampilkan kategori sponsor yang dipilih
    - Input readonly karena nilainya diisi otomatis
--}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Category</dt>
              <dd class="col-span-2">
                {{-- 
    Input Kategori
    - Nilai diambil dari data sponsor yang dipilih
    - Tampilan dinamis berdasarkan pilihan sponsor
--}}
                <input type="text" name="category" id="category" 
                       value="{{ old('category', $proposal->sponsorship->category ?? '') }}" 
                       readonly 
                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm" 
                       disabled>
              </dd>
            </div>

            {{-- 
    Field: Event Sponsor
    - Menampilkan event terkait sponsor yang dipilih
    - Input readonly karena nilainya diisi otomatis
--}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Event</dt>
              <dd class="col-span-2">
                {{-- 
    Input Event
    - Nilai diambil dari data sponsor yang dipilih
    - Tampilan dinamis berdasarkan pilihan sponsor
--}}
                <input type="text" name="event" id="event" 
                       value="{{ old('event', $proposal->sponsorship->event ?? '') }}" 
                       readonly 
                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm" 
                       disabled>
              </dd>
            </div>

            {{-- 
    Field: Nama Komunitas
    - Input teks untuk memasukkan nama komunitas
    - Wajib diisi (required)
--}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Name Community</dt>
              <dd class="col-span-2">
                {{-- 
    Input Nama Komunitas
    - Nilai default diambil dari data yang sudah ada
    - Validasi error ditampilkan jika ada kesalahan input
--}}
                <input type="text" 
                       name="name_community" 
                       value="{{ old('name_community', $proposal->name_community) }}" 
                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('name_community') border-red-500 @enderror" 
                       required>
                @error('name_community')
                {{-- Menampilkan pesan error jika validasi gagal --}}
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- 
    Field: Nama Event
    - Input teks untuk memasukkan nama event
    - Wajib diisi (required)
--}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Name Event</dt>
              <dd class="col-span-2">
                {{-- 
    Input Nama Event
    - Nilai default diambil dari data yang sudah ada
    - Validasi error ditampilkan jika ada kesalahan input
--}}
                <input type="text" 
                       name="name_event" 
                       value="{{ old('name_event', $proposal->name_event) }}" 
                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('name_event') border-red-500 @enderror" 
                       required>
                @error('name_event')
                {{-- Menampilkan pesan error jika validasi gagal --}}
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- 
    Field: Lokasi Event
    - Input teks untuk memasukkan lokasi event
    - Wajib diisi (required)
    - Placeholder sebagai contoh format input
--}}
            <div class="py-4 grid grid-cols-3 gap-4 items-start">
              <dt class="text-sm font-medium text-gray-600">Location</dt>
              <dd class="col-span-2 space-y-2">
                {{-- 
    Input Lokasi
    - Nilai default diambil dari data yang sudah ada
    - Placeholder memberikan contoh format yang diharapkan
    - Warna fokus oranye untuk konsistensi desain
--}}
                <input type="text" 
                       name="location" 
                       id="location" 
                       value="{{ old('location', $proposal->location) }}" 
                       placeholder="Rest Area 72 Lembang"
                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm focus:ring-orange-500 focus:border-orange-500 @error('location') border-red-500 @enderror"
                       required>
                @error('location')
                {{-- 
    Pesan Error Lokasi
    - Muncul ketika validasi lokasi gagal
    - Warna merah untuk menandakan kesalahan
--}}
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- 
    Field: Tanggal Event
    - Input untuk memilih tanggal event
    - Menggunakan flatpickr untuk tampilan kalender yang lebih baik
--}}
            <div class="py-4 grid grid-cols-3 gap-4 items-center">
              <dt class="text-sm font-medium text-gray-600">Date</dt>
              <dd class="col-span-2">
                {{-- 
    Input Tanggal Event
    - Menggunakan flatpickr untuk pemilihan tanggal yang user-friendly
    - Format tanggal disesuaikan dengan preferensi lokal
--}}
                <input type="text" 
                       name="date_event" 
                       id="date" 
                       value="{{ old('date_event', $proposal->date_event) }}" 
                       class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('date') border-red-500 @enderror" 
                       required>
                @error('date_event')
                {{-- 
    Pesan Error Tanggal
    - Muncul ketika validasi tanggal gagal
    - Warna merah untuk menandakan kesalahan
--}}
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- 
    Field: Feedback / Benefit Sponsor
    - Textarea untuk memasukkan manfaat sponsor
    - Wajib diisi (required)
    - Ukuran textarea bisa disesuaikan oleh pengguna
--}}
            <div class="py-4 grid grid-cols-3 gap-4 items-start">
              <dt class="text-sm font-medium text-gray-600">Feedback / Benefit Sponsor</dt>
              <dd class="col-span-2">
                {{-- 
    Textarea Feedback
    - Nilai default diambil dari data yang sudah ada
    - Ukuran minimum 3 baris untuk memastikan keterbacaan
--}}
                <textarea name="feedback_benefit" 
                          rows="3" 
                          class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 text-sm @error('feedback_benefit') border-red-500 @enderror" 
                          required>{{ old('feedback_benefit', $proposal->feedback_benefit) }}</textarea>
                @error('feedback_benefit')
                {{-- 
    Pesan Error Feedback
    - Muncul ketika validasi feedback/benefit gagal
    - Warna merah untuk menandakan kesalahan
--}}
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </dd>
            </div>

            {{-- ============================================================ --}}
{{-- BAGIAN RENCANA ANGGARAN --}}
{{-- ============================================================ --}}
{{-- 
    Rencana Anggaran (Budget Estimate Plan)
    - Tabel dinamis untuk menambahkan item anggaran
    - Setiap baris berisi item, deskripsi, dan perkiraan biaya
--}}
            <div class="py-4 grid grid-cols-3 gap-4 items-start">
              <dt class="text-sm font-medium text-gray-600">Budget Estimate Plan</dt>
              <dd class="col-span-2">
                {{-- 
    Tabel Rencana Anggaran
    - Header tabel dengan kolom Item, Deskripsi, dan Perkiraan Biaya
    - Setiap baris memiliki tombol hapus
--}}
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
                    {{-- 
    Baris Item Anggaran
    - Setiap baris mewakili satu item anggaran
    - Nilai diambil dari data yang sudah ada
    - Setiap input wajib diisi
--}}
                    @foreach ($budget_items as $i => $item)
                    <tr>
                      <td>
                        <input type="text" 
                               name="budget_items[]" 
                               value="{{ $item }}" 
                               class="border rounded px-2 py-1 w-full" 
                               placeholder="Nama item" 
                               required>
                      </td>
                      <td>
                        <input type="text" 
                               name="budget_descriptions[]" 
                               value="{{ $budget_descriptions[$i] ?? '' }}" 
                               class="border rounded px-2 py-1 w-full" 
                               placeholder="Deskripsi" 
                               required>
                      </td>
                      <td>
                        <input type="number" 
                               name="budget_costs[]" 
                               value="{{ $budget_costs[$i] ?? '' }}" 
                               class="border rounded px-2 py-1 w-full" 
                               placeholder="0" 
                               min="0" 
                               step="1000" 
                               required>
                      </td>
                      <td class="text-center">
                        {{-- Tombol untuk menghapus baris --}}
                        <button type="button" 
                                onclick="removeRow(this)" 
                                class="text-red-600 font-bold hover:text-red-800">
                          &times;
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{-- 
    Tombol Tambah Baris
    - Menambahkan baris baru ke tabel anggaran
    - Styling dengan warna biru untuk konsistensi
--}}
                <button type="button" 
                        onclick="addBudgetRow()" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm transition duration-200">
                  + Add Row
                </button>
              </dd>
            </div>

            {{-- ============================================================ --}}
{{-- BAGIAN RUNDOWN ACARA --}}
{{-- ============================================================ --}}
{{-- 
    Rundown Acara
    - Tabel dinamis untuk menambahkan jadwal acara
    - Setiap baris berisi waktu mulai, selesai, dan aktivitas
--}}
            <div class="py-4 grid grid-cols-3 gap-4 items-start">
              <dt class="text-sm font-medium text-gray-600">Rundown Event</dt>
              <dd class="col-span-2">
                {{-- 
    Tabel Rundown Acara
    - Header dengan kolom Waktu dan Aktivitas
    - Setiap baris memiliki input waktu dan deskripsi aktivitas
--}}
                <table class="w-full border border-gray-300 mb-2" id="rundown-table">
                  <thead>
                    <tr class="bg-gray-100">
                      <th class="border px-2 py-1 text-sm">Time</th>
                      <th class="border px-2 py-1 text-sm">Activity</th>
                      <th class="border px-2 py-1 text-sm text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    {{-- 
    Baris Rundown
    - Setiap baris mewakili satu aktivitas dalam rundown
    - Waktu dipisahkan menjadi mulai dan selesai
    - Input tersembunyi menyimpan format waktu yang lengkap
--}}
                    @foreach ($rundown_times as $i => $time)
                    @php
                        // Memisahkan rentang waktu menjadi waktu mulai dan selesai
                        $times = explode(' - ', $time);
                        $startTime = $times[0] ?? '';
                        $endTime = $times[1] ?? '';
                    @endphp
                    <tr>
                      <td>
                        <div class="flex space-x-1">
                          {{-- Input untuk waktu mulai --}}
                          <input type="text" 
                                 name="rundown_start_times[]" 
                                 value="{{ $startTime }}" 
                                 class="timepicker border border-gray-300 rounded px-2 py-1 w-1/2" 
                                 placeholder="Mulai" 
                                 required>
                          {{-- Input untuk waktu selesai --}}
                          <input type="text" 
                                 name="rundown_end_times[]" 
                                 value="{{ $endTime }}" 
                                 class="timepicker border border-gray-300 rounded px-2 py-1 w-1/2" 
                                 placeholder="Selesai" 
                                 required>
                        </div>
                        {{-- Input tersembunyi untuk menyimpan rentang waktu --}}
                        <input type="hidden" 
                               name="rundown_times[]" 
                               class="time-range" 
                               value="{{ $time }}">
                      </td>
                      <td>
                        <input type="text" 
                               name="rundown_activities[]" 
                               value="{{ $rundown_activities[$i] ?? '' }}" 
                               class="border rounded px-2 py-1 w-full" 
                               placeholder="Deskripsi aktivitas" 
                               required>
                      </td>
                      <td class="text-center">
                        {{-- Tombol untuk menghapus baris rundown --}}
                        <button type="button" 
                                onclick="removeRow(this)" 
                                class="text-red-600 hover:text-red-800 font-bold">
                          &times;
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{-- 
    Tombol Tambah Baris Rundown
    - Menambahkan baris baru ke tabel rundown
    - Styling konsisten dengan tombol tambah lainnya
--}}
                <button type="button" 
                        onclick="addRundownRow()" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm transition duration-200">
                  + Add Row
                </button>
              </dd>
            </div>

            {{-- ============================================================ --}}
            {{-- EVENT DOCUMENTATIONS --}}
            {{-- ============================================================ --}}
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
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 5MB each)</p>
                      </div>
                      <input id="event_documentations" name="event_documentations[]" type="file" class="hidden" multiple accept="image/*" />
                    </label>
                  </div>
                  <div id="file-preview" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                    @if(isset($event_documentations) && count($event_documentations) > 0)
                      @foreach($event_documentations as $doc)
                        <div class="relative group">
                          <img src="{{ $doc['file_path'] }}" alt="Event Documentation" class="w-full h-40 object-cover rounded-lg">
                          <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                            <button type="button" class="text-white p-2 hover:text-blue-300 view-image" data-image-src="{{ $doc['file_path'] }}">
                              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                              </svg>
                            </button>
                            <button type="button" class="text-white p-2 hover:text-red-400 remove-doc" data-doc-id="{{ $doc['id'] ?? '' }}">
                              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                              </svg>
                            </button>
                          </div>
                          <input type="hidden" name="existing_documentations[]" value="{{ $doc['id'] ?? '' }}" class="doc-checkbox">
                        </div>
                      @endforeach
                    @endif
                  </div>
                  <p class="text-xs text-gray-500 mt-2">Upload foto dokumentasi acara (opsional)</p>
                </div>
              </dd>
            </div>

            <!-- Image Preview Modal -->
            <div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-[9999] hidden">
              <div class="relative bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-auto">
                <button type="button" id="closeModal" class="fixed top-4 right-4 z-10 bg-black bg-opacity-50 rounded-full p-2 text-white hover:bg-opacity-75 focus:outline-none">
                  <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
                <div class="p-4">
                  <img id="modalImage" src="" alt="Preview" class="block max-w-full max-h-[80vh] mx-auto">
                </div>
              </div>
            </div>
          </dl>
        </div>
      </div>

      {{-- ============================================================ --}}
      {{-- TOMBOL AKSI --}}
      {{-- ============================================================ --}}
      <div class="flex justify-between mt-6 space-x-4">
        <a href="{{ url()->previous() }}" 
           class="w-1/2 py-2.5 rounded-2xl text-center bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition duration-300">
          Back
        </a>
        {{-- 
    Tombol Update Proposal
    - Menyimpan perubahan yang telah dibuat
    - Warna oranye untuk aksi utama
--}}
        <button type="submit" 
                class="w-1/2 py-2.5 rounded-2xl text-center bg-orange-500 text-white font-semibold hover:bg-orange-700 transition duration-300">
          Update Proposal
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ============================================================ --}}
{{-- SCRIPT UNTUK INTERAKTIVITAS --}}
{{-- ============================================================ --}}
{{-- 
    Library JavaScript yang diperlukan:
    1. Flatpickr - untuk date dan time picker
    2. SweetAlert2 - untuk notifikasi yang lebih baik
--}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
  /**
   * Inisialisasi flatpickr untuk input tanggal
   * - Menggunakan tema material_blue untuk konsistensi visual
   * - Format tanggal disesuaikan dengan standar Indonesia
   */
  flatpickr("#date", {
    altInput: true,                    // Menampilkan input alternatif yang diformat
    altFormat: "l, d F Y",            // Format tampilan tanggal (contoh: Senin, 1 Januari 2023)
    dateFormat: "Y-m-d",              // Format tanggal yang disimpan
    locale: "id",                     // Menggunakan bahasa Indonesia
    theme: "material_blue"            // Tema warna biru material
  });

  /**
   * Menginisialisasi timepicker untuk input waktu
   * @param {HTMLElement} container - Elemen container yang akan diinisialisasi timepickernya
   * - Menggunakan format 24 jam
   - Langkah waktu 30 menit untuk kemudahan pemilihan
   */
  function initializeTimePickers(container = document) {
    // Temukan semua elemen dengan class 'timepicker' dalam container
    const timepickers = container.querySelectorAll('.timepicker');
    
    // Inisialisasi flatpickr untuk setiap input waktu
    timepickers.forEach(input => {
      flatpickr(input, {
        enableTime: true,              // Mengaktifkan pemilihan waktu
        noCalendar: true,              // Menonaktifkan kalender
        dateFormat: "H:i",             // Format waktu 24 jam (contoh: 14:30)
        time_24hr: true,               // Menggunakan format 24 jam
        minuteIncrement: 30,            // Langkah menit (30 menit)
        defaultHour: 8,                 // Jam default
        defaultMinute: 0,               // Menit default
        // Fungsi yang dipanggil saat nilai berubah
        onChange: function(selectedDates, dateStr, instance) {
          updateTimeRange(instance.input);
        }
      });
    });
  }
  
  /**
   * Memperbarui input tersembunyi yang menyimpan rentang waktu
   * @param {HTMLInputElement} changedInput - Input yang berubah nilainya
   * - Menggabungkan waktu mulai dan selesai menjadi satu string
   * - Format: 'HH:MM - HH:MM'
   */
  function updateTimeRange(changedInput) {
    // Temukan elemen terkait dalam baris yang sama
    const row = changedInput.closest('tr');
    const startInput = row.querySelector('input[name="rundown_start_times[]"]');
    const endInput = row.querySelector('input[name="rundown_end_times[]"]');
    const hiddenInput = row.querySelector('input[name="rundown_times[]"]');
    
    // Gabungkan waktu mulai dan selesai menjadi satu string
    if (startInput.value && endInput.value) {
      hiddenInput.value = `${startInput.value} - ${endInput.value}`;
    } else {
      // Jika salah satu kosong, gunakan yang ada
      hiddenInput.value = startInput.value || endInput.value;
    }
  }

  // Inisialisasi saat dokumen selesai dimuat
  // - Mengatur event listener dan inisialisasi komponen
  // - Memastikan semua fungsi berjalan setelah DOM siap
  document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi timepicker untuk input waktu yang sudah ada
    initializeTimePickers();
    
    // Memperbarui tampilan input waktu yang sudah ada berdasarkan nilai tersembunyi
    document.querySelectorAll('input[name="rundown_times[]"]').forEach(hiddenInput => {
      const timeRange = hiddenInput.value;
      if (timeRange) {
        // Pisahkan waktu mulai dan selesai
        const times = timeRange.split(' - ');
        const row = hiddenInput.closest('tr');
        const startInput = row.querySelector('input[name="rundown_start_times[]"]');
        const endInput = row.querySelector('input[name="rundown_end_times[]"]');
        
        // Set nilai input waktu berdasarkan data yang ada
        if (times.length >= 2) {
          startInput.value = times[0].trim();
          endInput.value = times[1].trim();
        } else if (times.length === 1) {
          startInput.value = times[0].trim();
        }
      }
    });
  });

  /**
   * Mengatur perubahan pada dropdown sponsor
   * - Memperbarui nilai kategori dan event secara otomatis
   * - Berdasarkan sponsor yang dipilih
   */
  document.addEventListener('DOMContentLoaded', function() {
    const sponsorSelect = document.getElementById('sponsorship');
    
    if (sponsorSelect) {
      const categoryInput = document.getElementById('category');
      const eventInput = document.getElementById('event');

      // Saat sponsor dipilih, update kategori dan event terkait
      sponsorSelect.addEventListener('change', function () {
        const selected = sponsorSelect.options[sponsorSelect.selectedIndex];
        if (selected) {
          // Set nilai kategori dan event berdasarkan data yang dipilih
          categoryInput.value = selected.getAttribute('data-category') || '';
          eventInput.value = selected.getAttribute('data-event') || '';
        }
      });
    }
  });

  /**
   * Menambahkan baris baru ke tabel budget
   * - Membuat elemen baris baru dengan input yang diperlukan
   * - Menambahkan event listener untuk validasi
   */
  function addBudgetRow() {
    // Temukan elemen tbody dari tabel budget
    const tbody = document.querySelector('#budget-table tbody');
    
    // Buat elemen baris baru
    const row = document.createElement('tr');
    
    // Isi baris dengan input untuk item budget baru
    row.innerHTML = `
      <td><input type="text" name="budget_items[]" class="border rounded px-2 py-1 w-full" placeholder="Nama item" required></td>
      <td><input type="text" name="budget_descriptions[]" class="border rounded px-2 py-1 w-full" placeholder="Deskripsi" required></td>
      <td><input type="number" name="budget_costs[]" class="border rounded px-2 py-1 w-full" placeholder="0" min="0" required></td>
      <td class="text-center">
        <button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-800 font-bold">
          &times;
        </button>
      </td>
    `;
    
    // Tambahkan baris baru ke dalam tabel
    tbody.appendChild(row);
  }

  /**
   * Menambahkan baris baru ke tabel rundown
   * - Membuat elemen baris baru dengan input waktu dan aktivitas
   * - Inisialisasi timepicker untuk input waktu baru
   */
  function addRundownRow() {
    // Temukan elemen tbody dari tabel rundown
    const tbody = document.querySelector('#rundown-table tbody');
    
    // Buat elemen baris baru
    const row = document.createElement('tr');
    
    // Isi baris dengan input untuk item rundown baru
    row.innerHTML = `
      <td>
        <div class="flex space-x-1">
          <input type="text" 
                 name="rundown_start_times[]" 
                 class="timepicker border border-gray-300 rounded px-2 py-1 w-1/2" 
                 placeholder="Mulai" 
                 required>
          <input type="text" 
                 name="rundown_end_times[]" 
                 class="timepicker border border-gray-300 rounded px-2 py-1 w-1/2" 
                 placeholder="Selesai" 
                 required>
        </div>
        <input type="hidden" 
               name="rundown_times[]" 
               class="time-range">
      </td>
      <td>
        <input type="text" 
               name="rundown_activities[]" 
               class="border rounded px-2 py-1 w-full" 
               placeholder="Deskripsi aktivitas" 
               required>
      </td>
      <td class="text-center">
        <button type="button" 
                onclick="removeRow(this)" 
                class="text-red-600 hover:text-red-800 font-bold">
          &times;
        </button>
      </td>
    `;
    
    // Tambahkan baris baru ke dalam tabel
    tbody.appendChild(row);
    
    // Inisialisasi timepicker untuk input waktu pada baris baru
    initializeTimePickers(row);
  }

  /**
   * Menghapus baris dari tabel
   * @param {HTMLButtonElement} btn - Tombol hapus yang diklik
   * - Mencegah penghapusan baris terakhir
   * - Menampilkan peringatan jika mencoba menghapus baris terakhir
   */
  function removeRow(btn) {
    // Temukan baris yang berisi tombol yang diklik
    const row = btn.closest('tr');
    // Hapus baris jika ada dan bukan baris terakhir
    if (row && row.parentNode.rows.length > 1) {
      row.remove();
    } else {
      // Tampilkan pesan jika mencoba menghapus baris terakhir
      Swal.fire({
        title: 'Cannot Delete',
        text: 'At least one row of data must remain',
        icon: 'warning',
        confirmButtonText: 'Ok'
      });
    }
  }

  /**
   * Menangani submit form dengan konfirmasi
   * - Menampilkan dialog konfirmasi sebelum mengirim form
   * - Mencegah pengiriman form jika pengguna membatalkan
   */
  // Handle file upload preview
  const fileInput = document.getElementById('event_documentations');
  const filePreview = document.getElementById('file-preview');
  const maxFiles = Infinity; // Unlimited files
  let fileCount = document.querySelectorAll('#file-preview .relative').length;
  let selectedFiles = new DataTransfer();
  
  // Track existing documentations
  let existingDocs = [];
  document.querySelectorAll('.doc-checkbox').forEach(checkbox => {
    if (checkbox.value) {
      const imgElement = checkbox.closest('.relative')?.querySelector('img');
      if (imgElement) {
        existingDocs.push({
          id: checkbox.value,
          src: imgElement.src || ''
        });
      }
    }
  });
  
  // Initialize image preview modal
  document.addEventListener('DOMContentLoaded', function() {
    const imagePreviewModal = document.getElementById('imagePreviewModal');
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.getElementById('closeModal');
    
    // Function to close modal
    function closeImageModal() {
      imagePreviewModal.classList.add('hidden');
      document.body.style.overflow = ''; // Re-enable scrolling
    }
    
    // Close modal when clicking the close button
    if (closeModal) {
      closeModal.addEventListener('click', closeImageModal);
    }
    
    // Close modal when clicking outside the image
    if (imagePreviewModal) {
      imagePreviewModal.addEventListener('click', function(e) {
        if (e.target === imagePreviewModal) {
          closeImageModal();
        }
      });
    }
    
    // Handle view image button clicks using event delegation
    document.addEventListener('click', function(e) {
      // Handle view image button
      if (e.target.closest('.view-image') || e.target.matches('.view-image')) {
        e.preventDefault();
        const button = e.target.closest('.view-image') || e.target;
        const imageSrc = button.getAttribute('data-image-src');
        if (imageSrc && modalImage) {
          modalImage.src = imageSrc;
          imagePreviewModal.classList.remove('hidden');
          document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }
      }
    });
    
    // Add keyboard event to close modal with ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && !imagePreviewModal.classList.contains('hidden')) {
        closeImageModal();
      }
    });
  });

  // Function to update file count and disable/enable file input
  function updateFileCount() {
    fileCount = document.querySelectorAll('#file-preview .relative').length;
    if (fileCount >= maxFiles && maxFiles !== Infinity) {
      fileInput.disabled = true;
      fileInput.closest('label').classList.add('opacity-50', 'cursor-not-allowed');
    } else {
      fileInput.disabled = false;
      fileInput.closest('label').classList.remove('opacity-50', 'cursor-not-allowed');
    }
  }

  // Helper function to convert data URL to Blob
  function dataURLtoBlob(dataURL) {
    const arr = dataURL.split(',');
    const mime = arr[0].match(/:(.*?);/)[1];
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);
    while (n--) {
      u8arr[n] = bstr.charCodeAt(n);
    }
    return new Blob([u8arr], { type: mime });
  }

  // Function to update previews based on selected files
  function updatePreviews() {
    filePreview.innerHTML = '';
    
    // Add existing documentations first
    existingDocs.forEach(doc => {
      if (doc.id && doc.src) {
        const preview = document.createElement('div');
        preview.className = 'relative group';
        preview.innerHTML = `
          <img src="${doc.src}" alt="Preview" class="w-full h-40 object-cover rounded-lg">
          <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
            <button type="button" class="text-white p-2 hover:text-blue-300 view-image" data-image-src="${doc.src}">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
            </button>
            <button type="button" class="text-white p-2 hover:text-red-400 remove-doc" data-doc-id="${doc.id}">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
            </button>
          </div>
          <input type="hidden" name="existing_documentations[]" value="${doc.id}" class="doc-checkbox">
        `;
        filePreview.appendChild(preview);
      }
    });
    
    // Add new file previews
    const files = Array.from(selectedFiles.files);
    files.forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = function(e) {
        const preview = document.createElement('div');
        preview.className = 'relative group';
        const imageUrl = e.target.result;
        preview.innerHTML = `
          <img src="${imageUrl}" alt="${file.name}" class="w-full h-40 object-cover rounded-lg">
          <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg space-x-2">
            <button type="button" class="text-white p-2 hover:text-blue-300 view-image" data-image-src="${imageUrl}">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
            </button>
            <button type="button" class="text-white p-2 hover:text-red-400 remove-preview" data-file-index="${index}">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
            </button>
          </div>
        `;
        filePreview.appendChild(preview);
        
        // Add remove event for the new preview
        const removeBtn = preview.querySelector('.remove-preview');
        if (removeBtn) {
          removeBtn.addEventListener('click', function() {
            // Remove the file from selectedFiles
            const fileIndex = parseInt(this.getAttribute('data-file-index'));
            const dt = new DataTransfer();
            const input = fileInput;
            const { files } = input;
            
            for (let i = 0; i < files.length; i++) {
              if (index !== i) {
                dt.items.add(files[i]);
              }
            }
            
            selectedFiles = dt;
            input.files = dt.files;
            
            // Update previews
            updatePreviews();
            updateFileCount();
          });
        }
      };
      reader.readAsDataURL(file);
    });
    
    updateFileCount();
  }

  // Handle file selection
  if (fileInput) {
    fileInput.addEventListener('change', function(e) {
      const newFiles = Array.from(e.target.files);
      
      // Clear previous selections to avoid duplicates
      const existingFiles = Array.from(selectedFiles.files);
      selectedFiles = new DataTransfer();
      
      // Add existing files
      existingFiles.forEach(file => {
        selectedFiles.items.add(file);
      });
      
      // Add new files (up to maxFiles)
      const remainingSlots = maxFiles - selectedFiles.files.length;
      if (remainingSlots > 0) {
        const filesToAdd = newFiles.slice(0, remainingSlots);
        filesToAdd.forEach(file => {
          if (!file.type.match('image.*')) {
            alert(`File ${file.name} is not an image.`);
            return;
          }
          
          if (file.size > 5 * 1024 * 1024) {
            alert(`File ${file.name} is too large. Maximum size is 5MB.`);
            return;
          }
          
          selectedFiles.items.add(file);
        });
      }
      
      // Update the file input with all files
      fileInput.files = selectedFiles.files;
      
      // Update previews
      updatePreviews();
    });
  }
  
  // Handle removal of existing documentations
  document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-doc')) {
      const button = e.target.closest('.remove-doc');
      const docId = button.getAttribute('data-doc-id');
      const docItem = button.closest('.relative');
      
      if (docId) {
        // If it's an existing doc with an ID, add it to the removed_docs array
        const removedDocsInput = document.getElementById('removed_docs');
        let removedDocs = [];
        
        if (removedDocsInput && removedDocsInput.value) {
          removedDocs = JSON.parse(removedDocsInput.value);
        }
        
        if (!removedDocs.includes(docId)) {
          removedDocs.push(docId);
          
          if (!removedDocsInput) {
            const newInput = document.createElement('input');
            newInput.type = 'hidden';
            newInput.name = 'removed_docs';
            newInput.id = 'removed_docs';
            newInput.value = JSON.stringify(removedDocs);
            document.getElementById('updateForm').appendChild(newInput);
          } else {
            removedDocsInput.value = JSON.stringify(removedDocs);
          }
        }
        
        // Remove from existingDocs array
        existingDocs = existingDocs.filter(doc => doc.id !== docId);
        
        // Remove the preview item
        if (docItem) {
          docItem.remove();
          fileCount--;
          updateFileCount();
        }
      } else if (docItem) {
        // For new files that haven't been saved yet
        const fileInput = document.getElementById('event_documentations');
        const files = Array.from(fileInput.files);
        const fileName = docItem.querySelector('img').alt;
        
        // Remove the file from the FileList (by creating a new DataTransfer object)
        const dataTransfer = new DataTransfer();
        files.forEach(file => {
          if (file.name !== fileName) {
            dataTransfer.items.add(file);
          }
        });
        
        fileInput.files = dataTransfer.files;
        
        // Remove the preview item
        docItem.remove();
        fileCount--;
        updateFileCount();
      }
    }
  });

  // Initialize file count and previews on page load
  updateFileCount();
  updatePreviews();

  // Image Preview Modal Functionality
  const modal = document.getElementById('imagePreviewModal');
  const modalImg = document.getElementById('modalImage');
  const closeModal = document.getElementById('closeModal');
  
  // Open modal when clicking on view image button
  document.addEventListener('click', function(e) {
    if (e.target.closest('.view-image')) {
      const imgSrc = e.target.closest('.view-image').getAttribute('data-image-src');
      modalImg.src = imgSrc;
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }
  });
  
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

  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('updateForm');

    // Tambahkan event listener untuk submit form
    form.addEventListener('submit', function (e) {
      // Mencegah form submit default
      e.preventDefault();

      // Tampilkan dialog konfirmasi
      Swal.fire({
        title: 'Update Proposal?',
        text: "Please ensure all data is correct before updating.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
        customClass: {
          confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md',
          cancelButton: 'bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-md ml-2'
        },
        buttonsStyling: false,
        // Mengatur tampilan tombol saat dialog terbuka
        didOpen: () => {
          // Stil tambahan untuk tombol konfirmasi
          Swal.getConfirmButton().style.marginRight = '10px';
          Swal.getCancelButton().style.marginLeft = '10px';
        }
      }).then((result) => {
        // Jika pengguna mengkonfirmasi, submit form
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
</script>
@endsection
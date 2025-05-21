@extends('dashboard.layouts.main')

@section('container')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="p-10 sm:ml-80">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-4xl mx-auto mt-24 font-sans text-gray-800 leading-relaxed">
        <h1 class="text-3xl font-semibold mb-8 border-b pb-4 border-gray-300">Preview Proposal</h1>

        <div class="prose max-w-none text-gray-900">
            {!! $proposal !!}
        </div>

        {{-- Form Submit --}}
        <form id="submitForm" action="{{ route('proposals.submit') }}" method="POST">
            @csrf

            {{-- Hidden Inputs --}}
            <input type="hidden" name="proposal" value="{{ $raw_proposal }}">
            <input type="hidden" name="sponsor_id" value="{{ $sponsor_id }}">
            <input type="hidden" name="category" value="{{ $category }}">
            <input type="hidden" name="event" value="{{ $event }}">
            <input type="hidden" name="name_community" value="{{ $name_community }}">
            <input type="hidden" name="name_event" value="{{ $name_event }}">
            <input type="hidden" name="location" value="{{ $location }}">
            <input type="hidden" name="date_event" value="{{ $date_event }}">
            <input type="hidden" name="feedback_benefit" value="{{ $feedback_benefit }}">

            {{-- Budget Items --}}
            @foreach ($budget_items as $index => $item)
                <input type="hidden" name="budget_items[]" value="{{ $item }}">
                <input type="hidden" name="budget_descriptions[]" value="{{ $budget_descriptions[$index] }}">
                <input type="hidden" name="budget_costs[]" value="{{ $budget_costs[$index] }}">
            @endforeach

            {{-- Rundown Items --}}
            @foreach ($rundown_times as $index => $time)
                <input type="hidden" name="rundown_times[]" value="{{ $time }}">
                <input type="hidden" name="rundown_activities[]" value="{{ $rundown_activities[$index] }}">
            @endforeach
        </form>

        {{-- Back Button --}}
        <a href="{{ url()->previous() }}"
            class="block w-full mt-4 py-3 rounded-2xl text-center bg-blue-500 text-white font-semibold hover:bg-blue-700 transition duration-300">
            Back
        </a>
    </div>
</div>

{{-- SweetAlert Confirmation Before Submit --}}
<script>
    ddocument.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('submitForm');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Kirim Proposal?',
                text: "Pastikan semua data sudah benar sebelum dikirim.",
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

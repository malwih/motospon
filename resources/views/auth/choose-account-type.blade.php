@extends('dashboard.layouts.main')

@section('container')
<div x-data="{ open: true }">
    <!-- Modal Overlay -->
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <!-- Modal Box -->
        <div class="bg-white w-50 max-w-lg mx-auto p-8 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Pilih Jenis Akun</h2>

            <form method="POST" action="{{ route('store.account.type') }}">
                @csrf

                <div class="space-y-4">
                    <label class="flex items-center gap-3 p-4 border border-gray-300 rounded-lg hover:border-orange-500 cursor-pointer">
                        <input type="radio" name="account_type" value="company" class="form-radio text-orange-500" required>
                        <span class="text-gray-800 font-medium">Company</span>
                    </label>

                    <label class="flex items-center gap-3 p-4 border border-gray-300 rounded-lg hover:border-orange-500 cursor-pointer">
                        <input type="radio" name="account_type" value="community" class="form-radio text-orange-500" required>
                        <span class="text-gray-800 font-medium">Community</span>
                    </label>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full py-3 bg-orange-500 text-white rounded-2xl font-semibold hover:bg-orange-600 transition duration-300">
                        Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

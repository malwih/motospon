{{-- Extend layout utama --}}
@extends('dashboard.layouts.main')

{{-- Section untuk konten utama --}}
@section('container')
    {{-- Komponen Alpine.js untuk mengelola state modal --}}
    <div x-data="{ open: true }">
        {{-- Overlay modal --}}
        <div 
            x-show="open" 
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-title"
        >
            {{-- Kotak modal --}}
            <div class="bg-white w-full max-w-md mx-auto p-8 rounded-xl shadow-lg border border-gray-200">
                {{-- Judul modal --}}
                <h2 id="modal-title" class="text-2xl font-bold text-gray-900 mb-6 text-center">
                    Choose Account Type
                </h2>

                {{-- Form pemilihan tipe akun --}}
                <form method="POST" action="{{ route('store.account.type') }}">
                    @csrf

                    {{-- Daftar pilihan tipe akun --}}
                    <div class="space-y-4">
                        {{-- Pilihan Company --}}
                        <label class="flex items-center gap-3 p-4 border border-gray-300 rounded-lg hover:border-orange-500 cursor-pointer transition-colors">
                            <input 
                                type="radio" 
                                name="account_type" 
                                value="company" 
                                class="form-radio text-orange-500 focus:ring-orange-500" 
                                required
                                aria-label="Pilih akun Company"
                            >
                            <span class="text-gray-800 font-medium">Company</span>
                        </label>

                        {{-- Pilihan Community --}}
                        <label class="flex items-center gap-3 p-4 border border-gray-300 rounded-lg hover:border-orange-500 cursor-pointer transition-colors">
                            <input 
                                type="radio" 
                                name="account_type" 
                                value="community" 
                                class="form-radio text-orange-500 focus:ring-orange-500" 
                                required
                                aria-label="Pilih akun Community"
                            >
                            <span class="text-gray-800 font-medium">Community</span>
                        </label>
                    </div>

                    {{-- Tombol submit --}}
                    <div class="mt-6">
                        <button 
                            type="submit" 
                            class="w-full py-3 bg-orange-500 text-white rounded-2xl font-semibold hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-300"
                        >
                            Next
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

{{-- Menambahkan script Alpine.js --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

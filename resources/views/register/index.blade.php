{{-- Menggunakan layout utama --}}
@extends('layouts.main')

{{-- 
    Section untuk halaman registrasi
    - Menampilkan form pendaftaran akun baru
    - Terdapat validasi untuk setiap field
    - Mendukung dua tipe akun: company dan community
--}}
@section('container')

{{-- 
    Container utama halaman register
    - Mengatur layout dan styling halaman
    - Menggunakan warna background abu-abu muda
--}}
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 py-16">
    {{-- 
    Card untuk form register
    - Menggunakan shadow untuk efek kedalaman
    - Responsif dengan padding yang sesuai
--}}
    <div class="flex flex-col bg-white shadow-md px-6 md:px-10 py-12 rounded-md w-full max-w-md">
        {{-- Judul halaman register --}}
        <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800">Sign Up Your Account</div>

        {{-- Form register --}}
        <div class="mt-10">
            <form action="/register" method="post">
                @csrf
                {{-- CSRF token untuk keamanan form --}}

                {{-- 
    Field untuk memilih tipe akun
    - Dropdown dengan opsi company/community
    - Menggunakan ikon untuk visual yang lebih baik
--}}
                <div class="flex flex-col mb-6">
                    <label for="account_type" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">Account Type:</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <select id="account_type" name="account_type" required
                            class="form-control text-sm sm:text-base pl-10 pr-4 py-2 rounded-lg border border-gray-400 w-full focus:outline-none focus:border-blue-400 appearance-none">
                            <option value="" disabled selected>Select account type</option>
                            <option value="company">Company</option>
                            <option value="community">Community</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                    <input type="hidden" name="is_company" id="is_company" value="0">
                    <input type="hidden" name="is_community" id="is_community" value="0">
                </div>

                {{-- 
    Field untuk input nama lengkap
    - Validasi wajib diisi
    - Menampilkan pesan error jika validasi gagal
--}}
                <div class="flex flex-col mb-6">
                    <label for="name" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">Name:</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 py-2 rounded-lg border border-gray-400 w-full focus:outline-none focus:border-blue-400"
                            placeholder="John Doe" required />
                        @error('name')
                        <div class="invalid-feedback text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- 
    Field untuk input username
    - Harus unik di sistem
    - Validasi format dan ketersediaan
--}}
                <div class="flex flex-col mb-6">
                    <label for="username" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">Username:</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="username" type="text" name="username" value="{{ old('username') }}"
                            class="form-control @error('username') is-invalid @enderror text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 py-2 rounded-lg border border-gray-400 w-full focus:outline-none focus:border-blue-400"
                            placeholder="johndoe2023" required />
                        @error('username')
                        <div class="invalid-feedback text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- 
    Field untuk input email
    - Validasi format email
    - Harus unik di sistem
--}}
                <div class="flex flex-col mb-6">
                    <label for="email" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">E-Mail Address:</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 py-2 rounded-lg border border-gray-400 w-full focus:outline-none focus:border-blue-400"
                            placeholder="johndoe@gmail.com" required />
                        @error('email')
                        <div class="invalid-feedback text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- 
    Field untuk nomor WhatsApp
    - Format nomor telepon internasional
    - Wajib diisi untuk verifikasi
--}}
                <div class="flex flex-col mb-6">
                    <label for="whatsapp_number" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">WhatsApp Number:</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <input id="whatsapp_number" type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}"
                            class="form-control @error('whatsapp_number') is-invalid @enderror text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 py-2 rounded-lg border border-gray-400 w-full focus:outline-none focus:border-blue-400"
                            placeholder="+6281234567890" required />
                        @error('whatsapp_number')
                        <div class="invalid-feedback text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- 
    Field untuk input password
    - Mendukung toggle visibility
    - Validasi kekuatan password
--}}
                <div class="flex flex-col mb-6">
                    <label for="password" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">Password:</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror text-sm sm:text-base placeholder-gray-500 pl-10 pr-10 py-2 rounded-lg border border-gray-400 w-full focus:outline-none focus:border-blue-400"
                            placeholder="John_Doe123" required />
                        
                                {{-- 
    Tombol toggle password
    - Mengubah tipe input antara password/text
    - Ikon berubah sesuai status
--}}
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400" onclick="togglePassword()">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path id="eyePath" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        @error('password')
                        <div class="invalid-feedback text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex w-full mt-6">
                    <button type="submit"
                        class="flex items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-orange-600 hover:bg-orange-700 rounded py-2 w-full transition duration-150 ease-in">
                        <span class="mr-2 uppercase">Sign Up</span>
                        <span>
                            <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        {{-- 
    Link ke halaman login
    - Untuk pengguna yang sudah memiliki akun
    - Styling menonjol dengan ikon
--}}
        <div class="flex justify-center items-center mt-6">
            <a href="/login"
                class="inline-flex items-center font-bold text-orange-500 hover:text-orange-700 text-xs text-center">
                <svg class="h-6 w-6 mr-2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                    <path
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                You have an account? Login here!
            </a>
        </div>
    </div>
</div>

<script>
    /**
     * Menangani perubahan pilihan tipe akun
     * - Mengupdate nilai hidden input is_company dan is_community
     * - Nilai akan digunakan saat proses submit form
     */
    document.getElementById('account_type').addEventListener('change', function() {
        const accountType = this.value;
        document.getElementById('is_company').value = (accountType === 'company') ? '1' : '0';
        document.getElementById('is_community').value = (accountType === 'community') ? '1' : '0';
    });

    /**
     * Fungsi untuk menampilkan/menyembunyikan password
     * - Mengubah tipe input antara password dan text
     * - Mengganti ikon mata sesuai kondisi (terbuka/tertutup)
     * - Meningkatkan UX dengan visual feedback
     */
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';

        if (isPassword) {
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.965 9.965 0 013.234-4.146M15 12a3 3 0 01-4.243 2.828M9.172 9.172A3 3 0 0115 12m1.53 1.53A9.977 9.977 0 0121.542 12c-1.274-4.057-5.064-7-9.542-7a9.977 9.977 0 00-4.987 1.357M3 3l18 18" />
            `;
        } else {
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }
</script>

@endsection

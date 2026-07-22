@extends('layouts.main')

@section('container')


{{-- ============================================================ --}}
{{-- CONTAINER UTAMA --}}
{{-- ============================================================ --}}
{{--
    Container utama halaman login
    - Minimum tinggi viewport
    - Posisi konten di tengah vertikal dan horizontal
    - Background abu-abu muda
--}}
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 py-16">
  {{-- ============================================================ --}}
  {{-- KARTU LOGIN --}}
  {{-- ============================================================ --}}
  {{--
      Kartu untuk form login
      - Background putih dengan shadow
      - Padding responsif untuk berbagai ukuran layar
      - Lebar maksimum untuk tampilan yang rapi
  --}}
  <div class="flex flex-col bg-white shadow-md px-4 sm:px-6 md:px-8 lg:px-10 py-20 rounded-md w-full max-w-md">

    {{-- ============================================================ --}}
    {{-- NOTIFIKASI --}}
    {{-- ============================================================ --}}
    {{--
        Menampilkan notifikasi kepada pengguna
        - Notifikasi sukses (contoh: setelah registrasi)
        - Notifikasi error (contoh: login gagal)
    --}}
    @if(session('success'))
        <x-alert type="success" duration="3000">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error') || session('loginError'))
        <x-alert type="error">
            {{ session('error') ?? session('loginError') }}
        </x-alert>
    @endif



    {{-- ============================================================ --}}
    {{-- JUDUL HALAMAN --}}
    {{-- ============================================================ --}}
    <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800">
        Login To Your Account
    </div>

    {{-- ============================================================ --}}
    {{-- TOMBOL LOGIN GOOGLE --}}
    {{-- ============================================================ --}}
    {{--
        Tombol untuk login menggunakan akun Google
        - Menggunakan OAuth Google
        - Ikon Google yang sesuai dengan brand guidelines
        - Styling yang konsisten dengan tema
    --}}
    <a href="{{ url('auth/google') }}" class="flex items-center justify-center mt-6 bg-white dark:bg-white border border-gray-300 rounded-lg shadow-md py-2 text-sm font-medium text-gray-800 dark:text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-1 focus:ring-offset-0 focus:ring-gray-500">
      <button class="relative flex items-center justify-center ">
        <svg class="flex items-center justify-center h-4 w-4 mr-3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="-0.5 0 48 48" version="1.1">
          <title>Google-color</title>
          <desc>Created with Sketch.</desc>
          <defs> </defs>
          <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g id="Color-" transform="translate(-401.000000, -860.000000)">
              <g id="Google" transform="translate(401.000000, 860.000000)">
                <path d="M9.82727273,24 C9.82727273,22.4757333 10.0804318,21.0144 10.5322727,19.6437333 L2.62345455,13.6042667 C1.08206818,16.7338667 0.213636364,20.2602667 0.213636364,24 C0.213636364,27.7365333 1.081,31.2608 2.62025,34.3882667 L10.5247955,28.3370667 C10.0772273,26.9728 9.82727273,25.5168 9.82727273,24" id="Fill-1" fill="#FBBC05"> </path>
                <path d="M23.7136364,10.1333333 C27.025,10.1333333 30.0159091,11.3066667 32.3659091,13.2266667 L39.2022727,6.4 C35.0363636,2.77333333 29.6954545,0.533333333 23.7136364,0.533333333 C14.4268636,0.533333333 6.44540909,5.84426667 2.62345455,13.6042667 L10.5322727,19.6437333 C12.3545909,14.112 17.5491591,10.1333333 23.7136364,10.1333333" id="Fill-2" fill="#EB4335"> </path>
                <path d="M23.7136364,37.8666667 C17.5491591,37.8666667 12.3545909,33.888 10.5322727,28.3562667 L2.62345455,34.3946667 C6.44540909,42.1557333 14.4268636,47.4666667 23.7136364,47.4666667 C29.4455,47.4666667 34.9177955,45.4314667 39.0249545,41.6181333 L31.5177727,35.8144 C29.3995682,37.1488 26.7323182,37.8666667 23.7136364,37.8666667" id="Fill-3" fill="#34A853"> </path>
                <path d="M46.1454545,24 C46.1454545,22.6133333 45.9318182,21.12 45.6113636,19.7333333 L23.7136364,19.7333333 L23.7136364,28.8 L36.3181818,28.8 C35.6879545,31.8912 33.9724545,34.2677333 31.5177727,35.8144 L39.0249545,41.6181333 C43.3393409,37.6138667 46.1454545,31.6490667 46.1454545,24" id="Fill-4" fill="#4285F4"> </path>
              </g>
            </g>
          </g>
        </svg>
        <span>Continue with Google</span>
      </button>
    </a>

    {{-- ============================================================ --}}
    {{-- PEMISAH --}}
    {{-- ============================================================ --}}
    {{--
        Garis pemisah antara login Google dan email
        - Garis horizontal tipis
        - Teks "Or Login With Email" di tengah garis
        - Background putih untuk teks agar terlihat jelas
    --}}
    <div class="relative mt-10 h-px bg-gray-300">
      <div class="absolute left-0 top-0 flex justify-center w-full -mt-2">
        <span class="bg-white px-4 text-xs text-gray-500 uppercase">Or Login With Email</span>
      </div>
    </div>
    {{-- ============================================================ --}}
    {{-- FORM LOGIN --}}
    {{-- ============================================================ --}}
    {{--
        Form untuk login dengan email dan password
        - Menggunakan method POST ke route /login
        - CSRF protection untuk keamanan
    --}}
    <div class="mt-10">
      <form action="/login" method="post">
        @csrf
        {{-- ============================================================ --}}
        {{-- INPUT EMAIL --}}
        {{-- ============================================================ --}}
        {{--
            Field input untuk alamat email
            - Ikon amplop di sebelah kiri
            - Validasi error handling
            - Required field
        --}}
        <div class="flex flex-col mb-6">
          <label for="email" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">E-Mail Address:</label>
          <div class="relative">
            <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
              <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>

            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400" placeholder="Your Email" required />
            @error('email')
            <div class="invalid-feedback ">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>

        {{-- ============================================================ --}}
        {{-- INPUT PASSWORD --}}
        {{-- ============================================================ --}}
        {{--
            Field input untuk password
            - Ikon gembok di sebelah kiri
            - Tipe password untuk keamanan
            - Required field
        --}}
        <div class="flex flex-col mb-6">
          <label for="password" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">Password:</label>
          <div class="relative">
            <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
              <span>
                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                  <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </span>
            </div>

            <input id="password" type="password" name="password" class="text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400" placeholder="Your Password" required />
          </div>
        </div>

        <div class="flex items-center mb-6 -mt-4">
          <div class="flex ml-auto">
            <!-- <a href="#" class="inline-flex text-xs sm:text-sm text-blue-500 hover:text-blue-700">Forgot Your Password?</a> -->
          </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TOMBOL LOGIN --}}
        {{-- ============================================================ --}}
        {{--
            Tombol untuk submit form login
            - Warna oranye sesuai tema
            - Efek hover untuk interaktivitas
            - Ikon panah untuk indikator aksi
        --}}
        <div class="flex w-full">
          <button type="submit" class="flex items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-orange-600 hover:bg-orange-700 rounded py-2 w-full transition duration-150 ease-in">
            <span class="mr-2 uppercase">Login</span>
            <span>
              <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </span>
          </button>
        </div>
      </form>
    </div>
    {{-- ============================================================ --}}
    {{-- LINK REGISTRASI --}}
    {{-- ============================================================ --}}
    {{--
        Tautan ke halaman pendaftaran
        - Untuk pengguna baru yang belum memiliki akun
        - Warna oranye sesuai tema
        - Ikon tambah pengguna
    --}}
    <div class="flex justify-center items-center mt-6">
      <a href="/register" class="inline-flex items-center font-bold text-orange-500 hover:text-orange-700 text-xs text-center">
        <span>
          <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
        </span>
        <span class="ml-2">You don't have an account? Register Now!</span>
      </a>
    </div>
  </div>{{-- Penutup .flex.flex-col.bg-white... --}}
</div>{{-- Penutup .min-h-screen... --}}

@endsection
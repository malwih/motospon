{{-- ============================================================ --}}
{{-- NAVBAR UTAMA --}}
{{-- ============================================================ --}}
{{--
    Komponen header/navbar untuk dashboard
    - Fixed position di bagian atas halaman
    - Berisi logo, tombol toggle sidebar, dan menu user
    - Responsif dengan tampilan berbeda di mobile dan desktop
--}}
<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-700">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    {{-- ============================================================ --}}
{{-- KONTAINER UTAMA NAVBAR --}}
{{-- ============================================================ --}}
{{--
    Container untuk konten navbar
    - Menggunakan flexbox untuk tata letak
    - Padding untuk jarak dalam container
--}}
    <div class="flex items-center justify-between">
      {{-- Bagian Kiri Navbar (Logo & Tombol Sidebar) --}}
      <div class="flex items-center justify-start rtl:justify-end">
        {{-- ============================================================ --}}
{{-- TOMBOL TOGGLE SIDEBAR --}}
{{-- ============================================================ --}}
{{--
    Tombol untuk menampilkan/menyembunyikan sidebar di tampilan mobile
    - Hanya terlihat di layar kecil (sm:hidden)
    - Menggunakan ikon hamburger
    - Memiliki aksesibilitas dengan aria-* attributes
--}}
        <button 
          data-drawer-target="logo-sidebar" 
          data-drawer-toggle="logo-sidebar" 
          aria-controls="logo-sidebar" 
          type="button"
          class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        >
          <span class="sr-only">Open sidebar</span>
          <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
          </svg>
        </button>
        
        {{-- ============================================================ --}}
{{-- LOGO APLIKASI --}}
{{-- ============================================================ --}}
{{--
    Logo aplikasi yang mengarah ke halaman utama
    - Menggunakan gambar dari storage
    - Responsive dengan ukuran yang sesuai
    - Margin untuk jarak dengan elemen lain
--}}
        <a href="/" class="flex ms-20 md:me-24">
          <img 
            src="{{ asset('storage/img/logo.png') }}" 
            class="h-10 me-5" 
            alt="MotoSpon Logo" 
          />
        </a>
      </div>

      {{-- ============================================================ --}}
{{-- BAGIAN KANAN NAVBAR --}}
{{-- ============================================================ --}}
{{--
    Bagian kanan navbar berisi menu user
    - Menampilkan nama user yang sedang login
    - Dropdown menu untuk navigasi user
    - Tombol login jika user belum login
--}}
      <div class="relative">
        {{-- ============================================================ --}}
{{-- CEK STATUS USER --}}
{{-- ============================================================ --}}
{{--
    Memeriksa apakah user sudah login
    - Jika sudah login: tampilkan menu user
    - Jika belum login: tampilkan tombol login
--}}
        @auth
          {{-- ============================================================ --}}
{{-- TOMBOL DROPDOWN USER --}}
{{-- ============================================================ --}}
{{--
    Tombol untuk menampilkan dropdown menu user
    - Menampilkan nama user yang sedang login
    - Menggunakan ikon panah untuk indikator dropdown
    - Styling dengan warna oranye yang konsisten
--}}
          <button 
            id="userDropdownButton"
            aria-expanded="false" 
            aria-haspopup="true" 
            type="button"
            class="flex items-center bg-orange-500 hover:bg-orange-600 text-xs text-white font-bold px-4 xl:px-6 py-2 xl:py-3 rounded transition-colors duration-200"
          >
            <span class="whitespace-nowrap">Welcome, {{ auth()->user()->name }}</span>
            <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
            </svg>
          </button>

          {{-- ============================================================ --}}
{{-- MENU DROPDOWN --}}
{{-- ============================================================ --}}
{{--
    Dropdown menu yang muncul saat tombol user diklik
    - Berisi navigasi user (Home, Logout)
    - Menggunakan transisi untuk animasi halus
    - Posisi absolute di bawah tombol user
--}}
          <div 
            id="userDropdown" 
            class="z-10 hidden absolute right-0 mt-2 w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-lg dark:bg-gray-700"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="userDropdownButton"
          >
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" role="none">
              {{-- ============================================================ --}}
{{-- MENU HOME --}}
{{-- ============================================================ --}}
{{--
    Menu untuk kembali ke halaman utama
    - Mengarah ke route '/' (homepage)
    - Menggunakan ikon rumah
    - Styling hover untuk feedback interaktif
--}}
              <li role="none">
                <a 
                  href="/" 
                  class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-orange-500 dark:hover:text-white transition-colors duration-200"
                  role="menuitem"
                >
                  <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
                  Home
                </a>
              </li>
              
              {{-- ============================================================ --}}
{{-- MENU LOGOUT --}}
{{-- ============================================================ --}}
{{--
    Menu untuk logout user
    - Menggunakan form dengan method POST
    - CSRF token untuk keamanan
    - Ikon logout untuk kejelasan fungsi
--}}
              <li role="none">
                <form action="/logout" method="post" role="none">
                  @csrf
                  <button 
                    type="submit"
                    class="flex items-center w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-orange-500 dark:hover:text-white transition-colors duration-200"
                    role="menuitem"
                  >
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                  </button>
                </form>
              </li>
            </ul>
          </div>
        @else
          {{-- ============================================================ --}}
{{-- TOMBOL LOGIN --}}
{{-- ============================================================ --}}
{{--
    Tombol login yang ditampilkan jika user belum login
    - Mengarah ke halaman login
    - Styling dengan warna hitam yang berubah oranye saat hover
    - Responsif dengan ukuran teks dan padding yang sesuai
--}}
          <a href="/login">
            <button 
              type="button"
              class="bg-black hover:bg-orange-600 text-xs text-white font-bold px-4 xl:px-6 py-2 xl:py-3 rounded transition-colors duration-200 {{ ($active === 'login') ? 'active' : '' }}"
            >
              Login
            </button>
          </a>
        @endauth
      </div>

      {{-- Tombol Menu Mobile (Hanya Tampil di Mobile) --}}
      <button 
        data-collapse-toggle="navbar-sticky" 
        type="button"
        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        aria-controls="navbar-sticky"
        aria-expanded="false"
      >
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button>
    </div>
  </div>
</nav>

{{-- ============================================================ --}}
{{-- SCRIPT DROPDOWN USER --}}
{{-- ============================================================ --}}
{{--
    Script untuk mengatur perilaku dropdown menu user
    - Menangani klik di luar dropdown untuk menutup menu
    - Menutup dropdown saat item menu diklik
    - Menambahkan class 'active' untuk styling
--}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('userDropdownButton');
    const dropdownMenu = document.getElementById('userDropdown');

    // Jika tombol dropdown ada
    if (dropdownButton && dropdownMenu) {
      // Toggle dropdown saat tombol diklik
      dropdownButton.addEventListener('click', function(e) {
        e.preventDefault();
        const isHidden = dropdownMenu.classList.contains('hidden');
        
        if (isHidden) {
          dropdownMenu.classList.remove('hidden');
          dropdownButton.setAttribute('aria-expanded', 'true');
        } else {
          dropdownMenu.classList.add('hidden');
          dropdownButton.setAttribute('aria-expanded', 'false');
        }
      });

      // Tutup dropdown saat klik di luar
      document.addEventListener('click', function(event) {
        const isClickInside = dropdownButton.contains(event.target) || 
                            dropdownMenu.contains(event.target);
        
        if (!isClickInside) {
          dropdownMenu.classList.add('hidden');
          dropdownButton.setAttribute('aria-expanded', 'false');
        }
      });

      // Tutup dropdown saat tekan tombol Escape
      document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
          dropdownMenu.classList.add('hidden');
          dropdownButton.setAttribute('aria-expanded', 'false');
        }
      });
    }
  });
</script>

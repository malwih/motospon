@php
/*
    Menggunakan class Str untuk manipulasi string
    - Digunakan untuk memeriksa format URL gambar profil
*/
use Illuminate\Support\Str;
@endphp

{{-- ============================================================ --}}
{{-- SIDEBAR UTAMA --}}
{{-- ============================================================ --}}
{{--
    Komponen sidebar untuk navigasi dashboard
    - Posisi fixed di sisi kiri layar
    - Berisi profil user dan menu navigasi
    - Responsif dengan toggle untuk tampilan mobile
--}}
<aside id="logo-sidebar" 
       class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700" 
       aria-label="Sidebar">

    {{-- ============================================================ --}}
{{-- KONTAINER UTAMA SIDEBAR --}}
{{-- ============================================================ --}}
{{--
    Container untuk seluruh konten sidebar
    - Menggunakan flex column untuk tata letak vertikal
    - Background dan border untuk pemisah visual
    - Padding untuk jarak dalam container
--}}
    <div class="flex flex-col h-full bg-gray-100 p-6">
        {{-- ============================================================ --}}
{{-- KARTU PROFIL PENGGUNA --}}
{{-- ============================================================ --}}
{{--
    Kartu yang menampilkan informasi profil user
    - Berisi foto profil dan nama user
    - Shadow dan rounded corner untuk efek kartu
    - Padding dan margin yang sesuai
--}}
        <div class="bg-white rounded-lg p-8 pt-20 shadow-lg flex flex-col items-center">
            {{-- ============================================================ --}}
{{-- FOTO PROFIL --}}
{{-- ============================================================ --}}
{{--
    Komponen untuk menampilkan foto profil user
    - Prioritas tampilan: URL eksternal > penyimpanan lokal > default
    - Border oranye untuk highlight
    - Ukuran dan bentuk yang konsisten
--}}
            <div class="flex justify-center items-center mb-6">
                @php $avatar = auth()->user()->avatar; @endphp

                {{-- Menampilkan avatar pengguna dengan prioritas: URL eksternal > penyimpanan lokal > default --}}
                @if($avatar && Str::startsWith($avatar, 'http'))
                    {{-- Avatar dari Google atau URL eksternal --}}
                    <img class="w-20 h-20 rounded-full object-cover border-4 border-orange-400" 
                         src="{{ $avatar }}" 
                         alt="Google Profile Photo">
                @elseif($avatar)
                    {{-- Avatar dari penyimpanan lokal --}}
                    <img class="w-20 h-20 rounded-full object-cover border-4 border-orange-400" 
                         src="{{ asset('storage/' . $avatar) }}" 
                         alt="Profile Photo">
                @else
                    {{-- Default avatar jika tidak ada avatar yang diunggah --}}
                    <img class="w-20 h-20 rounded-full object-cover border-4 border-orange-400" 
                         src="{{ asset('storage/default-avatar.png') }}" 
                         alt="Default Profile Photo">
                @endif
            </div>

            {{-- ============================================================ --}}
{{-- MENU COMMUNITY --}}
{{-- ============================================================ --}}
{{--
    Kumpulan menu untuk user dengan role Community
    - Hanya ditampilkan jika user memiliki akses community
    - Berisi navigasi khusus community
    - Menggunakan ikon untuk kejelasan visual
--}}
            @can('community')
                <ul class="space-y-3 text-sm font-medium w-full">
                    {{-- Judul Seksi Menu --}}
                    <span class="block mb-2 text-xs font-semibold uppercase text-gray-400">Community</span>
                    
                    {{-- Item Menu Dashboard --}}
                    <li>
                        @php
                            // Menentukan apakah menu dashboard aktif berdasarkan rute saat ini
                            $isDashboardActive = (isset($activePage) && $activePage === 'dashboard') || 
                                             (request()->is('dashboard/community') && !request()->is('dashboard/community/sponsorships*')) || 
                                             request()->is('dashboard/company*') && !request()->is('dashboard/company/sponsorships*') ||
                                             request()->is('dashboard/hidden*') ||
                                             request()->is('dashboard/submitproposal*') || 
                                             Str::startsWith(request()->path(), 'dashboard/proposal') || 
                                             request()->is('dashboard/preview*') ||
                                             request()->is('proposal/preview/*');
                        @endphp
                        <a href="/dashboard/community" 
                           class="flex items-center space-x-3 p-2 rounded-md font-medium {{ $isDashboardActive ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                            <svg class="h-5 w-5 {{ $isDashboardActive ? 'text-white' : 'text-gray-600' }}" 
                                 xmlns="http://www.w3.org/2000/svg" 
                                 fill="none" 
                                 viewBox="0 0 24 24" 
                                 stroke="currentColor">
                                <path stroke-linecap="round" 
                                      stroke-linejoin="round" 
                                      stroke-width="2" 
                                      d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    {{-- Item Menu Sponsorships --}}
                    <li>
                        <a href="{{ route('community.sponsorships.index') }}" 
                           class="flex items-center space-x-3 p-2 rounded-md font-medium
                           {{ Request::is('dashboard/community/sponsorships*') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                            <svg class="h-5 w-5 {{ Request::is('dashboard/community/sponsorships*') ? 'text-white' : 'text-gray-600' }}" 
                                 xmlns="http://www.w3.org/2000/svg" 
                                 fill="none" 
                                 viewBox="0 0 24 24" 
                                 stroke="currentColor">
                                <path stroke-linecap="round" 
                                      stroke-linejoin="round" 
                                      stroke-width="2" 
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Sponsorships</span>
                        </a>
                    </li>
                    
                    {{-- Item Menu Profil Saya --}}
                    <li>
                        <a href="/dashboard/myprofile" 
                           class="flex items-center space-x-3 p-2 rounded-md font-medium
                           {{ (request()->is('dashboard/myprofile*') || request()->is('dashboard/profile*')) ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                            <svg class="h-5 w-5 {{ (request()->is('dashboard/myprofile*') || request()->is('dashboard/profile*')) ? 'text-white' : 'text-gray-600' }}" 
                                 xmlns="http://www.w3.org/2000/svg" 
                                 fill="none" 
                                 viewBox="0 0 24 24" 
                                 stroke="currentColor">
                                <path stroke-linecap="round" 
                                      stroke-linejoin="round" 
                                      stroke-width="2" 
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>My Profile</span>
                        </a>
                    </li>
                </ul>
            @endcan

            {{-- ============================================================ --}}
{{-- MENU COMPANY --}}
{{-- ============================================================ --}}
{{--
    Kumpulan menu untuk user dengan role Company
    - Hanya ditampilkan jika user memiliki akses company
    - Berisi navigasi khusus company
    - Menggunakan ikon untuk kejelasan visual
--}}
            @can('company')
                <div class="w-full">
                    {{-- Judul Seksi Menu --}}
                    <span class="block mb-2 text-xs font-semibold uppercase text-gray-400">Company</span>
                    
                    <ul class="space-y-3 text-sm font-medium">
                        {{-- Item Menu Dashboard Company --}}
                        <li>
                            @php
                                // Menentukan apakah menu dashboard company aktif berdasarkan rute saat ini
                                $isCompanyDashboardActive = request()->is('dashboard/company*') || 
                                                       request()->is('proposals/hidden*') ||
                                                       request()->is('dashboard/submitproposal*') || 
                                                       Str::startsWith(request()->path(), 'dashboard/proposal') || 
                                                       request()->is('dashboard/preview*') ||
                                                       request()->is('dashboard/proposal/edit-proposal*') ||
                                                       request()->is('dashboard/proposal/preview*') ||
                                                       request()->is('dashboard/proposal/proposal-preview*') ||
                                                       request()->is('proposal/preview/*');
                            @endphp
                            <a href="/dashboard/company" 
                               class="dashboard-menu-item flex items-center space-x-3 p-2 rounded-md font-medium {{ $isCompanyDashboardActive ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                                <svg class="h-5 w-5 {{ $isCompanyDashboardActive ? 'text-white' : 'text-gray-600' }}" 
                                     xmlns="http://www.w3.org/2000/svg" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="2" 
                                          d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        
                        {{-- Item Menu Profil Saya --}}
                        <li>
                            <a href="/dashboard/myprofile" 
                               class="flex items-center space-x-3 p-2 rounded-md font-medium
                               {{ (request()->is('dashboard/myprofile*') || request()->is('dashboard/profile*')) ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                                <svg class="h-5 w-5 {{ (request()->is('dashboard/myprofile*') || request()->is('dashboard/profile*')) ? 'text-white' : 'text-gray-600' }}" 
                                     xmlns="http://www.w3.org/2000/svg" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>My Profile</span>
                            </a>
                        </li>
                        
                        {{-- Item Menu Sponsorship --}}
                        <li>
                            <a href="/dashboard/sponsorships" 
                               class="flex items-center space-x-3 p-2 rounded-md font-medium
                               {{ Request::is('dashboard/sponsorships*') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                                <svg class="h-5 w-5 {{ Request::is('dashboard/sponsorships*') ? 'text-white' : 'text-gray-600' }}" 
                                     xmlns="http://www.w3.org/2000/svg" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="2" 
                                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <span>Sponsorship</span>
                            </a>
                        </li>
                        
                        {{-- Menu yang dinonaktifkan/dikomentari untuk referensi masa depan --}}
                        {{--
                        <li>
                            <a href="/dashboard/news" 
                               class="flex items-center space-x-3 p-2 rounded-md font-medium
                               {{ Request::is('dashboard/news*') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                                <svg class="h-5 w-5 {{ Request::is('dashboard/news*') ? 'text-white' : 'text-gray-600' }}" 
                                     xmlns="http://www.w3.org/2000/svg" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="2" 
                                          d="M6 7h6v6H6zm7 8H6v2h12v-2h-4zm1-4h4v2h-4zm0-4h4v2h-4z" />
                                </svg>
                                <span>News</span>
                            </a>
                        </li>
                        
                        <li>
                            <a href="/dashboard/student" 
                               class="flex items-center space-x-3 p-2 rounded-md font-medium
                               {{ Request::is('dashboard/student*') ? 'bg-orange-500 text-white' : 'text-gray-700 hover:bg-orange-500 hover:text-white' }}">
                                <svg class="h-5 w-5 {{ Request::is('dashboard/student*') ? 'text-white' : 'text-gray-600' }}" 
                                     xmlns="http://www.w3.org/2000/svg" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="2" 
                                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <span>Student List</span>
                            </a>
                        </li>
                        --}}
                    </ul>
                </div>
            @endcan
            {{-- Konten Sidebar --}}
        </div>
        
        {{-- ============================================================ --}}
{{-- FOOTER SIDEBAR --}}
{{-- ============================================================ --}}
{{--
    Bagian footer dari sidebar
    - Menampilkan informasi hak cipta
    - Pemisah visual dengan border atas
    - Teks kecil dan warna yang sesuai
--}}
        <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="text-center text-xs text-gray-500 dark:text-gray-400">
                <p>© {{ date('Y') }} Motospon. All rights reserved.</p>
                <p class="mt-1">v{{ config('app.version', '1.0.0') }}</p>
            </div>
        </div>
    </div>
</aside>

{{-- ============================================================ --}}
{{-- SCRIPT SIDEBAR --}}
{{-- ============================================================ --}}
{{--
    Script untuk mengatur perilaku interaktif sidebar
    - Inisialisasi komponen Flowbite
    - Menangani toggle sidebar di tampilan mobile
    - Menambahkan class aktif berdasarkan URL saat ini
--}}
@push('scripts')
<script>
    // Inisialisasi tooltip untuk menu
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi tooltip menggunakan Flowbite
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tooltip-target]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            new Tooltip(tooltipTriggerEl);
        });
        
        // Inisialisasi dropdown menu
        const dropdownButtons = [].slice.call(document.querySelectorAll('[data-dropdown-toggle]'));
        dropdownButtons.map(function (dropdownButtonEl) {
            new Dropdown(dropdownButtonEl);
        });
    });
</script>
@endpush


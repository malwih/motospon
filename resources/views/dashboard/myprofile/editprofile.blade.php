@extends('dashboard.layouts.main')

@section('title', 'Edit Profile - Motospon')

@php 
/*
    Menggunakan class Str untuk manipulasi string
    - Digunakan untuk memeriksa format URL gambar profil
*/
use Illuminate\Support\Str;

$activePage = 'myprofile';
@endphp

@section('container')

{{-- ============================================================ --}}
{{-- LIBRARY EKSTERNAL --}}
{{-- ============================================================ --}}
{{-- 
    Library yang digunakan:
    1. Cropper.js - untuk fitur crop gambar profil
    2. SweetAlert2 - untuk notifikasi interaktif
--}}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ============================================================ --}}
{{-- KONTEN UTAMA --}}
{{-- ============================================================ --}}
{{-- 
    Container utama dengan padding dan margin responsif
    - Padding untuk mobile dan desktop
    - Margin kiri untuk sidebar yang bisa disembunyikan
--}}
<div class="w-full p-10 sm:ml-64">
    {{-- 
        Card utama untuk form edit profil
        - Shadow dan border untuk efek kedalaman
        - Lebar maksimum 3xl untuk tampilan yang optimal
        - Margin atas 20 untuk memberikan jarak dari header
    --}}
    <div class="p-6 border border-gray-200 rounded-lg shadow-md bg-white max-w-3xl mx-auto mt-20">
        {{-- 
            Header card dengan judul halaman
            - Menggunakan flexbox untuk tata letak yang rapi
            - Border bottom sebagai pemisah visual
        --}}
        <div class="flex justify-between items-center pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
        </div>

        {{-- ============================================================ --}}
        {{-- NOTIFIKASI --}}
        {{-- ============================================================ --}}
        {{-- 
            Notifikasi sukses menggunakan AlpineJS
            - Muncul ketika ada session success
            - Bisa ditutup dengan tombol close
        --}}
        @if(session()->has('success'))
        <div class="flex items-center p-4 mt-4 mb-6 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- 
            Peringatan untuk nomor WhatsApp yang belum lengkap
            - Muncul ketika user diarahkan dari halaman lain yang membutuhkan nomor WhatsApp
            - Dapat ditutup oleh pengguna
        --}}
        @if(session('require_whatsapp'))
        <div class="relative p-4 pr-10 mt-4 mb-4 text-sm text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50" role="alert">
            <span class="font-medium">Important!</span> Please complete your WhatsApp number to continue.
            <button type="button" class="absolute top-1/2 right-3 transform -translate-y-1/2 text-xl text-yellow-800 hover:text-yellow-900 focus:outline-none" data-dismiss="alert" aria-label="Close">
                &times;
            </button>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- FORM EDIT PROFIL --}}
        {{-- ============================================================ --}}
        {{-- 
            Form untuk mengupdate data profil pengguna
            - Menggunakan method POST dengan enctype multipart untuk upload file
            - CSRF token untuk keamanan
            - Method spoofing untuk menggunakan PUT
        --}}
        <form id="editForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-6">
            @csrf
            @method('PUT')

            {{-- 
                Unggah Foto Profil
                - Container untuk upload foto profil
                - Mendukung drag and drop
                - Preview gambar yang dipilih
            --}}
            <div class="flex justify-center mb-8">
                {{-- 
                    Container untuk foto profil
                    - Menggunakan group untuk efek hover
                    - Posisi relatif untuk overlay
                --}}
                <div class="relative w-28 h-28 group cursor-pointer">
                    @php $avatar = $user->avatar; @endphp

                    {{-- 
                        Tampilkan gambar profil dengan prioritas:
                        1. URL eksternal (untuk Google Photo)
                        2. Penyimpanan lokal
                        3. Gambar default
                    --}}
                    @if($avatar && Str::startsWith($avatar, 'http'))
                        <img src="{{ $avatar }}" class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" alt="Google Photo">
                    @elseif($avatar)
                        <img src="{{ asset('storage/' . $avatar) }}" class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" alt="Profile Photo">
                    @else
                        <img src="{{ asset('storage/default-avatar.png') }}" class="w-28 h-28 rounded-full object-cover border-4 border-orange-400" alt="Default Photo">
                    @endif

                    {{-- 
                        Overlay untuk tombol unggah
                        - Muncul saat hover pada container
                        - Transisi halus untuk efek visual
                    --}}
                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center text-white font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                        Ubah
                    </div>
                    {{-- 
                        Input file yang tersembunyi
                        - Menerima semua tipe gambar
                        - Posisi absolut menutupi container
                    --}}
                    <input type="file" name="avatar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 rounded-full cursor-pointer">
                </div>
            </div>
            {{-- 
                Input tersembunyi untuk menyimpan hasil crop gambar
                - Akan diisi oleh JavaScript setelah proses crop
                - Berisi data URL dari gambar yang sudah dicrop
            --}}
            <input type="hidden" name="cropped_avatar" id="croppedAvatar">
            @error('avatar')
                <p class="text-sm text-red-600 text-center mt-1">{{ $message }}</p>
            @enderror

            {{-- ============================================================ --}}
            {{-- FIELD DATA PROFIL --}}
            {{-- ============================================================ --}}
            {{-- 
                Container untuk field-form data profil
                - Shadow dan border untuk efek kedalaman
                - Padding internal untuk konsistensi
            --}}
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="px-6 py-6">
                    {{-- 
                        Menggunakan description list untuk tata letak form yang rapi
                        - Garis pemisah antar field
                    --}}
                    <dl class="divide-y divide-gray-200">
                        {{-- 
                            Konfigurasi field yang akan ditampilkan
                            - Key: nama field di database
                            - Value: label yang ditampilkan
                        --}}
                        @php
                            $fields = [
                                'name' => 'Full Name',
                                'username' => 'Username',
                                'email' => 'Email',
                                'whatsapp_number' => 'WhatsApp'
                            ];
                        @endphp

                        {{-- 
                            Loop untuk menampilkan setiap field
                            - Setiap field memiliki container dengan padding
                            - Validasi error ditampilkan di bawah field
                        --}}
                        @foreach ($fields as $field => $label)
                        <div class="py-4">
                            <label for="{{ $field }}" class="block text-sm font-medium text-gray-600 mb-1">{{ $label }}</label>
                            
                            {{-- 
                                Input khusus untuk nomor WhatsApp
                                - Placeholder dengan format nomor Indonesia
                                - Required hanya jika diwajibkan
                            --}}
                            @if($field === 'whatsapp_number')
                                <input type="text"
                                       name="{{ $field }}"
                                       id="{{ $field }}"
                                       value="{{ old($field, $user->$field) }}"
                                       class="border border-gray-300 rounded-md shadow-sm block w-full px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500 @error($field) border-red-500 @enderror"
                                       placeholder="+6281234567890"
                                       {{ $requireWhatsApp ? 'required' : '' }}>
                            @else
                                {{-- 
                                    Input untuk field teks/email biasa
                                    - Tipe input disesuaikan (email/text)
                                    - Wajib diisi kecuali password
                                --}}
                                <input type="{{ $field === 'email' ? 'email' : 'text' }}"
                                       name="{{ $field }}"
                                       id="{{ $field }}"
                                       value="{{ old($field, $user->$field) }}"
                                       class="border border-gray-300 rounded-md shadow-sm block w-full px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500 @error($field) border-red-500 @enderror"
                                       required>
                            @endif
                            
                            {{-- 
                                Tampilkan pesan validasi error
                                - Warna merah untuk menandakan error
                                - Pesan dari Laravel validation
                            --}}
                            @error($field)
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @endforeach

                        {{-- ============================================================ --}}
                        {{-- FIELD PASSWORD --}}
                        {{-- ============================================================ --}}
                        {{-- 
                            Grid untuk field password
                            - 1 kolom di mobile, 2 kolom di desktop
                            - Jarak antar kolom 6 unit
                        --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- 
                                Input Password Baru
                                - Container dengan posisi relatif untuk ikon toggle
                            --}}
                            <div class="py-4">
                                <label for="password" class="block text-sm font-medium text-gray-600 mb-1">New Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           name="password" 
                                           id="password"
                                           class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 pr-10 text-sm focus:ring-orange-500 focus:border-orange-500 @error('password') border-red-500 @enderror"
                                           placeholder="(Optional)"
                                           autocomplete="new-password">
                                    {{-- 
                                        Tombol toggle visibility password
                                        - Posisi absolut di dalam container input
                                        - Ikon mata untuk indikator visibilitas
                                    --}}
                                    <button type="button" 
                                            tabindex="-1" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                                            onclick="togglePasswordVisibility('password', this)" 
                                            aria-label="Toggle Password Visibility">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 
                                Konfirmasi Password
                                - Harus sama dengan password baru
                                - Validasi dilakukan di sisi klien dan server
                            --}}
                            <div class="py-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-600 mb-1">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation"
                                           class="border border-gray-300 rounded-md shadow-sm block w-full py-2 px-3 pr-10 text-sm focus:ring-orange-500 focus:border-orange-500"
                                           placeholder="(Optional)"
                                           autocomplete="new-password">
                                    {{-- 
                                        Tombol toggle visibility password konfirmasi
                                        - Fungsionalitas sama dengan field password
                                    --}}
                                    <button type="button" 
                                            tabindex="-1"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                                            onclick="togglePasswordVisibility('password_confirmation', this)" 
                                            aria-label="Toggle Password Visibility">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- TOMBOL AKSI --}}
            {{-- ============================================================ --}}
            {{-- 
                Container untuk tombol aksi
                - Menggunakan flexbox untuk tata letak sejajar
                - Jarak antar tombol 4 unit
            --}}
            <div class="flex justify-between mt-6 space-x-4">
                {{-- 
                    Tombol Kembali
                    - Warna kuning untuk aksi sekunder
                    - Mengarahkan ke halaman profil
                --}}
                <a href="/dashboard/myprofile"
                    class="w-1/2 py-2.5 rounded-2xl text-center bg-yellow-500 text-white font-semibold hover:bg-yellow-600 transition duration-300">
                    Back
                </a>
                {{-- 
                    Tombol Simpan Perubahan
                    - Warna oranye untuk aksi utama
                    - Trigger submit form
                --}}
                <button type="submit"
                    class="w-1/2 py-2.5 rounded-2xl text-center bg-orange-500 text-white font-semibold hover:bg-orange-700 transition duration-300">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL CROP GAMBAR --}}
{{-- ============================================================ --}}
{{-- 
    Modal untuk memotong gambar profil
    - Posisi fixed menutupi seluruh layar
    - Background semi-transparan
    - Z-index tinggi agar muncul di atas konten lain
--}}
<div id="cropModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    {{-- 
        Container modal
        - Background putih
        - Shadow untuk efek kedalaman
        - Lebar maksimum dan tinggi responsif
    --}}
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md max-h-screen overflow-auto">
        {{-- Judul modal --}}
        <div class="text-lg font-bold mb-4 text-center">Crop Your Avatar</div>
        
        {{-- Container untuk preview gambar yang akan dipotong --}}
        <div class="flex justify-center">
            <img id="imageToCrop" class="max-w-full max-h-96 object-contain rounded-md shadow" alt="Preview Gambar" />
        </div>
        
        {{-- 
            Tombol aksi modal
            - Rata kanan dengan jarak antar tombol
            - Warna abu untuk batal, oranye untuk konfirmasi
        --}}
        <div class="mt-4 flex justify-end space-x-2">
            <button id="cancelCrop" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
            <button id="confirmCrop" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Crop</button>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================ --}}
{{-- 
    Script untuk menangani interaksi pada halaman edit profil
    - Konfirmasi sebelum submit form
    - Fungsi crop gambar
    - Toggle visibility password
--}}
<script>
    // Inisialisasi saat dokumen selesai dimuat
    document.addEventListener('DOMContentLoaded', function () {
        // ======================================================
        // KONFIRMASI SEBELUM SUBMIT FORM
        // ======================================================
        const form = document.getElementById('editForm');
        form.addEventListener('submit', function (e) {
            // Mencegah form submit default
            e.preventDefault();
            
            // Tampilkan konfirmasi menggunakan SweetAlert2
            Swal.fire({
                title: 'Update Profile?',
                text: "Please ensure all data is correct before updating.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                didOpen: () => {
                    // Styling tombol konfirmasi
                    Swal.getConfirmButton().style.background = '#16a34a'; // Hijau
                    Swal.getCancelButton().style.background = '#d33';     // Merah
                    Swal.getConfirmButton().style.color = '#fff';
                    Swal.getCancelButton().style.color = '#fff';
                }
            }).then((result) => {
                // Jika user mengkonfirmasi, submit form
                if (result.isConfirmed) form.submit();
            });
        });

        // ======================================================
        // INISIALISASI VARIABEL UNTUK CROPPER
        // ======================================================
        let cropper; // Menyimpan instance Cropper
        const avatarInput = document.querySelector('input[name="avatar"]');
        const cropModal = document.getElementById('cropModal');
        const imageToCrop = document.getElementById('imageToCrop');
        const croppedAvatarInput = document.getElementById('croppedAvatar');
        const previewImage = document.querySelector('.group img');

        // ======================================================
        // EVENT LISTENER UNTUK UPLOAD GAMBAR
        // ======================================================
        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return; // Keluar jika tidak ada file yang dipilih

            // Validasi tipe file
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file');
                return;
            }

            // Baca file yang dipilih menggunakan FileReader
            const reader = new FileReader();
            
            // Event ketika file selesai dibaca
            reader.onload = function (event) {
                // Tampilkan gambar di modal crop
                imageToCrop.src = event.target.result;
                cropModal.classList.remove('hidden');

                // Hancurkan instance Cropper yang ada (jika ada)
                if (cropper) cropper.destroy();
                
                // Inisialisasi Cropper dengan konfigurasi
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1,   // Rasio 1:1 (persegi)
                    viewMode: 1,     // Mode tampilan 1 (membatasi crop area)
                    autoCropArea: 1, // Area crop otomatis 100%
                    minContainerWidth: 300, // Lebar minimum container
                    minContainerHeight: 300 // Tinggi minimum container
                });
            };
            
            // Mulai membaca file sebagai URL data
            reader.readAsDataURL(file);
        });

        // ======================================================
        // TOMBOL BATAL CROP
        // ======================================================
        document.getElementById('cancelCrop').addEventListener('click', () => {
            // Sembunyikan modal dan reset input file
            cropModal.classList.add('hidden');
            avatarInput.value = '';
            
            // Hancurkan instance Cropper (jika ada)
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        // ======================================================
        // TOMBOL KONFIRMASI CROP
        // ======================================================
        document.getElementById('confirmCrop').addEventListener('click', () => {
            // Dapatkan canvas dari gambar yang sudah dicrop
            const canvas = cropper.getCroppedCanvas({
                width: 300,  // Lebar output
                height: 300, // Tinggi output
                minWidth: 256,
                minHeight: 256,
                maxWidth: 1024,
                maxHeight: 1024,
                fillColor: '#fff', // Warna latar belakang
                imageSmoothingEnabled: true, // Aktifkan smoothing
                imageSmoothingQuality: 'high' // Kualitas smoothing
            });
            
            // Konversi canvas ke blob (format biner)
            canvas.toBlob(function (blob) {
                const reader = new FileReader();
                
                // Setelah konversi selesai
                reader.onloadend = function () {
                    const base64data = reader.result;
                    
                    // Simpan hasil crop ke input tersembunyi
                    croppedAvatarInput.value = base64data;
                    
                    // Update preview gambar profil
                    previewImage.src = base64data;
                    
                    // Sembunyikan modal
                    cropModal.classList.add('hidden');
                    
                    // Hancurkan instance Cropper
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                };
                
                // Baca blob sebagai URL data
                reader.readAsDataURL(blob);
                
            }, 'image/jpeg', 0.9); // Format JPEG dengan kualitas 90%
        });
    });

    /**
     * Mengubah tipe input password antara text dan password
     * @param {string} inputId - ID dari input yang akan diubah
     * @param {HTMLElement} button - Tombol yang ditekan
     */
    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        if (!input) return; // Keluar jika input tidak ditemukan
        
        if (input.type === "password") {
            // Ubah ke text dan ganti ikon menjadi mata tertutup
            input.type = "text";
            button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.958 9.958 0 012.223-3.526m2.43-2.43A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.963 9.963 0 01-4.254 5.618M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
            </svg>`;
        } else {
            // Kembalikan ke password dan ganti ikon menjadi mata terbuka
            input.type = "password";
            button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>`;
        }
        
        // Fokus kembali ke input setelah mengubah tipe
        input.focus();
    }
</script>

@endsection
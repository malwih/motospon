@extends('layouts.main')

@section('container')
{{-- 
    Style untuk section skill
    - Mengatur tampilan container dan animasi logo mitra
--}}
<style>
    /* Style untuk container skill */

.container-skill {
  width: 100%;
  text-align: center;
  font-size: 20px;
  margin: 0 auto;
  height: 500px;
  box-sizing: border-box;
  flex: 2;
  padding: 50px;
}

.container-skill h1 {
  font-family: var(--poppins);
}



.slide-skill ul {
  list-style: none;
  text-align: center;
  display: flex;
}

.slide-skill {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.slide-skill img {
  width: 150px;
  margin: 60px;
  transition: 1s;
}

.slide-skill img:hover {
  transform: rotate(360deg) scale(1.1);
}
</style>



{{-- 
    Hero Section
    - Menampilkan judul utama dan deskripsi singkat
    - Menampilkan tombol aksi untuk registrasi/login
--}}
<section class="p-20 dark:bg-gray-100 dark:text-gray-100">
    <div class="container grid gap-2 mx-auto text-center lg:grid-cols-2 xl:grid-cols-5">
        <div class="w-full px-6 py-16 rounded-md sm:px-12 md:px-16 xl:col-span-2 dark:bg-white">
            <span class="block mb-2 text-orange-500 font-semibold">MOTOSPON</span>
            <h1 class="text-5xl font-extrabold text-gray-900">Wujudkan Kolaborasi yang Menguntungkan</h1>
            <p class="my-8 text-gray-600">
                <span class="font-medium">Platform terpercaya yang mempertemukan komunitas motor dengan sponsor potensial untuk menciptakan sinergi di dunia otomotif Bandung.</span>
            </p>
            @guest
                <div class="flex flex-col text-center space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4 justify-center">
                    <a href="/register" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 text-center">
                        Register
                    </a>
                    <a href="/login" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 text-center">
                        Login
                    </a>
                </div>
            @endguest
        </div>
        <div class="relative xl:col-span-3">
            <img src="{{ asset('storage/img/hero-home.jpg') }}" alt="Komunitas Motor" class="object-cover w-full h-full rounded-lg shadow-2xl">
            <div class="absolute -bottom-4 -right-4 bg-orange-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <span class="font-bold">100+</span> Komunitas Terdaftar
            </div>
        </div>
    </div>
</section>

{{-- 
    Section Fitur Unggulan
    - Menampilkan keunggulan platform Motospon
    - Setiap fitur memiliki ikon dan deskripsi
--}}
<div id="features" class="container relative flex flex-col justify-between h-full max-w-6xl px-10 mx-auto xl:px-0 mt-5 mb-20">
    <h2 class="flex justify-center mb-12 mt-3 text-3xl font-extrabold leading-tight text-orange-500">Mengapa Memilih Motospon?</h2>
    <div class="w-full">
        <div class="flex flex-col w-full mb-10 sm:flex-row">
            <div class="w-full mb-10 sm:mb-0 sm:w-1/2">
                <div class="relative h-full ml-0 mr-0 sm:mr-10 hover:transform hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-0 left-0 w-full h-full mt-1 ml-1 bg-orange-500 rounded-lg"></span>
                    <div class="relative h-full p-6 bg-white border-2 border-orange-500 rounded-lg">
                        <div class="flex items-center -mt-1">
                            <div class="p-3 bg-orange-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="my-2 ml-4 text-xl font-bold text-gray-800">Jaringan Komunitas Luas</h3>
                        </div>
                        <p class="mt-4 text-gray-600">Terhubung dengan ratusan komunitas motor di Bandung dan sekitarnya. Temukan mitra yang tepat untuk kegiatan touring, gathering, atau event komunitas Anda.</p>
                    </div>
                </div>
            </div>
            <div class="w-full sm:w-1/2">
                <div class="relative h-full ml-0 md:mr-10 hover:transform hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-0 left-0 w-full h-full mt-1 ml-1 bg-blue-500 rounded-lg"></span>
                    <div class="relative h-full p-6 bg-white border-2 border-blue-500 rounded-lg">
                        <div class="flex items-center -mt-1">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="my-2 ml-4 text-xl font-bold text-gray-800">Sponsorship Tepat Sasaran</h3>
                        </div>
                        <p class="mt-4 text-gray-600">Dapatkan akses ke berbagai brand dan sponsor yang tertarik untuk mendukung kegiatan komunitas motor. Kami membantu Anda menemukan kesesuaian yang tepat antara kebutuhan sponsor dan karakteristik komunitas Anda.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col w-full mb-5 sm:flex-row">
            <div class="w-full mb-10 sm:mb-0 sm:w-1/3">
                <div class="relative h-full ml-0 mr-0 sm:mr-10 hover:transform hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-0 left-0 w-full h-full mt-1 ml-1 bg-green-500 rounded-lg"></span>
                    <div class="relative h-full p-6 bg-white border-2 border-green-500 rounded-lg">
                        <div class="flex items-center -mt-1">
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="my-2 ml-4 text-lg font-bold text-gray-800">Kemudahan Berkolaborasi</h3>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">Proses kolaborasi yang mudah antara komunitas dan sponsor. Kirim proposal, negosiasi, hingga kesepakatan bisa dilakukan dalam satu platform.</p>
                    </div>
                </div>
            </div>
            <div class="w-full mb-10 sm:mb-0 sm:w-1/3">
                <div class="relative h-full ml-0 mr-0 sm:mr-10 hover:transform hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-0 left-0 w-full h-full mt-1 ml-1 bg-purple-500 rounded-lg"></span>
                    <div class="relative h-full p-6 bg-white border-2 border-purple-500 rounded-lg">
                        <div class="flex items-center -mt-1">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="my-2 ml-4 text-lg font-bold text-gray-800">Fleksibel & Real-time</h3>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">Pantau perkembangan event dan sponsor secara real-time melalui dashboard yang mudah digunakan. Update informasi dan laporan bisa diakses kapan saja, di mana saja.</p>
                    </div>
                </div>
            </div>
            <div class="w-full sm:w-1/3">
                <div class="relative h-full ml-0 md:mr-10 hover:transform hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-0 left-0 w-full h-full mt-1 ml-1 bg-red-500 rounded-lg"></span>
                    <div class="relative h-full p-6 bg-white border-2 border-red-500 rounded-lg">
                        <div class="flex items-center -mt-1">
                            <div class="p-3 bg-red-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                </svg>
                            </div>
                            <h3 class="my-2 ml-4 text-lg font-bold text-gray-800">Portofolio Digital</h3>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">Tampilkan portofolio komunitas atau brand Anda dengan profesional. Dokumentasikan setiap event dan kolaborasi untuk referensi di masa depan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- 
    Section Kolaborasi
    - Menampilkan logo mitra yang sudah bekerja sama
    - Dilengkapi dengan efek hover pada logo
--}}
<section id="skill" class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-center text-3xl font-bold text-gray-800 mb-12">Kolaborasi Kami</h2>
        <div class="slide-skill">
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12">
                <div class="bg-blue-600 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <img src="{{ asset('storage/img/Logo Yamaha Jabar.png') }}" alt="Yamaha Jabar" class="h-16 object-contain" />
                </div>
                <div class="bg-gray-800 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <img src="{{ asset('storage/img/Logo AES Putih Teambullaes.png') }}" alt="AES Team Bullaes" class="h-16 object-contain" />
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <img src="{{ asset('storage/img/Logo Maxi Journey.png') }}" alt="Maxi Journey" class="h-16 object-contain" />
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <img src="{{ asset('storage/img/Logo Aerox Bandung.png') }}" alt="Aerox Bandung" class="h-16 object-contain" />
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 
    Section Kontak
    - Menampilkan informasi kontak dalam bentuk card
    - Terdapat ikon untuk setiap jenis kontak (email, lokasi, telepon)
--}}
    <!-- Contact Us -->
    <section class="bg-gray-50 py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Hubungi Kami</h2>
                <div class="w-20 h-1 bg-orange-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">Tim kami siap membantu Anda. Hubungi kami melalui informasi di bawah ini atau kirim pesan melalui formulir kontak.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Email -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 text-center mb-3">Email</h3>
                    <p class="text-gray-600 text-center mb-2">Kirim email kepada kami kapan saja</p>
                    <a href="mailto:motospon@gmail.com" class="block text-orange-500 hover:text-orange-600 font-medium text-center">motospon@gmail.com</a>
                </div>

                <!-- Lokasi -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 text-center mb-3">Lokasi</h3>
                    <p class="text-gray-600 text-center mb-2">Kunjungi kantor kami</p>
                    <p class="text-gray-700 text-center">Jl. Setiabudi No. 123<br>Bandung, Jawa Barat<br>Indonesia 40152</p>
                </div>

                <!-- Telepon -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 text-center mb-3">Telepon</h3>
                    <p class="text-gray-600 text-center mb-2">Senin - Jumat, 08.00 - 17.00 WIB</p>
                    <a href="tel:0212559532" class="block text-orange-500 hover:text-orange-600 font-medium text-center text-xl">(021) 255 9532</a>
                </div>
            </div>
        </div>
    </section>



@endsection

@push('scripts')
<script>
    // Add smooth scrolling to top when clicking the back to top button
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.createElement('button');
        backToTopButton.className = 'fixed bottom-8 right-8 p-3 bg-orange-500 text-white rounded-full shadow-lg hover:bg-orange-600 transition-colors duration-200 opacity-0 invisible';
        backToTopButton.innerHTML = `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
        `;
        backToTopButton.title = 'Back to top';
        document.body.appendChild(backToTopButton);

        // Show/hide back to top button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.remove('opacity-100', 'visible');
                backToTopButton.classList.add('opacity-0', 'invisible');
            }
        });

        // Smooth scroll to top when clicked
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>
@endpush
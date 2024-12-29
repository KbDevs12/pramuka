<section id="hero" class="hero max-w-5xl mt-[64px] swiper md:rounded-xl">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img src="images/hero-item-1.jpeg" alt="hero images">
            <div class="slide-overlay">
                <h1>Selamat datang di Eduzillen</h1>
                <p class="text-gray-400">EduZillen.id adalah sebuah tempat untuk para pemuda/i yang berfokus pada
                    penyediaan akses,
                    pendidikan, dan fasilitator dengan pendekatan khusus terhadap kepemimpinan, pendidikan, dan gerakan
                    sosial.</p>
            </div>
        </div>
        <div class="swiper-slide">
            <img src="images/hero-item-2.jpeg" alt="hero images">
            <div class="slide-overlay">
                <h1>Selamat datang di Eduzillen</h1>
                <p class="text-gray-400">ceritanya content</p>
            </div>
        </div>
    </div>
    <div class="swiper-pagination"></div>
    <a href="#"
        class="absolute bottom-10 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white px-4 py-2 rounded-lg z-40 hover:bg-blue-700 transition">
        <svg class="w-5 h-5 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7v14"></path>
        </svg>
        Juknis
    </a>
</section>

<section class="max-w-4xl mx-auto">
    <div class="grid md:grid-cols-2 gap-6 mt-8">
        <!-- Total Registrants -->
        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Jumlah Pendaftar</p>
                    <p id="registrants-count" data-target="<?= $totalTransaksi ?>"
                        class="text-2xl font-bold text-blue-600 mt-2">0</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c3 0 5-2 5-5s-2-5-5-5-5 2-5 5 2 5 5 5zm0 0v6m0 0H9m3 0h3"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Bases -->
        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Jumlah Pangkalan</p>
                    <p id="bases-count" data-target="<?= $totalPangkalan ?>"
                        class="text-2xl font-bold text-purple-600 mt-2">0</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A2 2 0 006.829 19h10.342a2 2 0 001.708-.196l3.121-1.804a1 1 0 000-1.608L18.879 14.2a2 2 0 00-1.708-.196L12 15.82l-5.171-2.816a2 2 0 00-1.708-.196L2 13.4a1 1 0 000 1.608l3.121 1.804z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>
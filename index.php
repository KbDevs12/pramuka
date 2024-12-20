<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eduzillen | Registrasi</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <script src="js/tailwind.config.js"></script>

    <!-- swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        body {
            scroll-behavior: smooth !important;
        }
    </style>
</head>

<body class="font-poppins antialiased">

    <?php include('includes/navbar.php'); ?>
    <!-- hero section -->
    <div id="top"></div>
    <?php include('includes/hero.php'); ?>
    <!-- pendaftaran section -->
    <?php include('includes/pendaftaran.php'); ?>

    <!-- Transaction Status Section -->
    <section class="mt-20 max-w-5xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-primary rounded-full -translate-y-1/2 translate-x-1/2">
            </div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary rounded-full translate-y-1/2 -translate-x-1/2">
            </div>

            <!-- Content Container -->
            <div class="relative z-10">
                <!-- Header -->
                <div class="text-center mb-10">
                    <h1 class="font-bold text-3xl md:text-4xl text-gray-800 mb-4">Cek Status Pendaftaran</h1>
                    <p class="text-gray-600 max-w-md mx-auto">Masukkan kode transaksi Anda untuk melihat status
                        pendaftaran</p>
                </div>

                <!-- Form -->
                <form action="transaksi_status.php" method="get" class="max-w-md mx-auto">
                    <div class="relative mb-6 group">
                        <!-- Input Field -->
                        <input type="text" id="trx-code" name="trx-code" required
                            placeholder="Contoh: TRX-1234-56-YY-MM-DD" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg outline-none transition-all duration-300
                        focus:border-blue-500 focus:ring-4 focus:ring-blue-100 bg-white
                        text-gray-700 placeholder-gray-400">

                        <!-- Icon -->
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg
                    transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg
                    focus:outline-none focus:ring-4 focus:ring-blue-100">
                        Cek Status Pendaftaran
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-sm text-gray-500">
                        Kode transaksi dapat ditemukan saat anda melakukan pendaftaran
                    </p>
                </div>
            </div>
        </div>
        <div class="grid md:grid-cols-3 gap-6 mt-12">
            <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2">Verifikasi Cepat</h3>
                <p class="text-gray-600 text-sm">Status pendaftaran diperbarui secara real-time</p>
            </div>

            <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2">Data Aman</h3>
                <p class="text-gray-600 text-sm">Informasi Anda dilindungi dengan enkripsi</p>
            </div>

            <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg mb-2">Bantuan 24/7</h3>
                <p class="text-gray-600 text-sm">Tim support siap membantu kapan saja</p>
            </div>
        </div>
    </section>

    <section id="tentang" class="mt-20 max-w-5xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-primary rounded-full -translate-y-1/2 translate-x-1/2">
            </div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary rounded-full translate-y-1/2 -translate-x-1/2">
            </div>

            <!-- Content Container -->
            <div class="relative z-10">
                <div class="text-center mb-10">
                    <h2 class="font-bold text-3xl md:text-4xl text-gray-800 mb-4">
                        Gathering/Latihan Gabungan Resmi
                    </h2>
                    <p class="text-gray-600">Jabodetabek Open</p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Registration Details -->
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 text-blue-500">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Pendaftaran</h3>
                                <p class="text-gray-600">06 Januari 2025 s.d. 26 Maret 2025</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 text-blue-500">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Technical Meeting (TM)</h3>
                                <p class="text-gray-600">Jum'at, 18 April 2025</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 text-blue-500">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Pelaksanaan Acara</h3>
                                <p class="text-gray-600">29-31 Mei 2025</p>
                            </div>
                        </div>
                    </div>

                    <!-- Location and Other Details -->
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 text-blue-500">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Lokasi</h3>
                                <p class="text-gray-600">CIBUBUR (BUPERTA Jaktim)</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 text-blue-500">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Kuota</h3>
                                <p class="text-gray-600">100 Sangga</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-trophy">
                                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
                                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
                                    <path d="M4 22h16" />
                                    <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" />
                                    <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" />
                                    <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Reward Juara 1 & 2</h3>
                                <p class="text-gray-600">Go to Malaysia and Singapore for free</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 text-center">
                    <a href="#pendaftaran" class="bg-primary hover:bg-blue-600 text-white font-semibold py-3 px-8 rounded-lg
                    transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg
                    focus:outline-none focus:ring-4 focus:ring-blue-100">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include('includes/footer.php'); ?>
    <!-- script -->
    <script src="js/menu.js"></script>
    <script src="js/validator.js"></script>
    <script src="js/main.js"></script>
    <script src="js/swiper.config.js"></script>
</body>

</html>
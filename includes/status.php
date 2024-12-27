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
                    <input type="text" id="trx-code" name="trx-code" required placeholder="Contoh: TRX-1234-56-YY-MM-DD"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg outline-none transition-all duration-300
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
        <!-- Info Cards -->
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

    <!-- Additional Cards -->
    <div class="grid md:grid-cols-2 gap-6 mt-8">
        <!-- Total Registrants -->
        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Jumlah Pendaftar</p>
                    <p class="text-2xl font-bold text-blue-600 mt-2">
                        <?= number_format($totalTransaksi) ?>
                    </p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Bases -->
        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Jumlah Pangkalan</p>
                    <p class="text-2xl font-bold text-purple-600 mt-2">
                        <?= number_format($totalPangkalan) ?>
                    </p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

</section>
<nav class="p-5 md:rounded-full sticky top-5 z-50 bg-blue-500 max-w-5xl mx-auto">
    <div class="max-w-5xl flex justify-between items-center mx-auto">
        <div>
            <p class="text-xl font-bold">Eduzillen</p>
        </div>
        <div class="hidden md:flex gap-x-8 items-center">
            <a href="#top" class="text-gray-700 hover:text-blue-600">Beranda</a>
            <a href="#pendaftaran" class="text-gray-700 hover:text-blue-600">Pendaftaran</a>
            <a href="#tentang" class="text-gray-700 hover:text-blue-600">Tentang</a>
        </div>
        <!-- Menu button -->
        <button id="menu-toggle" class="block md:hidden text-gray-700 hover:text-blue-600 focus:outline-none">
            <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
            <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden mt-4 hidden flex-col items-center justify-center gap-y-1">
        <a href="#" class="block text-gray-700 hover:text-blue-600 py-2">Beranda</a>
        <a href="#pendaftaran" class="block text-gray-700 hover:text-blue-600 py-2">Pendaftaran</a>
        <a href="#tentang" class="block text-gray-700 hover:text-blue-600 py-2">Tentang</a>
    </div>
</nav>
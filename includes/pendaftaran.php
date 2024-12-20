<section id="pendaftaran" class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row items-center justify-center gap-y-6 md:gap-x-8 mb-12">
        <img src="images/logo.PNG" class="w-48 md:w-64" alt="logo">
        <img src="images/Logo-pradata.png" class="w-48 md:w-64" alt="logo pradata">
    </div>

    <div class="bg-white rounded-2xl shadow-lg max-w-2xl mx-auto">
        <!-- Form header -->
        <div class="bg-secondary rounded-t-2xl px-6 py-8 text-center">
            <h1 class="font-bold text-2xl md:text-3xl text-white">Formulir Pendaftaran</h1>
            <p class="text-blue-100 mt-2">Silahkan isi data dengan lengkap dan benar</p>
        </div>

        <!-- Form content -->
        <form action="pembayaran.php" method="post" class="p-6 space-y-6">
            <div class="space-y-2">
                <label for="nama_sekolah" class="block text-sm font-semibold text-gray-700">Nama Sekolah</label>
                <input type="text" id="nama_sekolah" name="nama_sekolah"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Masukkan nama sekolah">
            </div>

            <div class="space-y-2">
                <label for="pangkalan" class="block text-sm font-semibold text-gray-700">Pangkalan</label>
                <input type="text" id="pangkalan" name="pangkalan"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Masukkan pangkalan">
            </div>

            <div class="space-y-2">
                <label for="kwaran" class="block text-sm font-semibold text-gray-700">Kwaran</label>
                <input type="text" id="kwaran" name="kwaran"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Masukkan kwaran">
            </div>

            <div class="space-y-2">
                <label for="kwarlab" class="block text-sm font-semibold text-gray-700">Kwarlab</label>
                <input type="text" id="kwarlab" name="kwarlab"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Masukkan kwarlab">
            </div>

            <div class="space-y-2">
                <label for="pembina" class="block text-sm font-semibold text-gray-700">Pembina</label>
                <input type="text" id="pembina" name="pembina"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Masukkan nama pembina">
            </div>

            <div class="space-y-2">
                <label for="alamat_sekolah" class="block text-sm font-semibold text-gray-700">Alamat Sekolah</label>
                <textarea id="alamat_sekolah" name="alamat_sekolah" rows="3"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Masukkan alamat lengkap sekolah"></textarea>
            </div>

            <div class="space-y-2">
                <label for="no_gugus" class="block text-sm font-semibold text-gray-700">Nomor Gugus Depan</label>
                <input type="text" id="no_gugus" name="no_gugus"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Masukkan nomor gugus depan">
            </div>

            <div class="space-y-2">
                <label for="no_telp" class="block text-sm font-semibold text-gray-700">No. Telp</label>
                <input type="tel" id="no_telp" name="no_telp"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Masukkan nomor telepon">
            </div>
            <div class="space-y-2">
                <label for="kategori_perlombaan" class="block text-sm font-semibold text-gray-700">Jenis
                    Perlombaan</label>
                <select id="kategori_perlombaan" name="kategori_perlombaan"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200">
                    <option value="">Pilih kategori perlombaan</option>
                    <option value="lkbb">LKBB</option>
                    <option value="materi">Materi</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="metode_pembayaran" class="block text-sm font-semibold text-gray-700">Metode
                    Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200">
                    <option value="">Pilih metode pembayaran</option>
                    <option value="qris">Qris</option>
                    <option value="transfer">Transfer</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="jenis_pembayaran" class="block text-sm font-semibold text-gray-700">Metode
                    Pembayaran</label>
                <select id="jenis_pembayaran" name="jenis_pembayaran"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200">
                    <option value="">Pilih jenis pembayaran</option>
                    <option value="lunas">Lunas</option>
                    <option value="dp">DP (50%)</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="regu" class="block text-sm font-semibold text-gray-700">Regu</label>
                <input type="text" id="regu" name="regu"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                    placeholder="Masukkan nama regu">
            </div>

            <div class="pt-6">
                <button type="submit"
                    class="w-full bg-secondary text-white py-3 px-6 hover:-translate-y-1 rounded-lg text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-blue-200 transition transition-all duration-200">
                    Daftar Sekarang
                </button>
            </div>
        </form>
    </div>
</section>
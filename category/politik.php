<?php include '../header & footer/header.php'; ?>
<?php include '../header & footer/category_header.php'; ?>
<?php renderCategoryHeader('Politik'); ?>

<!-- Main Content -->
<div class="container mx-auto mt-8 mb-16">
    <!-- 4 Berita -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <?php for ($i = 1; $i <= 4; $i++): ?>
            <div class="relative">
                <a href="../news-detail.php">
                    <img src="https://via.placeholder.com/600x350" class="w-full h-auto object-cover rounded-lg">
                </a>
                <div class="p-4">
                    <span class="text-red-500 font-bold">Politik</span> <span class="text-gray-500">| 27 Januari 2025</span>
                    <a href="../news-detail.php">
                        <h3 class="text-lg font-bold mt-1">Tim Bridge Polije Raih Juara 2 SEABF Cup 2024</h3>
                    </a>
                    <p class="text-gray-700">Tim Bridge Polije berhasil meraih Juara 2 dalam 7th South East Asia Bridge Federation (SEABF) Cup dan 40th ASEAN Bridge Club Championship...</p>
                </div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Berita Populer -->
    <div class="py-8 mt-8">
        <div class="mb-4">
            <span class="inline-block bg-red-600 text-white px-6 py-1 rounded-t-md">Populer</span>
            <div class="border-b-4 border-red-600 mt-0"></div>
        </div>
        <!-- 3 Kartu Gambar -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
            <a href="../news-detail.php" class="relative overflow-hidden rounded-lg">
                <img src="https://via.placeholder.com/300x200" class="w-full h-56 object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                    <span class="text-white font-bold">Iptek | 3 menit lalu</span>
                    <h3 class="text-white text-lg font-bold mt-1">Tim Dosen Polije Bantu Masalah Usaha Mikro Penetasan Telur</h3>
                </div>
            </a>
            <a href="../news-detail.php" class="relative overflow-hidden rounded-lg">
                <img src="https://via.placeholder.com/300x200" class="w-full h-56 object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                    <span class="text-white font-bold">Iptek | 3 menit lalu</span>
                    <h3 class="text-white text-lg font-bold mt-1">Kolaborasi Dosen dan Mahasiswa Polije Ciptakan Bibit Tembakau Kasturi dengan Daya Kecambah 87%</h3>
                </div>
            </a>
            <a href="../news-detail.php" class="relative overflow-hidden rounded-lg">
                <img src="https://via.placeholder.com/300x200" class="w-full h-56 object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                    <span class="text-white font-bold">Iptek | 3 menit lalu</span>
                    <h3 class="text-white text-lg font-bold mt-1">Mahasiswa Polije Ciptakan Produk Inovasi Sirup Kopi</h3>
                </div>
            </a>
        </div>
    </div>

    <!-- Berita Lainnya dan Baru Baru Ini -->
    <div class="py-4 mt-16">
        <div class="flex flex-col lg:flex-row">
            <!-- Berita Lainnya Section -->
            <div class="w-full lg:w-2/3 pr-4">
                <div class="mb-4">
                    <span class="inline-block bg-[#45C630] text-white px-6 py-1 rounded-t-md">Berita Lainnya</span>
                    <div class="border-b-4 border-[#45C630] mt-0"></div>
                </div>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="flex mb-4 items-center">
                        <div class="flex-grow">
                            <span class="text-gray-400 text-sm block mb-1">2 jam yang lalu</span>
                            <a href="../news-detail.php">
                                <h3 class="text-lg font-bold mt-1">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit <?= $i ?></h3>
                            </a>
                            <p class="text-gray-500 mt-1 mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        </div>
                        <img src="https://via.placeholder.com/400x300" class="w-80 h-60 object-cover rounded-lg ml-4">
                    </div>
                    <div class="border-b border-gray-300 mt-1 mb-4"></div>
                <?php endfor; ?>
            </div>

            <!-- Baru Baru Ini Section -->
            <div class="w-full lg:w-1/3 pl-4 mt-8 md:mt-12 lg:mt-0">
                <div class="mb-4">
                    <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">Baru Baru Ini</span>
                    <div class="border-b-4 border-[#FFC300] mt-0"></div>
                </div>
                <ul class="pl-4">
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <li class="mb-4">
                            <div class="flex items-center">
                                <div>
                                    <span class="text-gray-400 text-sm">2 jam yang lalu</span>
                                    <a href="../news-detail.php">
                                        <h3 class="text-lg font-bold mt-1">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit <?= $i ?></h3>
                                    </a>
                                    <div class="border-b border-gray-300 mt-2"></div>
                                </div>
                            </div>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../header & footer/footer.php'; ?>
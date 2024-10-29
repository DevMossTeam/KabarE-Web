<?php include 'header & footer/header.php'; ?>

<div class="container mx-auto mt-8 mb-16">
    <div class="flex flex-col lg:flex-row">
        <!-- Gambar Utama -->
        <div class="w-full lg:w-2/3 pr-4">
            <img src="https://via.placeholder.com/800x450" class="w-full h-auto object-cover rounded-lg mb-4">
            <h1 class="text-3xl font-bold mb-2">Tim Bridge Polije Raih Juara 2 SEABF Cup 2024</h1>
            <span class="text-gray-500 text-sm">27 Januari 2025, 13:00 WIB</span>
            <p class="mt-4 text-gray-700">Tim Bridge Polije berhasil meraih juara 2 dalam 7th South East Asia Bridge Federation (SEABF) Cup dan 40th ASEAN Bridge Club Championship...</p>
        </div>

        <!-- Sidebar -->
        <div class="w-full lg:w-1/3 pl-4 mt-8 lg:mt-0">
            <div class="mb-4">
                <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">Berita Terkait</span>
                <div class="border-b-4 border-[#FFC300] mt-0"></div>
            </div>
            <ul class="pl-4">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <li class="mb-4">
                        <div class="flex items-center">
                            <img src="https://via.placeholder.com/100x75" class="w-20 h-15 object-cover rounded-lg mr-4">
                            <div>
                                <span class="text-gray-400 text-sm">2 jam yang lalu</span>
                                <h3 class="text-lg font-bold mt-1">Judul Berita Terkait <?= $i ?></h3>
                            </div>
                        </div>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>
</div>

<?php include 'header & footer/footer.php'; ?>
<div class="container mx-auto px-4 lg:px-8"> <!-- Tambahkan pembungkus -->
<?php for ($i = 0; $i < 4; $i++): ?>
    <div class="flex items-start mb-6 relative">
        <div class="flex-grow max-w-4xl pr-6"> <!-- Tambahkan padding kanan lebih besar untuk menggeser ke kiri -->
            <span class="text-[#ABABAB] text-sm font-inter">12 September 2024</span>
            <h3 class="text-2xl font-medium mt-2 mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h3> <!-- Ukuran font 24 untuk judul berita, medium -->
            <p class="text-gray-500 text-sm mb-2">| Diperbarui 2 jam yang lalu</p>
        </div>
        <div class="relative">
            <img src="https://via.placeholder.com/400x200" alt="Gambar Disukai" class="w-56 h-28 object-cover rounded ml-2"> <!-- Kurangi margin kiri -->
            <div class="absolute top-0 right-[-15px] transform translate-x-1/2 -translate-y-1/2"> <!-- Geser ikon lebih ke kanan -->
                <i class="fas fa-ellipsis-v text-gray-500"></i>
            </div>
        </div>
    </div>
    <div class="border-b border-gray-300 mb-4 max-w-6xl"></div> <!-- Sesuaikan lebar garis -->
<?php endfor; ?>
</div> <!-- Tutup pembungkus -->
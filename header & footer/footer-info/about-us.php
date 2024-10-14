<?php include '../header.php'; ?>
<?php include '../category_header.php'; ?>
<?php renderCategoryHeader(categoryName: 'Tentang Kami'); ?>

<main class="container mx-auto my-8 flex-grow">
    <div class="p-4 max-w-4xl mx-auto text-center">
        <div class="mb-8">
            <img src="path/to/your/image.png" alt="Tentang Kami" class="mx-auto">
        </div>
        <div class="text-left">
            <p><strong>Lembaga Penyelenggara:</strong> Politeknik Negeri Jember</p>
            <p><strong>Unit Kegiatan Mahasiswa:</strong> Explant</p>
            <p><strong>Koordinator Umum Kegiatan:</strong> Nama Koordinator</p>
            <p><strong>Alamat:</strong> Alamat lengkap</p>
            <p><strong>No. Telepon:</strong> 123-456-7890</p>
            <p><strong>Email:</strong> email@example.com</p>
            <p><strong>Koordinator Produksi Tim:</strong> Nama Koordinator</p>
            <p><strong>Penulis Team:</strong> Nama Penulis</p>
        </div>
        <hr class="my-8">
        <h2 class="text-xl font-semibold mb-4">Developer Team</h2>
        <div class="flex justify-center space-x-4">
            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
        </div>
        <p class="mt-8">Untuk pertanyaan mengenai situs web dan dukungan, Anda dapat mengirimkan email ke devsupport@example.com</p>
    </div>
</main>

<?php include '../footer.php'; ?>


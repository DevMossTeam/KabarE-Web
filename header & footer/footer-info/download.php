<?php include '../../header & footer/header.php'; ?>
<?php include '../../header & footer/category_header.php'; ?>
<?php renderCategoryHeader(categoryName: 'Download'); ?>

<main class="container mx-auto my-8 flex-grow">
    <div class="p-4 max-w-3xl mx-auto text-center animate-fade-in">
        <h1 class="text-4xl font-bold mb-6">Download Aplikasi Mobile Kami</h1>
        <p class="text-lg mb-8">Nikmati kemudahan akses berita mahasiswa terpercaya langsung dari perangkat mobile Anda. Dapatkan notifikasi berita terbaru dan fitur eksklusif lainnya.</p>
        <div class="flex justify-center space-x-4">
            <a href="link-to-your-app-store" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105">Download di App Store</a>
            <a href="link-to-your-play-store" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105">Download di Google Play</a>
        </div>
        <div class="mt-10">
            <img src="../assets/mobile-app-preview.png" alt="Preview Aplikasi Mobile" class="mx-auto rounded-lg shadow-lg">
        </div>
    </div>
</main>

<?php include '../../header & footer/footer.php'; ?>
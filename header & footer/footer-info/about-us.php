<?php 

include '../header.php';
include '../category_header.php';
renderCategoryHeader('Tentang KabarE');

?>

<main class="container mx-auto my-8 flex-grow">
    <div class="p-4 max-w-4xl mx-auto">
        <!-- Logo dan Judul -->
        <div class="flex items-center mb-8">
            <img src="../../assets/web-icon/Ic-main-KabarE.svg" alt="KabarE Logo" class="w-8 h-8">
            <h1 class="text-2xl font-bold ml-4">Tentang KabarE</h1>
        </div>

        <!-- Deskripsi dan Fitur -->
        <div class="text-justify mb-8">
            <p class="mb-4">
                KabarE adalah aplikasi berita resmi dari UKPM Explant di Politeknik Negeri Jember.
                Aplikasi ini menyediakan berita terkini, ulasan mendalam, dan informasi penting yang
                relevan dengan mahasiswa dan civitas kampus. KabarE dirancang untuk memberikan
                akses cepat dan mudah terhadap berita-berita kampus, serta topik-topik menarik
                yang dibahas oleh komunitas UKPM Explant.
            </p>
            <p class="mb-4">
                Dengan fitur yang intuitif dan tampilan yang menarik, KabarE menjadi platform ideal
                bagi mereka yang ingin tetap terhubung dengan informasi terbaru. Selain itu, aplikasi
                ini memungkinkan pengguna untuk memberikan umpan balik, berbagi artikel, dan
                berinteraksi langsung melalui fitur pusat bantuan.
            </p>
        </div>

        <!-- Fitur Unggulan -->
        <div class="mb-4">
            <h2 class="font-bold mb-4">Fitur Unggulan:</h2>
            <ul class="list-disc list-inside space-y-2 ml-4">
                <li>Berita terbaru langsung dari UKPM Explant</li>
                <li>Kategori berita yang bervariasi</li>
                <li>Pusat bantuan untuk pertanyaan atau dukungan</li>
                <li>Notifikasi berita penting</li>
            </ul>
        </div>

        <p class="mb-2">KabarE tersedia untuk diunduh secara gratis di platform mobile.</p>
        <hr class="border-gray-300 mb-6">

        <!-- Download Section -->
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-4">Unduh KabarE Sekarang</h2>
            <p class="mb-4">
                Nikmati akses penuh ke berita kampus di mana pun Anda berada dengan KabarE
                versi mobile. Unduh sekarang untuk perangkat Android!
            </p>
            <a href="/header & footer/footer-info/download/download.php" class="text-blue-500 hover:text-blue-600 font-medium">
                Download Sekarang
            </a>
            <p class="mt-4">
                Dapatkan KabarE dan nikmati pengalaman membaca berita yang lebih mudah dan
                cepat di ponsel Anda!
            </p>
        </div>
    </div>
</main>

<?php include '../footer.php'; ?>
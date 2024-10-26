<?php include '../header.php'; ?>
<?php include '../category_header.php'; ?>
<?php renderCategoryHeader(categoryName: 'Peta Situs'); ?>

<main class="container mx-auto my-8 flex-grow">
    <div class="p-4 max-w-6xl mx-auto text-left">
        <h1 class="text-2xl font-bold mb-4">Peta Situs Aplikasi KabarE</h1>
        <p>Peta situs ini memberikan gambaran umum tentang struktur dan konten yang tersedia di aplikasi KabarE. Anda dapat menggunakan peta ini untuk menavigasi ke bagian yang Anda minati.</p>
        
        <h2 class="text-xl font-semibold mt-4">1. Beranda</h2>
        <ul class="list-disc ml-6">
            <li><a href="/index.php" class="text-blue-500 hover:underline">Beranda</a></li>
        </ul>

        <h2 class="text-xl font-semibold mt-4">2. Kategori</h2>
        <ul class="list-disc ml-6">
            <li><a href="/category/kampus.php" class="text-blue-500 hover:underline">Kampus</a></li>
            <li><a href="/category/prestasi.php" class="text-blue-500 hover:underline">Prestasi</a></li>
            <li><a href="/category/politik.php" class="text-blue-500 hover:underline">Politik</a></li>
            <li><a href="/category/kesehatan.php" class="text-blue-500 hover:underline">Kesehatan</a></li>
            <li><a href="/category/olahraga.php" class="text-blue-500 hover:underline">Olahraga</a></li>
            <li><a href="/category/ekonomi.php" class="text-blue-500 hover:underline">Ekonomi</a></li>
            <li><a href="/category/bisnis.php" class="text-blue-500 hover:underline">Bisnis</a></li>
            <li><a href="/category/ukm.php" class="text-blue-500 hover:underline">UKM</a></li>
            <li><a href="/category/other_category/berita_lainnya.php" class="text-blue-500 hover:underline">Berita Lainnya</a></li>
        </ul>

        <h2 class="text-xl font-semibold mt-4">3. Informasi</h2>
        <ul class="list-disc ml-6">
            <li><a href="/footer-info/about-us.php" class="text-blue-500 hover:underline">Tentang Kami</a></li>
            <li><a href="/footer-info/media-guidelines.php" class="text-blue-500 hover:underline">Pedoman Media Siber</a></li>
            <li><a href="/footer-info/privacy.php" class="text-blue-500 hover:underline">Kebijakan Privasi</a></li>
            <li><a href="/footer-info/terms.php" class="text-blue-500 hover:underline">Syarat dan Ketentuan</a></li>
        </ul>

        <h2 class="text-xl font-semibold mt-4">4. Akun Pengguna</h2>
        <ul class="list-disc ml-6">
            <li><a href="/user-auth/login.php" class="text-blue-500 hover:underline">Login</a></li>
            <li><a href="/user-auth/register.php" class="text-blue-500 hover:underline">Daftar</a></li>
            <li><a href="/profile/mainEditor.php" class="text-blue-500 hover:underline">Profil</a></li>
        </ul>

        <h2 class="text-xl font-semibold mt-4">5. Kontak</h2>
        <ul class="list-disc ml-6">
            <li><a href="/contact.php" class="text-blue-500 hover:underline">Hubungi Kami</a></li>
        </ul>
    </div>
</main>

<?php include '../footer.php'; ?>

<?php
session_start();
include '../connection/config.php'; // Pastikan jalur relatif ini benar
include '../header.php'; // Sertakan header

// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']);
?>

<div class="flex ml-12 mt-4"> <!-- Kurangi margin atas di sini -->
    <!-- Sidebar -->
    <div class="w-1/6 p-4 mt-4"> <!-- Kurangi margin atas di sini -->
        <div class="flex justify-center">
            <i class="fas fa-user-circle text-9xl text-gray-500"></i> <!-- Perbesar ikon profil -->
        </div>
        <h2 class="text-center text-2xl font-semibold mt-4">Chiquita Clairina K</h2> <!-- Ukuran font 24 untuk nama di bawah profil, semi-bold -->
        <p class="text-center text-gray-600 text-lg">Mahasiswa Jurusan Teknologi Informasi</p> <!-- Ukuran font 16 -->
        <p class="text-left mt-2 text-sm text-gray-700">Bio: Platform berita kampus yang menyajikan informasi terkini, akurat, dan terpercaya. Selalu di depan dalam mengabarkan suara mahasiswa.</p> <!-- Ukuran font 14 -->
        <div class="flex items-center mt-2">
            <i class="fas fa-edit text-blue-500"></i> <!-- Ikon edit berwarna biru -->
            <span class="text-xs text-[#9F9F9F] ml-2">Pembaca</span> <!-- Teks pembaca ukuran 12 -->
        </div>
        <button class="mt-4 w-full bg-blue-500 text-white py-2 rounded">Edit Profile</button>
        <div class="mt-4">
            <h3 class="text-gray-700 font-bold">Social Media:</h3>
            <p class="text-gray-600"><i class="fab fa-instagram"></i> ndikka005</p>
            <p class="text-gray-600"><i class="fab fa-tiktok"></i> asyourArt01</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="w-5/6 ml-12 mt-4"> <!-- Tambahkan margin kiri untuk menggeser ke kanan -->
        <h2 class="text-4xl font-semibold mt-4 mb-2 ml-4">Chiquita Clairina K</h2> <!-- Ukuran font 48 untuk nama di atas pilihan menu, semi-bold, geser ke kanan -->
        <div class="relative flex mt-4 border-b ml-4"> <!-- Geser pilihan menu ke kanan -->
            <a href="/profile/mainEditor.php?page=artikel" class="menu-item mr-4 pb-2 text-sm">ARTIKEL</a> <!-- Ubah ukuran font -->
            <a href="/profile/mainEditor.php?page=bacaNanti" class="menu-item mr-4 pb-2 text-sm">BACA NANTI</a> <!-- Ubah ukuran font -->
            <a href="/profile/mainEditor.php?page=liked" class="menu-item pb-2 text-sm">DISUKAI</a> <!-- Ubah ukuran font -->
            <div id="active-indicator" class="absolute bottom-0 h-0.5 bg-blue-500 transition-all duration-300" style="width: 60px;"></div> <!-- Sesuaikan lebar -->
        </div>
        <div class="mt-4">
            <?php
            // Tentukan halaman yang akan dimuat berdasarkan parameter GET
            $page = $_GET['page'] ?? 'artikel'; // Default ke 'artikel' jika tidak ada parameter

            // Sertakan file yang sesuai berdasarkan parameter
            switch ($page) {
                case 'bacaNanti':
                    include 'bacaNanti.php';
                    break;
                case 'liked':
                    include 'liked.php';
                    break;
                case 'artikel':
                default:
                    include 'artikel.php';
                    break;
            }
            ?>
        </div>
    </div>
</div>

<?php include '../footer.php'; // Sertakan footer ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const menuItems = document.querySelectorAll('.menu-item');
        const activeIndicator = document.getElementById('active-indicator');

        function updateIndicator(element) {
            const { offsetLeft, offsetWidth } = element;
            activeIndicator.style.width = `${offsetWidth}px`;
            activeIndicator.style.left = `${offsetLeft}px`;
        }

        menuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                updateIndicator(item);
                window.location.href = item.href;
            });
        });

        // Set initial position
        const currentPage = '<?php echo $page; ?>';
        const initialItem = Array.from(menuItems).find(item => item.href.includes(currentPage));
        if (initialItem) {
            updateIndicator(initialItem);
        }
    });
</script>
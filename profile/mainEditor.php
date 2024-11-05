<?php
session_start();
include '../connection/config.php'; // Pastikan jalur relatif ini benar
include '../header & footer/header.php'; // Sertakan header

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
        <p class="text-center text-sm text-[#9F9F9F]">@chiquita</p> <!-- Tambahkan username dengan warna dan ukuran lebih kecil -->
        <p class="text-center text-gray-600 text-lg">Mahasiswa Jurusan Teknologi Informasi</p> <!-- Ukuran font 16 -->
        <button class="mt-4 w-full bg-blue-500 text-white py-2 rounded">Edit Profile</button>
    </div>

    <!-- Main Content -->
    <div class="w-5/6 ml-12 mt-4"> <!-- Tambahkan margin kiri untuk menggeser ke kanan -->
        <h2 class="text-4xl font-semibold mt-4 mb-2 ml-4">
            Chiquita Clairina K <span class="text-4xl text-gray-600">(pembaca)</span> <!-- Ukuran teks sama -->
        </h2> <!-- Ukuran font 48 untuk nama di atas pilihan menu, semi-bold, geser ke kanan -->
        <div class="relative flex mt-4 border-b ml-4"> <!-- Geser pilihan menu ke kanan -->
            <a href="/profile/mainEditor.php?page=bacaNanti" class="menu-item mr-4 pb-2 text-sm">BACA NANTI</a> <!-- Ubah ukuran font -->
            <a href="/profile/mainEditor.php?page=liked" class="menu-item pb-2 text-sm">DISUKAI</a> <!-- Ubah ukuran font -->
            <div id="active-indicator" class="absolute bottom-0 h-0.5 bg-blue-500 transition-all duration-300" style="width: 60px;"></div> <!-- Sesuaikan lebar -->
        </div>
        <div class="mt-4">
            <?php
            // Tentukan halaman yang akan dimuat berdasarkan parameter GET
            $page = $_GET['page'] ?? 'bacaNanti'; // Default ke 'bacaNanti' jika tidak ada parameter

            // Sertakan file yang sesuai berdasarkan parameter
            switch ($page) {
                case 'bacaNanti':
                    include 'bacaNanti.php';
                    break;
                case 'liked':
                    include 'liked.php';
                    break;
            }
            ?>
        </div>
    </div>
</div>

<?php include '../header & footer/footer.php'; // Sertakan footer ?>
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
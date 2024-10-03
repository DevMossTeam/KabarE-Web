<?php
session_start();
include 'config.php';

$email = ''; // Inisialisasi variabel email

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT email FROM user WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $email = $user['email'];
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Tailwind CSS custom configuration */
        @layer utilities {
            .transition-transform {
                transition: transform 0.3s ease-in-out;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav id="mainNavbar" class="z-10 relative">
        <!-- Bagian Atas Navbar -->
        <div class="bg-blue-500 p-4">
            <div class="container mx-auto flex justify-between items-center">
                <a href="/index.php" class="text-white text-2xl font-bold">
                    <img src="/assets/web-icon/KabarE-logo.png" alt="KabarE Logo" class="h-8 inline-block">
                </a>
                <div class="flex-grow flex justify-center">
                    <form id="searchForm" action="/search.php" method="GET" class="flex items-center">
                        <div class="relative">
                            <input type="text" name="query" placeholder="Search..." class="px-2 py-1 border border-gray-300 rounded-full focus:outline-none text-sm">
                            <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-2 text-blue-500">
                                <i class="fas fa-search text-xs"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Ikon Edit, Notifikasi, dan Profil -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button id="editButton" class="text-white focus:outline-none">
                            <i class="fas fa-edit text-xl"></i>
                        </button>
                    </div>

                    <div class="relative">
                        <button id="notificationButton" class="text-white focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                        </button>
                        <div id="notificationMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-20">
                            <div class="block px-4 py-2 text-gray-800">Tidak ada notifikasi baru</div>
                        </div>
                    </div>

                    <div class="relative">
                        <button id="profileButton" class="flex items-center text-white focus:outline-none">
                            <i class="fas fa-user-circle text-3xl"></i>
                        </button>
                        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-20">
                            <div class="block px-4 py-2 text-gray-800">
                                <?= htmlspecialchars($email ?: 'Guest'); ?>
                            </div>
                            <hr class="border-gray-200">
                            <a href="/subscriptions.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                <i class="fas fa-newspaper mr-2"></i>Berlangganan
                            </a>
                            <hr class="border-gray-200">
                            <a href="/saved-content.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                <i class="fas fa-bookmark mr-2"></i>Konten yang Disimpan
                            </a>
                            <hr class="border-gray-200">
                            <a href="/liked-content.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                <i class="fas fa-thumbs-up mr-2"></i>Konten yang Disukai
                            </a>
                            <hr class="border-gray-200">
                            <a href="/profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                <i class="fas fa-user mr-2"></i>Lihat Profil
                            </a>
                            <hr class="border-gray-200">
                            <a href="/settings.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                <i class="fas fa-cog mr-2"></i>Pengaturan
                            </a>
                            <hr class="border-gray-200">
                            <a href="/logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembatas -->
        <hr class="border-t border-gray-300">

        <!-- Bagian Bawah Navbar -->
        <div class="bg-white p-4">
            <div class="container mx-auto flex flex-wrap justify-between items-center">
                <a href="/index.php" class="nav-link text-black hover:text-blue-500 px-2 md:px-4">Home</a>
                <a href="/category/kampus.php" class="nav-link text-black hover:text-blue-500 px-2 md:px-4">Kampus</a>
                <a href="/category/pemilu.php" class="nav-link text-black hover:text-blue-500 px-2 md:px-4">Pemilu</a>
                <a href="/category/teknologi.php" class="nav-link text-black hover:text-blue-500 px-2 md:px-4">Teknologi</a>
                <a href="/category/otomotif.php" class="nav-link text-black hover:text-blue-500 px-2 md:px-4">Otomotif</a>
                <a href="/category/olahraga.php" class="nav-link text-black hover:text-blue-500 px-2 md:px-4">Olahraga</a>
                <a href="/category/lifestyle.php" class="nav-link text-black hover:text-blue-500 px-2 md:px-4">Lifestyle</a>
                <a href="/category/tren.php" class="nav-link text-black hover:text-blue-500 px-2 md:px-4">Tren</a>
                <a href="/category/kesehatan.php" class="nav-link text-black hover:text-blue-500 px-2 md:px-4">Kesehatan</a>
                <a href="/category/ekonomi.php" class="nav-link text-black hover:text-blue-500 px-2 md:px-4">Ekonomi</a>
                <div class="relative">
                    <button id="otherCategoryButton" class="text-black hover:text-blue-500 px-2 md:px-4">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <div id="otherCategoryMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-20">
                        <a href="/category/other_category/berita_lainnya.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Berita Lainnya</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header yang muncul saat scroll -->
    <div id="scrollHeader" class="fixed top-0 left-0 right-0 bg-blue-500 p-4 transform -translate-y-full transition-transform">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/index.php" class="text-white text-2xl font-bold">
                <img src="/assets/web-icon/KabarE-logo.png" alt="KabarE Logo" class="h-8 inline-block">
            </a>
            <div class="flex space-x-4">
                <a href="/index.php" class="nav-link text-white hover:text-blue-300">Home</a>
                <a href="/category/kampus.php" class="nav-link text-white hover:text-blue-300">Kampus</a>
                <a href="/category/pemilu.php" class="nav-link text-white hover:text-blue-300">Pemilu</a>
                <a href="/category/teknologi.php" class="nav-link text-white hover:text-blue-300">Teknologi</a>
                <a href="/category/otomotif.php" class="nav-link text-white hover:text-blue-300">Otomotif</a>
                <a href="/category/olahraga.php" class="nav-link text-white hover:text-blue-300">Olahraga</a>
                <a href="/category/lifestyle.php" class="nav-link text-white hover:text-blue-300">Lifestyle</a>
                <a href="/category/tren.php" class="nav-link text-white hover:text-blue-300">Tren</a>
                <a href="/category/kesehatan.php" class="nav-link text-white hover:text-blue-300">Kesehatan</a>
                <a href="/category/ekonomi.php" class="nav-link text-white hover:text-blue-300">Ekonomi</a>
                <div class="relative">
                    <button id="otherCategoryButtonScroll" class="text-white hover:text-blue-300">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <div id="otherCategoryMenuScroll" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-20">
                        <a href="/category/other_category/berita_lainnya.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Berita Lainnya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const otherCategoryButton = document.getElementById('otherCategoryButton');
        const otherCategoryMenu = document.getElementById('otherCategoryMenu');
        const profileButton = document.getElementById('profileButton');
        const profileMenu = document.getElementById('profileMenu');
        const notificationButton = document.getElementById('notificationButton');
        const notificationMenu = document.getElementById('notificationMenu');
        const scrollHeader = document.getElementById('scrollHeader');
        const navLinks = document.querySelectorAll('.nav-link');

        otherCategoryButton.addEventListener('click', () => {
            otherCategoryMenu.classList.toggle('hidden');
        });

        profileButton.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });

        notificationButton.addEventListener('click', () => {
            notificationMenu.classList.toggle('hidden');
        });

        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                scrollHeader.classList.remove('-translate-y-full');
            } else {
                scrollHeader.classList.add('-translate-y-full');
            }
        });

        window.addEventListener('click', (e) => {
            if (!otherCategoryButton.contains(e.target) && !otherCategoryMenu.contains(e.target)) {
                otherCategoryMenu.classList.add('hidden');
            }
            if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
            if (!notificationButton.contains(e.target) && !notificationMenu.contains(e.target)) {
                notificationMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
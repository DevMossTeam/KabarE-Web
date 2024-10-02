<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-blue-500 p-4 z-10 relative">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-2">
                <div class="flex items-center">
                    <a href="index.php" class="text-white text-2xl font-bold">
                        <img src="assets/web-icon/KabarE-logo.png" alt="KabarE Logo" class="h-8 inline-block">
                    </a>
                    <div class="relative ml-4">
                        <form id="searchForm" action="search.php" method="GET" class="flex items-center">
                            <div class="relative">
                                <input type="text" name="query" placeholder="Search..." class="px-2 py-1 border border-gray-300 rounded-full focus:outline-none text-sm">
                                <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-2 text-blue-500">
                                    <i class="fas fa-search text-xs"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="relative">
                    <button id="profileButton" class="text-white px-4">
                        <i class="fas fa-user-circle text-2xl"></i>
                    </button>
                    <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-20">
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                            <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Logout</a>
                        <?php else: ?>
                            <a href="register.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Register</a>
                            <a href="login.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <hr class="border-gray-300">
            <div class="flex justify-between items-center mt-2">
                <a href="index.php" class="text-white px-4">Home</a>
                <a href="category/news.php" class="text-white px-4">News</a>
                <a href="category/pemilu.php" class="text-white px-4">Pemilu</a>
                <a href="category/teknologi.php" class="text-white px-4">Teknologi</a>
                <a href="category/otomotif.php" class="text-white px-4">Otomotif</a>
                <a href="category/olahraga.php" class="text-white px-4">Olahraga</a>
                <a href="category/lifestyle.php" class="text-white px-4">Lifestyle</a>
                <a href="category/tren.php" class="text-white px-4">Tren</a>
                <a href="category/kesehatan.php" class="text-white px-4">Kesehatan</a>
                <a href="category/ekonomi.php" class="text-white px-4">Ekonomi</a>
                <div class="relative">
                    <button id="otherCategoryButton" class="text-white px-4">
                        <i class="fas fa-ellipsis-h text-2xl"></i>
                    </button>
                    <div id="otherCategoryMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-20">
                        <a href="category/other_category/berita_lainnya.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Berita Lainnya</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>
        const profileButton = document.getElementById('profileButton');
        const profileMenu = document.getElementById('profileMenu');
        const otherCategoryButton = document.getElementById('otherCategoryButton');
        const otherCategoryMenu = document.getElementById('otherCategoryMenu');

        profileButton.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });

        otherCategoryButton.addEventListener('click', () => {
            otherCategoryMenu.classList.toggle('hidden');
        });

        window.addEventListener('click', (e) => {
            if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
            if (!otherCategoryButton.contains(e.target) && !otherCategoryMenu.contains(e.target)) {
                otherCategoryMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../connection/config.php';

// Redirect ke index.php jika halaman ini diakses langsung dan bukan halaman yang diizinkan
$allowed_pages = ['index.php', 'previewAuthor.php', 'publishAuthor.php', 'Main_author.php', 
'reviewStat.php', 'mainEditor.php', 'kampus.php', 'search.php', 'prestasi.php', 'politik.php', 
'kesehatan.php', 'olahraga.php', 'ekonomi.php', 'bisnis.php', 'ukm.php', 'berita_lainnya.php', 
'privacy.php', 'site-map.php', 'about-us.php', 'media-guidelines.php', 'terms.php', 'test.php', 
'news-detail.php'];
$current_page = basename($_SERVER['PHP_SELF']);

if (!in_array($current_page, $allowed_pages)) {
    header('Location: /index.php');
    exit();
}

$email = $_SESSION['email'] ?? ''; // Ambil email dari session
$profile_pic = '../assets/default-profile.png'; // Default profile picture
$isLoggedIn = false; // Inisialisasi status login

if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {
    $user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];

    $stmt = $conn->prepare("SELECT email, profile_pic FROM user WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $email = $user['email'];
            $profile_pic = $user['profile_pic'] ?: $profile_pic; // Use default if empty
            $isLoggedIn = true;

            // Set sesi jika hanya cookie yang ada
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
                $_SESSION['profile_pic'] = $profile_pic;
            }
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="bg-gray-100 flex flex-col min-h-screen overflow-x-hidden">
    <!-- Navbar -->
    <nav id="mainNavbar" class="z-10 relative">
        <!-- Bagian Atas Navbar -->
        <div class="bg-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <a href="/index.php" class="text-black text-2xl font-bold">
                    <img src="../assets/web-icon/KabarE-UTDK.png" alt="Logo" class="w-10 h-10">
                    <!-- Perbesar logo -->
                </a>
                <div class="flex items-center space-x-2"> <!-- Kurangi jarak antar elemen -->
                    <!-- Search Form -->
                    <form id="searchForm" action="/search.php" method="GET" class="flex items-center">
                        <div class="relative">
                            <input type="text" name="query" placeholder="Search..."
                                class="px-2 py-1 border border-gray-300 rounded-full focus:outline-none text-sm">
                            <button type="submit"
                                class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-500">
                                <i class="fas fa-search text-xs"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Ikon Notifikasi, Draft, Upload, dan Review -->
                    <div class="relative z-30">
                        <button id="notificationButton" class="text-gray-500 hover:text-[#4A99FF] focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                        </button>
                        <div id="notificationMenu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-30">
                            <div class="block px-4 py-2 text-gray-800">Tidak ada notifikasi baru</div>
                        </div>
                    </div>

                    <div class="relative z-30">
                        <button id="draftButton" class="text-gray-500 hover:text-[#4A99FF] focus:outline-none">
                            <i class="fas fa-file-alt text-xl"></i>
                        </button>
                        <div id="draftMenu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-30">
                            <a href="../authors/Main_author.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Tambah</a>
                            <a href="../authors/draftAuthor.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Draf</a>
                            <a href="../authors/reviewAuthor.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Dalam Peninjauan</a>
                            <a href="../authors/publishAuthor.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Publikasi</a>
                        </div>
                    </div>

                    <div class="relative z-30">
                        <button id="uploadButton" class="text-gray-500 hover:text-[#4A99FF] focus:outline-none">
                            <i class="fas fa-upload text-xl"></i>
                        </button>
                    </div>

                    <div class="relative z-30">
                        <button id="reviewButton" class="bg-[#4A99FF] text-white rounded-[15px] px-3 py-1 font-semibold italic focus:outline-none">
                            Review
                        </button>
                    </div>

                    <div class="relative z-30">
                        <button id="profileButton" class="flex items-center text-gray-500 focus:outline-none">
                            <i class="fas fa-user-circle text-3xl"></i> <!-- Ikon dari FontAwesome dengan ukuran lebih besar -->
                        </button>
                        <div id="profileMenu"
                            class="hidden absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg py-4 z-50">
                            <?php if ($isLoggedIn || isset($_SESSION['email'])): ?>
                                <div class="text-center">
                                    <div class="mt-2 text-gray-800 font-bold"><?= htmlspecialchars($email) ?></div>
                                    <div class="text-gray-500">Penulis</div>
                                    <i class="fas fa-user-circle text-8xl text-gray-500 mt-2"></i> <!-- Ikon dari FontAwesome dengan ukuran lebih besar -->
                                </div>
                                <button id="editProfileButton" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-md mx-auto block" style="width: 80%;">Edit Profile</button>
                                <hr class="my-2 border-gray-200">
                                <a href="/saved-content.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 text-left">
                                    <i class="fas fa-bookmark mr-2"></i>Konten yang Disimpan
                                </a>
                                <a href="/liked-content.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 text-left">
                                    <i class="fas fa-thumbs-up mr-2"></i>Konten yang Disukai
                                </a>
                                <a href="../settings/mainSetting.php?page=umum" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 text-left">
                                    <i class="fas fa-cog mr-2"></i>Pengaturan
                                </a>
                                <a href="/logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 text-left">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                                </a>
                            <?php else: ?>
                                <a href="user-auth/login.php" class="block px-4 py-2 text-blue-500 hover:underline">Login</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Bawah Navbar -->
        <div class="bg-blue-500 p-2">
            <div class="container mx-auto flex flex-wrap justify-between items-center">
                <div class="flex flex-col items-center">
                    <a href="/index.php" class="text-white text-2xl font-bold">
                        <!-- Logo SVG -->
                    </a>
                    <div id="currentDate" class="text-white text-sm mt-1"></div> <!-- Elemen untuk menampilkan waktu -->
                </div>
                <a href="/category/kampus.php" class="nav-link text-white hover:text-blue-300 px-1 md:px-2">Kampus</a>
                <a href="/category/prestasi.php" class="nav-link text-white hover:text-blue-300 px-1 md:px-2">Prestasi</a>
                <a href="/category/politik.php" class="nav-link text-white hover:text-blue-300 px-1 md:px-2">Politik</a>
                <a href="/category/kesehatan.php" class="nav-link text-white hover:text-blue-300 px-1 md:px-2">Kesehatan</a>
                <a href="/category/olahraga.php" class="nav-link text-white hover:text-blue-300 px-1 md:px-2">Olahraga</a>
                <a href="/category/ekonomi.php" class="nav-link text-white hover:text-blue-300 px-1 md:px-2">Ekonomi</a>
                <a href="/category/bisnis.php" class="nav-link text-white hover:text-blue-300 px-1 md:px-2 hidden lg:inline">Bisnis</a>
                <a href="/category/ukm.php" class="nav-link text-white hover:text-blue-300 px-1 md:px-2 hidden lg:inline">UKM</a>
                <div class="relative">
                    <button id="otherCategoryButton" class="text-white hover:text-blue-300 px-1 md:px-2">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <div id="otherCategoryMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-30">
                        <a href="/category/other_category/berita_lainnya.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Berita Lainnya</a>
                        <a href="/category/bisnis.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 md:block lg:hidden">Bisnis</a>
                        <a href="/category/ukm.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 md:block lg:hidden">UKM</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header yang muncul saat scroll -->
    <div id="scrollHeader"
        class="fixed top-0 left-0 right-0 bg-blue-500 p-2 transform -translate-y-full transition-transform duration-500 ease-out z-20">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/index.php" class="text-white text-2xl font-bold">
                <img src="../assets/web-icon/KabarE-UTDF.png" alt="Logo" class="w-10 h-10">
            </a>
            <div class="flex space-x-4 ml-4">
                <a href="/category/kampus.php" class="nav-link text-white hover:text-blue-300">Kampus</a>
                <a href="/category/prestasi.php" class="nav-link text-white hover:text-blue-300">Prestasi</a>
                <a href="/category/politik.php" class="nav-link text-white hover:text-blue-300">Politik</a>
                <a href="/category/kesehatan.php" class="nav-link text-white hover:text-blue-300">Kesehatan</a>
                <a href="/category/olahraga.php" class="nav-link text-white hover:text-blue-300">Olahraga</a>
                <div class="relative">
                    <button id="otherCategoryButtonScroll" class="text-white hover:text-blue-300">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <div id="otherCategoryMenuScroll"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-30">
                        <a href="/category/ekonomi.php"
                            class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Ekonomi</a>
                        <a href="/category/bisnis.php"
                            class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Bisnis</a>
                        <a href="/category/ukm.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">UKM</a>
                        <a href="/category/other_category/berita_lainnya.php"
                            class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Berita Lainnya</a>
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
        const otherCategoryButtonScroll = document.getElementById('otherCategoryButtonScroll');
        const otherCategoryMenuScroll = document.getElementById('otherCategoryMenuScroll');
        const uploadButton = document.getElementById('uploadButton');
        const reviewButton = document.getElementById('reviewButton');
        const editProfileButton = document.getElementById('editProfileButton');
        const draftButton = document.getElementById('draftButton');
        const draftMenu = document.getElementById('draftMenu');

        otherCategoryButton.addEventListener('click', () => {
            otherCategoryMenu.classList.toggle('hidden');
        });

        profileButton.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
            profileMenu.classList.toggle('scale-100');
        });

        notificationButton.addEventListener('click', () => {
            notificationMenu.classList.toggle('hidden');
        });

        otherCategoryButtonScroll.addEventListener('click', () => {
            otherCategoryMenuScroll.classList.toggle('hidden');
        });

        uploadButton.addEventListener('click', () => {
            location.href = '/authors/Main_author.php'; // Arahkan ke halaman Main_author
        });

        reviewButton.addEventListener('click', () => {
            location.href = '/reviews/reviewStat.php'; // Arahkan ke halaman reviewStat
        });

        editProfileButton.addEventListener('click', () => {
            location.href = '../settings/umum.php';
        });

        draftButton.addEventListener('click', () => {
            draftMenu.classList.toggle('hidden');
        });

        let lastScrollTop = 0;
        window.addEventListener('scroll', () => {
            let scrollTop = window.scrollY || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // Scroll ke bawah
                scrollHeader.classList.remove('-translate-y-full');
                scrollHeader.classList.add('translate-y-0');
            } else {
                // Scroll ke atas
                scrollHeader.classList.remove('translate-y-0');
                scrollHeader.classList.add('-translate-y-full');
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // For Mobile or negative scrolling
        });

        window.addEventListener('click', (e) => {
            if (!otherCategoryButton.contains(e.target) && !otherCategoryMenu.contains(e.target)) {
                otherCategoryMenu.classList.add('hidden');
            }
            if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
                profileMenu.classList.remove('scale-100');
            }
            if (!notificationButton.contains(e.target) && !notificationMenu.contains(e.target)) {
                notificationMenu.classList.add('hidden');
            }
            if (!otherCategoryButtonScroll.contains(e.target) && !otherCategoryMenuScroll.contains(e.target)) {
                otherCategoryMenuScroll.classList.add('hidden');
            }
            if (!draftButton.contains(e.target) && !draftMenu.contains(e.target)) {
                draftMenu.classList.add('hidden');
            }
        });

         // Fungsi untuk mengubah lokasi ke mainEditor.php
         function loadContent(url) {
            location.href = '/profile/' + url; // Pastikan '/editor/' ditambahkan jika file berada di dalam folder 'editor'
        }

        // Update current date
        function updateCurrentDate() {
            const currentDateElement = document.getElementById('currentDate');
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            currentDateElement.textContent = now.toLocaleDateString('id-ID', options);
        }

        updateCurrentDate();

        document.addEventListener('DOMContentLoaded', () => {
            const userEmail = document.getElementById('userEmail');
            if (userEmail) {
                userEmail.addEventListener('click', () => {
                    loadContent('mainEditor.php');
                });
            }
        });
    </script>
</body>

</html>

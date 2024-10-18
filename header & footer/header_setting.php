<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../connection/config.php';

$email = '';
$isLoggedIn = false;

if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {
    $user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];

    $stmt = $conn->prepare("SELECT email FROM user WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $email = $user['email'];
            $isLoggedIn = true;

            if (!isset($_SESSION['user_id'])) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
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
    <title>Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="bg-gray-100 flex flex-col min-h-screen overflow-x-hidden">
    <div class="bg-white py-2 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/index.php" class="flex items-center">
                <img src="/assets/web-icon/KabarE-UTDK.png" alt="Logo" class="w-10 h-10"> 
            </a>
            <div class="flex items-center space-x-8 lg:space-x-16">
                <a href="/settings/umum.php" class="text-[#A2A2A2] font-bold text-lg hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">Umum</a>
                <a href="/settings/keamananSetting.php" class="text-[#A2A2A2] font-bold text-lg hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">Keamanan</a>
                <a href="/settings/notif_setting.php" class="text-[#A2A2A2] font-bold text-lg hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">Notifikasi</a>
                <a href="/settings/riwayat.php" class="text-[#A2A2A2] font-bold text-lg hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">Riwayat</a>
                <a href="/settings/bantuan/pusat_bantuan.php" class="text-[#A2A2A2] font-bold text-lg hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">Bantuan</a>
                <a href="/settings/tentang/appAbout.php" class="text-[#A2A2A2] font-bold text-lg hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">Tentang</a>
            </div>
            <div class="flex items-center space-x-4">
                <button id="notificationButton" class="text-gray-500 hover:text-[#4A99FF] focus:outline-none">
                    <i class="fas fa-bell text-xl"></i>
                </button>
                <div id="notificationMenu" class="hidden absolute right-0 mt-32 w-48 bg-white rounded-md shadow-lg py-2 z-30 transform -translate-x-12">
                    <div class="block px-4 py-2 text-gray-800">Tidak ada notifikasi baru</div>
                </div>
                <button id="profileButton" class="flex items-center text-gray-500 focus:outline-none">
                    <i class="fas fa-user-circle text-3xl"></i>
                </button>
                <div id="profileMenu" class="hidden absolute right-0 mt-20 md:mt-80 lg:mt-80 w-48 bg-white rounded-md shadow-lg py-2 z-50 transform -translate-x-8">
                    <?php if ($isLoggedIn): ?>
                        <div class="block px-4 py-2 text-gray-800">
                            <span id="userEmail" class="cursor-pointer"><?= htmlspecialchars($email) ?></span>
                        </div>
                        <hr class="border-gray-200">
                        <a href="/saved-content.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                            <i class="fas fa-bookmark mr-2"></i>Konten yang Disimpan
                        </a>
                        <hr class="border-gray-200">
                        <a href="/liked-content.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                            <i class="fas fa-thumbs-up mr-2"></i>Konten yang Disukai
                        </a>
                        <hr class="border-gray-200">
                        <a href="../settings/mainSetting.php?page=umum" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                            <i class="fas fa-cog mr-2"></i>Pengaturan
                        </a>
                        <hr class="border-gray-200">
                        <a href="/logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                        </a>
                    <?php else: ?>
                        <a href="/login.php" class="block px-4 py-2 text-blue-500 hover:underline">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        const profileButton = document.getElementById('profileButton');
        const profileMenu = document.getElementById('profileMenu');
        const notificationButton = document.getElementById('notificationButton');
        const notificationMenu = document.getElementById('notificationMenu');

        profileButton.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });

        notificationButton.addEventListener('click', () => {
            notificationMenu.classList.toggle('hidden');
        });

        window.addEventListener('click', (e) => {
            if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
            if (!notificationButton.contains(e.target) && !notificationMenu.contains(e.target)) {
                notificationMenu.classList.add('hidden');
            }
        });

        // Fungsi untuk mengubah lokasi ke mainEditor.php
        function loadContent(url) {
            location.href = '/profile/' + url; // Pastikan '/profile/' ditambahkan jika file berada di dalam folder 'profile'
        }

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

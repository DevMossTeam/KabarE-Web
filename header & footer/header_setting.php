<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../connection/config.php';

$email = '';
$profile_pic = '../assets/default-profile.png'; // Default profile picture
$isLoggedIn = false;

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
            <div class="flex-grow flex justify-center">
                <div class="flex items-center space-x-8 lg:space-x-16">
                    <a href="/settings/umum.php" class="text-[#A2A2A2] font-bold text-lg hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">Umum</a>
                    <a href="/settings/keamananSetting.php" class="text-[#A2A2A2] font-bold text-lg hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">Keamanan</a>
                    <a href="/settings/notif_setting.php" class="text-[#A2A2A2] font-bold text-lg hover:text-blue-500 border-b-2 border-transparent hover:border-blue-500">Notifikasi</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk mengubah lokasi ke mainEditor.php
        function loadContent(url) {
            location.href = '/profile/' + url;
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

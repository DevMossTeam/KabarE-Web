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
    <div class="bg-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/index.php" class="text-black text-2xl font-bold">
                <img src="../assets/web-icon/KabarE-UTDF.png" alt="Logo" class="w-10 h-10">
            </a>
            <div class="flex items-center space-x-4">
                <a href="/settings/umum.php" class="text-gray-700 hover:text-blue-500">Umum</a>
                <a href="/settings/keamanan.php" class="text-gray-700 hover:text-blue-500">Keamanan</a>
                <a href="/settings/notifikasi.php" class="text-gray-700 hover:text-blue-500">Notifikasi</a>
                <!-- Tambahkan menu lainnya sesuai kebutuhan -->
            </div>
        </div>
    </div>
</body>

</html>

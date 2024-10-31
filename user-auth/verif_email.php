<?php
session_start();
require __DIR__ . '/vendor/autoload.php'; // Menggunakan autoloader Composer

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

$email = $_SESSION['email'] ?? '';

// Jika email tidak ada, arahkan kembali ke halaman registrasi
if (!$email) {
    header('Location: register.php');
    exit;
}

// Logika untuk memverifikasi email
if (isset($_POST['verify'])) {
    $factory = (new Factory)->withServiceAccount(__DIR__ . '/firebase/kabare-cf940-firebase-adminsdk-8qu0w-017b632945.json');
    $auth = $factory->createAuth();

    try {
        $user = $auth->getUserByEmail($email);
        if ($user->emailVerified) {
            header('Location: create_password.php');
            exit;
        } else {
            $error = "Email belum diverifikasi. Silakan cek email Anda.";
        }
    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        $error = "Pengguna tidak ditemukan.";
    } catch (\Exception $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        // Timer mundur 2 menit
        let time = 120;
        const timer = setInterval(() => {
            if (time <= 0) {
                clearInterval(timer);
                document.getElementById('timer').innerText = "Waktu Habis";
            } else {
                time--;
                document.getElementById('timer').innerText = `0:${time < 10 ? '0' : ''}${time}`;
            }
        }, 1000);
    </script>
</head>
<body class="flex h-screen">
    <div class="flex-1 bg-blue-500 flex items-center justify-center relative">
        <img src="../assets/web-icon/KabarE-UTDF.png" alt="Logo" class="h-12 absolute top-0 left-0 m-4">
        <img src="../assets/web-icon/your-icon.png" alt="Icon" class="h-64">
    </div>
    <div class="flex-1 flex items-center justify-center bg-white">
        <div class="w-full max-w-md text-center">
            <h2 class="text-3xl font-bold mb-2" style="color: #61A6FF;">Verifikasi Email</h2>
            <p class="text-gray-600 mb-2">Silahkan buka email anda dan konfirmasi</p>
            <p class="text-gray-600 mb-6">Kami telah mengirimkan link ke email</p>
            <p class="text-black font-bold mb-6"><?php echo htmlspecialchars($email); ?></p>
            <div id="timer" class="text-4xl font-bold mb-6">2:00</div>
            <p class="text-gray-600 mb-4">Tidak menerima pesan?</p>
            <form method="POST">
                <button name="verify" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Verifikasi Email</button>
            </form>
            <?php if (isset($error)): ?>
                <div class="text-red-500 mt-4"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

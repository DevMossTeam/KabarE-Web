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
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex h-screen">
    <div class="flex-1 bg-blue-500 flex items-center justify-center relative">
        <img src="../assets/web-icon/KabarE-UTDF.png" alt="Logo" class="h-12 absolute top-0 left-0 m-4">
        <img src="../assets/web-icon/your-icon.png" alt="Icon" class="h-64">
    </div>
    <div class="flex-1 flex items-center justify-center bg-white">
        <div class="w-full max-w-md text-center">
            <h2 class="text-3xl font-bold mb-2" style="color: #61A6FF;">Verifikasi OTP</h2>
            <p class="text-gray-600 mb-2">Silahkan buka email anda dan konfirmasi</p>
            <p class="text-gray-600 mb-6">Kami telah mengirimkan kode otp ke email</p>
            <p class="text-black font-bold mb-6"><?php echo htmlspecialchars($email); ?></p>
            <div class="flex justify-center mb-6">
                <!-- Kotak input OTP -->
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <input type="text" maxlength="1" class="w-12 h-12 border-2 border-gray-300 rounded text-center mx-1">
                <?php endfor; ?>
            </div>
            <p class="text-gray-600 mb-4">Tidak menerima kode OTP?</p>
            <form method="POST">
                <button name="verify" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Kirim Ulang</button>
            </form>
            <?php if (isset($error)): ?>
                <div class="text-red-500 mt-4"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
session_start();
require __DIR__ . '/vendor/autoload.php'; // Menggunakan autoloader Composer

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $namaLengkap = $_POST['fullname'];

    // Simpan data di sesi
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['nama_lengkap'] = $namaLengkap;

    // Kirim email verifikasi menggunakan Firebase
    $factory = (new Factory)->withServiceAccount(__DIR__ . '/firebase/kabare-cf940-firebase-adminsdk-8qu0w-017b632945.json');
    $auth = $factory->createAuth();

    try {
        // Buat link verifikasi email
        $actionCodeSettings = [
            'continueUrl' => 'http://kabare-web.test:81/user-auth/create-password.php',
            'handleCodeInApp' => true,
        ];
        $link = $auth->getEmailVerificationLink($email, $actionCodeSettings);

        // Kirim email menggunakan fungsi mail() atau library lain
        mail($email, "Verifikasi Email Anda", "Klik link berikut untuk verifikasi: $link");

        echo "Email verifikasi telah dikirim.";
    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        // Handle the case where the user is not found
        echo 'User not found: ' . $e->getMessage();
    } catch (\Exception $e) {
        // Handle other exceptions
        echo 'Error: ' . $e->getMessage();
    }

    // Arahkan ke halaman verifikasi email
    header('Location: verif_email.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex h-screen m-0">
    <div class="flex-1 bg-blue-500 flex items-center justify-center relative">
        <img src="../assets/web-icon/KabarE-UTDF.png" alt="Logo" class="h-12 absolute top-0 left-0 m-4">
        <img src="../assets/web-icon/your-icon.png" alt="Icon" class="h-64">
    </div>
    <div class="flex-1 flex items-center justify-center bg-white">
        <form action="register.php" method="POST" class="w-full max-w-md px-8 pt-6 pb-8 mb-4">
            <h2 class="text-3xl font-bold mb-2 text-center text-blue-500">Belum Punya Akun?</h2>
            <p class="text-center text-gray-600 mb-6">Daftar sekarang di KabarE dan nikmati akses penuh ke berita terkini dan fitur menarik dengan langkah mudah!</p>
            <?php if (isset($error)): ?>
                <div class="mb-4 text-red-500 text-center">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <div class="mb-4">
                <label for="fullname" class="block text-sm font-bold mb-2 text-gray-700">NAMA LENGKAP</label>
                <input type="text" id="fullname" name="fullname" placeholder="Masukkan nama lengkap" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="username" class="block text-sm font-bold mb-2 text-gray-700">USERNAME</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold mb-2 text-gray-700">ALAMAT EMAIL</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Konfirmasi</button>
            <p class="text-center text-gray-600 mt-4">Sudah Memiliki akun? <a href="login.php" class="text-blue-500 hover:underline">Masuk</a></p>
            <p class="text-center mt-4 text-sm text-gray-500">Dengan mendaftar di KabarE, kamu setuju untuk mematuhi kebijakan privasi kami dan menyetujui syarat serta ketentuan layanan yang berlaku.</p>
        </form>
    </div>
</body>
</html>

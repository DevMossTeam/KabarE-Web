<?php
session_start();
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan input ada sebelum mengaksesnya
    $input = $_POST['usernameOrEmail'] ?? null; // Sesuaikan nama input
    $password = $_POST['password'] ?? null;

    if ($input && $password) {
        // Query untuk memeriksa username atau email
        $stmt = $conn->prepare("SELECT id, email, password FROM user WHERE (username = ? OR email = ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $input, $input);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Verifikasi password
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];

                    // Set cookie untuk menyimpan sesi pengguna
                    setcookie('user_id', $user['id'], time() + (86400 * 30), "/"); // Cookie berlaku selama 30 hari
                    setcookie('email', $user['email'], time() + (86400 * 30), "/");

                    header("Location: /index.php");
                    exit();
             
                } else {
                    echo "Password salah.";
                }
            } else {
                echo "Email atau username tidak ditemukan.";
            }

            $stmt->close();
        } else {
            echo "Terjadi kesalahan pada query.";
        }
    } else {
        echo "Silakan masukkan username/email dan password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-white flex items-center justify-center h-screen">
    <div class="absolute top-0 left-0 m-4">
        <img src="../assets/web-icon/KabarE-UTDK.png" alt="Logo" class="h-12">
    </div>
    <div class="w-full max-w-md">
        <h2 class="text-3xl font-bold mb-2 text-center" style="color: #61A6FF;">Selamat Datang Kembali!</h2>
        <p class="text-center text-gray-600 mb-6">Ayo masuk dan jangan lewatkan berita penting di kampusmu!</p>
        <form action="login.php" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label for="usernameOrEmail" class="block text-sm font-bold mb-2" style="color: #7C7C7C;">USERNAME</label>
                <input type="text" id="usernameOrEmail" name="usernameOrEmail" placeholder="Masukkan email atau username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-bold mb-2" style="color: #7C7C7C;">PASSWORD</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex justify-end mb-2">
                <a href="forgot_password.php" class="text-blue-500 hover:underline">Lupa Password?</a>
            </div>
            <div class="flex items-center justify-between mb-4">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Masuk</button>
            </div>
            <div class="flex items-center my-4">
                <hr class="flex-grow border-t border-gray-300">
                <span class="mx-2 text-sm text-gray-500" style="color: #6D6D6D;">ATAU MASUK DENGAN</span>
                <hr class="flex-grow border-t border-gray-300">
            </div>
            <button type="button" class="w-full flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <i class="fas fa-envelope mr-2"></i> Masuk Via Email
            </button>
        </form>
        <p class="text-center text-gray-600">Belum Memiliki akun? <a href="register.php" class="text-blue-500 hover:underline">Daftar</a></p>
        <p class="text-center mt-4 text-sm" style="color: #929292;">Dengan login di KabarE, kamu menyetujui kebijakan kami terkait pengelolaan data, penggunaan aplikasi, dan ketentuan layanan yang berlaku.</p>
    </div>
</body>
</html>

<?php
session_start();
require '../connection/config.php';

$email = $_SESSION['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword === $confirmPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password di tabel user
        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            // Password berhasil diubah, arahkan ke halaman login
            header('Location: login.php');
            exit();
        } else {
            echo "Terjadi kesalahan saat mengubah password.";
        }

        $stmt->close();
    } else {
        echo "Password tidak cocok.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password</title>
    <link rel="shortcut icon" href="../assets/web-icon/Ic-main-KabarE.svg" type="KabarE">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="flex h-screen">
    <div class="hidden lg:flex-1 lg:flex lg:items-center lg:justify-center lg:bg-blue-500 lg:relative">
        <img src="../assets/web-icon/KabarE-UTDF.svg" alt="Logo" class="h-12 absolute top-0 left-0 m-4">
        <img src="../assets/LR-icon/Ic-CC.svg" alt="Icon" width="780" height="646">
    </div>
    <div class="flex-1 flex items-center justify-center bg-white">
        <div class="w-full max-w-md">
            <h2 class="text-3xl font-bold mb-2 text-center text-blue-500">Ubah Password</h2>
            <p class="text-center text-gray-600 mb-6">Lupa password? Jangan khawatir, atur ulang password-mu dengan mudah di sini</p>
            <form action="change_password.php" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4 relative">
                    <label for="newPassword" class="block text-sm font-bold mb-2 text-gray-700">PASSWORD BARU</label>
                    <input type="password" id="newPassword" name="newPassword" placeholder="Masukkan password baru" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <i class="fas fa-eye absolute right-3 top-10 cursor-pointer"></i>
                </div>
                <div class="mb-6 relative">
                    <label for="confirmPassword" class="block text-sm font-bold mb-2 text-gray-700">ULANGI PASSWORD</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Ulangi password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <i class="fas fa-eye absolute right-3 top-10 cursor-pointer"></i>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Konfirmasi</button>
            </form>
            <p class="text-center text-gray-600">Sudah Memiliki akun? <a href="login.php" class="text-blue-500 hover:underline">Masuk</a></p>
            <p class="text-center mt-4 text-sm text-gray-400">Dengan membuat password di KabarE, kamu setuju untuk menjaga kerahasiaan data dan mematuhi kebijakan keamanan kami.</p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.querySelectorAll('.fa-eye').forEach(icon => {
            icon.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
    </script>
</body>
</html>

<?php
session_start();
require '../connection/config.php';
$username = $_SESSION['username'] ?? '';
$email = $_SESSION['email'] ?? '';
$namaLengkap = $_SESSION['nama_lengkap'] ?? '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    if ($newPassword === $confirmPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        // Masukkan data ke tabel user
        $stmt = $conn->prepare("INSERT INTO user (username, password, email, nama_lengkap) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashedPassword, $email, $namaLengkap);
        if ($stmt->execute()) {
            echo "Registrasi berhasil!";
            header('Location: login.php');
            exit;
        } else {
            echo "Terjadi kesalahan saat menyimpan data.";
        }
        $stmt->close();
    } else {
        echo "Password dan konfirmasi password tidak cocok!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="flex h-screen">
    <div class="flex-1 bg-blue-500 flex items-center justify-center relative">
        <img src="../assets/web-icon/KabarE-UTDK.png" alt="Logo" class="h-12 absolute top-0 left-0 m-4">
        <img src="../assets/web-icon/your-icon.png" alt="Icon" class="h-64">
    </div>
    <div class="flex-1 flex items-center justify-center bg-white">
        <div class="w-full max-w-md">
            <h2 class="text-3xl font-bold mb-2 text-center" style="color: #61A6FF;">Buat Password</h2>
            <p class="text-center text-gray-600 mb-6">Sudah hampir selesai!<br>Buat password yang aman untuk melindungi akun KabarE!</p>
            <form action="create_password.php" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4 relative">
                    <label for="newPassword" class="block text-sm font-bold mb-2" style="color: #7C7C7C;">PASSWORD BARU</label>
                    <input type="password" id="newPassword" name="newPassword" placeholder="Masukkan password baru" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <i class="fas fa-eye absolute right-3 top-10 cursor-pointer"></i>
                </div>
                <div class="mb-6 relative">
                    <label for="confirmPassword" class="block text-sm font-bold mb-2" style="color: #7C7C7C;">ULANGI PASSWORD</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Ulangi password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <i class="fas fa-eye absolute right-3 top-10 cursor-pointer"></i>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Konfirmasi</button>
            </form>
            <p class="text-center text-gray-600">Sudah Memiliki akun? <a href="login.php" class="text-blue-500 hover:underline">Masuk</a></p>
            <p class="text-center mt-4 text-sm" style="color: #929292;">Dengan membuat password di KabarE, kamu setuju untuk menjaga kerahasiaan data dan mematuhi kebijakan keamanan kami.</p>
        </div>
    </div>
    <script>
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
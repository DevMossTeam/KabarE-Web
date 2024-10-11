<?php
session_start();
require 'config/config.php'; // Menggunakan file config.php untuk koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Hapus validasi email khusus
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email tidak valid!";
    } elseif ($password !== $confirmPassword) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk memasukkan data pengguna baru ke tabel user
        $stmt = $conn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            header('Location: login.php'); // Arahkan ke halaman login setelah registrasi berhasil
            exit;
        } else {
            $error = "Terjadi kesalahan saat mendaftar!";
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
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
        <?php if (isset($error)): ?>
            <div class="mb-4 text-red-500 text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="confirmPassword" class="block text-gray-700">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Konfirmasi password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Register</button>
        </form>
        <div class="mt-4 text-center">
            <p class="text-gray-700">Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Login here</a></p>
        </div>
    </div>
</body>
</html>
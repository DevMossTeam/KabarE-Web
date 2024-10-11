<?php
session_start();
include 'config/config.php';

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
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
        <?php if (isset($error)): ?>
            <div class="mb-4 text-red-500 text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="mb-4">
                <label for="usernameOrEmail" class="block text-gray-700">Username or Email</label>
                <input type="text" id="usernameOrEmail" name="usernameOrEmail" placeholder="Masukkan email atau username" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">Login</button>
        </form>
        <div class="mt-4 text-center">
            <p class="text-gray-700">Don't have an account? <a href="register.php" class="text-blue-500 hover:underline">Register here</a></p>
            <p class="text-gray-700 mt-2"><a href="forgot_password.php" class="text-blue-500 hover:underline">Forgot password?</a></p>
        </div>
    </div>
</body>
</html>

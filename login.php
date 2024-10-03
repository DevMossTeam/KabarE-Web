<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Debugging: Periksa input email dan password
    error_log("Email yang diterima: $email");

    // Validasi kredensial pengguna
    $stmt = $conn->prepare("SELECT id, password FROM user WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Debugging: Periksa hasil query
            error_log("User ditemukan: " . print_r($user, true));

            if (password_verify($password, $user['password'])) {
                // Set user_id di sesi
                $_SESSION['user_id'] = $user['id'];
                error_log("User ID berhasil diset di sesi: " . $_SESSION['user_id']);
                header("Location: /index.php"); // Redirect ke halaman index setelah login
                exit();
            } else {
                echo "Password salah.";
            }
        } else {
            echo "Email tidak ditemukan.";
        }

        $stmt->close();
    } else {
        error_log("Query gagal: " . $conn->error);
    }
} else {
    error_log("Metode request bukan POST.");
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
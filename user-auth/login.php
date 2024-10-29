<?php
session_start();
include '../connection/config.php';

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = $_POST['usernameOrEmail'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($input && $password) {
        $stmt = $conn->prepare("SELECT id, email, password FROM user WHERE (username = ? OR email = ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $input, $input);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
                    setcookie('email', $user['email'], time() + (86400 * 30), "/");
                    header("Location: /index.php");
                    exit();
                } else {
                    $errorMessage = "Password salah.";
                }
            } else {
                $errorMessage = "Email atau username tidak ditemukan.";
            }
            $stmt->close();
        } else {
            $errorMessage = "Terjadi kesalahan pada query.";
        }
    } else {
        $errorMessage = "Silakan masukkan username/email dan password.";
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
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body class="flex h-screen m-0">
    <div class="flex-1 bg-blue-500 flex items-center justify-center relative">
        <img src="../assets/web-icon/KabarE-UTDF.png" alt="Logo" class="h-12 absolute top-0 left-0 m-4">
        <img src="../assets/web-icon/your-icon.png" alt="Icon" class="h-64">
    </div>
    <div class="flex-1 flex items-center justify-center bg-white">
        <form action="login.php" method="POST" class="w-full max-w-md px-8 pt-6 pb-8 mb-4">
            <h2 class="text-3xl font-bold mb-2 text-center text-blue-500">Selamat Datang Kembali!</h2>
            <p class="text-center text-gray-600 mb-6">Ayo masuk dan jangan lewatkan berita penting di kampusmu!</p>
            <div class="mb-4">
                <label for="usernameOrEmail" class="block text-sm font-bold mb-2 text-gray-700">USERNAME</label>
                <input type="text" id="usernameOrEmail" name="usernameOrEmail" placeholder="Masukkan email atau username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-6 relative">
                <label for="password" class="block text-sm font-bold mb-2 text-gray-700">PASSWORD</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <i class="fas fa-eye absolute right-3 top-10 cursor-pointer"></i>
            </div>
            <div class="flex justify-end mb-2">
                <a href="forgot_password.php" class="text-blue-500 hover:underline">Lupa Password?</a>
            </div>
            <div class="flex items-center justify-between mb-4">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Masuk</button>
            </div>
            <div class="flex items-center my-4">
                <hr class="flex-grow border-t border-gray-300">
                <span class="mx-2 text-sm text-gray-500">ATAU MASUK DENGAN</span>
                <hr class="flex-grow border-t border-gray-300">
            </div>
            <div id="g_id_onload"
                 data-client_id="894068159772-teaqelumke1vmctgtg04921otomv6oa6.apps.googleusercontent.com"
                 data-callback="handleCredentialResponse">
            </div>
            <div class="g_id_signin"
                 data-type="standard"
                 data-shape="rectangular"
                 data-theme="outline"
                 data-text="sign_in_with"
                 data-size="large">
            </div>
            <p class="text-center text-gray-600 mt-4">Belum Memiliki akun? <a href="register.php" class="text-blue-500 hover:underline">Daftar</a></p>
            <p class="text-center mt-4 text-sm text-gray-500">Dengan login di KabarE, kamu menyetujui kebijakan kami terkait pengelolaan data, penggunaan aplikasi, dan ketentuan layanan yang berlaku.</p>
        </form>
    </div>

    <!-- Modal -->
    <?php if ($errorMessage): ?>
    <div id="errorModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-md text-center">
            <i class="fas fa-exclamation-circle text-red-500 text-4xl mb-4"></i>
            <p><?php echo $errorMessage; ?></p>
            <button onclick="closeModal()" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Tutup</button>
        </div>
    </div>
    <script>
        function closeModal() {
            document.getElementById('errorModal').style.display = 'none';
        }
    </script>
    <?php endif; ?>
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

<?php
session_start();
include '../connection/config.php';

// Inisialisasi pesan kesalahan dari session
$usernameError = $_SESSION['usernameError'] ?? '';
$passwordError = $_SESSION['passwordError'] ?? '';
$errorCount = $_SESSION['errorCount'] ?? 0;

// Hapus pesan kesalahan dari session setelah diambil
unset($_SESSION['usernameError'], $_SESSION['passwordError']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = $_POST['usernameOrEmail'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($input && $password) {
        // Tentukan query berdasarkan tipe input (email atau username)
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("SELECT uid, email, nama_pengguna, password, profile_pic, role FROM user WHERE email = ?");
        } else {
            $stmt = $conn->prepare("SELECT uid, email, nama_pengguna, password, profile_pic, role FROM user WHERE nama_pengguna = ?");
        }

        if ($stmt) {
            $stmt->bind_param("s", $input);
            $stmt->execute();
            $result = $stmt->get_result();

            $loginSuccess = false;
            $userExists = false;

            while ($user = $result->fetch_assoc()) {
                $userExists = true;
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['uid'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['profile_pic'] = $user['profile_pic'] ?: 'default-profile.png';
                    $_SESSION['role'] = $user['role'];
                    setcookie('user_id', $user['uid'], time() + (86400 * 30), "/");
                    setcookie('email', $user['email'], time() + (86400 * 30), "/");

                    $loginSuccess = true;
                    $_SESSION['errorCount'] = 0; // Reset error count on success

                    // Redirect berdasarkan role
                    if ($user['role'] === 'admin') {
                        header("Location: ../dashboard_admin/home/index.php");
                    } else {
                        header("Location: /index.php");
                    }
                    exit();
                }
            }

            if (!$loginSuccess) {
                $_SESSION['errorCount'] = ++$errorCount; // Increment error count
                if (!$userExists) {
                    $_SESSION['usernameError'] = "Username atau email yang Anda masukkan salah. Silakan coba lagi.";
                } else {
                    $_SESSION['passwordError'] = "Password yang Anda masukkan salah. Silakan coba lagi.";
                }

                if ($_SESSION['errorCount'] >= 5) {
                    $_SESSION['usernameError'] = "Anda terlalu sering salah memasukkan username atau password. Silakan coba lagi nanti atau reset password Anda.";
                }
            }

            $stmt->close();
        } else {
            $_SESSION['errorMessage'] = "Terjadi kesalahan pada query.";
        }
    } else {
        if (!$input) {
            $_SESSION['usernameError'] = "Silakan masukkan username/email.";
        }
        if (!$password) {
            $_SESSION['passwordError'] = "Silakan masukkan password.";
        }
    }

    // Redirect untuk menghindari pengulangan form submission
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="shortcut icon" href="../assets/web-icon/Ic-main-KabarE.svg" type="KabarE">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="module">
        // Import Firebase modules
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";
        import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-auth.js";

        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyBdDOQx-Yfl9ydoB5FIWEyn4DJrEwWjp5k",
            authDomain: "kabare-cf940.firebaseapp.com",
            projectId: "kabare-cf940",
            storageBucket: "kabare-cf940.appspot.com",
            messagingSenderId: "675057825306",
            appId: "1:675057825306:web:ebe75a0745d0971fbc43a3",
            measurementId: "G-NVK1DR5YMN"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        // Function to handle Google Sign-In
        window.signInWithGoogle = function() {
            const provider = new GoogleAuthProvider();

            signInWithPopup(auth, provider)
                .then((result) => {
                    const user = result.user;
                    return user.getIdToken().then((token) => {
                        // Send token and email to the server
                        return fetch('verify_token.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ token, email: user.email })
                        });
                    });
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Server responded with an error: ' + response.status);
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        // Simpan email dan foto profil ke session
                        return fetch('set_session.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ email: data.email, profile_pic: data.picture })
                        });
                    } else {
                        console.error('Token verification failed:', data.message);
                        alert('Token verification failed. Please try again.');
                    }
                })
                .then(() => {
                    window.location.href = '/index.php'; // Redirect to index on success
                })
                .catch((error) => {
                    console.error('Google Sign-In error:', error);
                    alert('An error occurred during Google Sign-In. Please try again: ' + error.message);
                });
        };
    </script>
</head>
<body class="flex h-screen m-0">
    <div class="hidden lg:flex-1 lg:flex lg:items-center lg:justify-center lg:bg-blue-500 lg:relative">
        <img src="../assets/web-icon/KabarE-UTDF.svg" alt="Logo" class="h-12 absolute top-0 left-0 m-4">
        <img src="../assets/LR-icon/Ic-login.svg" alt="Icon" width="780" height="646">
    </div>
    <div class="flex-1 flex items-center justify-center bg-white w-full">
        <form action="login.php" method="POST" class="w-full max-w-md px-8 pt-6 pb-8 mb-4">
            <h2 class="text-3xl font-bold mb-2 text-center text-blue-500">Selamat Datang Kembali!</h2>
            <p class="text-center text-gray-600 mb-6">Ayo masuk dan jangan lewatkan berita penting di kampusmu!</p>
            <div class="mb-4 relative">
                <label for="usernameOrEmail" class="block text-sm font-bold mb-2 text-gray-700">USERNAME ATAU EMAIL</label>
                <input type="text" id="usernameOrEmail" name="usernameOrEmail" placeholder="Masukkan email atau username" class="shadow appearance-none border <?php echo $usernameError ? 'border-red-500' : 'border-gray-300'; ?> rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <?php if ($usernameError): ?>
                <i class="fas fa-exclamation-circle text-red-500 absolute right-3 top-10"></i>
                <p class="text-red-500 text-xs italic mt-2"><?php echo $usernameError; ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-6 relative">
                <label for="password" class="block text-sm font-bold mb-2 text-gray-700">PASSWORD</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" class="shadow appearance-none border <?php echo $passwordError ? 'border-red-500' : 'border-gray-300'; ?> rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <?php if (!$passwordError): ?>
                <i class="fas fa-eye absolute right-3 top-10 cursor-pointer"></i>
                <?php endif; ?>
                <?php if ($passwordError): ?>
                <i class="fas fa-exclamation-circle text-red-500 absolute right-3 top-10"></i>
                <p class="text-red-500 text-xs italic mt-2"><?php echo $passwordError; ?></p>
                <?php endif; ?>
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
            <button type="button" class="flex items-center justify-center w-full bg-white border border-gray-300 text-gray-700 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline hover:bg-gray-100" onclick="signInWithGoogle()">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google logo" class="w-5 h-5 mr-2">
                Sign in with Google
            </button>
            <p class="text-center text-gray-600 mt-4">Belum Memiliki akun? <a href="register.php" class="text-blue-500 hover:underline">Daftar</a></p>
            <p class="text-center mt-4 text-sm text-gray-500">Dengan login di KabarE, kamu menyetujui kebijakan kami terkait pengelolaan data, penggunaan aplikasi, dan ketentuan layanan yang berlaku.</p>
            <div id="id-token-display" class="hidden"></div>
        </form>
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

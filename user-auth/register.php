<?php
session_start();
require '../connection/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        // Konfigurasi server email
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Host SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'devmossteam@gmail.com'; // Email Anda
        $mail->Password = 'auarutsuzgpwtriy'; // Password email Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Penerima
        $mail->setFrom('devmossteam@gmail.com', 'KabarE'); // Pastikan email pengirim sesuai
        $mail->addAddress($email);

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP Anda';

        // Konten HTML email
        $htmlBody = "
            <html>
            <head>
                <style>
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        background-color: #f4f4f4;
                        padding: 20px;
                        margin: 0;
                    }
                    .email-container {
                        background-color: #ffffff;
                        padding: 40px;
                        border-radius: 10px;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                        width: 100%;
                        max-width: 600px;
                        margin: 0 auto;
                    }
                    h2 {
                        color: #333;
                        font-size: 28px;
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    .otp-code {
                        font-size: 40px;
                        font-weight: bold;
                        color: #007BFF;
                        text-align: center;
                        padding: 15px 0;
                        background-color: #f0f8ff;
                        border-radius: 5px;
                        margin: 20px 0;
                    }
                    .footer {
                        text-align: center;
                        font-size: 14px;
                        color: #888;
                        margin-top: 30px;
                    }
                    .footer a {
                        color: #007BFF;
                        text-decoration: none;
                    }
                    .footer a:hover {
                        text-decoration: underline;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <h2>Kode OTP Anda</h2>
                    <p>Terima kasih telah menggunakan layanan kami. Berikut adalah Kode One-Time Password (OTP) Anda untuk verifikasi:</p>
                    <div class='otp-code'>$otp</div>
                    <p>Jika Anda tidak meminta OTP ini, silakan abaikan email ini.</p>
                </div>
            </body>
            </html>
        ";

        $mail->Body = $htmlBody;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$error = null; // Inisialisasi variabel error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pengguna = $_POST['username'];
    $email = $_POST['email'];
    $namaLengkap = $_POST['fullname'];

    // Cek apakah email sudah ada di database
    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Email sudah terdaftar. Silakan gunakan email lain.";
    } else {
        // Buat OTP 6 digit
        $otp = rand(100000, 999999);

        // Simpan data di sesi
        $_SESSION['nama_pengguna'] = $nama_pengguna;
        $_SESSION['email'] = $email;
        $_SESSION['nama_lengkap'] = $namaLengkap;
        $_SESSION['otp'] = $otp;
        $_SESSION['from_register'] = true;

        // Kirim email OTP
        if (sendOTP($email, $otp)) {
            // Arahkan ke halaman verifikasi email
            header('Location: verif_email.php');
            exit;
        } else {
            $error = "Gagal mengirim email. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="shortcut icon" href="../assets/web-icon/Ic-main-KabarE.svg" type="KabarE">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex h-screen m-0">
    <div class="hidden lg:flex-1 lg:flex lg:items-center lg:justify-center lg:bg-blue-500 lg:relative">
        <img src="../assets/web-icon/KabarE-UTDF.svg" alt="Logo" class="h-12 absolute top-0 left-0 m-4">
        <img src="../assets/LR-icon/Ic-register.svg" alt="Icon" width="780" height="646">
    </div>
    <div class="flex-1 flex items-center justify-center bg-white">
        <form action="register.php" method="POST" class="w-full max-w-md px-8 pt-6 pb-8 mb-4">
            <h2 class="text-3xl font-bold mb-2 text-center text-blue-500">Belum Punya Akun?</h2>
            <p class="text-center text-gray-600 mb-6">Daftar sekarang di KabarE dan nikmati akses penuh ke berita terkini dan fitur menarik dengan langkah mudah!</p>
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
                <input type="email" id="email" name="email" placeholder="Masukkan email" class="shadow appearance-none border <?php echo $error ? 'border-red-500' : ''; ?> rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <?php if ($error): ?>
                    <div class="text-red-500 text-center mt-2">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Konfirmasi</button>
            <p class="text-center text-gray-600 mt-4">Sudah Memiliki akun? <a href="login.php" class="text-blue-500 hover:underline">Masuk</a></p>
            <p class="text-center mt-4 text-sm text-gray-500">Dengan mendaftar di KabarE, kamu setuju untuk mematuhi kebijakan privasi kami dan menyetujui syarat serta ketentuan layanan yang berlaku.</p>
        </form>
    </div>
</body>
</html>

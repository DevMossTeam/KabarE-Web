<?php
session_start();
require '../connection/config.php';
require '../vendor/autoload.php'; // Pastikan PHPMailer di-autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Periksa apakah email ada di database
    $stmt = $conn->prepare("SELECT Uid FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate kode OTP
        $otp = rand(100000, 999999);
        $_SESSION['email'] = $email;
        $_SESSION['otp'] = $otp;
        $_SESSION['from_register'] = false;

        // Kirim email dengan kode OTP
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'devmossteam@gmail.com'; // Ganti dengan email Anda
            $mail->Password = 'auarutsuzgpwtriy'; // Ganti dengan password email Anda
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('devmossteam@gmail.com', 'KabarE');
            $mail->addAddress($email);

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
                        <p>Terima kasih telah menggunakan layanan kami. Berikut adalah Kode One-Time Password (OTP) Anda untuk memulihkan kata sandi:</p>
                        <div class='otp-code'>$otp</div>
                        <p>Jika Anda tidak meminta pemulihan kata sandi ini, silakan abaikan email ini.</p>
                    </div>
                </body>
                </html>
            ";

            $mail->Body = $htmlBody;

            $mail->send();
            header('Location: verif_email.php');
            exit;
        } catch (Exception $e) {
            echo "Gagal mengirim email. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email tidak ditemukan.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        // Reset the OTP timer when accessing this page
        localStorage.removeItem('otp_timer');
    </script>
</head>
<body class="flex h-screen">
    <div class="hidden lg:flex-1 lg:flex lg:items-center lg:justify-center lg:bg-blue-500 lg:relative">
        <img src="../assets/web-icon/KabarE-UTDF.svg" alt="Logo" class="h-12 absolute top-0 left-0 m-4">
        <img src="../assets/LR-icon/Ic-lupa-password.svg" alt="Icon" width="780" height="646">
    </div>
    <div class="flex-1 flex items-center justify-center bg-white">
        <div class="w-full max-w-md">
            <h2 class="text-3xl font-bold mb-2 text-center" style="color: #61A6FF;">Lupa Password?</h2>
            <p class="text-center text-gray-600 mb-6">Masukkan email akunmu, dan kami akan mengirimkan link untuk anda verifikasi</p>
            <form action="forgot_password.php" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-bold mb-2" style="color: #7C7C7C;">ALAMAT EMAIL</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Kirim</button>
                <a href="login.php" class="w-full block text-center mt-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Batal</a>
            </form>
            <p class="text-center mt-4 text-sm" style="color: #929292;">Dengan mengirim email melalui KabarE, kamu memberikan persetujuan untuk penggunaan data sesuai dengan kebijakan privasi kami dan menyetujui syarat dan ketentuan yang berlaku.</p>
        </div>
    </div>
</body>
</html>

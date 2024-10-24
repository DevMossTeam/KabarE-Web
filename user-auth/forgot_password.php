<?php
session_start();
require '../connection/config.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Periksa apakah email ada di database
    $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email;

        // Kirim email reset password
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'devmossteam@gmail.com';
            $mail->Password = 'jbrfnqjdvivrpkur';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('devmossteam@gmail.com', 'KabarE');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password Anda';
            $mail->Body    = '
            <html>
            <head>
              <title>Reset Password</title>
            </head>
            <body>
              <p>Silahkan klik tautan berikut untuk mengganti password Anda:</p>
              <a href="http://kabare-web.test:81/user-auth/change_password.php">Ganti Password</a>
            </body>
            </html>
            ';

            $mail->send();
            header('Location: verif_email.php');
            exit;
        } catch (Exception $e) {
            echo "Email tidak dapat dikirim. Mailer Error: {$mail->ErrorInfo}";
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
</head>
<body class="bg-white flex items-center justify-center h-screen">
    <div class="absolute top-0 left-0 m-4">
        <img src="../assets/web-icon/KabarE-UTDK.png" alt="Logo" class="h-12">
    </div>
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
</body>
</html>

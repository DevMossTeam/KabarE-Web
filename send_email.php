<?php
session_start();
require '../vendor/autoload.php'; // Autoload PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_SESSION['email'] ?? '';
// Jika email tidak ada, arahkan kembali ke halaman registrasi
if (!$email) {
    header('Location: register.php');
    exit;
}

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
        $mail->Body    = "Kode OTP Anda adalah: <b>$otp</b>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
<?php
session_start();
require '../connection/config.php';

$nama_pengguna = $_SESSION['nama_pengguna'] ?? '';
$email = $_SESSION['email'] ?? '';
$namaLengkap = $_SESSION['nama_lengkap'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword === $confirmPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Masukkan data ke tabel user
        $stmt = $conn->prepare("INSERT INTO user (nama_pengguna, password, email, nama_lengkap) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama_pengguna, $hashedPassword, $email, $namaLengkap);

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
        
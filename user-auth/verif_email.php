<?php
session_start();

$email = $_SESSION['email'] ?? '';

// Jika email tidak ada, arahkan kembali ke halaman registrasi
if (!$email) {
    header('Location: register.php');
    exit;
}

// Kode untuk mengirim email verifikasi dihapus
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        // Timer mundur 1 menit
        let time = 60;
        const timer = setInterval(() => {
            if (time <= 0) {
                clearInterval(timer);
                document.getElementById('timer').innerText = "Waktu Habis";
            } else {
                time--;
                document.getElementById('timer').innerText = `0:${time < 10 ? '0' : ''}${time}`;
            }
        }, 1000);
    </script>
</head>
<body class="bg-white flex items-center justify-center h-screen">
    <div class="absolute top-0 left-0 m-4">
        <img src="../assets/web-icon/KabarE-UTDK.png" alt="Logo" class="h-12">
    </div>
    <div class="w-full max-w-md text-center">
        <h2 class="text-3xl font-bold mb-2" style="color: #61A6FF;">Verifikasi Email</h2>
        <p class="text-gray-600 mb-2">Silahkan buka email anda dan konfirmasi</p>
        <p class="text-gray-600 mb-6">Kami telah mengirimkan link ke email</p>
        <p class="font-bold mb-6"><?php echo htmlspecialchars($email); ?></p>
        <div id="timer" class="text-4xl font-bold mb-6">1:00</div>
        <p class="text-gray-600 mb-4">Tidak menerima pesan?</p>
        <form method="POST">
            <button name="resend" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Kirim Ulang</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();
$email = $_SESSION['email'] ?? '';
$otp_session = $_SESSION['otp'] ?? ''; // Ambil OTP dari sesi
$from_register = $_SESSION['from_register'] ?? false; // Cek asal pengguna

if (!$email) {
    header('Location: register.php');
    exit;
}

if (isset($_POST['verify'])) {
    $otp_input_array = $_POST['otp'] ?? [];
    $otp_input = implode('', $otp_input_array); // Gabungkan input OTP
    if ($otp_input == $otp_session) {
        // Verifikasi berhasil, arahkan ke halaman yang sesuai
        if ($from_register) {
            header('Location: create_password.php');
        } else {
            header('Location: change_password.php');
        }
        exit;
    } else {
        $error = "Kode OTP salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex h-screen">
    <div class="hidden lg:flex-1 lg:flex lg:items-center lg:justify-center lg:bg-blue-500 lg:relative">
        <img src="../assets/web-icon/KabarE-UTDF.svg" alt="Logo" class="h-12 absolute top-0 left-0 m-4">
        <img src="../assets/LR-icon/Ic-verif-email.svg" alt="Icon" width="780" height="646">
    </div>
    <div class="flex-1 flex items-center justify-center bg-white">
        <div class="w-full max-w-md text-center">
            <h2 class="text-3xl font-bold mb-2" style="color: #61A6FF;">Verifikasi OTP</h2>
            <p class="text-gray-600 mb-2">Silahkan buka email anda dan konfirmasi</p>
            <p class="text-gray-600 mb-6">Kami telah mengirimkan kode otp ke email</p>
            <p class="text-black font-bold mb-6"><?php echo htmlspecialchars($email); ?></p>
            <form method="POST">
                <div class="flex justify-center mb-6">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <input type="text" name="otp[]" maxlength="1" class="w-12 h-12 border-2 border-gray-300 rounded text-center mx-1 otp-input" required>
                    <?php endfor; ?>
                </div>
                <button name="verify" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Verifikasi</button>
            </form>
            <?php if (isset($error)): ?>
                <div class="text-red-500 mt-4"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        const inputs = document.querySelectorAll('.otp-input');

        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && index > 0 && input.value.length === 0) {
                    inputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').replace(/\D/g, '');
                pasteData.split('').forEach((char, i) => {
                    if (i < inputs.length) {
                        inputs[i].value = char;
                    }
                });
                inputs[Math.min(pasteData.length, inputs.length) - 1].focus();
            });
        });
    </script>
</body>
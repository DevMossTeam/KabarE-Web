<?php
session_start();
include '../connection/config.php'; // Pastikan path ini benar
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Fungsi untuk mengirim OTP
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'devmossteam@gmail.com';
        $mail->Password = 'auarutsuzgpwtriy';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('devmossteam@gmail.com', 'KabarE');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP Anda';
        $htmlBody = "
            <html>
            <head>
                <style>
                    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
                    .email-container { background-color: #ffffff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); width: 100%; max-width: 600px; margin: 0 auto; }
                    h2 { color: #333; font-size: 28px; text-align: center; margin-bottom: 20px; }
                    .otp-code { font-size: 40px; font-weight: bold; color: #007BFF; text-align: center; padding: 15px 0; background-color: #f0f8ff; border-radius: 5px; margin: 20px 0; }
                    .footer { text-align: center; font-size: 14px; color: #888; margin-top: 30px; }
                    .footer a { color: #007BFF; text-decoration: none; }
                    .footer a:hover { text-decoration: underline; }
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

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'] ?? null;
if ($user_id) {
    $stmt = $conn->prepare("SELECT email FROM user WHERE uid = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();
}

// Proses pengiriman OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendOTP'])) {
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otpFor'] = 'currentEmail'; // Menandai OTP untuk email saat ini
    if (sendOTP($email, $otp)) {
        $otpSuccess = "Kode OTP telah dikirim ke email Anda.";
        echo "<script>document.addEventListener('DOMContentLoaded', function() { showOtpModal(); });</script>";
    } else {
        $otpError = "Gagal mengirim kode OTP. Silakan coba lagi.";
    }
}

// Verifikasi OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verifyOTP'])) {
    $otp_input_array = $_POST['otp'] ?? [];
    $otp_input = implode('', $otp_input_array);
    if ($otp_input == $_SESSION['otp']) {
        if ($_SESSION['otpFor'] === 'currentEmail') {
            // Jika OTP untuk email saat ini, buka modal untuk memasukkan email baru
            echo "<script>document.addEventListener('DOMContentLoaded', function() { closeOtpModal(); showNewEmailModal(); });</script>";
        } elseif ($_SESSION['otpFor'] === 'newEmail') {
            // Jika OTP untuk email baru, perbarui email di database
            $newEmail = $_SESSION['newEmail'];
            $stmt = $conn->prepare("UPDATE user SET email = ? WHERE uid = ?");
            $stmt->bind_param("ss", $newEmail, $user_id);
            if ($stmt->execute()) {
                $emailUpdateSuccess = "Email berhasil diperbarui.";
                echo "<script>document.addEventListener('DOMContentLoaded', function() { closeOtpModal(); showSuccessModal(); });</script>";
            } else {
                $emailUpdateError = "Gagal memperbarui email.";
                echo "<script>document.addEventListener('DOMContentLoaded', function() { showOtpModal(); });</script>";
            }
            $stmt->close();
        }
    } else {
        $otpError = "Kode OTP salah.";
        echo "<script>document.addEventListener('DOMContentLoaded', function() { showOtpModal(); });</script>";
    }
}

// Proses pembaruan email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateEmail'])) {
    $newEmail = $_POST['newEmail'];
    $currentPassword = $_POST['currentPassword'];

    // Verifikasi kata sandi saat ini
    $stmt = $conn->prepare("SELECT password FROM user WHERE uid = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($currentPassword, $hashedPassword)) {
        // Kirim OTP ke email baru
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['newEmail'] = $newEmail;
        $_SESSION['otpFor'] = 'newEmail'; // Menandai OTP untuk email baru
        if (sendOTP($newEmail, $otp)) {
            echo "<script>document.addEventListener('DOMContentLoaded', function() { showOtpModal(); });</script>";
        } else {
            $emailUpdateError = "Gagal mengirim kode OTP ke email baru.";
        }
    } else {
        $emailUpdateError = "Kata sandi saat ini salah.";
        echo "<script>document.addEventListener('DOMContentLoaded', function() { showNewEmailModal(); });</script>";
    }
}

// Proses pembaruan kata sandi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updatePassword'])) {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Verifikasi kata sandi lama
    $stmt = $conn->prepare("SELECT password FROM user WHERE uid = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($oldPassword, $hashedPassword)) {
        if ($newPassword === $confirmPassword) {
            // Hash kata sandi baru
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Perbarui kata sandi di database
            $stmt = $conn->prepare("UPDATE user SET password = ? WHERE uid = ?");
            $stmt->bind_param("ss", $newHashedPassword, $user_id);
            if ($stmt->execute()) {
                $passwordUpdateSuccess = "Kata sandi berhasil diperbarui.";
                echo "<script>document.addEventListener('DOMContentLoaded', function() { closePasswordModal(); showSuccessModal(); });</script>";
            } else {
                $passwordUpdateError = "Gagal memperbarui kata sandi.";
            }
            $stmt->close();
        } else {
            $passwordUpdateError = "Kata sandi baru dan konfirmasi tidak cocok.";
        }
    } else {
        $passwordUpdateError = "Kata sandi lama salah.";
    }
}
?>

<?php include '../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex space-x-8 lg:px-8">
    <!-- Pembungkus Keamanan -->
    <div class="w-1/3 lg:w-1/4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex flex-col items-start">
                <h3 class="text-lg font-semibold mb-2">Keamanan</h3>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-lock text-blue-500"></i>
                    <span class="text-gray-700">Pengaturan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pembungkus Pengaturan Keamanan -->
    <div class="w-2/3 lg:w-3/4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-[20px] font-regular mb-4 ml-10">Pengaturan Keamanan</h2>
            <div class="space-y-4 max-w-5xl mx-auto">
                <div class="flex items-center pb-2">
                    <i class="fas fa-envelope text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Email</p>
                            <p class="font-semibold" id="emailText"><?php echo htmlspecialchars($email); ?></p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="showEmailModal()"></i>
                </div>
                
                <div class="flex items-center pb-2">
                    <i class="fas fa-lock text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Kata Sandi</p>
                            <p class="font-semibold" id="passwordText">********</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="showPasswordModal()"></i>
                </div>

                <!-- Bantuan -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold">Bantuan</h3>
                    <div class="flex items-center space-x-2 mt-4">
                        <i class="fas fa-question-circle text-gray-500"></i>
                        <div>
                            <p class="text-gray-700">Lupa Kata Sandi?</p>
                            <p class="text-blue-500 hover:underline">Hubungi kami untuk mengatasi permasalahan anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Verifikasi Email -->
<div id="emailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md text-center">
        <img src="https://img.icons8.com/ios-filled/50/000000/email.png" alt="Email Icon" class="mx-auto mb-4" />
        <h2 class="text-xl font-bold mb-4">Verifikasi Email</h2>
        <p class="text-gray-700 mb-4">Untuk melanjutkan, kami perlu memverifikasi email Anda. Klik tombol di bawah untuk mengirim kode verifikasi ke email Anda saat ini.</p>
        <form method="POST" class="flex justify-center space-x-2">
            <button type="button" onclick="closeEmailModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Tutup</button>
            <button name="sendOTP" class="bg-blue-500 text-white px-4 py-2 rounded">Kirim Kode Verifikasi</button>
        </form>
        <?php if (isset($otpSuccess)): ?>
            <div class="text-green-500 mt-4"><?php echo $otpSuccess; ?></div>
        <?php elseif (isset($otpError)): ?>
            <div class="text-red-500 mt-4"><?php echo $otpError; ?></div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal untuk Memasukkan Kode OTP -->
<div id="otpModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md text-center">
        <img src="https://img.icons8.com/ios-filled/50/000000/lock.png" alt="OTP Icon" class="mx-auto mb-4" />
        <h2 class="text-xl font-bold mb-4">Masukkan Kode OTP</h2>
        <p class="text-gray-700 mb-4">Kami telah mengirimkan kode OTP ke email Anda. Silakan masukkan kode tersebut di bawah ini untuk melanjutkan.</p>
        <form method="POST">
            <div class="flex justify-center mb-6">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <input type="text" name="otp[]" maxlength="1" class="w-12 h-12 border-2 border-gray-300 rounded text-center mx-1 otp-input" required>
                <?php endfor; ?>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeOtpModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button name="verifyOTP" class="bg-blue-500 text-white px-4 py-2 rounded">Verifikasi</button>
            </div>
        </form>
        <?php if (isset($otpError)): ?>
            <div class="text-red-500 mt-4"><?php echo $otpError; ?></div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal untuk Memasukkan Email Baru -->
<div id="newEmailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md text-center">
        <img src="https://img.icons8.com/ios-filled/50/000000/edit.png" alt="Edit Icon" class="mx-auto mb-4" />
        <h2 class="text-xl font-bold mb-4">Masukkan Email Baru</h2>
        <p class="text-gray-700 mb-4">Silakan masukkan email baru Anda dan konfirmasi dengan kata sandi terakhir Anda untuk memperbarui informasi email Anda.</p>
        <form method="POST">
            <input type="email" name="newEmail" placeholder="Email Baru" class="w-full p-2 border border-gray-300 rounded mb-4" required>
            <input type="password" name="currentPassword" placeholder="Kata Sandi Terakhir" class="w-full p-2 border border-gray-300 rounded mb-4" required>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeNewEmailModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button name="updateEmail" class="bg-blue-500 text-white px-4 py-2 rounded">Perbarui Email</button>
            </div>
        </form>
        <?php if (isset($emailUpdateError)): ?>
            <div class="text-red-500 mt-4"><?php echo $emailUpdateError; ?></div>
        <?php elseif (isset($emailUpdateSuccess)): ?>
            <div class="text-green-500 mt-4">Email berhasil diperbarui.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal untuk Mengubah Kata Sandi -->
<div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md text-center">
        <h2 class="text-xl font-bold mb-4">Ubah Kata Sandi</h2>
        <p class="text-gray-700 mb-4">Untuk keamanan akun Anda, silakan masukkan kata sandi lama Anda dan buat kata sandi baru yang kuat.</p>
        <form method="POST">
            <input type="password" name="oldPassword" placeholder="Kata Sandi Lama" class="w-full p-2 border border-gray-300 rounded mb-4" required>
            <input type="password" name="newPassword" placeholder="Kata Sandi Baru" class="w-full p-2 border border-gray-300 rounded mb-4" required>
            <input type="password" name="confirmPassword" placeholder="Konfirmasi Kata Sandi Baru" class="w-full p-2 border border-gray-300 rounded mb-4" required>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closePasswordModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button name="updatePassword" class="bg-blue-500 text-white px-4 py-2 rounded">Perbarui Kata Sandi</button>
            </div>
        </form>
        <?php if (isset($passwordUpdateError)): ?>
            <div class="text-red-500 mt-4"><?php echo $passwordUpdateError; ?></div>
        <?php elseif (isset($passwordUpdateSuccess)): ?>
            <div class="text-green-500 mt-4">Kata sandi berhasil diperbarui.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal untuk Sukses Memperbarui Email -->
<div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md text-center">
        <img src="https://img.icons8.com/ios-filled/50/000000/checked.png" alt="Success Icon" class="mx-auto mb-4" />
        <h2 class="text-xl font-bold mb-4">Sukses</h2>
        <p class="text-gray-700 mb-4">Email Anda telah berhasil diperbarui. Terima kasih telah memperbarui informasi Anda.</p>
        <button type="button" onclick="closeSuccessModal()" class="bg-blue-500 text-white px-4 py-2 rounded">Tutup</button>
    </div>
</div>

<script>
    function showEmailModal() {
        document.getElementById('emailModal').classList.remove('hidden');
    }

    function closeEmailModal() {
        document.getElementById('emailModal').classList.add('hidden');
    }

    function showOtpModal() {
        document.getElementById('otpModal').classList.remove('hidden');
    }

    function closeOtpModal() {
        document.getElementById('otpModal').classList.add('hidden');
    }

    function showNewEmailModal() {
        document.getElementById('newEmailModal').classList.remove('hidden');
    }

    function closeNewEmailModal() {
        document.getElementById('newEmailModal').classList.add('hidden');
    }

    function showPasswordModal() {
        document.getElementById('passwordModal').classList.remove('hidden');
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').classList.add('hidden');
    }

    function showErrorModal(message) {
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('errorModal').classList.remove('hidden');
    }

    function closeErrorModal() {
        document.getElementById('errorModal').classList.add('hidden');
    }

    function showSuccessModal() {
        document.getElementById('successModal').classList.remove('hidden');
    }

    function closeSuccessModal() {
        document.getElementById('successModal').classList.add('hidden');
    }

    document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
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

    <?php if (isset($otpVerified) && $otpVerified): ?>
        closeOtpModal();
        showSuccessModal();
    <?php endif; ?>

    <?php if (isset($updateError)): ?>
        showErrorModal('<?php echo $updateError; ?>');
    <?php elseif (isset($updateSuccess)): ?>
        showSuccessModal();
    <?php endif; ?>

    <?php if (isset($passwordUpdateError)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showPasswordModal();
        });
    <?php endif; ?>
</script>
<?php
session_start();
include '../connection/config.php'; // Pastikan path ini benar

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

// Proses pembaruan kata sandi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['currentPassword'], $_POST['newPassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    // Verifikasi kata sandi saat ini
    $stmt = $conn->prepare("SELECT password FROM user WHERE uid = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($currentPassword, $hashedPassword)) {
        // Update kata sandi baru
        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE uid = ?");
        $stmt->bind_param("ss", $newHashedPassword, $user_id);
        if ($stmt->execute()) {
            $updateSuccess = true;
        } else {
            $updateError = "Terjadi kesalahan saat memperbarui kata sandi.";
        }
        $stmt->close();
    } else {
        $updateError = "Kata sandi saat ini salah.";
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
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('email')"></i>
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

<!-- Modal untuk Ubah Kata Sandi -->
<div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Ubah Kata Sandi</h2>
        <form id="passwordForm" method="POST">
            <div class="mb-4">
                <label for="currentPassword" class="block text-gray-700">Kata Sandi Terakhir</label>
                <input type="password" name="currentPassword" id="currentPassword" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="newPassword" class="block text-gray-700">Kata Sandi Baru</label>
                <input type="password" name="newPassword" id="newPassword" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="confirmPassword" class="block text-gray-700">Konfirmasi Kata Sandi Baru</label>
                <input type="password" id="confirmPassword" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closePasswordModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal untuk Pesan Kesalahan -->
<div id="errorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md text-center">
        <h2 class="text-xl font-bold mb-4 text-red-500">Kesalahan</h2>
        <p id="errorMessage" class="text-gray-700"></p>
        <button onclick="closeErrorModal()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Tutup</button>
    </div>
</div>

<!-- Modal untuk Pesan Sukses -->
<div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md text-center">
        <h2 class="text-xl font-bold mb-4 text-green-500">Sukses</h2>
        <p class="text-gray-700">Kata sandi berhasil diperbarui.</p>
        <button onclick="closeSuccessModal()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Tutup</button>
    </div>
</div>

<script>
    let currentEdit = null;

    function toggleEdit(field) {
        const infoElement = document.getElementById(`${field}Info`);

        if (currentEdit && currentEdit !== field) {
            cancelEdit(currentEdit);
        }

        if (currentEdit === field) {
            saveEdit(field);
        } else {
            infoElement.contentEditable = true;
            infoElement.classList.add('focus:border-b-2', 'focus:border-blue-500');
            infoElement.textContent = '';
            infoElement.focus();
            currentEdit = field;
        }
    }

    function saveEdit(field) {
        const textElement = document.getElementById(`${field}Text`);
        const infoElement = document.getElementById(`${field}Info`);

        if (infoElement.textContent.trim() !== '') {
            textElement.textContent = infoElement.textContent;
            updateDatabase(field, infoElement.textContent);
        }
        infoElement.contentEditable = false;
        infoElement.classList.remove('focus:border-b-2', 'focus:border-blue-500');
        currentEdit = null;
    }

    function cancelEdit(field) {
        const infoElement = document.getElementById(`${field}Info`);
        infoElement.contentEditable = false;
        infoElement.classList.remove('focus:border-b-2', 'focus:border-blue-500');
        currentEdit = null;
    }

    function updateDatabase(field, value) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(`field=${field}&value=${encodeURIComponent(value)}`);
    }

    document.addEventListener('click', function(event) {
        if (currentEdit) {
            const infoElement = document.getElementById(`${currentEdit}Info`);
            const pencilIcon = document.querySelector(`.fas.fa-pen[onclick="toggleEdit('${currentEdit}')"]`);
            if (!infoElement.contains(event.target) && !pencilIcon.contains(event.target)) {
                cancelEdit(currentEdit);
            }
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && currentEdit) {
            event.preventDefault();
            saveEdit(currentEdit);
        } else if (event.key === 'Escape' && currentEdit) {
            cancelEdit(currentEdit);
        }
    });

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

    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (newPassword !== confirmPassword) {
            event.preventDefault();
            showErrorModal('Kata sandi baru dan konfirmasi tidak cocok.');
        }
    });

    <?php if (isset($updateError)): ?>
        showErrorModal('<?php echo $updateError; ?>');
    <?php elseif (isset($updateSuccess)): ?>
        showSuccessModal();
    <?php endif; ?>
</script>
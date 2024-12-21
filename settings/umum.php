<?php
session_start();
include '../connection/config.php'; // Pastikan path ini benar

// Inisialisasi variabel
$profile_pic = $nama_lengkap = $nama_pengguna = $kredensial = '';
$role = 'Pembaca'; // Inisialisasi role dengan nilai default

$profile_pic = $_SESSION['profile_pic'] ?? ''; // Ambil foto profil dari session

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'] ?? null;
if ($user_id) {
    $stmt = $conn->prepare("SELECT profile_pic, nama_lengkap, nama_pengguna, role, kredensial FROM user WHERE uid = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($profile_pic, $nama_lengkap, $nama_pengguna, $role, $kredensial);
    $stmt->fetch();
    $stmt->close();
}

// Update atau insert data pengguna
// Tambahkan di bagian atas file, sebelum logika utama
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // Ini adalah request AJAX
    $isAjaxRequest = true;
} else {
    $isAjaxRequest = false;
}

// Modifikasi logika update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES['profile_pic'])) {
    // Pastikan output bersih
    ob_clean();

    // Aktifkan error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 0); // Matikan display error di production

    // Fungsi untuk membuat response JSON yang aman
    function sendJsonResponse($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    // Sanitasi input
    $field = isset($_POST['field']) ? trim($_POST['field']) : null;
    $value = isset($_POST['value']) ? trim($_POST['value']) : null;

    // Pemetaan field yang diperbolehkan
    $allowed_fields = [
        'nama_lengkap' => 'nama_lengkap',
        'username' => 'nama_pengguna',
        'infoLainnya' => 'kredensial'
    ];

    // Validasi field
    $db_field = $allowed_fields[$field] ?? null;

    // Respon untuk field tidak valid
    if (!$db_field) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Field tidak valid',
            'debug' => [
                'received_field' => $field,
                'allowed_fields' => array_keys($allowed_fields)
            ]
        ]);
    }

    // Validasi user_id
    if (!$user_id) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Sesi pengguna tidak valid'
        ]);
    }

    // Validasi value
    if (empty($value)) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Nilai tidak boleh kosong'
        ]);
    }

    try {
        // Persiapan statement
        $stmt = $conn->prepare("UPDATE user SET $db_field = ? WHERE uid = ?");

        if (!$stmt) {
            throw new Exception("Gagal mempersiapkan query: " . $conn->error);
        }

        $stmt->bind_param("ss", $value, $user_id);

        // Eksekusi update
        if (!$stmt->execute()) {
            throw new Exception("Gagal mengeksekusi query: " . $stmt->error);
        }

        // Ambil data terbaru
        $stmt_fetch = $conn->prepare("SELECT $db_field FROM user WHERE uid = ?");
        $stmt_fetch->bind_param("s", $user_id);
        $stmt_fetch->execute();
        $stmt_fetch->bind_result($updated_value);
        $stmt_fetch->fetch();
        $stmt_fetch->close();

        // Kirim respon sukses
        sendJsonResponse([
            'success' => true,
            'field' => $field,
            'value' => $updated_value
        ]);
    } catch (Exception $e) {
        // Tangani error
        sendJsonResponse([
            'success' => false,
            'error' => $e->getMessage(),
            'debug' => [
                'field' => $field,
                'db_field' => $db_field,
                'value' => $value
            ]
        ]);
    }
}

// Update atau insert foto profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $fileData = file_get_contents($_FILES['profile_pic']['tmp_name']);

    $stmt = $conn->prepare("UPDATE user SET profile_pic = ? WHERE uid = ?");
    $stmt->bind_param("bs", $fileData, $user_id);
    $stmt->send_long_data(0, $fileData);
    if ($stmt->execute()) {
        $_SESSION['profile_pic'] = $fileData;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
    exit();
}

// Hapus foto profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove_profile_pic') {
    $stmt = $conn->prepare("UPDATE user SET profile_pic = NULL WHERE uid = ?");
    $stmt->bind_param("s", $user_id);
    if ($stmt->execute()) {
        $_SESSION['profile_pic'] = null;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
    exit();
}
?>
<?php include '../header & footer/header_setting.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="container mx-auto mt-8 flex space-x-8 lg:px-8">
    <!-- Pembungkus Edit Profile -->
    <div class="w-1/3 lg:w-1/4">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <div class="flex flex-col items-center">
                <h3 class="text-lg font-semibold mb-2">Edit Profile</h3>
                <div class="relative">
                    <form id="uploadForm" action="" method="POST" enctype="multipart/form-data">
                        <input type="file" name="profile_pic" id="profilePicInput" class="hidden" accept="image/*" onchange="previewImage(event)">
                        <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center mb-2">
                            <?php if ($profile_pic): ?>
                                <img id="profilePicPreview" src="data:image/jpeg;base64,<?= base64_encode($profile_pic) ?>" alt="Profile Picture" class="w-full h-full rounded-full object-cover">
                            <?php endif; ?>
                            <div class="absolute bottom-1 right-1 bg-blue-500 text-white px-3 py-2 rounded-full cursor-pointer" onclick="toggleMenu(event)">
                                <i class="fas fa-camera text-white text-lg"></i>
                            </div>
                            <div id="cameraMenu" class="hidden absolute bottom-0 right-0 transform translate-y-full translate-x-24 bg-white text-black rounded-lg shadow-md">
                                <ul>
                                    <li class="px-4 py-2 cursor-pointer hover:bg-gray-200" onclick="document.getElementById('profilePicInput').click()">Ganti Foto Profil</li>
                                    <li class="px-4 py-2 cursor-pointer hover:bg-gray-200 <?php echo empty($profile_pic) ? 'text-gray-400 cursor-not-allowed' : ''; ?>" onclick="showRemoveConfirmation()" <?php echo empty($profile_pic) ? 'style="pointer-events: none;"' : ''; ?>>Hapus Foto Profil</li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
                <p class="text-center text-gray-500 mt-2">Foto ini akan muncul dalam profil anda, ayo pasang profile terbaikmu!</p>
                <button type="button" class="bg-blue-500 text-white w-full py-1 rounded-lg mt-2" onclick="confirmChange()">Ganti Foto</button>
            </div>
        </div>
    </div>

    <!-- Pembungkus Informasi Akun -->
    <div class="w-2/3 lg:w-3/4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-[20px] font-regular mb-4 ml-10">Informasi Akun Anda</h2>
            <div class="space-y-4 max-w-5xl mx-auto">
                <!-- Nama Lengkap -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-user text-gray-500 text-xl"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Nama Lengkap</p>
                            <p class="font-semibold" id="namaLengkapText" contenteditable="false"><?php echo htmlspecialchars($nama_lengkap); ?></p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="namaLengkapInfo" data-default-text="Informasi ini harus akurat">Informasi ini harus akurat</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 text-xl cursor-pointer ml-6" onclick="toggleEdit('namaLengkap')"></i>
                </div>
                <!-- Username -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-user-tag text-gray-500 text-xl"></i>
                    <div class="flex-1 ml-8">
                        <div>
                            <p class="text-gray-600">Username</p>
                            <p class="font-semibold" id="usernameText" contenteditable="false"><?php echo htmlspecialchars($nama_pengguna); ?></p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="usernameInfo" data-default-text="Nama ini akan terlihat pembaca dan tertera sebagai editor">Nama ini akan terlihat pembaca dan tertera sebagai editor</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 text-xl cursor-pointer ml-6" onclick="toggleEdit('username')"></i>
                </div>
                <!-- Posisi -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-briefcase text-gray-500 text-xl"></i>
                    <div class="flex-1 ml-9">
                        <div>
                            <p class="text-gray-600">Posisi</p>
                            <p class="font-semibold" id="posisiText"><?php echo htmlspecialchars($role ?? 'pembaca'); ?></p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="posisiInfo" contenteditable="false" data-default-text="Informasi ini tidak dapat diubah oleh anda">Informasi ini tidak dapat diubah oleh anda</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-gray-400 text-xl cursor-not-allowed ml-6"></i>
                </div>
                <!-- Kredensial -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-info-circle text-gray-500 text-xl"></i>
                    <div class="flex-1 ml-9">
                        <div>
                            <p class="text-gray-600">Kredensial</p>
                            <p class="font-semibold" id="infoLainnyaText" contenteditable="false">
                                <?php echo htmlspecialchars($kredensial ?: 'isi kredensial dibawah'); ?>
                            </p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="infoLainnyaInfo" data-default-text="Nama ini akan terlihat pembaca dan tertera sebagai editor">Nama ini akan terlihat pembaca dan tertera sebagai editor</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 text-xl cursor-pointer ml-6" onclick="toggleEdit('infoLainnya')"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="confirmationPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-4 rounded-lg shadow-md">
        <p>Apakah Anda yakin ingin mengganti foto profil?</p>
        <div class="flex justify-end space-x-2 mt-4">
            <button class="bg-red-500 text-white px-4 py-2 rounded" onclick="closePopup()">Batal</button>
            <button class="bg-green-500 text-white px-4 py-2 rounded" onclick="submitForm()">Ya</button>
        </div>
    </div>
</div>

<div id="removeConfirmationPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-4 rounded-lg shadow-md">
        <p>Apakah Anda yakin ingin menghapus foto profil?</p>
        <div class="flex justify-end space-x-2 mt-4">
            <button class="bg-red-500 text-white px-4 py-2 rounded" onclick="closeRemovePopup()">Batal</button>
            <button class="bg-green-500 text-white px-4 py-2 rounded" onclick="confirmRemoveProfilePicture()">Ya</button>
        </div>
    </div>
</div>

<div id="noChangePopup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-4 rounded-lg shadow-md">
        <p>Tidak ada perubahan pada foto profil.</p>
        <div class="flex justify-end mt-4">
            <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="closeNoChangePopup()">OK</button>
        </div>
    </div>
</div>

<script>
    let currentEdit = null;

    const fieldLabels = {
        namaLengkap: "Nama Lengkap",
        posisi: "Posisi",
        username: "Username",
        infoLainnya: "Kredensial"
    };

    function toggleEdit(field) {
        const textElement = document.getElementById(`${field}Text`);
        textElement.dataset.originalText = textElement.textContent;
        textElement.contentEditable = true;
        textElement.focus();
        currentEdit = field;
    }

    function saveEdit(field) {
        const textElement = document.getElementById(`${field}Text`);
        if (textElement.textContent.trim() !== '') {
            showConfirmationPopup(field, textElement.textContent);
        }
    }

    function cancelEdit(field) {
        const textElement = document.getElementById(`${field}Text`);
        textElement.textContent = textElement.dataset.originalText;
        textElement.contentEditable = false;
        currentEdit = null;
    }

    function showConfirmationPopup(field, value) {
        const confirmationPopup = document.getElementById('confirmationPopup');
        const fieldLabel = fieldLabels[field] || field;
        confirmationPopup.querySelector('p').textContent = `Apakah Anda yakin ingin mengganti ${fieldLabel}?`;
        confirmationPopup.classList.remove('hidden');
        confirmationPopup.querySelector('button[onclick="submitForm()"]').onclick = function() {
            updateDatabase(field, value);
            closePopup();
        };
    }

    function updateDatabase(field, value) {
        // Pemetaan field yang benar
        const fieldMap = {
            'namaLengkap': 'nama_lengkap',
            'username': 'username',
            'infoLainnya': 'infoLainnya'
        };

        const mappedField = fieldMap[field] || field;

        const formData = new FormData();
        formData.append('field', mappedField);
        formData.append('value', value);

        fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                // Tambahkan pengecekan response
                if (!response.ok) {
                    // Coba ambil teks error jika ada
                    return response.text().then(text => {
                        console.error('Response not OK:', text);
                        throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                // Log data response untuk debugging
                console.log('Response data:', data);

                if (data.success) {
                    // Update elemen di halaman
                    const textElement = document.getElementById(`${field}Text`);
                    textElement.textContent = data.value;
                    textElement.contentEditable = false;
                    currentEdit = null;

                    showNotification('Data berhasil diperbarui');
                } else {
                    // Tampilkan pesan error dengan detail
                    console.error('Update error:', data);
                    showNotification(data.error || 'Gagal memperbarui data', 'error');
                }
            })
            .catch(error => {
                // Tangani error jaringan atau parsing
                console.error('Fetch error:', error);
                showNotification('Terjadi kesalahan: ' + error.message, 'error');
            });
    }

    function showNotification(message, type = 'success') {
        // Buat elemen notifikasi
        const notification = document.createElement('div');
        notification.classList.add(
            'fixed', 'top-4', 'right-4', 'z-50', 'px-4', 'py-2', 'rounded',
            type === 'success' ? 'bg-green-500' : 'bg-red-500',
            'text-white'
        );
        notification.textContent = message;

        // Tambahkan ke body
        document.body.appendChild(notification);

        // Hapus notifikasi setelah beberapa detik
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && currentEdit) {
            event.preventDefault();
            saveEdit(currentEdit);
        } else if (event.key === 'Escape' && currentEdit) {
            cancelEdit(currentEdit);
        }
    });

    document.addEventListener('click', function(event) {
        if (currentEdit) {
            const textElement = document.getElementById(`${currentEdit}Text`);
            const pencilIcon = document.querySelector(`.fas.fa-pen[onclick="toggleEdit('${currentEdit}')"]`);
            if (!textElement.contains(event.target) && !pencilIcon.contains(event.target)) {
                cancelEdit(currentEdit);
            }
        }
    });

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profilePicPreview');
            if (output && output.src === reader.result) {
                showNoChangePopup();
            } else {
                if (output) {
                    output.src = reader.result;
                    output.classList.remove('hidden');
                } else {
                    const img = document.createElement('img');
                    img.id = 'profilePicPreview';
                    img.src = reader.result;
                    img.className = 'fa-solid fa-user';
                    document.querySelector('.w-40.h-40').appendChild(img);
                }
            }
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function showNoChangePopup() {
        document.getElementById('noChangePopup').classList.remove('hidden');
    }

    function closeNoChangePopup() {
        document.getElementById('noChangePopup').classList.add('hidden');
    }

    function confirmChange() {
        document.getElementById('confirmationPopup').classList.remove('hidden');
    }

    function closePopup() {
        document.getElementById('confirmationPopup').classList.add('hidden');
    }

    function submitForm() {
        const formData = new FormData(document.getElementById('uploadForm'));

        // Tambahkan data field dan value jika ada perubahan
        if (currentEdit) {
            const infoElement = document.getElementById(`${currentEdit}Info`);
            formData.append('field', currentEdit);
            formData.append('value', infoElement.textContent.trim());
        }

        fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.profile_pic) {
                        document.getElementById('profilePicPreview').src = 'data:image/jpeg;base64,' + btoa(data.profile_pic);
                        document.getElementById('profilePicPreview').classList.remove('hidden');
                    }
                    window.location.href = 'umum.php';
                }
                closePopup();
            })
            .catch(error => console.error('Error:', error));
    }

    function toggleMenu(event) {
        event.stopPropagation();
        const menu = document.getElementById('cameraMenu');
        menu.classList.toggle('hidden');
    }

    function showRemoveConfirmation() {
        document.getElementById('cameraMenu').classList.add('hidden'); // Sembunyikan menu
        document.getElementById('removeConfirmationPopup').classList.remove('hidden');
    }

    function closeRemovePopup() {
        document.getElementById('removeConfirmationPopup').classList.add('hidden');
    }

    function confirmRemoveProfilePicture() {
        fetch(window.location.href, {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'remove_profile_pic'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const output = document.getElementById('profilePicPreview');
                    if (output) {
                        output.remove(); // Hapus elemen gambar jika ada
                    }
                    closeRemovePopup();
                } else {
                    console.error('Error:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    document.addEventListener('click', function(event) {
        const menu = document.getElementById('cameraMenu');
        if (menu.classList.contains('hidden') && event.target.closest('.fas.fa-camera')) {
            toggleMenu(event);
        }
    });

    // Tambahkan event listener untuk menutup menu ketika mengklik di luar
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('cameraMenu');
        const cameraIcon = document.querySelector('.fas.fa-camera');

        // Jika menu terbuka dan klik terjadi di luar menu dan ikon kamera, tutup menu
        if (!menu.classList.contains('hidden') && !menu.contains(event.target) && !cameraIcon.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
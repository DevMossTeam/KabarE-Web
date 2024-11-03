<?php
session_start();
include '../connection/config.php'; // Pastikan path ini benar

// Inisialisasi variabel
$profile_pic = $nama_lengkap = $nama_pengguna = $role = $kredensial = '';

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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES['profile_pic'])) {
    $field = $_POST['field'] ?? null;
    $value = $_POST['value'] ?? null;

    if ($user_id && $field && $value) {
        $allowed_fields = ['nama_lengkap', 'nama_pengguna', 'kredensial'];
        if (in_array($field, $allowed_fields)) {
            $stmt = $conn->prepare("UPDATE user SET $field = ? WHERE uid = ?");
            $stmt->bind_param("ss", $value, $user_id);

            if ($stmt->execute()) {
                $_SESSION[$field] = $value; // Simpan ke sesi
                echo json_encode(['success' => true, 'field' => $field, 'value' => $value]); // Kirim data yang diperbarui
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }
            $stmt->close();
            exit;
        }
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
?>
<?php include '../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex space-x-8">
    <!-- Pembungkus Edit Profile -->
    <div class="w-1/3 lg:w-1/4">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <div class="flex flex-col items-center">
                <h3 class="text-lg font-semibold mb-2">Edit Profile</h3>
                <div class="relative">
                    <form id="uploadForm" action="" method="POST" enctype="multipart/form-data">
                        <input type="file" name="profile_pic" id="profilePicInput" class="hidden" accept="image/*" onchange="previewImage(event)">
                        <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center mb-2">
                            <img id="profilePicPreview" src="data:image/jpeg;base64,<?= base64_encode($profile_pic) ?>" alt="Profile Picture" class="w-full h-full rounded-full object-cover <?php echo empty($profile_pic) ? 'hidden' : ''; ?>">
                            <i id="defaultIcon" class="fas fa-user text-8xl text-gray-500 <?php echo !empty($profile_pic) ? 'hidden' : ''; ?>"></i>
                            <div class="absolute bottom-1 right-1 bg-blue-500 text-white px-3 py-2 rounded-full cursor-pointer" onclick="document.getElementById('profilePicInput').click()">
                                <i class="fas fa-camera text-white text-lg"></i>
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
                    <i class="fas fa-user text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Nama Lengkap</p>
                            <p class="font-semibold" id="namaLengkapText"><?php echo htmlspecialchars($nama_lengkap); ?></p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="namaLengkapInfo" contenteditable="false" data-default-text="Informasi ini harus akurat">Informasi ini harus akurat</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('namaLengkap')"></i>
                </div>
                <!-- Username -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-user-tag text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Username</p>
                            <p class="font-semibold" id="usernameText"><?php echo htmlspecialchars($nama_pengguna); ?></p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="usernameInfo" contenteditable="false" data-default-text="Nama ini akan terlihat pembaca dan tertera sebagai editor">Nama ini akan terlihat pembaca dan tertera sebagai editor</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('username')"></i>
                </div>
                <!-- Posisi -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-briefcase text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Posisi</p>
                            <p class="font-semibold" id="posisiText"><?php echo htmlspecialchars($role); ?></p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="posisiInfo" contenteditable="false" data-default-text="Informasi ini tidak dapat diubah oleh anda">Informasi ini tidak dapat diubah oleh anda</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('posisi')"></i>
                </div>
                <!-- Kredensial -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-info-circle text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Kredensial</p>
                            <p class="font-semibold" id="infoLainnyaText">
                                <?php echo htmlspecialchars($kredensial ?: 'isi kredensial dibawah'); ?>
                            </p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="infoLainnyaInfo" contenteditable="false" data-default-text="Nama ini akan terlihat pembaca dan tertera sebagai editor">Nama ini akan terlihat pembaca dan tertera sebagai editor</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('infoLainnya')"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="confirmationPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-4 rounded-lg shadow-md">
        <p>Apakah Anda yakin ingin mengganti kredensial?</p>
        <div class="flex justify-end space-x-2 mt-4">
            <button class="bg-red-500 text-white px-4 py-2 rounded" onclick="closePopup()">Batal</button>
            <button class="bg-green-500 text-white px-4 py-2 rounded" onclick="submitForm()">Ya</button>
        </div>
    </div>
</div>

<script>
    let currentEdit = null;

    const fieldLabels = {
        namaLengkap: "Nama Lengkap",
        username: "Username",
        infoLainnya: "Kredensial"
    };

    function toggleEdit(field) {
        const infoElement = document.getElementById(`${field}Info`);
        infoElement.dataset.originalText = infoElement.textContent;
        infoElement.textContent = '';
        infoElement.contentEditable = true;
        infoElement.focus();
        currentEdit = field;
    }

    function saveEdit(field) {
        const infoElement = document.getElementById(`${field}Info`);
        if (infoElement.textContent.trim() !== '') {
            showConfirmationPopup(field, infoElement.textContent);
        }
    }

    function cancelEdit(field) {
        const infoElement = document.getElementById(`${field}Info`);
        infoElement.textContent = infoElement.dataset.originalText;
        infoElement.contentEditable = false;
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
        const formData = new FormData();
        formData.append('field', field);
        formData.append('value', value);

        console.log('Sending data:', field, value);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            if (data.success) {
                document.getElementById(`${field}Text`).textContent = data.value;
                document.getElementById(`${field}Info`).contentEditable = false;
                currentEdit = null;
            } else {
                console.error('Error:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
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
            const infoElement = document.getElementById(`${currentEdit}Info`);
            const pencilIcon = document.querySelector(`.fas.fa-pen[onclick="toggleEdit('${currentEdit}')"]`);
            if (!infoElement.contains(event.target) && !pencilIcon.contains(event.target)) {
                cancelEdit(currentEdit);
            }
        }
    });

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profilePicPreview');
            const defaultIcon = document.getElementById('defaultIcon');
            output.src = reader.result;
            output.classList.remove('hidden');
            defaultIcon.classList.add('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
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
                    document.getElementById('defaultIcon').classList.add('hidden');
                }
                window.location.href = 'umum.php';
            }
            closePopup();
        })
        .catch(error => console.error('Error:', error));
    }
</script>

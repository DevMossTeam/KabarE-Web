<?php
session_start();
include '../connection/config.php'; // Pastikan path ini benar

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'] ?? null;
if ($user_id) {
    $stmt = $conn->prepare("SELECT profile_pic, nama_lengkap, username, kredensial FROM user WHERE uid = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($profile_pic, $nama_lengkap, $username, $kredensial);
    $stmt->fetch();
    $_SESSION['profile_pic'] = $profile_pic;
    $stmt->close();
}

// Update data pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES['profile_pic'])) {
    $field = $_POST['field'] ?? null;
    $value = $_POST['value'] ?? null;

    if ($user_id && $field && $value) {
        $allowed_fields = ['nama_lengkap', 'username', 'kredensial'];
        if (in_array($field, $allowed_fields)) {
            if ($field === 'kredensial') {
                $stmt = $conn->prepare("SELECT kredensial FROM user WHERE uid = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->bind_result($existing_kredensial);
                $stmt->fetch();
                $stmt->close();

                if (empty($existing_kredensial)) {
                    // Jika kredensial kosong, lakukan INSERT
                    $stmt = $conn->prepare("UPDATE user SET kredensial = ? WHERE uid = ?");
                } else {
                    // Jika kredensial sudah ada, lakukan UPDATE
                    $stmt = $conn->prepare("UPDATE user SET kredensial = ? WHERE uid = ?");
                }
            } else {
                $stmt = $conn->prepare("UPDATE user SET $field = ? WHERE uid = ?");
            }
            $stmt->bind_param("si", $value, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
    // Refresh halaman setelah update
    header("Location: umum.php");
    exit;
}

// Update atau insert foto profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $profile_pic = $_FILES['profile_pic']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($profile_pic);

    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("SELECT profile_pic FROM user WHERE uid = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($existing_profile_pic);
        $stmt->fetch();
        $stmt->close();

        if (empty($existing_profile_pic)) {
            // Jika profile_pic kosong, lakukan INSERT
            $stmt = $conn->prepare("UPDATE user SET profile_pic = ? WHERE uid = ?");
            $stmt->bind_param("si", $profile_pic, $user_id);
        } else {
            // Jika profile_pic sudah ada, lakukan UPDATE
            $stmt = $conn->prepare("UPDATE user SET profile_pic = ? WHERE uid = ?");
            $stmt->bind_param("si", $profile_pic, $user_id);
        }

        $stmt->execute();
        $stmt->close();

        // Perbarui sesi dengan gambar baru
        $_SESSION['profile_pic'] = $profile_pic;

        // Kirim respons JSON
        echo json_encode(['success' => true, 'profile_pic' => $profile_pic]);
        exit();
    }
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
                            <img id="profilePicPreview" src="<?php echo 'uploads/' . htmlspecialchars($_SESSION['profile_pic'] ?? 'default-profile.png'); ?>" alt="Profile Picture" class="w-full h-full rounded-full object-cover <?php echo empty($_SESSION['profile_pic']) ? 'hidden' : ''; ?>">
                            <i id="defaultIcon" class="fas fa-user text-8xl text-gray-500 <?php echo !empty($_SESSION['profile_pic']) ? 'hidden' : ''; ?>"></i>
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
                            <p class="font-semibold" id="usernameText"><?php echo htmlspecialchars($username); ?></p>
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
                            <p class="font-semibold" id="posisiText">Jurnalis / Penulis</p>
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
        <p>Apakah Anda yakin ingin mengganti foto profil?</p>
        <div class="flex justify-end space-x-2 mt-4">
            <button class="bg-red-500 text-white px-4 py-2 rounded" onclick="closePopup()">Batal</button>
            <button class="bg-green-500 text-white px-4 py-2 rounded" onclick="submitForm()">Ya</button>
        </div>
    </div>
</div>

<script>
    let currentEdit = null;

    function toggleEdit(field) {
        const infoElement = document.getElementById(`${field}Info`);
        infoElement.dataset.originalText = infoElement.textContent; // Simpan teks asli
        infoElement.textContent = ''; // Kosongkan teks saat mulai mengedit
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

    function showConfirmationPopup(field, value) {
        const confirmationPopup = document.getElementById('confirmationPopup');
        confirmationPopup.querySelector('p').textContent = `Apakah Anda yakin ingin mengganti ${field}?`;
        confirmationPopup.classList.remove('hidden');
        confirmationPopup.querySelector('button[onclick="submitForm()"]').onclick = function() {
            updateDatabase(field, value);
            closePopup();
        };
    }

    function updateDatabase(field, value) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Perbarui teks di elemen yang sesuai
                document.getElementById(`${field}Text`).textContent = value;
                // Refresh halaman setelah update
                window.location.reload();
            }
        };
        xhr.send(`field=${field}&value=${encodeURIComponent(value)}`);
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
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Perbarui gambar profil di tampilan
                document.getElementById('profilePicPreview').src = 'uploads/' + data.profile_pic;
                document.getElementById('profilePicPreview').classList.remove('hidden');
                document.getElementById('defaultIcon').classList.add('hidden');
                // Redirect ke umum.php setelah berhasil
                window.location.href = 'umum.php';
            }
            closePopup();
        })
        .catch(error => console.error('Error:', error));
    }
</script>
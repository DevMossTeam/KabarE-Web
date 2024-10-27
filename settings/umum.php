<?php
session_start();
include '../connection/config.php'; // Pastikan path ini benar

// Ambil data gambar dari database
$user_id = $_SESSION['user_id'] ?? null;
if ($user_id) {
    $stmt = $conn->prepare("SELECT profile_pic FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($profile_pic);
    $stmt->fetch();
    $_SESSION['profile_pic'] = $profile_pic;
    $stmt->close();
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
                    <form id="uploadForm" action="upload_profile_pic.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="profile_pic" id="profilePicInput" class="hidden" accept="image/*" onchange="previewImage(event)">
                        <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center mb-2">
                            <img id="profilePicPreview" src="" alt="Profile Picture" class="w-full h-full rounded-full object-cover hidden">
                            <i id="defaultIcon" class="fas fa-user text-8xl text-gray-500"></i>
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
                            <p class="font-semibold" id="namaLengkapText">Chiquita Clairina K</p>
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
                            <p class="font-semibold" id="usernameText">Chiquita</p>
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
                <!-- Info Lainnya -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-info-circle text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Info Lainnya</p>
                            <p class="font-semibold" id="infoLainnyaText">Mahasiswa Jurusan Teknologi Informasi</p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="infoLainnyaInfo" contenteditable="false" data-default-text="Nama ini akan terlihat pembaca dan tertera sebagai editor">Nama ini akan terlihat pembaca dan tertera sebagai editor</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('infoLainnya')"></i>
                </div>
                <!-- Bio Anda -->
                <div class="flex items-center pb-2">
                    <i class="fas fa-align-left text-gray-500"></i>
                    <div class="flex-1 ml-10">
                        <div>
                            <p class="text-gray-600">Bio Anda</p>
                            <p class="font-semibold" id="bioText">Platform berita kampus yang menyajikan informasi terkini, akurat, dan terpercaya. Selalu di depan dalam mengabarkan suara mahasiswa</p>
                            <p class="text-gray-500 border-b border-gray-300 focus:border-b-2 focus:border-blue-500 outline-none" id="bioInfo" contenteditable="false" data-default-text="Informasi ini akan terlihat pembaca">Informasi ini akan terlihat pembaca</p>
                        </div>
                    </div>
                    <i class="fas fa-pen text-blue-500 cursor-pointer ml-6" onclick="toggleEdit('bio')"></i>
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
        }
        infoElement.contentEditable = false;
        infoElement.classList.remove('focus:border-b-2', 'focus:border-blue-500');
        infoElement.textContent = infoElement.getAttribute('data-default-text');
        currentEdit = null;
    }

    function cancelEdit(field) {
        const infoElement = document.getElementById(`${field}Info`);
        infoElement.contentEditable = false;
        infoElement.classList.remove('focus:border-b-2', 'focus:border-blue-500');
        infoElement.textContent = infoElement.getAttribute('data-default-text');
        currentEdit = null;
    }

    document.addEventListener('click', function(event) {
        if (currentEdit && !event.target.closest('.flex')) {
            cancelEdit(currentEdit);
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && currentEdit) {
            event.preventDefault();
            saveEdit(currentEdit);
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
        document.getElementById('uploadForm').submit();
    }
</script>

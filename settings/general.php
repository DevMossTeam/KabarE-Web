<?php
session_start();
include '../connection/config.php';
include '../header & footer/header_setting.php';

$user_id = $_SESSION['user_id'] ?? null;

// Proses pembaruan data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $uid = $data['uid'] ?? null;
    $field = $data['field'] ?? null;
    $value = $data['value'] ?? null;

    if (!empty($uid) && !empty($field) && !empty($value)) {
        $stmt = $conn->prepare("UPDATE user SET $field = ? WHERE uid = ?");
        if ($stmt !== false) {
            $stmt->bind_param("ss", $value, $uid);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Ambil data terbaru setelah pembaruan
    $stmt = $conn->prepare("SELECT $field FROM user WHERE uid = ?");
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo json_encode($row);
    exit();
}

$sql = "SELECT nama_lengkap, nama_pengguna, role, IFNULL(kredensial, 'kredensial anda') AS kredensial FROM user WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="max-w-lg w-full bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="p-6">
        <div class="flex items-center mb-4">
            <img src="../assets/web-icon/Ic-main-KabarE.svg" alt="User Icon" class="mr-2 w-6 h-6">
            <h2 class="text-2xl text-black">Informasi Akun Anda</h2>
        </div>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="bg-white p-4 rounded-lg mb-4">
                    <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center mb-2">
                        <?php if ($profile_pic): ?>
                            <img id="profilePicPreview" src="data:image/jpeg;base64,<?= base64_encode($profile_pic) ?>" alt="Profile Picture" class="w-full h-full rounded-full object-cover">
                        <?php else: ?>
                            <i id="defaultIcon" class="fa-solid fa-user text-gray-500 text-6xl"></i>
                        <?php endif; ?>
                        <div class="absolute bottom-1 right-1 bg-blue-500 text-white px-3 py-2 rounded-full cursor-pointer" onclick="toggleMenu(event)">
                            <i class="fas fa-camera text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <img src="https://img.icons8.com/ios-filled/24/888888/user.png" alt="Nama Lengkap Icon" class="mr-2" width="" height="8">
                        <div class="flex-grow">
                            <p class="text-sm text-gray-700">Nama Lengkap</p>
                            <p class="text-lg font-bold text-gray-800" id="nama_lengkap"><?= htmlentities($row['nama_lengkap'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-xs text-gray-500">Informasi ini harus akurat</p>
                        </div>
                        <button class="edit-button ml-4" data-field="nama_lengkap" data-uid="<?= htmlentities($row['uid'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <img src="https://img.icons8.com/ios-filled/20/1176FA/pencil.png" alt="Edit Icon">
                        </button>
                    </div>
                    <hr class="border-gray-300 mb-2">
                    <div class="flex items-center mb-2">
                        <img src="https://img.icons8.com/ios-filled/24/888888/contacts.png" alt="Username Icon" class="mr-2">
                        <div class="flex-grow">
                            <p class="text-sm text-gray-700">Username</p>
                            <p class="text-lg font-bold text-gray-800" id="nama_pengguna"><?= htmlentities($row['nama_pengguna'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-xs text-gray-500">Nama ini akan terlihat pembaca dan tertera sebagai editor</p>
                        </div>
                        <button class="edit-button ml-4" data-field="nama_pengguna" data-uid="<?= htmlentities($row['uid'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <img src="https://img.icons8.com/ios-filled/20/1176FA/pencil.png" alt="Edit Icon">
                        </button>
                    </div>
                    <hr class="border-gray-300 mb-2">
                    <div class="flex items-center mb-2">
                        <img src="https://img.icons8.com/ios-filled/24/888888/briefcase.png" alt="Posisi Icon" class="mr-2">
                        <div class="flex-grow">
                            <p class="text-sm text-gray-700">Posisi</p>
                            <p class="text-lg font-bold text-gray-800" id="role"><?= htmlentities($row['role'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-xs text-gray-500">Informasi ini tidak dapat di ubah oleh anda</p>
                        </div>
                        <button class="role-button ml-4" data-field="role" data-uid="<?= htmlentities($row['uid'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <img src="https://img.icons8.com/ios-filled/20/1176FA/pencil.png" alt="Edit Icon">
                        </button>
                    </div>
                    <hr class="border-gray-300 mb-2">
                    <div class="flex items-center mb-2">
                        <img src="https://img.icons8.com/ios-filled/24/888888/info.png" alt="Info Lainnya Icon" class="mr-2">
                        <div class="flex-grow">
                            <p class="text-sm text-gray-700">Info Lainnya</p>
                            <p class="text-lg font-bold text-gray-800" id="kredensial"><?= htmlentities($row['kredensial'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-xs text-gray-500">Nama ini akan terlihat pembaca dan tertera sebagai editor</p>
                        </div>
                        <button class="edit-button ml-4" data-field="kredensial" data-uid="<?= htmlentities($row['uid'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <img src="https://img.icons8.com/ios-filled/20/1176FA/pencil.png" alt="Edit Icon">
                        </button>
                    </div>
                    <hr class="border-gray-300">
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-gray-500">Tidak ada data.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div id="confirmationModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <p class="mb-4">Apakah Anda yakin ingin menyimpan perubahan ini?</p>
        <button id="confirmButton" class="bg-indigo-500 text-white p-2 rounded mr-2">Ya</button>
        <button id="cancelButton" class="bg-red-500 text-white p-2 rounded">Tidak</button>
    </div>
</div>

<script>
        let currentInput, currentValue, currentField, currentUid, selectedRole;

        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function() {
                const field = this.getAttribute('data-field');
                const uid = this.getAttribute('data-uid');
                const valueElement = this.previousElementSibling.querySelector('p.text-lg');
                currentValue = valueElement.textContent;

                if (currentInput) {
                    if (currentInput.value === currentValue) {
                        closeInput();
                    } else {
                        document.getElementById('confirmationModal').classList.remove('hidden');
                    }
                    return;
                }

                const input = document.createElement('input');
                input.type = 'text';
                input.value = currentValue;
                input.className = 'w-full p-2 border border-gray-300 rounded mt-1';

                valueElement.replaceWith(input);
                currentInput = input;
                currentField = field;
                currentUid = uid;

                input.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        if (input.value !== currentValue) {
                            document.getElementById('confirmationModal').classList.remove('hidden');
                        } else {
                            closeInput();
                        }
                    }
                });

                document.addEventListener('click', function(event) {
                    if (!input.contains(event.target) && !button.contains(event.target)) {
                        closeInput();
                    }
                }, { once: true });
            });
        });

        document.querySelectorAll('.role-button').forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent closing immediately

                const field = this.getAttribute('data-field');
                const uid = this.getAttribute('data-uid');
                const valueElement = this.previousElementSibling.querySelector('p.text-lg');
                currentValue = valueElement.textContent;

                const roleOptions = document.createElement('div');
                roleOptions.className = 'flex flex-col mt-1 bg-gray-100 p-2 rounded shadow';

                const roles = ['pembaca', 'penulis'];
                roles.forEach(role => {
                    const roleOption = document.createElement('p');
                    roleOption.textContent = role;
                    roleOption.className = 'cursor-pointer p-2 rounded hover:bg-gray-200';

                    if (role === currentValue) {
                        roleOption.classList.add('text-gray-400');
                    } else {
                        roleOption.classList.add('text-black');
                        roleOption.addEventListener('click', function() {
                            selectedRole = role;
                            document.getElementById('confirmationModal').classList.remove('hidden');
                        });
                    }

                    roleOptions.appendChild(roleOption);
                });

                valueElement.replaceWith(roleOptions);
                currentInput = roleOptions;
                currentField = field;
                currentUid = uid;

                document.addEventListener('click', function(event) {
                    if (!roleOptions.contains(event.target) && !button.contains(event.target)) {
                        closeInput();
                    }
                }, { once: true });
            });
        });

        function closeInput() {
            const newText = document.createElement('p');
            newText.className = 'text-lg font-bold text-gray-800';
            newText.textContent = currentValue;
            currentInput.replaceWith(newText);
            currentInput = null;
        }

        document.getElementById('confirmButton').addEventListener('click', function() {
            const newValue = selectedRole || currentInput.value;
            document.getElementById('confirmationModal').classList.add('hidden');

            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    uid: currentUid,
                    field: currentField,
                    value: newValue
                })
            }).then(response => response.json())
              .then(data => {
                  if (data && data[currentField]) {
                      const newText = document.createElement('p');
                      newText.className = 'text-lg font-bold text-gray-800';
                      newText.textContent = data[currentField];
                      currentInput.replaceWith(newText);
                      currentInput = null;
                  } else {
                      alert('Gagal memperbarui data.');
                  }
              });
        });

        document.getElementById('cancelButton').addEventListener('click', function() {
            document.getElementById('confirmationModal').classList.add('hidden');
            closeInput();
        });
    </script>
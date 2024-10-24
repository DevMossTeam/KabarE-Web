<?php
include '../header & footer/header_AuthRev.php';

// Ambil data dari POST
$title = $_POST['title'] ?? 'Judul tidak tersedia';

// Proses file cover
$coverPath = '';
if (isset($_FILES['cover']) && $_FILES['cover']['error'] == UPLOAD_ERR_OK) {
    $coverPath = 'uploads/' . basename($_FILES['cover']['name']);
    if (!is_dir('uploads/')) {
        mkdir('uploads/', 0777, true);
    }
    if (move_uploaded_file($_FILES['cover']['tmp_name'], $coverPath)) {
        $coverPath = htmlspecialchars($coverPath);
    } else {
        $coverPath = '';
    }
}
?>

<div class="container mx-auto p-4">
    <div class="bg-white shadow-md rounded-lg p-6 flex">
        <div class="w-2/3">
            <h1 class="text-xl font-bold mb-4">Konfirmasi Publikasi</h1>
            <?php if ($coverPath): ?>
                <img src="<?php echo $coverPath; ?>" alt="Cover Image" class="w-full h-auto mb-4">
            <?php endif; ?>
            <h2 class="text-lg font-bold mb-2"><?php echo htmlspecialchars($title); ?></h2>
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="publishArticle()">Ajukan Publikasi</button>
        </div>
        <div class="w-1/3 pl-4">
            <div class="mb-4">
                <h2 class="font-semibold">Visibilitas</h2>
                <p class="text-sm text-gray-600">Atur visibilitas artikel agar dapat dilihat oleh kelompok yang diinginkan.</p>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="visibility" value="public" class="form-radio" checked>
                        <span class="ml-2">Public</span>
                    </label>
                    <label class="inline-flex items-center ml-4">
                        <input type="radio" name="visibility" value="private" class="form-radio">
                        <span class="ml-2">Private</span>
                    </label>
                </div>
            </div>
            <div class="mb-4">
                <h2 class="font-semibold">Tambahkan Tag</h2>
                <p class="text-sm text-gray-600">Tambahkan tag untuk membantu pembaca menemukan berita artikel.</p>
                <div id="tagContainer" class="flex flex-wrap mb-2"></div>
                <input type="text" id="tagInput" placeholder="Tambah tag" class="w-full border-b-2 border-gray-300 focus:outline-none">
            </div>
            <div>
                <h2 class="font-semibold">Kategori</h2>
                <p class="text-sm text-gray-600">Menambah kategori untuk mempermudah pencarian artikel.</p>
                <select class="w-full border-b-2 border-gray-300 focus:outline-none mt-2">
                    <option>Politik</option>
                    <option>Ekonomi</option>
                    <option>Teknologi</option>
                    <option>Olahraga</option>
                    <option>Hiburan</option>
                    <option>Kesehatan</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div id="popup" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg transform transition-transform scale-0">
        <div class="flex items-center mb-4">
            <i class="fas fa-check-circle text-green-500 text-2xl mr-2"></i>
            <h2 class="text-xl font-bold" id="popupMessage">Artikel Berhasil dipublikasi!</h2>
        </div>
        <button id="closePopup" class="bg-blue-500 text-white px-4 py-2 rounded">Tutup</button>
    </div>
</div>

<script>
    function publishArticle() {
        // Simulate a successful publication
        showPopup('Artikel Berhasil dipublikasi!', true);
        setTimeout(() => {
            window.location.href = '../index.php'; // Corrected path to index.php
        }, 2000); // Redirect after 2 seconds
    }

    function showPopup(message, success = true) {
        const icon = document.querySelector('#popup i');
        const messageElement = document.getElementById('popupMessage');
        messageElement.textContent = message;
        if (success) {
            icon.classList.replace('fa-times-circle', 'fa-check-circle');
            icon.classList.replace('text-red-500', 'text-green-500');
        } else {
            icon.classList.replace('fa-check-circle', 'fa-times-circle');
            icon.classList.replace('text-green-500', 'text-red-500');
        }
        document.getElementById('popup').classList.remove('hidden');
        document.querySelector('#popup div').classList.add('scale-100');
    }

    const closePopup = document.getElementById('closePopup');
    closePopup.addEventListener('click', () => {
        document.getElementById('popup').classList.add('hidden');
        document.querySelector('#popup div').classList.remove('scale-100');
    });

    const tagInput = document.getElementById('tagInput');
    const tagContainer = document.getElementById('tagContainer');
    let lastValue = '';

    tagInput.addEventListener('keyup', (e) => {
        if (lastValue.includes(',') && tagInput.value.trim() !== lastValue) {
            const tagText = lastValue.split(',')[0].trim();
            if (tagText) {
                const tag = document.createElement('span');
                tag.className = 'bg-gray-200 text-gray-800 rounded-full px-2 py-1 mr-2 mb-2 text-xs font-medium flex items-center';
                tag.style.fontFamily = 'Inter, sans-serif';
                tag.textContent = tagText;

                const removeIcon = document.createElement('span');
                removeIcon.className = 'ml-1 text-gray-500 cursor-pointer hidden';
                removeIcon.textContent = 'x';
                removeIcon.style.fontSize = '10px';
                removeIcon.onclick = () => tag.remove();

                tag.appendChild(removeIcon);
                tag.onmouseenter = () => removeIcon.classList.remove('hidden');
                tag.onmouseleave = () => removeIcon.classList.add('hidden');

                tagContainer.appendChild(tag);
                tagInput.value = tagInput.value.replace(lastValue.split(',')[0] + ',', '').trim();
            }
        }
        lastValue = tagInput.value;
    });
</script>

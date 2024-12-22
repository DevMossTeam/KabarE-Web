<?php
session_start(); // Mulai sesi
include '../connection/config.php'; // Pastikan path ini sesuai dengan struktur folder Anda

// Inisialisasi variabel dengan nilai default
$judul = '';
$konten = '';
$visibilitas = 'public'; // Atur default visibilitas jika diperlukan
$kategori = '';
$tags = '';

// Fungsi generate ID tag unik
function generateTagId($conn) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    
    do {
        $tagId = 'TAG';
        for ($i = 0; $i < 9; $i++) {
            $tagId .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        // Potong untuk memastikan panjang sesuai
        $tagId = substr($tagId, 0, 12);
        
        // Cek keunikan ID
        $checkQuery = "SELECT COUNT(*) as count FROM tag WHERE id = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('s', $tagId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();
        $checkStmt->close();
    } while ($row['count'] > 0);
    
    return $tagId;
}

// Cek apakah ada ID untuk edit
if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    // Query untuk mendapatkan data artikel
    $query = "SELECT b.id, b.judul, b.konten_artikel, b.visibilitas, b.kategori
              FROM berita b
              WHERE b.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
        $judul = $article['judul'];
        $konten = $article['konten_artikel'];
        $visibilitas = $article['visibilitas'];
        $kategori = $article['kategori'];
    } else {
        // Respon plain text untuk tidak ditemukannya artikel
        header('Content-Type: text/plain');
        die("Artikel tidak ditemukan.");
    }

    // Query untuk mendapatkan tag terkait
    $tagQuery = "SELECT nama_tag FROM tag WHERE berita_id = ?";
    $tagStmt = $conn->prepare($tagQuery);
    $tagStmt->bind_param('s', $edit_id);
    $tagStmt->execute();
    $tagResult = $tagStmt->get_result();

    $tagsArray = [];
    while ($tagRow = $tagResult->fetch_assoc()) {
        $tagsArray[] = $tagRow['nama_tag'];
    }
    $tags = implode(',', $tagsArray);
} else {
    // Respon plain text untuk ID artikel tidak valid
    header('Content-Type: text/plain');
    die("ID artikel tidak ditemukan.");
}

// Proses update artikel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish'])) {
    // Set header untuk respon plain text
    header('Content-Type: text/plain');

    $conn->begin_transaction(); // Mulai transaksi

    try {
        // Ambil ID dari parameter GET dan pastikan adalah string
        $edit_id = (string)$_GET['edit_id'];

        // Ambil data dari form
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category = $_POST['category'];
        $visibility = isset($_POST['visibility']) ? $_POST['visibility'] : 'public';
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        // Validasi input
        if ($user_id === null) {
            throw new Exception("User ID tidak ditemukan.");
        }

        // Cek kepemilikan artikel dan ambil data artikel saat ini
        $check_ownership_query = "SELECT * FROM berita WHERE id = ? AND user_id = ?";
        $check_ownership_stmt = $conn->prepare($check_ownership_query);
        $check_ownership_stmt->bind_param('ss', $edit_id, $user_id);
        $check_ownership_stmt->execute();
        $ownership_result = $check_ownership_stmt->get_result();
        $current_article = $ownership_result->fetch_assoc();

        if (!$current_article) {
            throw new Exception("Anda tidak memiliki izin untuk mengedit artikel ini.");
        }

        // Cek apakah ada perubahan
        $is_changed = false;

        // Bandingkan setiap field
        if ($title !== $current_article['judul']) $is_changed = true;
        if ($content !== $current_article['konten_artikel']) $is_changed = true;
        if ($category !== $current_article['kategori']) $is_changed = true;
        if ($visibility !== $current_article['visibilitas']) $is_changed = true;

        // Cek perubahan tag
        $current_tags_query = "SELECT nama_tag FROM tag WHERE berita_id = ?";
        $current_tags_stmt = $conn->prepare($current_tags_query);
        $current_tags_stmt->bind_param('s', $edit_id);
        $current_tags_stmt->execute();
        $current_tags_result = $current_tags_stmt->get_result();
        
        $current_tags = [];
        while ($tag_row = $current_tags_result->fetch_assoc()) {
            $current_tags[] = $tag_row['nama_tag'];
        }

        // Ambil tag baru dari form
        $new_tags = !empty($_POST['tags']) ? explode(',', $_POST['tags']) : [];
        $new_tags = array_map('trim', $new_tags);
        $new_tags = array_filter($new_tags);

        // Bandingkan tag
        sort($current_tags);
        sort($new_tags);
        if ($current_tags !== $new_tags) $is_changed = true;

        // Jika tidak ada perubahan, kembalikan pesan
        if (!$is_changed) {
            echo "Tidak ada perubahan pada artikel.";
            exit;
        }

        // Query untuk memperbarui artikel
        $query = "UPDATE berita SET 
                    judul = ?, 
                    konten_artikel = ?, 
                    kategori = ?, 
                    visibilitas = ? 
                  WHERE id = ? AND user_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'ssssss',
            $title,
            $content,
            $category,
            $visibility,
            $edit_id,
            $user_id
        );

        // Eksekusi query
        if (!$stmt->execute()) {
            throw new Exception("Eksekusi query gagal: " . $stmt->error);
        }

        // Hapus tag lama
        $delete_tag_query = "DELETE FROM tag WHERE berita_id = ?";
        $delete_tag_stmt = $conn->prepare($delete_tag_query);
        $delete_tag_stmt->bind_param('s', $edit_id);
        $delete_tag_stmt->execute();

        // Proses penyimpanan tag baru
        if (!empty($_POST['tags'])) {
            foreach ($new_tags as $tag) {
                $tagId = generateTagId($conn);
                $insert_tag_query = "INSERT INTO tag (id, nama_tag, berita_id) VALUES (?, ?, ?)";
                $insert_tag_stmt = $conn->prepare($insert_tag_query);
                $insert_tag_stmt->bind_param('sss', $tagId, $tag, $edit_id);
                $insert_tag_stmt->execute();
            }
        }

        $conn->commit();

        // Respon plain text untuk sukses
        echo "Artikel berhasil diperbarui!";
        exit;
    } catch (Exception $e) {
        $conn->rollback();

        // Log error untuk debugging
        error_log("Gagal memperbarui artikel: " . $e->getMessage());

        // Respon plain text untuk error
        echo "Gagal memperbarui artikel: " . $e->getMessage();
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penulisan Artikel</title>
    <link rel="shortcut icon" href="../assets/web-icon/Ic-main-KabarE.svg" type="KabarE">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-editor img {
            max-width: 80%;
            max-height: 300px;
            height: auto;
            width: auto;
            display: block;
            margin: 0 auto;
        }

        .ql-editor iframe {
            max-width: 80%;
            height: 300px;
            display: block;
            margin: 0 auto;
            background-color: #f0f0f0;
        }

        .tag-item {
            display: inline-flex;
            align-items: center;
            background-color: #e2e8f0;
            /* Warna abu-abu */
            color: #1a202c;
            /* Warna teks */
            border-radius: 9999px;
            /* Membuat bungkusan bulat */
            padding: 0.25rem 0.5rem;
            /* Padding dalam bungkusan */
            margin: 0.25rem;
            /* Jarak antar bungkusan */
        }

        .tag-item button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            margin-right: 0.5rem;
            /* Jarak antara tombol "×" dan teks */
        }
    </style>
</head>

<body class="bg-white">
    <!-- Animasi Loading -->
    <div id="loading" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden flex items-center justify-center z-50">
        <div class="w-16 h-16 border-4 border-t-4 border-gray-200 border-t-blue-500 rounded-full animate-spin"></div>
    </div>
    <?php include '../header & footer/header_AuthRev.php'; ?>

    <div class="container mx-auto p-6 lg:px-8 relative">
        <div class="absolute top-4 right-4 text-gray-500 rounded-full w-10 h-10 flex items-center justify-center cursor-pointer z-50 sm:flex md:flex lg:hidden xl:hidden transition-transform duration-300 ease-in-out" id="toggleSidebar">
            <i class="fas fa-cog text-2xl transition-transform duration-300 ease-in-out" id="toggleIcon"></i>
        </div>
        <h1 class="text-2xl font-bold mb-6 md:hidden" id="pageTitle">Penulisan Artikel</h1>

        <form id="articleForm" method="post" class="lg:flex lg:space-x-8">
            <div class="w-full lg:w-3/4 pr-8">
                <div class="mb-4">
                    <label for="title" class="block text-lg font-semibold mb-2">Judul Artikel</label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($judul); ?>" placeholder="Tulis judul artikelmu sendiri" class="w-full border-b-2 border-gray-300 outline-none focus:border-blue-500">
                </div>

                <div class="mb-2">
                    <label for="articleContent" class="block text-lg font-semibold mb-2">Form Penulisan Artikel</label>
                    <div id="quillEditor" class="border border-gray-300 rounded p-4 h-96">
                        <?php echo $konten; // Tampilkan konten HTML yang disimpan 
                        ?>
                    </div>
                    <input type="hidden" name="content" id="hiddenContent">
                </div>
            </div>

            <!-- Sidebar -->
            <div id="sidebar" class="fixed inset-y-0 right-0 transform translate-x-full lg:translate-x-0 lg:relative lg:w-1/4 md:w-1/2 bg-white transition-transform duration-300 ease-in-out lg:px-4 lg:mt-8">
                <div class="p-8">
                    <div class="flex justify-end lg:hidden">
                        <button id="closeSidebar" class="text-gray-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="flex space-x-4 items-center mt-24 mb-6 lg:hidden justify-center">
                        <i class="fas fa-cloud text-gray-800"></i>
                        <button type="button" id="previewButton" class="text-gray-800 border border-gray-400 px-4 py-2 rounded hover:bg-gray-300 flex items-center">
                            <i class="fas fa-eye mr-2"></i> Pratinjau
                        </button>
                        <button type="submit" id="publishButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Perbarui
                        </button>
                    </div>
                    <div class="hidden lg:flex space-x-2 items-center mb-4 justify-center" style="margin-top: -40px; margin-bottom: 40px;">
                        <i class="fas fa-cloud cloud-icon" id="cloudIcon"></i>
                        <button type="button" id="previewButtonLg" class="text-gray-800 border border-gray-400 px-4 py-2 rounded hover:bg-gray-300 flex items-center">
                            <i class="fas fa-eye mr-2"></i> Pratinjau
                        </button>
                        <button type="submit" id="publishButtonLg" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Perbarui
                        </button>
                    </div>
                    <h2 class="text-lg font-bold my-4 text-center">Pengaturan publikasi</h2>
                    <div class="mb-4">
                        <h2 class="font-semibold">Visibilitas</h2>
                        <p class="text-sm text-gray-600">Atur visibilitas artikel agar dapat dilihat oleh kelompok yang diinginkan.</p>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="visibility" value="public" class="form-radio" <?php echo ($visibilitas == 'public') ? 'checked' : ''; ?>>
                                <span class="ml-2">Public</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="radio" name="visibility" value="private" class="form-radio" <?php echo ($visibilitas == 'private') ? 'checked' : ''; ?>>
                                <span class="ml-2">Private</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h2 class="font-semibold">Tambahkan Tag</h2>
                        <p class="text-sm text-gray-600">Tambahkan tag untuk membantu pembaca menemukan berita artikel.</p>
                        <div id="labelContainer" class="flex flex-wrap mb-2">
                            <?php
                            if (!empty($tags)) {
                                $tagArray = explode(',', $tags);
                                foreach ($tagArray as $tag) {
                                    echo "<span class='tag-item bg-gray-200 text-black rounded-full px-2 py-0.5 m-1 flex items-center'>
                                            <button type='button' class='mr-2 text-black' onclick='removeLabel(this)'>×</button>
                                            <span>{$tag}</span>
                                          </span>";
                                }
                            }
                            ?>
                        </div>
                        <input type="text" id="labelInput" placeholder="Tambah tag" class="w-full border-b-2 border-gray-300 outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <h2 class="font-semibold">Kategori</h2>
                        <p class="text-sm text-gray-600">Menambah kategori untuk mempermudah pencarian artikel.</p>
                        <select id="categorySelect" name="category" class="w-full border-b-2 border-gray-300 focus:outline-none mt-2">
                            <option value="" disabled <?php echo empty($kategori) ? 'selected' : ''; ?>>Pilih Kategori</option>
                            <option value="Kampus" <?php echo ($kategori == 'Kampus') ? 'selected' : ''; ?>>Kampus</option>
                            <option value="Prestasi" <?php echo ($kategori == 'Prestasi') ? 'selected' : ''; ?>>Prestasi</option>
                            <option value="Politik" <?php echo ($kategori == 'Politik') ? 'selected' : ''; ?>>Politik</option>
                            <option value="Kesehatan" <?php echo ($kategori == 'Kesehatan') ? 'selected' : ''; ?>>Kesehatan</option>
                            <option value="Olahraga" <?php echo ($kategori == 'Olahraga') ? 'selected' : ''; ?>>Olahraga</option>
                            <option value="Ekonomi" <?php echo ($kategori == 'Ekonomi') ? 'selected' : ''; ?>>Ekonomi</option>
                            <option value="Bisnis" <?php echo ($kategori == 'Bisnis') ? 'selected' : ''; ?>>Bisnis</option>
                            <option value="UKM" <?php echo ($kategori == 'UKM') ? 'selected' : ''; ?>>UKM</option>
                            <option value="Berita Lainnya" <?php echo ($kategori == 'Berita Lainnya') ? 'selected' : ''; ?>>Berita Lainnya</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <!-- Tambahkan div untuk pratinjau -->
        <div id="previewContainer" class="hidden">
            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-2/3 pr-4">
                    <span id="previewCategory" class="inline-block bg-red-500 text-white px-4 py-1 rounded-md"></span>
                    <h1 id="previewTitle" class="text-3xl font-bold mt-2"></h1>
                    <span class="text-gray-500 text-sm">Pratinjau - <span id="previewDate"></span></span>
                    <img id="previewImage" src="" class="w-full h-auto object-cover rounded-lg my-4">
                    <p id="previewContent" class="mt-4 text-gray-700"></p>
                </div>
            </div>
            <button id="backToEditor" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Kembali ke Editor</button>
        </div>
    </div>

    <div id="popup" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg transform transition-transform scale-0">
            <div class="flex items-center mb-4">
                <i class="fas fa-exclamation-circle text-red-500 text-2xl mr-2"></i>
                <h2 class="text-xl font-bold" id="popupTitle">Pesan</h2>
            </div>
            <p class="mb-4" id="popupMessage">Pesan kesalahan.</p>
            <button id="closePopup" class="bg-blue-500 text-white px-4 py-2 rounded">Tutup</button>
        </div>
    </div>

    <div id="confirmPopup" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg transform transition-transform scale-0">
            <div class="flex items-center mb-4">
                <i class="fas fa-question-circle text-blue-500 text-2xl mr-2"></i>
                <h2 class="text-xl font-bold">Konfirmasi perbarui</h2>
            </div>
            <p class="mb-4">Apakah Anda yakin ingin memperbarui artikel ini?</p>
            <div class="flex justify-end">
                <button id="confirmYes" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Ya</button>
                <button id="confirmNo" class="bg-gray-500 text-white px-4 py-2 rounded">Tidak</button>
            </div>
        </div>
    </div>

    <input type="file" id="videoInput" accept="video/*" style="display: none;">

    <div id="successPopup" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col items-center">
            <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
            <h2 class="text-xl font-bold mb-2">Berhasil!</h2>
            <p>Artikel berhasil diperbarui!</p>
            <button id="closeSuccessPopup" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Tutup</button>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Sembunyikan animasi loading saat halaman dimuat
        window.addEventListener('load', function() {
            document.getElementById('loading').classList.add('hidden');
        });

        // Memaksa refresh halaman jika dimuat dari cache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        const quill = new Quill('#quillEditor', {
            theme: 'snow',
            modules: {
                toolbar: {
                    container: [
                        [{
                            'font': []
                        }, {
                            'size': []
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        [{
                            'script': 'sub'
                        }, {
                            'script': 'super'
                        }],
                        [{
                            'header': '1'
                        }, {
                            'header': '2'
                        }, 'blockquote', 'code-block'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }, {
                            'indent': '-1'
                        }, {
                            'indent': '+1'
                        }],
                        [{
                            'direction': 'rtl'
                        }, {
                            'align': []
                        }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ],
                    handlers: {
                        video: function() {
                            // Trigger click on the hidden file input
                            document.getElementById('videoInput').click();
                        },
                        link: function() {
                            const range = quill.getSelection();
                            if (range) {
                                const url = prompt('Masukkan URL:');
                                if (url) {
                                    quill.format('link', url);
                                }
                            } else {
                                alert('Silakan pilih teks atau gambar yang ingin Anda tambahkan tautan.');
                            }
                        }
                    }
                }
            }
        });

        const hiddenContent = document.getElementById('hiddenContent');
        const previewButton = document.getElementById('previewButton');
        const popup = document.getElementById('popup');
        const closePopup = document.getElementById('closePopup');
        const popupTitle = document.getElementById('popupTitle');
        const popupMessage = document.getElementById('popupMessage');
        const toggleSidebar = document.getElementById('toggleSidebar');
        const toggleIcon = document.getElementById('toggleIcon');
        const sidebar = document.getElementById('sidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const labelInput = document.getElementById('labelInput');
        const labelContainer = document.getElementById('labelContainer');
        const categorySelect = document.getElementById('categorySelect');

        const videoInput = document.getElementById('videoInput');

        videoInput.addEventListener('change', function() {
            const file = videoInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const range = quill.getSelection();
                    if (range) {
                        const videoTag = `<video controls src="${e.target.result}" style="max-width: 80%; max-height: 300px; display: block; margin: 0 auto;"></video>`;
                        quill.clipboard.dangerouslyPasteHTML(range.index, videoTag);
                    } else {
                        alert('Silakan pilih lokasi di editor untuk menyisipkan video.');
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('translate-x-full');
            if (sidebar.classList.contains('translate-x-full')) {
                toggleIcon.classList.replace('fa-times', 'fa-cog');
            } else {
                toggleIcon.classList.replace('fa-cog', 'fa-times');
            }
        });

        closeSidebar.addEventListener('click', () => {
            sidebar.classList.add('translate-x-full');
            toggleIcon.classList.replace('fa-times', 'fa-cog');
        });

        labelInput.addEventListener('keydown', function(event) {
            if ((event.key === ',' || event.key === 'Enter') && labelInput.value.trim() !== '') {
                event.preventDefault();
                const labelText = labelInput.value.trim().replace(',', '');
                if (labelText.length > 15) {
                    showPopup('Tag tidak boleh lebih dari 15 karakter!', false);
                } else if (labelContainer.children.length >= 10) {
                    showPopup('Anda hanya dapat menambahkan hingga 10 tag!', false);
                } else {
                    createLabel(labelText);
                    labelInput.value = '';
                }
            }
        });

        function createLabel(text) {
            const label = document.createElement('span');
            label.className = 'bg-gray-200 text-black rounded-full px-2 py-0.5 m-1 flex items-center';
            label.innerHTML = `<button type="button" class="mr-2 text-black" onclick="removeLabel(this)">×</button>${text}`;
            labelContainer.appendChild(label);
            updateLabelInputState();
        }

        function removeLabel(button) {
            const label = button.parentElement;
            label.remove();
        }

        function updateLabelInputState() {
            if (labelContainer.children.length >= 10) {
                labelInput.disabled = true;
            } else {
                labelInput.disabled = false;
            }
        }

        categorySelect.addEventListener('focus', function() {
            this.size = 5; // Tampilkan 5 opsi saat dropdown difokuskan
        });

        categorySelect.addEventListener('change', function() {
            this.size = 1; // Kembali ke ukuran normal setelah memilih
            this.blur(); // Hilangkan fokus untuk menutup dropdown
        });

        categorySelect.addEventListener('blur', function() {
            this.size = 1; // Pastikan dropdown kembali ke ukuran normal saat kehilangan fokus
        });

        // Gabungkan event listener untuk tombol pratinjau
        const previewButtons = document.querySelectorAll('#previewButton, #previewButtonLg');
        previewButtons.forEach(button => {
            button.addEventListener('click', () => {
                const titleInput = document.getElementById('title').value.trim();
                const contentHtml = quill.root.innerHTML;
                const selectedCategory = categorySelect.value;
                const hasImage = checkForImage();

                if (titleInput === '') {
                    showPopup('Judul artikel tidak boleh kosong!', false);
                } else if (contentHtml === '') {
                    showPopup('Konten artikel tidak boleh kosong!', false);
                } else if (selectedCategory === 'Pilih Kategori') {
                    showPopup('Silakan pilih kategori terlebih dahulu!', false);
                } else if (!hasImage) {
                    showPopup('Silakan tambahkan gambar ke dalam artikel sebelum pratinjau!', false);
                } else {
                    // Set data pratinjau
                    document.getElementById('previewTitle').textContent = titleInput || 'Penulisan Artikel';
                    document.getElementById('previewContent').innerHTML = contentHtml;
                    document.getElementById('previewCategory').textContent = selectedCategory;
                    document.getElementById('previewDate').textContent = new Date().toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });

                    // Sembunyikan elemen yang tidak diperlukan pada layar md ke bawah
                    document.getElementById('pageTitle').classList.add('hidden');
                    document.getElementById('toggleSidebar').classList.add('hidden'); // Sembunyikan toggle sidebar

                    // Tampilkan pratinjau dan sembunyikan editor
                    document.getElementById('articleForm').classList.add('hidden');
                    document.getElementById('previewContainer').classList.remove('hidden');
                }
            });
        });

        document.getElementById('backToEditor').addEventListener('click', () => {
            // Kembali ke editor
            document.getElementById('articleForm').classList.remove('hidden');
            document.getElementById('previewContainer').classList.add('hidden');

            // Tampilkan kembali elemen yang disembunyikan
            document.getElementById('pageTitle').classList.remove('hidden');
            document.getElementById('toggleSidebar').classList.remove('hidden'); // Tampilkan kembali toggle sidebar
        });

        function showPopup(message, success = true) {
            const icon = document.querySelector('#popup i');
            popupTitle.textContent = success ? 'Sukses' : 'Kesalahan';
            popupMessage.textContent = message;
            if (success) {
                icon.classList.replace('fa-exclamation-circle', 'fa-check-circle');
                icon.classList.replace('text-red-500', 'text-green-500');
            } else {
                icon.classList.replace('fa-check-circle', 'fa-exclamation-circle');
                icon.classList.replace('text-green-500', 'text-red-500');
            }
            popup.classList.remove('hidden');
            document.querySelector('#popup div').classList.add('scale-100');
        }

        closePopup.addEventListener('click', () => {
            popup.classList.add('hidden');
            document.querySelector('#popup div').classList.remove('scale-100');
        });

        // Tambahkan event listener untuk gambar dan video
        quill.on('text-change', function(delta, oldDelta, source) {
            if (source === 'user') {
                const images = document.querySelectorAll('.ql-editor img');
                images.forEach((img) => {
                    img.style.maxWidth = '80%';
                    img.style.maxHeight = '300px';
                    img.style.height = 'auto';
                    img.style.width = 'auto';
                    img.style.display = 'block';
                    img.style.margin = '0 auto';
                });

                const iframes = document.querySelectorAll('.ql-editor iframe');
                iframes.forEach((iframe) => {
                    iframe.style.maxWidth = '80%';
                    iframe.style.height = '300px';
                    iframe.style.display = 'block';
                    iframe.style.margin = '0 auto';
                    iframe.style.backgroundColor = '#f0f0f0';
                });
            }
        });

        const confirmPopup = document.getElementById('confirmPopup');
        const confirmYes = document.getElementById('confirmYes');
        const confirmNo = document.getElementById('confirmNo');

        function showConfirmPopup() {
            confirmPopup.classList.remove('hidden');
            document.querySelector('#confirmPopup div').classList.add('scale-100');
        }

        function hideConfirmPopup() {
            confirmPopup.classList.add('hidden');
            document.querySelector('#confirmPopup div').classList.remove('scale-100');
        }

        confirmYes.addEventListener('click', () => {
            hideConfirmPopup(); // Sembunyikan pop-up konfirmasi

            // Tampilkan animasi loading
            document.getElementById('loading').classList.remove('hidden');

            // Ambil data dari form
            const titleInput = document.getElementById('title').value.trim();
            const contentHtml = quill.root.innerHTML.trim();
            const selectedCategory = categorySelect.value;
            const visibility = document.querySelector('input[name="visibility"]:checked').value;
            const editId = '<?php echo $edit_id; ?>';

            // Ambil tag dari labelContainer
            const tags = Array.from(document.getElementById('labelContainer').children)
                .map(label => label.textContent.trim().replace('×', ''));

            // Kirim data ke server menggunakan AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'Update_author.php?edit_id=' + encodeURIComponent(editId), true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    window.location.href = 'publishAuthor.php';
                } else {
                    alert('Terjadi kesalahan saat memperbarui artikel.' + xhr.responseText);
                }
            };

            // Tambahkan parameter tags ke dalam pengiriman
            xhr.send(`publish=true&title=${encodeURIComponent(titleInput)}&content=${encodeURIComponent(contentHtml)}&category=${encodeURIComponent(selectedCategory)}&visibility=${encodeURIComponent(visibility)}&tags=${encodeURIComponent(tags.join(','))}`);
        });

        confirmNo.addEventListener('click', hideConfirmPopup);

        function checkForImage() {
            const images = quill.root.querySelectorAll('img');
            return images.length > 0;
        }

        document.getElementById('publishButton').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah form submit dan refresh halaman
            const titleInput = document.getElementById('title').value.trim();
            const contentHtml = quill.root.innerHTML.trim();
            const isContentEmpty = contentHtml === '<p><br></p>'; // Quill's default empty content
            const selectedCategory = categorySelect.value;
            const hasImage = checkForImage();

            if (titleInput === '') {
                showPopup('Judul artikel tidak boleh kosong!', false);
            } else if (titleInput.length > 100) {
                showPopup('Judul artikel tidak boleh lebih dari 100 karakter!', false);
            } else if (isContentEmpty) {
                showPopup('Konten artikel tidak boleh kosong!', false);
            } else if (selectedCategory === 'Pilih Kategori') {
                showPopup('Silakan pilih kategori terlebih dahulu!', false);
            } else if (!hasImage) {
                showPopup('Silakan tambahkan gambar ke dalam artikel sebelum diperbarui!', false);
            } else {
                showConfirmPopup();
            }
            // Ambil semua tag dari labelContainer
            const tags = Array.from(document.getElementById('labelContainer').children)
                .map(label => label.textContent.trim().replace('×', ''));

            // Set nilai input hidden untuk tag
            document.getElementById('hiddenTags').value = tags.join(',');

            // Lanjutkan dengan validasi dan submit form
            document.getElementById('articleForm').submit();
        });

        // Tambahkan fungsi untuk mengumpulkan tag sebelum submit
        function collectTags() {
            const tags = Array.from(document.getElementById('labelContainer').children)
                .map(label => label.textContent.trim().replace('×', ''));
            document.getElementById('hiddenTags').value = tags.join(',');
        }

        // Panggil collectTags() sebelum submit form di event listener publish
        document.getElementById('publishButton').addEventListener('click', function(event) {
            collectTags(); // Kumpulkan tag sebelum submit
            // ... kode validasi lainnya
        });

        document.getElementById('publishButtonLg').addEventListener('click', function(event) {
            collectTags(); // Kumpulkan tag sebelum submit
            // ... kode validasi lainnya
        });

        document.getElementById('publishButtonLg').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah form submit dan refresh halaman
            const titleInput = document.getElementById('title').value.trim();
            const contentHtml = quill.root.innerHTML.trim();
            const isContentEmpty = contentHtml === '<p><br></p>'; // Quill's default empty content
            const selectedCategory = categorySelect.value;
            const hasImage = checkForImage();

            if (titleInput === '') {
                showPopup('Judul artikel tidak boleh kosong!', false);
            } else if (titleInput.length > 100) {
                showPopup('Judul artikel tidak boleh lebih dari 100 karakter!', false);
            } else if (isContentEmpty) {
                showPopup('Konten artikel tidak boleh kosong!', false);
            } else if (selectedCategory === 'Pilih Kategori') {
                showPopup('Silakan pilih kategori terlebih dahulu!', false);
            } else if (!hasImage) {
                showPopup('Silakan tambahkan gambar ke dalam artikel sebelum perbarui!', false);
            } else {
                showConfirmPopup();
            }
        });

        function showSuccessPopup() {
            const successPopup = document.getElementById('successPopup');
            successPopup.classList.remove('hidden');
        }

        document.getElementById('closeSuccessPopup').addEventListener('click', () => {
            const successPopup = document.getElementById('successPopup');
            successPopup.classList.add('hidden');
        });

        // Panggil showSuccessPopup() setelah artikel berhasil diperbarui
        if (xhr.status === 200) {
            showSuccessPopup();
            // Redirect setelah beberapa detik jika diperlukan
            setTimeout(() => {
                window.location.href = 'draftAuthor.php';
            }, 3000);
        } else {
            alert('Terjadi kesalahan saat memperbarui artikel.');
        }
    </script>
</body>

</html>
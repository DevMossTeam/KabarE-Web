<?php
session_start(); // Mulai sesi
include '../connection/config.php'; // Pastikan path ini sesuai dengan struktur folder Anda

function generateId($conn) {
    $prefix = "BRT";
    $query = "SELECT MAX(CAST(SUBSTRING(id, 4) AS UNSIGNED)) AS max_id FROM berita WHERE id LIKE '$prefix%'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $maxId = $row['max_id'] ? $row['max_id'] : 0;
    $newId = $prefix . ($maxId + 1);
    return $newId;
}

function generateTagId($conn) {
    $prefix = "TDB";
    $query = "SELECT MAX(CAST(SUBSTRING(id, 4) AS UNSIGNED)) AS max_id FROM tag WHERE id LIKE '$prefix%'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $maxId = $row['max_id'] ? $row['max_id'] : 0;
    $newId = $prefix . ($maxId + 1);
    return $newId;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish'])) {
    $id = generateId($conn);
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $visibility = isset($_POST['visibility']) ? $_POST['visibility'] : 'public'; // Set default ke 'public'
    $status = 'publikasi';
    $created_at = date('Y-m-d H:i:s');

    // Ambil user_id dari sesi atau sumber lain
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($user_id === null) {
        echo "User ID tidak ditemukan.";
        exit;
    }

    if ($visibility === null) {
        echo "Visibilitas tidak boleh kosong.";
        exit;
    }

    $query = "INSERT INTO berita (id, judul, konten_artikel, kategori, status_publikasi, tanggal_dibuat, user_id, visibilitas) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssss', $id, $title, $content, $category, $status, $created_at, $user_id, $visibility);

    if ($stmt->execute()) {
        // Ambil tag dari input dan simpan ke database
        if (!empty($_POST['tags'])) {
            $tags = explode(',', $_POST['tags']);
            foreach ($tags as $tag) {
                $tagId = generateTagId($conn);
                $tagQuery = "INSERT INTO tag (id, nama_tag, berita_id) VALUES (?, ?, ?)";
                $tagStmt = $conn->prepare($tagQuery);
                $tagStmt->bind_param('sss', $tagId, trim($tag), $id);
                $tagStmt->execute();
                $tagStmt->close();
            }
        }
        echo "Artikel berhasil dipublikasikan!";
    } else {
        echo "Gagal mempublikasikan artikel.";
    }
    $stmt->close();
    $conn->close();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penulisan Artikel</title>
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
    </style>
</head>
<body class="bg-white">
    <!-- Animasi Loading -->
    <div id="loading" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden flex items-center justify-center z-50">
        <div class="w-16 h-16 border-4 border-t-4 border-gray-200 border-t-blue-500 rounded-full animate-spin"></div>
    </div>
    <?php include '../header & footer/header_AuthRev.php'; ?>

    <div class="container mx-auto p-6 relative">
        <div class="absolute top-4 right-4 text-gray-500 rounded-full w-10 h-10 flex items-center justify-center cursor-pointer z-50 sm:flex md:flex lg:hidden xl:hidden transition-transform duration-300 ease-in-out" id="toggleSidebar">
            <i class="fas fa-cog text-2xl transition-transform duration-300 ease-in-out" id="toggleIcon"></i>
        </div>
        <h1 class="text-2xl font-bold mb-6 md:hidden" id="pageTitle">Penulisan Artikel</h1>

        <form id="articleForm" method="post">
            <div class="flex">
                <div class="w-full lg:w-3/4 pr-8">
                    <div class="mb-4">
                        <label for="title" class="block text-lg font-semibold mb-2">Judul Artikel</label>
                        <input type="text" name="title" id="title" placeholder="Tulis judul artikelmu sendiri" class="w-full border-b-2 border-gray-300 outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-2">
                        <label for="articleContent" class="block text-lg font-semibold mb-2">Form Penulisan Artikel</label>
                        <div id="quillEditor" class="border border-gray-300 rounded p-4 h-96"></div>
                        <input type="hidden" name="content" id="hiddenContent">
                    </div>
                </div>

                <!-- Sidebar -->
                <div id="sidebar" class="fixed inset-y-0 right-0 transform translate-x-full lg:translate-x-0 lg:relative lg:w-1/4 md:w-1/2 bg-white transition-transform duration-300 ease-in-out">
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
                                <i class="fas fa-paper-plane mr-2"></i> Publikasi
                            </button>
                        </div>
                        <div class="hidden lg:flex space-x-2 items-center mb-4 justify-center" style="margin-top: -40px; margin-bottom: 40px;">
                            <i class="fas fa-cloud cloud-icon" id="cloudIcon"></i>
                            <button type="button" id="previewButtonLg" class="text-gray-800 border border-gray-400 px-4 py-2 rounded hover:bg-gray-300 flex items-center">
                                <i class="fas fa-eye mr-2"></i> Pratinjau
                            </button>
                            <button type="submit" id="publishButtonLg" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i> Publikasi
                            </button>
                        </div>
                        <h2 class="text-lg font-bold my-4 text-center">Pengaturan Publikasi</h2>
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
                            <div id="labelContainer" class="flex flex-wrap mb-2"></div>
                            <input type="text" id="labelInput" placeholder="Tambah tag" class="w-full border-b-2 border-gray-300 outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <h2 class="font-semibold">Kategori</h2>
                            <p class="text-sm text-gray-600">Menambah kategori untuk mempermudah pencarian artikel.</p>
                            <select id="categorySelect" class="w-full border-b-2 border-gray-300 focus:outline-none mt-2" size="1" onclick="this.size=5" onblur="this.size=1" onchange="this.size=1; this.blur();">
                                <option disabled selected>Pilih Kategori</option>
                                <option>Kampus</option>
                                <option>Prestasi</option>
                                <option>Politik</option>
                                <option>Kesehatan</option>
                                <option>Olahraga</option>
                                <option>Ekonomi</option>
                                <option>Bisnis</option>
                                <option>UKM</option>
                                <option>Berita Lainnya</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="tags" id="hiddenTags">
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
                <h2 class="text-xl font-bold">Konfirmasi Publikasi</h2>
            </div>
            <p class="mb-4">Apakah Anda yakin ingin mempublikasikan artikel ini?</p>
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
            <p>Artikel berhasil dipublikasikan!</p>
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
                        [{ 'font': [] }, { 'size': [] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'script': 'sub' }, { 'script': 'super' }],
                        [{ 'header': '1' }, { 'header': '2' }, 'blockquote', 'code-block'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
                        [{ 'direction': 'rtl' }, { 'align': [] }],
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
            labelContainer.removeChild(label);
            updateLabelInputState();
        }

        function updateLabelInputState() {
            if (labelContainer.children.length >= 10) {
                labelInput.disabled = true;
            } else {
                labelInput.disabled = false;
            }
        }

        categorySelect.addEventListener('click', function() {
            this.size = 5;
        });

        categorySelect.addEventListener('change', function() {
            this.size = 1;
            this.blur();
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
                    document.getElementById('previewDate').textContent = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

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

            // Kirim data ke server menggunakan AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'Main_author.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    window.location.href = 'draftAuthor.php'; // Redirect setelah berhasil
                } else {
                    alert('Terjadi kesalahan saat mempublikasikan artikel.');
                }
            };
            xhr.send(`publish=true&title=${encodeURIComponent(titleInput)}&content=${encodeURIComponent(contentHtml)}&category=${encodeURIComponent(selectedCategory)}`);
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
            } else if (isContentEmpty) {
                showPopup('Konten artikel tidak boleh kosong!', false);
            } else if (selectedCategory === 'Pilih Kategori') {
                showPopup('Silakan pilih kategori terlebih dahulu!', false);
            } else if (!hasImage) {
                showPopup('Silakan tambahkan gambar ke dalam artikel sebelum publikasi!', false);
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

        document.getElementById('publishButtonLg').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah form submit dan refresh halaman
            const titleInput = document.getElementById('title').value.trim();
            const contentHtml = quill.root.innerHTML.trim();
            const isContentEmpty = contentHtml === '<p><br></p>'; // Quill's default empty content
            const selectedCategory = categorySelect.value;
            const hasImage = checkForImage();

            if (titleInput === '') {
                showPopup('Judul artikel tidak boleh kosong!', false);
            } else if (isContentEmpty) {
                showPopup('Konten artikel tidak boleh kosong!', false);
            } else if (selectedCategory === 'Pilih Kategori') {
                showPopup('Silakan pilih kategori terlebih dahulu!', false);
            } else if (!hasImage) {
                showPopup('Silakan tambahkan gambar ke dalam artikel sebelum publikasi!', false);
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

        // Panggil showSuccessPopup() setelah artikel berhasil dipublikasikan
        if (xhr.status === 200) {
            showSuccessPopup();
            // Redirect setelah beberapa detik jika diperlukan
            setTimeout(() => {
                window.location.href = 'draftAuthor.php';
            }, 3000);
        } else {
            alert('Terjadi kesalahan saat mempublikasikan artikel.');
        }
    </script>
</body>
</html>
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
            max-width: 600px;
            max-height: 400px;
            height: auto;
            width: auto;
        }
    </style>
</head>
<body class="bg-white">
    <?php include '../header & footer/header_AuthRev.php'; ?>

    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Penulisan Artikel</h1>

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
                <div id="sidebar" class="fixed inset-y-0 right-0 transform translate-x-full lg:translate-x-0 lg:relative lg:w-1/4 bg-white transition-transform duration-300 ease-in-out">
                    <div class="p-8">
                        <div class="flex justify-end lg:hidden">
                            <button id="closeSidebar" class="text-gray-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="flex space-x-2 items-center mb-4">
                            <i class="fas fa-cloud cloud-icon" id="cloudIcon"></i>
                            <button type="button" id="previewButton" class="text-gray-800 border border-gray-400 px-4 py-2 rounded hover:bg-gray-300 flex items-center">
                                <i class="fas fa-eye mr-2"></i> Pratinjau
                            </button>
                            <button type="submit" id="publishButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
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
        </form>

        <!-- Toggle Button -->
        <button id="toggleSidebar" class="fixed top-4 right-4 lg:hidden bg-blue-500 text-white p-3 rounded-full shadow-lg">
            <i class="fas fa-cog"></i>
        </button>
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

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        const quill = new Quill('#quillEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': [] }, { 'size': [] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub' }, { 'script': 'super' }],
                    [{ 'header': '1' }, { 'header': '2' }, 'blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }, { 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        const hiddenContent = document.getElementById('hiddenContent');
        const previewButton = document.getElementById('previewButton');
        const publishButton = document.getElementById('publishButton');
        const popup = document.getElementById('popup');
        const closePopup = document.getElementById('closePopup');
        const popupTitle = document.getElementById('popupTitle');
        const popupMessage = document.getElementById('popupMessage');
        const articleForm = document.getElementById('articleForm');
        const cloudIcon = document.getElementById('cloudIcon');
        const toggleSidebar = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const labelInput = document.getElementById('labelInput');
        const labelContainer = document.getElementById('labelContainer');
        const categorySelect = document.getElementById('categorySelect');

        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('translate-x-full');
        });

        closeSidebar.addEventListener('click', () => {
            sidebar.classList.add('translate-x-full');
        });

        labelInput.addEventListener('keydown', function(event) {
            if (event.key === ',' || event.key === 'Enter') {
                event.preventDefault();
                const labelText = labelInput.value.trim().replace(',', '');
                if (labelText) {
                    createLabel(labelText);
                    labelInput.value = '';
                }
            }
        });

        function createLabel(text) {
            const label = document.createElement('span');
            label.className = 'bg-gray-200 text-black rounded-full px-3 py-1 m-1 flex items-center';
            label.innerHTML = `<button type="button" class="mr-2 text-black" onclick="removeLabel(this)">×</button>${text}`;
            labelContainer.appendChild(label);
        }

        function removeLabel(button) {
            const label = button.parentElement;
            labelContainer.removeChild(label);
        }

        categorySelect.addEventListener('click', function() {
            this.size = 5;
        });

        categorySelect.addEventListener('change', function() {
            this.size = 1;
            this.blur();
        });

        previewButton.addEventListener('click', () => {
            const titleInput = document.getElementById('title').value.trim();
            const contentText = quill.getText().trim();

            if (titleInput === '') {
                showPopup('Judul artikel tidak boleh kosong!', false);
            } else if (contentText === '') {
                showPopup('Konten artikel tidak boleh kosong!', false);
            } else {
                hiddenContent.value = quill.root.innerHTML;
                // Kode terkait previewAuthor.php dihapus
            }
        });

        publishButton.addEventListener('click', (e) => {
            if (quill.getText().trim() === '') {
                e.preventDefault();
                showPopup('Konten artikel tidak boleh kosong!', false);
            } else {
                hiddenContent.value = quill.root.innerHTML;
                articleForm.action = 'publishAuthor.php';
                articleForm.submit();
            }
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

        // Tambahkan event listener untuk gambar
        quill.on('text-change', function(delta, oldDelta, source) {
            if (source === 'user') {
                const images = document.querySelectorAll('.ql-editor img');
                images.forEach((img, index) => {
                    img.style.maxWidth = '600px';
                    img.style.maxHeight = '400px';
                    img.style.height = 'auto';
                    img.style.width = 'auto';

                    // Jadikan gambar pertama sebagai cover
                    if (index === 0) {
                        img.classList.add('cover-image');
                    }
                });
            }
        });
    </script>
</body>
</html>
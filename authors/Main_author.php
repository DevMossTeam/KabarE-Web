<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penulisan Artikel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../header & footer/header_AuthRev.php'; ?>

    <div class="container mx-auto p-4">
        <div id="editorWrapper" class="bg-white shadow-md rounded-lg p-6 relative" style="height: 700px;">
            <!-- Tombol Pratinjau dan Publikasi -->
            <div class="absolute top-4 right-4 flex space-x-2">
                <button id="previewButton" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 flex items-center">
                    <i class="fas fa-eye mr-2"></i> Pratinjau
                </button>
                <button id="publishButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
                    <i class="fas fa-paper-plane mr-2"></i> Publikasi
                </button>
            </div>

            <!-- Judul Artikel -->
            <h1 class="text-xl font-bold mb-2">Judul Artikel</h1>
            <input type="text" placeholder="Tulis judul artikelmu sendiri" class="w-full border-b-2 border-gray-300 focus:outline-none mb-4">

            <!-- Upload Cover Gambar/Video -->
            <label for="uploadCover" class="block mb-2">Upload Cover Gambar/Video</label>
            <input type="file" id="uploadCover" class="mb-4">

            <!-- Label dan Textarea untuk konten artikel -->
            <label for="articleContent" class="block mb-2"><strong>Form Penulisan Artikel</strong></label>
            <textarea name="Editor" id="Editor" class="w-full border border-gray-300 p-2 focus:outline-none" style="height: calc(100% - 200px); resize: none;" placeholder="Tulis konten artikelmu disini"></textarea>
        </div>
    </div>

    <!-- Pop-up Box -->
    <div id="popup" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg transform transition-transform scale-0">
            <div class="flex items-center mb-4">
                <i class="fas fa-exclamation-circle text-red-500 text-2xl mr-2"></i>
                <h2 class="text-xl font-bold">Konten Masih Kosong</h2>
            </div>
            <p class="mb-4">Anda belum menambahkan konten disini.</p>
            <button id="closePopup" class="bg-blue-500 text-white px-4 py-2 rounded">Tutup</button>
        </div>
    </div>

    <script>
        const previewButton = document.getElementById('previewButton');
        const publishButton = document.getElementById('publishButton');
        const popup = document.getElementById('popup');
        const closePopup = document.getElementById('closePopup');

        function showPopup() {
            popup.classList.remove('hidden');
            popup.querySelector('div').classList.add('scale-100');
        }

        function hidePopup() {
            popup.classList.add('hidden');
            popup.querySelector('div').classList.remove('scale-100');
        }

        function checkContent() {
            const content = document.getElementById('Editor').value.trim();
            if (content === '') {
                showPopup();
                return false;
            }
            return true;
        }

        previewButton.addEventListener('click', (e) => {
            if (!checkContent()) e.preventDefault();
            else window.location.href = 'previewAuthor.php';
        });

        publishButton.addEventListener('click', (e) => {
            if (!checkContent()) e.preventDefault();
            else window.location.href = 'publishAuthor.php';
        });

        closePopup.addEventListener('click', hidePopup);

        window.addEventListener('click', (e) => {
            if (e.target === popup) hidePopup();
        });
    </script>
</body>
</html>

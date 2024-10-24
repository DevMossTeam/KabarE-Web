<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penulisan Artikel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Arial:wght@400;700&family=Courier+New:wght@400;700&family=Georgia:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../header & footer/header_AuthRev.php'; ?>

    <div class="container mx-auto p-4">
        <form id="articleForm" action="publishAuthor.php" method="post" enctype="multipart/form-data">
            <div id="editorWrapper" class="bg-white shadow-md rounded-lg p-6 relative" style="height: 700px;">
                <div class="absolute top-4 right-4 flex space-x-2">
                    <button type="button" id="previewButton" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 flex items-center">
                        <i class="fas fa-eye mr-2"></i> Pratinjau
                    </button>
                    <button type="submit" id="publishButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> Publikasi
                    </button>
                </div>

                <h1 class="text-xl font-bold mb-2">Judul Artikel</h1>
                <input type="text" name="title" id="title" placeholder="Tulis judul artikelmu sendiri" class="w-full border-b-2 border-gray-300 focus:outline-none mb-4">

                <label for="uploadCover" class="block mb-2 font-bold">Upload Cover</label>
                <input type="file" id="uploadCover" name="cover" class="mb-4" accept="image/*">

                <label for="articleContent" class="block mb-2"><strong>Form Penulisan Artikel</strong></label>
                <div id="quillEditor" style="height: 400px;"></div>
                <input type="hidden" name="content" id="hiddenContent">
            </div>
        </form>
    </div>

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

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        const quill = new Quill('#quillEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': ['sans-serif', 'serif', 'monospace'] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub' }, { 'script': 'super' }],
                    [{ 'header': '1' }, { 'header': '2' }, 'blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }, { 'align': [] }],
                    ['link', 'image', 'video', 'formula'],
                    ['clean']
                ]
            }
        });

        const previewButton = document.getElementById('previewButton');
        const publishButton = document.getElementById('publishButton');
        const popup = document.getElementById('popup');
        const closePopup = document.getElementById('closePopup');
        const articleForm = document.getElementById('articleForm');
        const hiddenContent = document.getElementById('hiddenContent');
        const titleInput = document.getElementById('title');

        // Load data from localStorage
        window.addEventListener('load', () => {
            const savedTitle = localStorage.getItem('articleTitle');
            const savedContent = localStorage.getItem('articleContent');

            if (savedTitle) {
                titleInput.value = savedTitle;
            }

            if (savedContent) {
                quill.root.innerHTML = savedContent;
            }
        });

        // Save data to localStorage
        titleInput.addEventListener('input', () => {
            localStorage.setItem('articleTitle', titleInput.value);
        });

        quill.on('text-change', () => {
            localStorage.setItem('articleContent', quill.root.innerHTML);
        });

        function showPopup() {
            popup.classList.remove('hidden');
            popup.querySelector('div').classList.add('scale-100');
        }

        function hidePopup() {
            popup.classList.add('hidden');
            popup.querySelector('div').classList.remove('scale-100');
        }

        function checkContent() {
            const content = quill.getText().trim();
            if (content === '') {
                showPopup();
                return false;
            }
            return true;
        }

        previewButton.addEventListener('click', (e) => {
            if (checkContent()) {
                hiddenContent.value = quill.root.innerHTML;
                articleForm.action = 'previewAuthor.php'; // Set action to previewAuthor.php
                articleForm.submit();
            } else {
                e.preventDefault();
            }
        });

        publishButton.addEventListener('click', (e) => {
            if (checkContent()) {
                hiddenContent.value = quill.root.innerHTML;
                // Remove data from localStorage only after successful publication
                localStorage.removeItem('articleTitle');
                localStorage.removeItem('articleContent');
            } else {
                e.preventDefault();
            }
        });

        closePopup.addEventListener('click', hidePopup);

        window.addEventListener('click', (e) => {
            if (e.target === popup) hidePopup();
        });
    </script>
</body>
</html>
<?php
include '../header & footer/header_AuthRev.php';
?>

<div class="container mx-auto p-4">
    <div class="bg-white shadow-md rounded-lg p-6 relative">
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

        <!-- Formulir Penulisan Artikel -->
        <h2 class="text-lg font-semibold mb-2">Form Penulisan Artikel</h2>
        <!-- CKEditor -->
        <div class="border border-gray-300 rounded mb-4">
            <textarea name="content" id="editor" rows="10" class="w-full p-2 border border-gray-300">Tulis artikelnya disini</textarea>
        </div>
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

<!-- Tambahkan Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<script>
    // Inisialisasi CKEditor
    CKEDITOR.replace('editor', {
        on: {
            instanceReady: function(evt) {
                const editor = evt.editor;
                const initialContent = 'Tulis artikelnya disini';

                // Set initial content
                editor.setData(initialContent);

                // Remove placeholder on focus
                editor.on('focus', function() {
                    if (editor.getData().trim() === initialContent) {
                        editor.setData('');
                    }
                });

                // Restore placeholder if empty on blur
                editor.on('blur', function() {
                    if (editor.getData().trim() === '') {
                        editor.setData(initialContent);
                    }
                });
            }
        }
    });

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
        const content = CKEDITOR.instances.editor.getData().trim();
        if (content === '' || content === 'Tulis artikelnya disini') {
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
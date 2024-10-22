<?php
include '../header & footer/header_AuthRev.php';
?>

<div class="container mx-auto p-4">
    <!-- Header dan Tombol Kembali -->
    <div class="bg-blue-500 text-white p-4 rounded-t-lg">
        <a href="#" class="text-white hover:underline"><< Kembali</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <!-- Judul Artikel -->
        <h1 class="text-xl font-bold mb-2">Judul Artikel</h1>
        <input type="text" placeholder="Tulis judul artikelmu sendiri" class="w-full border-b-2 border-gray-300 focus:outline-none mb-4">

        <!-- Formulir Penulisan Artikel -->
        <h2 class="text-lg font-semibold mb-2">Form Penulisan Artikel</h2>
        <textarea placeholder="Tulis artikelnya disini" class="w-full h-64 border-b-2 border-gray-300 focus:outline-none mb-4"></textarea>

        <!-- Tombol Pratinjau dan Publikasi -->
        <div class="flex justify-end space-x-2">
            <button id="previewButton" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Pratinjau</button>
            <button id="publishButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Publikasi</button>
        </div>
    </div>
</div>

<script>
    const previewButton = document.getElementById('previewButton');
    const publishButton = document.getElementById('publishButton');

    previewButton.addEventListener('click', () => {
        window.location.href = 'previewAuthor.php';
    });

    publishButton.addEventListener('click', () => {
        window.location.href = 'publishAuthor.php';
    });

    const menuButton = document.getElementById('menuButton');
    const menuDropdown = document.getElementById('menuDropdown');

    menuButton.addEventListener('click', () => {
        menuDropdown.classList.toggle('hidden');
    });

    window.addEventListener('click', (e) => {
        if (!menuButton.contains(e.target) && !menuDropdown.contains(e.target)) {
            menuDropdown.classList.add('hidden');
        }
    });
</script>

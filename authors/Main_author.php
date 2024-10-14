<?php
include '../header & footer/header.php';
?>

<div class="container mx-auto p-4">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Judul Artikel</h1>
            <div class="relative">
                <button id="menuButton" class="text-gray-500 hover:text-blue-500 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div id="menuDropdown" class="hidden absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg py-2">
                    <a href="/authors/previewAuthor.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Preview</a>
                    <a href="/authors/publishAuthor.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Publish</a>
                </div>
            </div>
        </div>
        <input type="text" placeholder="Tulis judul artikelmu sendiri" class="w-full border-b-2 border-gray-300 focus:outline-none mb-4">
        <textarea placeholder="Tulis artikelnya disini" class="w-full h-64 border-b-2 border-gray-300 focus:outline-none"></textarea>
    </div>
    <div class="flex flex-col items-center mt-4">
        <button class="mb-2 text-blue-500 hover:text-blue-700"><i class="fas fa-image"></i></button>
        <button class="mb-2 text-blue-500 hover:text-blue-700"><i class="fas fa-link"></i></button>
        <button class="mb-2 text-blue-500 hover:text-blue-700"><i class="fas fa-envelope"></i></button>
        <button class="text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
    </div>
</div>

<script>
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

<?php
include '../header & footer/footer.php';
?>
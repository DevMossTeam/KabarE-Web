<?php
include '../header & footer/header_AuthRev.php';
include '../header & footer/category_header.php';

renderCategoryHeader('Publikasi');
?>

<div class="container mx-auto mt-4">
    <?php for ($i = 0; $i < 6; $i++): ?>
        <div class="flex items-center justify-between p-4 mb-4">
            <div class="flex items-center">
                <img src="https://via.placeholder.com/128x64" alt="Thumbnail" class="w-32 h-16 mr-4 rounded-md">
                <div>
                    <div class="text-gray-500 text-sm">Dipublish 2 jam yang lalu</div>
                    <div class="text-black font-semibold">Ribuan Mahasiswa Baru Ikut PKKMB Polije, Ditempa Jadi Generasi Tangguh</div>
                </div>
            </div>
            <div class="relative">
                <button class="text-gray-500 hover:text-blue-500 focus:outline-none">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="hidden absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg py-2 z-30">
                    <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Unpublish</a>
                    <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Hapus</a>
                </div>
            </div>
        </div>
    <?php endfor; ?>
</div>

<script>
    document.querySelectorAll('.relative button').forEach(button => {
        button.addEventListener('click', () => {
            const menu = button.nextElementSibling;
            menu.classList.toggle('hidden');
        });
    });

    window.addEventListener('click', (e) => {
        document.querySelectorAll('.relative div').forEach(menu => {
            if (!menu.contains(e.target) && !menu.previousElementSibling.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    });
</script>
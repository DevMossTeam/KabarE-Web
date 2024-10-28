<?php
include '../header & footer/header_AuthRev.php';
include '../header & footer/category_header.php';

renderCategoryHeader('Draft');
?>

<div class="container mx-auto mt-4">
    <?php for ($i = 0; $i < 6; $i++): ?>
        <div class="flex items-center justify-between bg-white p-4 mb-2 shadow-md rounded-md">
            <div class="flex items-center">
                <img src="../assets/sample-image.jpg" alt="Thumbnail" class="w-16 h-16 mr-4 rounded-md">
                <div>
                    <div class="text-gray-500 text-sm">12 September 2024 | Diperbarui 2 jam yang lalu</div>
                    <div class="text-black font-semibold">Ribuan Mahasiswa Baru Ikut PKKMB Polije, Ditempa Jadi Generasi Tangguh</div>
                </div>
            </div>
            <div class="flex space-x-4">
                <a href="#" class="text-blue-500 hover:underline flex items-center">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                <a href="#" class="text-red-500 hover:underline flex items-center">
                    <i class="fas fa-trash-alt mr-1"></i>Hapus
                </a>
            </div>
        </div>
    <?php endfor; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
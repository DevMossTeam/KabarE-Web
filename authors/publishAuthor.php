<?php
include '../header & footer/header_AuthRev.php';
include '../header & footer/category_header.php';

renderCategoryHeader('Publikasi');
?>

<!-- Tambahkan link Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="container mx-auto mt-4 hidden" id="publicationContainer">
    <!-- Konten berita dihapus -->
</div>

<div id="emptyMessage" class="flex flex-col items-center justify-center mt-8">
    <i class="fas fa-book-open text-gray-400 text-8xl mb-4"></i>
    <p class="text-gray-500">Tidak ada konten publikasi saat ini.</p>
</div>

<!-- Popup Konfirmasi Hapus -->
<div id="popup" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg transform transition-transform scale-0">
        <div class="flex items-center mb-4">
            <i class="fas fa-exclamation-circle text-red-500 text-2xl mr-2"></i>
            <h2 class="text-xl font-bold" id="popupTitle">Konfirmasi</h2>
        </div>
        <p class="mb-4" id="popupMessage">Apakah Anda yakin ingin menghapus item ini?</p>
        <div class="flex justify-end">
            <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded mr-2">Hapus</button>
            <button id="cancelDelete" class="bg-gray-300 text-black px-4 py-2 rounded">Batal</button>
        </div>
    </div>
</div>

<script>
    let cardToDelete = null;

    document.querySelectorAll('.menu-button').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const menu = button.nextElementSibling;
            menu.classList.toggle('hidden');
        });
    });

    document.querySelectorAll('.delete-option').forEach(option => {
        option.addEventListener('click', (e) => {
            e.preventDefault();
            cardToDelete = option.closest('.flex');
            showPopup();
        });
    });

    window.addEventListener('click', () => {
        document.querySelectorAll('.menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    });

    function showPopup() {
        const popup = document.getElementById('popup');
        popup.classList.remove('hidden');
        document.querySelector('#popup div').classList.add('scale-100');
    }

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (cardToDelete) {
            cardToDelete.remove();
            checkIfEmpty();
            closePopup();
        }
    });

    document.getElementById('cancelDelete').addEventListener('click', closePopup);

    function closePopup() {
        const popup = document.getElementById('popup');
        popup.classList.add('hidden');
        document.querySelector('#popup div').classList.remove('scale-100');
    }

    function checkIfEmpty() {
        const publicationContainer = document.getElementById('publicationContainer');
        const emptyMessage = document.getElementById('emptyMessage');
        if (publicationContainer.children.length === 0) {
            publicationContainer.classList.add('hidden');
            emptyMessage.classList.remove('hidden');
        }
    }
</script>
<?php
include '../header & footer/header_AuthRev.php';
include '../header & footer/category_header.php';

renderCategoryHeader('Draft');
?>

<div class="container mx-auto mt-4" id="draftContainer">
    <?php for ($i = 0; $i < 6; $i++): ?>
        <div class="card flex items-center justify-between p-4 mb-4 relative">
            <div class="flex items-center">
                <img src="https://via.placeholder.com/128x64" alt="Thumbnail" class="w-32 h-16 mr-4 rounded-md">
                <div>
                    <div class="text-gray-500 text-sm">12 September 2024 | Diperbarui 2 jam yang lalu</div>
                    <div class="text-black font-semibold">Ribuan Mahasiswa Baru Ikut PKKMB Polije, Ditempa Jadi Generasi Tangguh</div>
                </div>
            </div>
            <div class="flex space-x-4">
                <a href="#" class="text-blue-500 hover:underline flex items-center">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                <a href="#" class="text-red-500 hover:underline flex items-center delete-card">
                    <i class="fas fa-trash-alt mr-1"></i>Hapus
                </a>
            </div>
        </div>
    <?php endfor; ?>
</div>

<div id="emptyMessage" class="hidden flex flex-col items-center justify-center mt-8">
    <i class="fas fa-book-open text-gray-400 text-8xl mb-4"></i>
    <p class="text-gray-500 mt-4">Tidak ada konten draft saat ini.</p>
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

    document.querySelectorAll('.delete-card').forEach(option => {
        option.addEventListener('click', (e) => {
            e.preventDefault();
            cardToDelete = option.closest('.card');
            showPopup();
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
        const draftContainer = document.getElementById('draftContainer');
        const emptyMessage = document.getElementById('emptyMessage');
        if (draftContainer.children.length === 0) {
            draftContainer.classList.add('hidden');
            emptyMessage.classList.remove('hidden');
        }
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
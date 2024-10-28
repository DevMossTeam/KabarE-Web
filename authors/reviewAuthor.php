<?php
include '../header & footer/header_AuthRev.php';
include '../header & footer/category_header.php';

renderCategoryHeader('Dalam Peninjauan');
?>

<div class="container mx-auto mt-4">
    <?php 
    $statuses = [
        ['status' => 'Diajukan', 'color' => 'text-blue-500', 'action' => 'Batalkan Pengajuan'],
        ['status' => 'Ditolak', 'color' => 'text-red-500', 'action' => 'Pindahkan ke draft'],
        ['status' => 'Revisi Kecil', 'color' => 'text-green-500', 'action' => 'Pindahkan ke draft'],
        ['status' => 'Revisi Besar', 'color' => 'text-yellow-500', 'action' => 'Pindahkan ke draft']
    ];

    foreach ($statuses as $status): ?>
        <div class="flex items-center justify-between bg-white p-4 mb-2 shadow-md rounded-md">
            <div class="flex items-center">
                <img src="../assets/sample-image.jpg" alt="Thumbnail" class="w-16 h-16 mr-4 rounded-md">
                <div>
                    <div class="text-gray-500 text-sm">12 September 2024 | Diperbarui 2 jam yang lalu</div>
                    <div class="text-black font-semibold">Ribuan Mahasiswa Baru Ikut PKKMB Polije, Ditempa Jadi Generasi Tangguh</div>
                </div>
            </div>
            <div class="flex flex-col items-end">
                <span class="<?= $status['color'] ?> font-semibold"><?= $status['status'] ?></span>
                <a href="#" class="text-gray-500 hover:underline"><?= $status['action'] ?></a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
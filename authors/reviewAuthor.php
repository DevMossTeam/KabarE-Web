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
        ['status' => 'Revisi Minor', 'color' => 'text-green-500', 'action' => 'Pindahkan ke draft'],
        ['status' => 'Revisi Mayor', 'color' => 'text-yellow-500', 'action' => 'Pindahkan ke draft']
    ];

    foreach ($statuses as $status): ?>
        <div class="flex items-center justify-between p-4 mb-4">
            <div class="flex items-center">
                <img src="https://via.placeholder.com/128x64" alt="Thumbnail" class="w-32 h-16 mr-4 rounded-md">
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
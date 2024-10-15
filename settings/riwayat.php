<?php include '../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex">
    <!-- Sidebar -->
    <div class="w-1/4 bg-white shadow-md p-4">
        <h2 class="text-xl font-bold mb-4">Riwayat</h2>
        <div class="flex items-center space-x-2 mb-4">
            <i class="fas fa-history text-blue-500"></i>
            <span class="text-gray-700">Riwayat KabarE</span>
        </div>
        <button class="flex items-center space-x-2 text-red-500 hover:underline">
            <i class="fas fa-trash-alt"></i>
            <span>Hapus Riwayat</span>
        </button>
    </div>

    <!-- Main Content -->
    <div class="w-3/4 bg-white shadow-md p-6 ml-4">
        <div class="flex items-center mb-4">
            <input type="text" placeholder="Cari..." class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none">
            <button class="ml-2 text-blue-500">
                <i class="fas fa-search"></i>
            </button>
        </div>
        
        <div class="space-y-6">
            <!-- Hari Ini -->
            <div>
                <h3 class="text-lg font-semibold mb-2">Hari ini - Sab, 12 Okt 2024</h3>
                <ul class="space-y-2">
                    <?php for ($i = 0; $i < 10; $i++): ?>
                    <li class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" class="form-checkbox">
                            <span class="text-gray-700">19.40 prestasi polije 2024 - pencarian KabarE</span>
                        </div>
                        <button class="text-blue-500 hover:underline">Hapus</button>
                    </li>
                    <?php endfor; ?>
                </ul>
            </div>

            <!-- Kemarin -->
            <div>
                <h3 class="text-lg font-semibold mb-2">Kemarin - Jum'at, 11 Okt 2024</h3>
                <ul class="space-y-2">
                    <?php for ($i = 0; $i < 10; $i++): ?>
                    <li class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" class="form-checkbox">
                            <span class="text-gray-700">19.40 prestasi polije 2024 - pencarian KabarE</span>
                        </div>
                        <button class="text-blue-500 hover:underline">Hapus</button>
                    </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
    </div>
</div>


<?php include '../../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex">
    <!-- Sidebar -->
    <div class="w-1/4 bg-white shadow-md p-4">
        <h2 class="text-xl font-bold mb-4">Bantuan</h2>
        <div class="flex items-center space-x-2 mb-4">
            <i class="fas fa-life-ring text-blue-500"></i>
            <span class="text-gray-700">Pusat Bantuan</span>
        </div>
        <button class="flex items-center space-x-2 text-gray-700 hover:underline">
            <i class="fas fa-phone-alt"></i>
            <span>Hubungi Kami</span>
        </button>
    </div>

    <!-- Main Content -->
    <div class="w-3/4 bg-white shadow-md p-6 ml-4">
        <h2 class="text-2xl font-bold mb-4">Pusat Bantuan</h2>
        <div class="mb-4">
            <input type="text" placeholder="Telusuri Pusat Bantuan" class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none">
        </div>
        
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-100 h-24 rounded-md"></div>
            <div class="bg-gray-100 h-24 rounded-md"></div>
            <div class="bg-gray-100 h-24 rounded-md"></div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Topik Pusat Bantuan</h3>
            <ul class="space-y-2">
                <li class="flex items-center space-x-2">
                    <i class="fas fa-flag text-blue-500"></i>
                    <span class="text-gray-700">Mulai</span>
                </li>
                <li class="flex items-center space-x-2">
                    <i class="fas fa-user-shield text-blue-500"></i>
                    <span class="text-gray-700">Akun dan Keamanan</span>
                </li>
                <!-- Tambahkan item lainnya sesuai kebutuhan -->
            </ul>
        </div>

        <div>
            <h3 class="text-lg font-semibold mb-2">Permasalahan yang sering terjadi</h3>
            <ul class="space-y-2">
                <li class="flex items-center space-x-2">
                    <i class="fas fa-exclamation-circle text-gray-500"></i>
                    <span class="text-gray-700">Akun dan Keamanan</span>
                </li>
                <!-- Tambahkan item lainnya sesuai kebutuhan -->
            </ul>
        </div>
    </div>
</div>


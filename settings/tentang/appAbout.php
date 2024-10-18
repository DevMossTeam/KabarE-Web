<?php include '../../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex">
    <!-- Sidebar -->
    <div class="w-1/4 bg-white shadow-md p-4">
        <h2 class="text-xl font-bold mb-4">Tentang</h2>
        <div class="flex items-center space-x-2 mb-4">
            <i class="fas fa-info-circle text-blue-500"></i>
            <a href="appAbout.php?page=app" class="text-gray-700 hover:underline">Aplikasi</a>
        </div>
        <a href="appLicense.php?page=license" class="flex items-center space-x-2 text-gray-700 hover:underline">
            <i class="fas fa-file-alt"></i>
            <span>License</span>
        </a>
    </div>

    <!-- Main Content -->
    <div class="w-3/4 bg-white shadow-md p-6 ml-4 text-center">
        <h2 class="text-2xl font-bold mb-4">Aplikasi</h2>
        <div class="mb-6">
            <h3 class="text-xl font-semibold">Kabar Explant</h3>
            <p class="text-gray-500">Versi 1.0</p>
        </div>
        <div class="mb-6">
            <img src="../../assets/web-icon/KabarE-UTDF.png" alt="Logo" class="mx-auto w-24 h-24">
        </div>
        <p class="text-gray-600">
            © 2024 KabarE Application<br>
            KabarE for Android is built using open source software: <a href="#" class="text-blue-500 hover:underline">License</a>
        </p>
    </div>
</div>

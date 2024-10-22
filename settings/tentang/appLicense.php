<?php include '../../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex space-x-8">
    <!-- Sidebar -->
    <div class="w-1/3 lg:w-1/4">
        <div class="bg-white p-4 rounded-lg shadow-md h-auto">
            <h3 class="text-lg font-semibold mb-2">Tentang</h3>
            <div class="flex items-center space-x-2 mb-4">
                <i class="fas fa-info-circle text-gray-500"></i>
                <a href="appAbout.php?page=app" class="text-gray-700 hover:underline">Aplikasi</a>
            </div>
            <a href="appLicense.php?page=license" class="flex items-center space-x-2 text-blue-500 hover:underline">
                <i class="fas fa-file-alt"></i>
                <span>License</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="w-2/3 lg:w-3/4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">License</h2>
            <div class="prose max-w-none">
                <h3 class="text-lg font-semibold">GNU GENERAL PUBLIC LICENSE</h3>
                <p>Version 3, 29 June 2007</p>
                <p>Copyright (C) 2007 Free Software Foundation, Inc. &lt;<a href="https://fsf.org/" class="text-blue-500 hover:underline">https://fsf.org/</a>&gt;</p>
                <p>Everyone is permitted to copy and distribute verbatim copies of this license document, but changing it is not allowed.</p>
                <h4 class="font-semibold">Preamble</h4>
                <p>The GNU General Public License is a free, copyleft license for software and other kinds of works.</p>
                <!-- Add more paragraphs as needed -->
            </div>
        </div>
    </div>
</div>

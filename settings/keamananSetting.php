<?php include '../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex">
    <!-- Sidebar -->
    <div class="w-1/4 bg-white shadow-md p-4">
        <h2 class="text-xl font-bold mb-4">Keamanan</h2>
        <div class="flex items-center space-x-2">
            <i class="fas fa-lock text-blue-500"></i>
            <span class="text-gray-700">Pengaturan</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="w-3/4 bg-white shadow-md p-6 ml-4">
        <h2 class="text-2xl font-bold mb-4">Pengaturan Keamanan</h2>
        <div class="space-y-6">
            <!-- Keamanan Akun Anda -->
            <div>
                <h3 class="text-lg font-semibold">Keamanan Akun Anda</h3>
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-envelope text-gray-500"></i>
                        <div>
                            <p class="text-gray-700">Email</p>
                            <p class="text-gray-500">E4123@polije.ac.id</p>
                        </div>
                    </div>
                    <button class="text-blue-500 hover:underline">Edit</button>
                </div>
                <p class="text-gray-500 mt-1">Informasi ini harus akurat</p>
                
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-lock text-gray-500"></i>
                        <div>
                            <p class="text-gray-700">Kata Sandi</p>
                            <p class="text-gray-500">********</p>
                        </div>
                    </div>
                    <button class="text-blue-500 hover:underline">Edit</button>
                </div>
                <p class="text-gray-500 mt-1">Informasi ini harus akurat</p>
            </div>

            <!-- Bantuan -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold">Bantuan</h3>
                <div class="flex items-center space-x-2 mt-4">
                    <i class="fas fa-question-circle text-gray-500"></i>
                    <div>
                        <p class="text-gray-700">Lupa Kata Sandi?</p>
                        <p class="text-blue-500 hover:underline">Hubungi Gwehj</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


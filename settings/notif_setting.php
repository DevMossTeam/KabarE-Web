<?php include '../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex space-x-8">
    <!-- Pembungkus Notifikasi -->
    <div class="w-1/3 lg:w-1/4">
        <div class="bg-white p-4 rounded-lg shadow-md h-auto">
            <div class="flex flex-col items-start">
                <h3 class="text-lg font-semibold mb-2">Notifikasi</h3>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-bell text-blue-500"></i>
                    <span class="text-gray-700">Pengaturan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pembungkus Pengaturan Notifikasi -->
    <div class="w-2/3 lg:w-3/4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Pengaturan Notifikasi</h2>
            <div class="space-y-6">
                <!-- Notifikasi -->
                <div>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Notifikasi</span>
                        <button class="bg-gray-200 relative inline-flex h-6 w-11 items-center rounded-full focus:outline-none" onclick="toggleSwitch(this)">
                            <span class="sr-only">Toggle</span>
                            <span class="translate-x-1 inline-block h-4 w-4 transform bg-white rounded-full transition-transform duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    <p class="text-gray-500">Dapatkan pesan suara masuk</p>
                </div>

                <!-- Berita Terbaru -->
                <div>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Berita Terbaru</span>
                        <button class="bg-blue-500 relative inline-flex h-6 w-11 items-center rounded-full focus:outline-none" onclick="toggleSwitch(this)">
                            <span class="sr-only">Toggle</span>
                            <span class="translate-x-6 inline-block h-4 w-4 transform bg-white rounded-full transition-transform duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    <p class="text-gray-500">Tampilkan notifikasi untuk terus dapatkan update terbaru</p>
                    <ul class="list-disc list-inside text-gray-500">
                        <li>Update Berita</li>
                        <li>Kategori Favorit</li>
                        <li>Rekomendasi Berita</li>
                    </ul>
                </div>

                <!-- Interaksi Pengguna -->
                <div>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Interaksi Pengguna</span>
                        <button class="bg-blue-500 relative inline-flex h-6 w-11 items-center rounded-full focus:outline-none" onclick="toggleSwitch(this)">
                            <span class="sr-only">Toggle</span>
                            <span class="translate-x-6 inline-block h-4 w-4 transform bg-white rounded-full transition-transform duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    <p class="text-gray-500">Tampilkan notifikasi interaksi antar pengguna</p>
                    <ul class="list-disc list-inside text-gray-500">
                        <li>Komentar Baru</li>
                        <li>Balasan Komentar</li>
                        <li>Menyukai Komentar</li>
                    </ul>
                </div>

                <!-- Berita Terkait -->
                <div>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Berita Terkait</span>
                        <button class="bg-blue-500 relative inline-flex h-6 w-11 items-center rounded-full focus:outline-none" onclick="toggleSwitch(this)">
                            <span class="sr-only">Toggle</span>
                            <span class="translate-x-6 inline-block h-4 w-4 transform bg-white rounded-full transition-transform duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    <p class="text-gray-500">Tampilkan notifikasi update label terpopuler</p>
                    <ul class="list-disc list-inside text-gray-500">
                        <li>Update Label Terpopuler</li>
                        <li>Update Berita yang baru dibaca</li>
                    </ul>
                </div>

                <!-- Pengingat -->
                <div>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Pengingat</span>
                        <button class="bg-blue-500 relative inline-flex h-6 w-11 items-center rounded-full focus:outline-none" onclick="toggleSwitch(this)">
                            <span class="sr-only">Toggle</span>
                            <span class="translate-x-6 inline-block h-4 w-4 transform bg-white rounded-full transition-transform duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    <p class="text-gray-500">Tampilkan notifikasi sebagai pengingat</p>
                    <ul class="list-disc list-inside text-gray-500">
                        <li>Berita yang disimpan</li>
                        <li>Batas waktu baca nanti</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSwitch(button) {
        const span = button.querySelector('span:nth-child(2)');
        const isActive = button.classList.contains('bg-blue-500');

        if (isActive) {
            button.classList.remove('bg-blue-500');
            button.classList.add('bg-gray-200');
            span.classList.remove('translate-x-6');
            span.classList.add('translate-x-1');
        } else {
            button.classList.remove('bg-gray-200');
            button.classList.add('bg-blue-500');
            span.classList.remove('translate-x-1');
            span.classList.add('translate-x-6');
        }
    }
</script>

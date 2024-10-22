<?php include '../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex space-x-8">
    <!-- Pembungkus Riwayat -->
    <div class="w-1/3 lg:w-1/4">
        <div class="bg-white p-4 rounded-lg shadow-md h-auto">
            <div class="flex flex-col items-start">
                <h3 class="text-lg font-semibold mb-2">Riwayat</h3>
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-history text-blue-500"></i>
                    <span class="text-gray-700">Riwayat KabarE</span>
                </div>
                <button class="flex items-center space-x-2 text-red-500 hover:underline" onclick="hapusRiwayatTerseleksi()">
                    <i class="fas fa-trash-alt"></i>
                    <span>Hapus Riwayat</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Pembungkus Konten Utama -->
    <div class="w-2/3 lg:w-3/4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center mb-4">
                <input type="text" placeholder="Cari..." class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none">
                <button class="ml-2 text-blue-500">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <div class="space-y-6" id="riwayatContainer">
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
                            <button class="text-blue-500 hover:underline" onclick="hapusRiwayat(this)">Hapus</button>
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
                            <button class="text-blue-500 hover:underline" onclick="hapusRiwayat(this)">Hapus</button>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function hapusRiwayatTerseleksi() {
        const riwayatContainer = document.getElementById('riwayatContainer');
        const checkboxes = riwayatContainer.querySelectorAll('input[type="checkbox"]:checked');
        
        checkboxes.forEach(checkbox => {
            const listItem = checkbox.closest('li');
            listItem.remove();
        });

        if (riwayatContainer.querySelectorAll('li').length === 0) {
            tampilkanPesanKosong();
        }
    }

    function hapusRiwayat(button) {
        const listItem = button.closest('li');
        listItem.remove();

        const riwayatContainer = document.getElementById('riwayatContainer');
        if (riwayatContainer.querySelectorAll('li').length === 0) {
            tampilkanPesanKosong();
        }
    }

    function tampilkanPesanKosong() {
        const riwayatContainer = document.getElementById('riwayatContainer');
        riwayatContainer.innerHTML = `
            <div class="flex flex-col items-center text-center mt-8">
                <i class="fas fa-history text-4xl text-gray-400"></i>
                <p class="font-bold mt-4">Anda akan menemukan histori disini</p>
                <p class="text-gray-500 mt-2">Anda dapat melihat halaman yang pernah Anda buka atau menghapusnya dari histori.</p>
            </div>
        `;
    }
</script>

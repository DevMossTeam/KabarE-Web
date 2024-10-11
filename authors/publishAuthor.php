<?php
include '../header.php';
?>

<div class="container mx-auto p-4">
    <div class="bg-white shadow-md rounded-lg p-6 flex">
        <div class="w-2/3">
            <h1 class="text-xl font-bold mb-4">Konfirmasi Publikasi</h1>
            <div class="bg-gray-200 h-64 mb-4"></div>
            <input type="text" placeholder="Preview Judul Artikel" class="w-full border-b-2 border-gray-300 focus:outline-none mb-4">
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ajukan Publikasi</button>
        </div>
        <div class="w-1/3 pl-4">
            <div class="mb-4">
                <h2 class="font-semibold">Visibilitas</h2>
                <p class="text-sm text-gray-600">Atur visibilitas artikel agar dapat dilihat oleh kelompok yang diinginkan.</p>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="visibility" value="public" class="form-radio" checked>
                        <span class="ml-2">Public</span>
                    </label>
                    <label class="inline-flex items-center ml-4">
                        <input type="radio" name="visibility" value="private" class="form-radio">
                        <span class="ml-2">Private</span>
                    </label>
                </div>
            </div>
            <div class="mb-4">
                <h2 class="font-semibold">Label</h2>
                <p class="text-sm text-gray-600">Menambahkan label untuk membantu pembaca menemukan berita artikel.</p>
                <input type="text" placeholder="Tambah label" class="w-full border-b-2 border-gray-300 focus:outline-none">
            </div>
            <div>
                <h2 class="font-semibold">Kategori</h2>
                <p class="text-sm text-gray-600">Menambah kategori untuk mempermudah pencarian artikel.</p>
                <select class="w-full border-b-2 border-gray-300 focus:outline-none mt-2">
                    <option>Politik</option>
                    <option>Ekonomi</option>
                    <option>Teknologi</option>
                    <option>Olahraga</option>
                    <option>Hiburan</option>
                    <option>Kesehatan</option>
                </select>
            </div>
        </div>
    </div>
</div>

<?php
include '../footer.php'
?>
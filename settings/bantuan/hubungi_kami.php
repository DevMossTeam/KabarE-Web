<?php include '../../header & footer/header_setting.php'; ?>

<div class="container mx-auto mt-8 flex">
    <!-- Sidebar -->
    <div class="w-1/4 bg-white shadow-md p-4">
        <h2 class="text-xl font-bold mb-4">Bantuan</h2>
        <div class="flex items-center space-x-2 mb-4">
            <i class="fas fa-life-ring text-gray-500"></i>
            <span class="text-gray-700">Pusat Bantuan</span>
        </div>
        <button class="flex items-center space-x-2 text-blue-500 hover:underline">
            <i class="fas fa-phone-alt"></i>
            <span>Hubungi Kami</span>
        </button>
    </div>

    <!-- Main Content -->
    <div class="w-3/4 bg-white shadow-md p-6 ml-4">
        <h2 class="text-2xl font-bold mb-4">Hubungi Kami</h2>
        <form>
            <textarea placeholder="Jelaskan masalah anda" class="w-full h-32 p-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 focus:outline-none">Kirim</button>
        </form>
        <p class="mt-4 text-gray-600">
            Kami akan merespon anda melalui email atau anda dapat menghubungi kami dengan mengirimkan pesan melalui email resmi kami <a href="mailto:devmoss@gmail.com" class="text-blue-500 hover:underline">devmoss@gmail.com</a>
        </p>
    </div>
</div>


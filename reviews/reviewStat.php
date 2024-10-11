<?php
include '../header.php';
?>

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Statistik Review</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold">Total Pengajuan</h2>
            <p class="text-3xl font-bold text-blue-500">20</p>
            <p class="text-gray-600">Menunggu Tindakan</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold">Pengajuan Ditolak</h2>
            <p class="text-3xl font-bold text-red-500">20</p>
            <p class="text-gray-600">Tertolak</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold">Revisi Kecil</h2>
            <p class="text-3xl font-bold text-green-500">20</p>
            <p class="text-gray-600">Membutuhkan Revisi</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold">Publikasi</h2>
            <p class="text-3xl font-bold text-yellow-500">20</p>
            <p class="text-gray-600">Terpublished</p>
        </div>
    </div>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left">Icon</th>
                <th class="py-2 px-4 text-left">Username</th>
                <th class="py-2 px-4 text-left">Posisi</th>
                <th class="py-2 px-4 text-left">Email</th>
                <th class="py-2 px-4 text-left">Tanggal Pengajuan</th>
                <th class="py-2 px-4 text-left">Judul Artikel</th>
                <th class="py-2 px-4 text-left">Reviewer</th>
                <th class="py-2 px-4 text-left">Status</th>
                <th class="py-2 px-4 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b">
                <td class="py-2 px-4"><i class="fas fa-pencil-alt"></i></td>
                <td class="py-2 px-4">ChiquitaClairinaK</td>
                <td class="py-2 px-4">Penulis</td>
                <td class="py-2 px-4">kyrevi@gmail.com</td>
                <td class="py-2 px-4">27 Januari 2025</td>
                <td class="py-2 px-4">Polije dan Perusahaan Teknologi Jepang Sepakat Kerja Sama Kembangkan Energi Terbarukan</td>
                <td class="py-2 px-4">Belum Ada</td>
                <td class="py-2 px-4"><span class="text-gray-500">•</span></td>
                <td class="py-2 px-4"><button class="text-blue-500 hover:underline">Ambil</button></td>
            </tr>
            <!-- Tambahkan baris lain sesuai kebutuhan -->
        </tbody>
    </table>
</div>

<?php
include '../footer.php';
?>
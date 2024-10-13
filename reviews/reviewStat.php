<?php
include '../header.php';
?>

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Statistik Review</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-gray-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Total Pengajuan</h2>
                <p class="text-4xl font-semibold" style="margin-bottom: 0.5rem;">20 <span class="text-sm italic" style="color: #000000; margin-top: -1rem; display: inline-block; font-size: 16px;">Menunggu Tindakan</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-red-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Pengajuan Ditolak <span class="inline-block w-2 h-2" style="background-color: #FF0000; border-radius: 50%;"></span></h2>
                <p class="text-2xl" style="margin-bottom: 0;">20 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Tertolak</span></p>
                <p class="text-2xl mt-0">2 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Kembali Diajukan</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-green-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Revisi Minor <span class="inline-block w-2 h-2" style="background-color: #32FF00; border-radius: 50%;"></span></h2>
                <p class="text-2xl" style="margin-bottom: 0;">20 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Membutuhkan Revisi</span></p>
                <p class="text-2xl mt-0">2 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Kembali Diajukan</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-yellow-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Revisi Mayor <span class="inline-block w-2 h-2" style="background-color: #FFF500; border-radius: 50%;"></span></h2>
                <p class="text-2xl" style="margin-bottom: 0;">20 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Membutuhkan Revisi</span></p>
                <p class="text-2xl mt-0">2 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Kembali Diajukan</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-blue-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Publikasi <span class="inline-block w-2 h-2" style="background-color: #4A99FF; border-radius: 50%;"></span></h2>
                <p class="text-4xl font-semibold" style="margin-bottom: 0.5rem;">20 <span class="text-sm italic" style="color: #000000; margin-top: -1rem; display: inline-block; font-size: 16px;">Terpublished</span></p>
            </div>
        </div>
    </div>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden border border-gray-300">
        <thead class="bg-gray-200 border-b border-gray-300">
            <tr>
                <th class="py-2 px-4 text-left" colspan="8">
                    <label for="filter" class="mr-2">Tampilkan</label>
                    <select id="filter" class="border border-gray-300 rounded p-1">
                        <option>All Documents</option>
                        <!-- Opsi lainnya -->
                    </select>
                </th>
                <th class="py-2 px-4 text-right">
                    <div class="relative flex items-center justify-end">
                        <input type="text" id="search" placeholder="Search" class="border border-gray-300 rounded p-1 transition-all duration-300 ease-in-out transform origin-right" style="width: 0; opacity: 0; position: absolute; right: 3rem;">
                        <button id="searchBtn" class="bg-blue-500 text-white p-2" style="border-radius: 50%; width: 40px; height: 40px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </th>
            </tr>
            <tr>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Icon</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Username</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Posisi</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Email</th>
                <th class="py-2 px-4 text-center w-56 border-r border-gray-300 bg-white">Tanggal Pengajuan</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Judul Artikel</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Reviewer</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Status</th>
                <th class="py-2 px-4 text-center w-40 bg-white">Action</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white"><i class="fas fa-arrows-alt-v"></i></td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">ChiquitaClairinaK</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">Penulis</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">kyrevi@gmail.com</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">27 Januari 2025</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Polije dan Perusahaan Teknologi Jepang Sepakat Kerja Sama Kembangkan Energi Terbarukan</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Belum Ada</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white"><span class="inline-block w-3 h-3 bg-gray-500 rounded-full"></span></td>
                <td class="py-2 px-4 text-center bg-white"><button class="text-blue-500 hover:underline"><i class="fas fa-edit mr-1"></i>Ambil</button></td>
            </tr>
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white"><i class="fas fa-arrows-alt-v"></i></td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">ChiquitaClairinaK</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">Penulis</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">kyrevi@gmail.com</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">2 Desember 2024</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Tingkatkan Kompetensi Guru, UPA Bahasa Polije Gelar Workshop dan Sertifikasi TOEIC bagi Guru SMK di Jember</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">SatiraArdhiataUno</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white"><span class="inline-block w-3 h-3 bg-green-500 rounded-full"></span></td>
                <td class="py-2 px-4 text-center bg-white"><button class="text-blue-500 hover:underline"><i class="fas fa-edit mr-1"></i>Edit</button></td>
            </tr>
            <!-- Tambahkan baris lain sesuai kebutuhan -->
        </tbody>
    </table>
</div>

<script>
    document.getElementById('searchBtn').addEventListener('click', function() {
        const searchInput = document.getElementById('search');
        if (searchInput.style.width === '0px' || searchInput.style.width === '') {
            searchInput.style.width = '200px';
            searchInput.style.opacity = '1';
        } else {
            searchInput.style.width = '0';
            searchInput.style.opacity = '0';
        }
    });

    document.getElementById('search').addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filter));
            row.style.display = match ? '' : 'none';
        });
    });
</script>

<?php
include '../footer.php';
?>
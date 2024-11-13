<?php
include '../header & footer/header_AuthRev.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

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
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Pengajuan ditolak <span class="inline-block w-2 h-2" style="background-color: #FF0000; border-radius: 50%;"></span></h2>
                <p class="text-2xl" style="margin-bottom: 0;">20 <span class="text-sm italic" style="color: #000000; font-size: 16px;">ditolak</span></p>
                <p class="text-2xl mt-0">2 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Kembali diajukan</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-green-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Revisi Minor <span class="inline-block w-2 h-2" style="background-color: #32FF00; border-radius: 50%;"></span></h2>
                <p class="text-2xl" style="margin-bottom: 0;">20 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Membutuhkan Revisi</span></p>
                <p class="text-2xl mt-0">2 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Kembali diajukan</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-yellow-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Revisi Mayor <span class="inline-block w-2 h-2" style="background-color: #FFF500; border-radius: 50%;"></span></h2>
                <p class="text-2xl" style="margin-bottom: 0;">20 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Membutuhkan Revisi</span></p>
                <p class="text-2xl mt-0">2 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Kembali diajukan</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-blue-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Publikasi <span class="inline-block w-2 h-2" style="background-color: #4A99FF; border-radius: 50%;"></span></h2>
                <p class="text-4xl font-semibold" style="margin-bottom: 0.5rem;">20 <span class="text-sm italic" style="color: #000000; margin-top: -1rem; display: inline-block; font-size: 16px;">Publikasi</span></p>
            </div>
        </div>
    </div>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden border border-gray-300">
        <thead class="bg-gray-200 border-b border-gray-300">
            <tr>
                <th class="py-2 px-4 text-left" colspan="7">
                    <label for="filter" class="mr-2">Tampilkan</label>
                    <select id="filter" class="border border-gray-300 rounded p-1">
                        <option value="all">All Documents</option>
                        <option value="Ditolak">Ditolak</option>
                        <option value="Revisi Minor">Revisi Minor</option>
                        <option value="Revisi Mayor">Revisi Mayor</option>
                        <option value="Publikasi">Publikasi</option>
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
            <tr class="bg-white">
                <th class="py-2 px-4 border border-gray-300">Username</th>
                <th class="py-2 px-4 border border-gray-300">Email</th>
                <th class="py-2 px-4 border border-gray-300">Tanggal Pengajuan</th>
                <th class="py-2 px-4 border border-gray-300">Judul Artikel</th>
                <th class="py-2 px-4 border border-gray-300">Reviewer</th>
                <th class="py-2 px-4 border border-gray-300">Status</th>
                <th class="py-2 px-4 border border-gray-300" colspan="2">Action</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr class="bg-white border-b border-gray-300">
                <td class="py-2 px-4 border border-gray-300">user1</td>
                <td class="py-2 px-4 border border-gray-300">user1@example.com</td>
                <td class="py-2 px-4 border border-gray-300">2023-10-01</td>
                <td class="py-2 px-4 border border-gray-300">Artikel 1</td>
                <td class="py-2 px-4 border border-gray-300">Reviewer 1</td>
                <td class="py-2 px-4 border border-gray-300">Ditolak</td>
                <td class="py-2 px-4 border border-gray-300 text-center" colspan="2">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 1</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 2</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 3</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="bg-white border-b border-gray-300">
                <td class="py-2 px-4 border border-gray-300">user2</td>
                <td class="py-2 px-4 border border-gray-300">user2@example.com</td>
                <td class="py-2 px-4 border border-gray-300">2023-10-02</td>
                <td class="py-2 px-4 border border-gray-300">Artikel 2</td>
                <td class="py-2 px-4 border border-gray-300">Reviewer 2</td>
                <td class="py-2 px-4 border border-gray-300">Revisi Minor</td>
                <td class="py-2 px-4 border border-gray-300 text-center" colspan="2">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 1</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 2</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 3</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="bg-white border-b border-gray-300">
                <td class="py-2 px-4 border border-gray-300">user3</td>
                <td class="py-2 px-4 border border-gray-300">user3@example.com</td>
                <td class="py-2 px-4 border border-gray-300">2023-10-03</td>
                <td class="py-2 px-4 border border-gray-300">Artikel 3</td>
                <td class="py-2 px-4 border border-gray-300">Reviewer 3</td>
                <td class="py-2 px-4 border border-gray-300">Revisi Mayor</td>
                <td class="py-2 px-4 border border-gray-300 text-center" colspan="2">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 1</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 2</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 3</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="bg-white border-b border-gray-300">
                <td class="py-2 px-4 border border-gray-300">user4</td>
                <td class="py-2 px-4 border border-gray-300">user4@example.com</td>
                <td class="py-2 px-4 border border-gray-300">2023-10-04</td>
                <td class="py-2 px-4 border border-gray-300">Artikel 4</td>
                <td class="py-2 px-4 border border-gray-300">Reviewer 4</td>
                <td class="py-2 px-4 border border-gray-300">Publikasi</td>
                <td class="py-2 px-4 border border-gray-300 text-center" colspan="2">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 1</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 2</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 3</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="bg-white border-b border-gray-300">
                <td class="py-2 px-4 border border-gray-300">user5</td>
                <td class="py-2 px-4 border border-gray-300">user5@example.com</td>
                <td class="py-2 px-4 border border-gray-300">2023-10-05</td>
                <td class="py-2 px-4 border border-gray-300">Artikel 5</td>
                <td class="py-2 px-4 border border-gray-300">Reviewer 5</td>
                <td class="py-2 px-4 border border-gray-300">Ditolak</td>
                <td class="py-2 px-4 border border-gray-300 text-center" colspan="2">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 1</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 2</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 3</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="bg-white border-b border-gray-300">
                <td class="py-2 px-4 border border-gray-300">user6</td>
                <td class="py-2 px-4 border border-gray-300">user6@example.com</td>
                <td class="py-2 px-4 border border-gray-300">2023-10-06</td>
                <td class="py-2 px-4 border border-gray-300">Artikel 6</td>
                <td class="py-2 px-4 border border-gray-300">Reviewer 6</td>
                <td class="py-2 px-4 border border-gray-300">Revisi Minor</td>
                <td class="py-2 px-4 border border-gray-300 text-center" colspan="2">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 1</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 2</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 3</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="bg-white border-b border-gray-300">
                <td class="py-2 px-4 border border-gray-300">user7</td>
                <td class="py-2 px-4 border border-gray-300">user7@example.com</td>
                <td class="py-2 px-4 border border-gray-300">2023-10-07</td>
                <td class="py-2 px-4 border border-gray-300">Artikel 7</td>
                <td class="py-2 px-4 border border-gray-300">Reviewer 7</td>
                <td class="py-2 px-4 border border-gray-300">Revisi Mayor</td>
                <td class="py-2 px-4 border border-gray-300 text-center" colspan="2">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 1</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 2</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 3</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="bg-white border-b border-gray-300">
                <td class="py-2 px-4 border border-gray-300">user8</td>
                <td class="py-2 px-4 border border-gray-300">user8@example.com</td>
                <td class="py-2 px-4 border border-gray-300">2023-10-08</td>
                <td class="py-2 px-4 border border-gray-300">Artikel 8</td>
                <td class="py-2 px-4 border border-gray-300">Reviewer 8</td>
                <td class="py-2 px-4 border border-gray-300">Publikasi</td>
                <td class="py-2 px-4 border border-gray-300 text-center" colspan="2">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 1</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 2</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 3</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="bg-white border-b border-gray-300">
                <td class="py-2 px-4 border border-gray-300">user9</td>
                <td class="py-2 px-4 border border-gray-300">user9@example.com</td>
                <td class="py-2 px-4 border border-gray-300">2023-10-09</td>
                <td class="py-2 px-4 border border-gray-300">Artikel 9</td>
                <td class="py-2 px-4 border border-gray-300">Reviewer 9</td>
                <td class="py-2 px-4 border border-gray-300">Ditolak</td>
                <td class="py-2 px-4 border border-gray-300 text-center" colspan="2">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 1</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 2</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 3</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="bg-white border-b border-gray-300">
                <td class="py-2 px-4 border border-gray-300">user10</td>
                <td class="py-2 px-4 border border-gray-300">user10@example.com</td>
                <td class="py-2 px-4 border border-gray-300">2023-10-10</td>
                <td class="py-2 px-4 border border-gray-300">Artikel 10</td>
                <td class="py-2 px-4 border border-gray-300">Reviewer 10</td>
                <td class="py-2 px-4 border border-gray-300">Revisi Minor</td>
                <td class="py-2 px-4 border border-gray-300 text-center" colspan="2">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg hidden">
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 1</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 2</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Option 3</a>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="bg-white p-3 mt-4 rounded-lg shadow-md">
        <div class="flex justify-between items-center">
            <div class="text-gray-600">
                Showing results 1-10 of 100
            </div>
            <div class="flex space-x-2">
                <button class="bg-white text-gray-700 border border-gray-300 px-3 py-1 rounded hover:bg-gray-200">Previous</button>
                <button class="bg-white text-gray-700 border border-gray-300 px-3 py-1 rounded hover:bg-gray-200">Next</button>
            </div>
        </div>
    </div>
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

    document.querySelectorAll('.fa-ellipsis-v').forEach(button => {
        button.addEventListener('click', function() {
            const menu = this.nextElementSibling;
            menu.classList.toggle('hidden');
        });
    });
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <title>Pagination Example</title>
</head>

<body class="bg-[#ECEFF5]">
    <?php
    // Include necessary components
    include '../layouts/navbar.php'; 
    include '../layouts/sidebar.php';   
    ?>
    <div class="p-4 sm:ml-64 mt-14">
        <div class="p-4 dark:border-gray-700">
            <div class="container mx-auto">
                <!-- Breadcrumb -->
                <nav class="flex mb-5" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="#" class="inline-flex items-center text-gray-700 hover:text-primary-600">
                                <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                    </path>
                                </svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <a href="#" class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2">Pengguna</a>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Table Container -->
                <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 w-6 h-6 text-black transition duration-75" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                <path
                                    d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                            </svg>
                            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl ml-4">Daftar Pengguna</h1>
                            <!-- Popover Container -->
                            <div class="relative group">
                                <button type="button" class="w-6 h-6 text-gray-400 hover:text-gray-500">
                                    <svg class="w-5 h-5 ml-2.5 mt-1 ml-1" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                    </svg>
                                    <span class="sr-only">Show inbox</span>
                                </button>

                                <!-- Popover (named 'inbox') -->
                                <div
                                    class="absolute z-10 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-opacity duration-300 text-sm text-gray-500 bg-white border border-gray-200 rounded-lg shadow-sm w-72 top-full mt-2 left-0">
                                    <div class="p-3 space-y-2">
                                        <h3 class="font-semibold text-gray-900">Kotak Masuk</h3>
                                        <p>Di dalam kotak masuk Anda, terdapat berbagai pesan terbaru, termasuk pesan
                                            Masukan, laporan berita beserta keterangan, dan laporan komentar dengan
                                            rincian
                                            terkait dari pengguna. Anda dapat menghapus komentar yang dilaporkan dan
                                            meninjau berita yang dilaporkan sebelum mengambil tindakan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="createUser" data-drawer-placement="right" onclick="resetForm()"
                            class="modal_user_toggle inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 ">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Tambah User
                        </button>
                    </div>

                    <!-- Filter Input -->
                    <div class="flex items-center justify-between mb-4">
                        <input type="text" placeholder="Search Name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-96 p-1.5"
                            id="search-input" />

                        <!-- Filter Role -->
                        <div class="relative inline-block text-left">
                            <button id="dropdownHoverButton" data-dropdown-toggle="dropdownHover"
                                data-dropdown-trigger="hover"
                                class="bg-white border focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center"
                                type="button">
                                Filter
                                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="dropdownHover"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownHoverButton">
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Semua</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">penulis</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">pembaca</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">admin</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 alert-dynamic">
                    </div>

                    <!-- Table -->
                    <div class="flex flex-col">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden shadow">
                                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                        <thead class="bg-gray-100 text-sm">
                                            <tr>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase ">
                                                    Id
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Pengguna
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Role
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="pengguna-table-body" class="text-gray-600 text-sm">
                                            <!-- Data rows will be inserted here dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Pagination -->
                    <div id="pagination" class="flex justify-between items-center mt-4">
                        <div>
                            <span id="results-info" class="text-sm text-gray-600">Showing 1-10 of 10 results</span>
                        </div>
                        <div>
                            <nav aria-label="Page navigation">
                                <ul id="pagination-links" class="flex space-x-1">
                                    <!-- Pagination links will be inserted dynamically -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main modal Form -->
    <div
        class="modal_roleUserUbahStatus hidden fixed top-0 left-0 right-0 bottom-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <!-- Modal content wrapper -->
        <form id="penggunaForm" action="#" method="POST"
            class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-4 relative">
            <!-- Modal header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900"><span class="edit_pengguna_text"></span> Form Pengguna
                </h3>
                <button type="button" class="modal_roleUserUbahStatus-close text-gray-500 hover:text-gray-900">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="py-4">
                <!-- Profile Picture Upload Section -->
                <div class="flex justify-center mb-4 relative group">
                    <div class="w-24 h-24 overflow-hidden rounded-full border-2 border-gray-300 relative group">
                        <!-- Profile Image (will show the base64 image if available, otherwise show the default) -->
                        <img id="profile-img"
                            src="https://img.freepik.com/premium-vector/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-vector-illustration_561158-3407.jpg?w=360"
                            alt="Profile Picture" class="w-full h-full object-cover">

                        <!-- Upload Text (will appear on hover) -->
                        <div
                            class="absolute inset-0 flex justify-center items-center bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Upload
                        </div>

                        <!-- File Input (hidden, visible on hover) -->
                        <input type="file" id="profile_pic" name="profile_pic"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            onchange="handleImageUpload(event)">
                    </div>

                    <!-- JavaScript to handle image upload -->
                    <script>
                        function handleImageUpload(event) {
                            const file = event.target.files[0]; // Get the uploaded file
                            const reader = new FileReader();

                            reader.onloadend = function () {
                                const profileImg = document.getElementById('profile-img');
                                profileImg.src = reader.result; // Update the <img> src with Base64 data
                                console.log("Base64 Image:", reader.result); // Log the Base64 string
                            };

                            if (file) {
                                reader.readAsDataURL(file); // Convert file to Base64
                            } else {
                                console.warn("No file selected");
                            }
                        }
                    </script>
                </div>

                <!-- Two-column layout for other fields -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Nama Pengguna -->
                    <div class="mb-4">
                        <label for="nama_pengguna" class="block mb-2 text-sm font-medium text-gray-900">Nama
                            Pengguna <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_pengguna" name="nama_pengguna"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Isi Nama Pengguna" required>
                    </div>
                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900">
                            Nama Lengkap
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Isi nama lengkap" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Isi email" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Isi password" required>
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label for="roles" class="block mb-2 text-sm font-medium text-gray-900">Role
                            <span class="text-red-500">*</span></label>
                        <select id="roles" name="role"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                            <option selected>Pilih Role</option>
                            <option value="pembaca">pembaca</option>
                            <option value="penulis">penulis</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="flex justify-end pt-4 border-t">
                <button type="button"
                    class="modal_roleUserUbahStatus-close ml-2 px-4 py-2 text-gray-700 border rounded-lg hover:bg-gray-100 mr-2">Balik</button>
                <button type="submit"
                    class="modal_roleUserUbahStatus-close px-4 py-2 text-white bg-blue-700 rounded-lg hover:bg-blue-800">Submit</button>
            </div>
        </form>
    </div>
    <!--script -->
    <script>
        let currentPage = 1;
        const itemsPerPage = 10;
        let totalItems = 0;
        let userData = [];

        fetch('http://localhost/KabarE-Web/api/user.php')
            .then(response => response.json())
            .then(data => {
                console.log(data); // Log the entire data object to check the structure
                userData = data.data || [];
                totalItems = userData.length;
                console.log(totalItems);
                console.log(userData);
                updateTable();
                updatePagination();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });

        function updateTable(filteredPenggunas = userData) {
            const tableBody = document.getElementById('pengguna-table-body');
            const paginationLinks = document.getElementById('pagination-links');
            const searchTerm = document.getElementById('search-input').value.trim(); // Get the search term

            const totalPages = Math.ceil(filteredPenggunas.length / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredPenggunas.length);

            // Filter the data based on the selected role
            let filteredData = userData;
            if (currentRole && currentRole !== 'Semua') {
                filteredData = userData.filter(pengguna => pengguna.role === currentRole);
            }

            // Count how many admin users exist in the filtered data
            const adminCount = filteredData.filter(pengguna => pengguna.role === 'admin').length;

            // Clear previous rows
            tableBody.innerHTML = '';

            // If there is no data to display
            if (filteredData.length === 0) {
                const noDataRow = document.createElement('tr');
                noDataRow.innerHTML = `
            <td colspan="5" class="py-4 px-6 text-center text-base text-gray-900">
                No data available
            </td>
        `;
                tableBody.appendChild(noDataRow);
            } else {
                // Populate rows with data for the current page
                for (let i = startIndex; i < endIndex; i++) {
                    const pengguna = filteredData[i];
                    const row = document.createElement('tr');

                    // Highlight search term in nama_pengguna and email
                    const highlightedNamaPengguna = searchTerm ? pengguna.nama_pengguna.replace(new RegExp(searchTerm,
                        'gi'), (match) => {
                        return `<span class="bg-yellow-300">${match}</span>`; // Highlight with yellow background
                    }) : pengguna.nama_pengguna;

                    const highlightedEmail = searchTerm ? pengguna.email.replace(new RegExp(searchTerm, 'gi'), (
                        match) => {
                        return `<span class="bg-yellow-300">${match}</span>`; // Highlight with yellow background
                    }) : pengguna.email;

                    row.innerHTML = `
                <td class="py-4 px-6 border-b text-left text-base text-gray-900 max-w-[100px] overflow-x-auto whitespace-nowrap">
                    ${pengguna.uid}
                </td>

                <td class="py-4 px-6 border-b">
                    <div class="flex items-center px-6 py-4 whitespace-nowrap">
                        <img id="profilePicPreview" class="w-10 h-10 rounded-full" src="data:image/jpeg;base64,${pengguna.profile_pic}">
                            <div class="text-sm font-normal text-gray-500 ml-4">
                                <div class="text-base font-semibold text-gray-900">${highlightedNamaPengguna}</div>
                                <div class="text-sm font-normal text-gray-500">${highlightedEmail}</div>
                            </div>
                    </div>
                </td>
                <td class="py-4 px-6 border-b text-base text-left font-medium text-gray-900 whitespace-nowrap">${pengguna.role}</td>                
                <td class="py-4 px-6 border-b text-center">
                    ${adminCount > 1 || pengguna.role !== 'admin' ? `
                    <button type="button" class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300" onclick="deletePengguna('${pengguna.uid}')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>                                
                    </button>` : ''}

                    <button type="button" class="modal_user_toggle inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-center text-white bg-yellow-500 rounded-lg hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-300 mt-0 sm:mt-5" onclick="getEditPengguna('${pengguna.uid}')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </td>
            `;
                    tableBody.appendChild(row);
                }
            }

            // Update results info
            const resultsInfo = document.getElementById('results-info');
            resultsInfo.textContent = `Showing ${startIndex + 1}-${endIndex} of ${filteredData.length} results`;

            // Update pagination
            updatePagination(totalPages);
        }

        const searchInput = document.getElementById('search-input');

        // Event listener for pressing Enter in the search input
        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                const searchTerm = searchInput.value.trim();
                const searchUrl =
                    `http://localhost/KabarE-Web/dashboard_admin/menu/pengguna.php?search=${searchTerm}`;
                window.history.pushState({}, '', searchUrl);

                // Filter the data based on the search term
                const filteredUser = searchTerm ?
                    userData.filter(user => user.nama_pengguna.toLowerCase().includes(searchTerm
                        .toLowerCase()) || user.email.toLowerCase().includes(searchTerm.toLowerCase())) :
                    userData;

                // Update the table with the filtered data
                updateTable(filteredUser);
                updatePagination(filteredUser);
            }
        });


        let currentRole = ''; // Variable to store the selected role

        // Get all filter items in the dropdown
        const filterItems = document.querySelectorAll('#dropdownHover a');

        filterItems.forEach(item => {
            item.addEventListener('click', function () {
                currentRole = this.textContent
                    .trim(); // Set the currentRole to the text content of the clicked item
                updateTable(); // Re-render the table with the new filtered data
            });
        });

        // Function to update pagination buttons
        function updatePagination() {
            const paginationLinks = document.getElementById('pagination-links');
            const totalPages = Math.ceil(totalItems / itemsPerPage);

            // Clear previous pagination links
            paginationLinks.innerHTML = '';

            // Add "Previous" button
            const prevButton = document.createElement('li');
            prevButton.innerHTML = `
        <a href="#" class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300" aria-label="Previous" onclick="changePage(currentPage - 1)">
            &laquo;
        </a>
    `;
            prevButton.classList.toggle('cursor-not-allowed', currentPage === 1);
            paginationLinks.appendChild(prevButton);

            // Define the maximum pages to show
            const maxPagesToShow = 3;
            let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
            let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

            // Adjust start page if end page is less than total pages
            if (endPage - startPage < maxPagesToShow - 1) {
                startPage = Math.max(1, endPage - maxPagesToShow + 1);
            }

            // Add page number buttons
            for (let i = startPage; i <= endPage; i++) {
                const pageButton = document.createElement('li');
                pageButton.innerHTML = `
            <a href="#" class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100 ${currentPage === i ? 'bg-blue-100' : ''}" onclick="changePage(${i})">${i}</a>
        `;
                paginationLinks.appendChild(pageButton);
            }

            // Add "Next" button
            const nextButton = document.createElement('li');
            nextButton.innerHTML = `
        <a href="#" class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300" aria-label="Next" onclick="changePage(currentPage + 1)">
            &raquo;
        </a>
    `;
            nextButton.classList.toggle('cursor-not-allowed', currentPage === totalPages);
            paginationLinks.appendChild(nextButton);
        }

        // Change page logic to update both the table and pagination
        function changePage(pageNumber) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);

            if (pageNumber >= 1 && pageNumber <= totalPages) {
                currentPage = pageNumber;
                updateTable(); // Update the table with new data
                updatePagination(); // Update the pagination links
            }
        }


        // Get modal elements
        const modal = document.querySelector('.modal_roleUserUbahStatus');
        const modalToggle = document.querySelector(
            '.modal_user_toggle');
        const modalCloseButtons = modal.querySelectorAll('.modal_roleUserUbahStatus-close');

        // Open modal - Ensure there is a trigger element for modal toggle
        if (modalToggle) {
            modalToggle.addEventListener('click', () => {
                document.querySelector('.edit_pengguna_text').textContent = "";
                modal.classList.remove('hidden'); // Show the modal
            });
        }

        // Close modal when clicking close buttons (SVG icon and 'Balik' button)
        modalCloseButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.classList.add('hidden'); // Hide the modal
            });
        });

        // Close modal when clicking outside the modal content
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden'); // Close the modal if clicked outside of it
            }
        });

        function deletePengguna(penggunaId) {
            console.log("delete id" + penggunaId);
            if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
                // Update the URL to match the correct API endpoint
                const apiUrl = `http://localhost/KabarE-Web/api/user.php?id=${penggunaId}`;

                fetch(apiUrl, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showAlert('success', 'Pengguna deleted successfully!');

                            // Close the dropdown if it exists
                            const dropdown = document.querySelector(`#dropdown-menu-${penggunaId}`);
                            if (dropdown) {
                                dropdown.classList.add('hidden');
                            }

                            // Remove the pengguna from the data and update table/pagination
                            userData = userData.filter(pengguna => pengguna.uid !== penggunaId);
                            totalItems = userData.length;

                            // Check if the current page is now empty after deletion
                            if (userData.length <= (currentPage - 1) * itemsPerPage) {
                                currentPage = Math.max(1, currentPage -
                                    1); // Go back to previous page if the current page is now out of range
                            }

                            // Update table and pagination after deletion
                            updateTable();
                            updatePagination();
                        } else {
                            showAlert('error', 'Failed to delete the pengguna.');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting pengguna:', error);
                        showAlert('error', 'An error occurred while deleting the pengguna.');
                    });
            }
        }

        function showAlert(type, message) {
            // Create the alert div
            const alertDiv = document.createElement('div');
            alertDiv.classList.add('flex', 'items-center', 'p-4', 'mb-4', 'rounded-lg', 'text-sm', 'font-medium');

            // Check the type of alert (success or error)
            if (type === 'success') {
                alertDiv.classList.add('bg-green-50', 'text-green-800', );
                alertDiv.innerHTML = `
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Success</span>
                    <div class="ms-3">${message}</div>
                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" onclick="closeAlert(this)">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                `;
            } else {
                alertDiv.classList.add('bg-red-50', 'text-red-800', );
                alertDiv.innerHTML = `
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Error</span>
                    <div class="ms-3">${message}</div>
                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8" onclick="closeAlert(this)">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                `;
            }

            // Append the alert to the body or a specific container
            document.querySelector('.alert-dynamic').appendChild(alertDiv);

            // Automatically remove the alert after 5 seconds
            setTimeout(() => closeAlert(alertDiv), 5000);
        }

        function closeAlert(alert) {
            alert.remove();
        }



        // Function to fetch and populate user data into the modal        
        let currentPenggunaId = null;

        function getEditPengguna(editPengguna) {
            currentPenggunaId = editPengguna;
            console.log("Stored currentPenggunaId:", currentPenggunaId);
            console.log('Editing pengguna with ID:', editPengguna);

            document.querySelector('.edit_pengguna_text').textContent = "Edit";

            // Ensure the editPengguna is not undefined or null
            if (!editPengguna) {
                console.error("No ID provided for edit!");
                showAlert('error', 'No valid ID provided for edit.');
                return; // Exit if the ID is invalid
            }

            // Log the ID before fetching to ensure it's valid
            console.log("Attempting to fetch data for ID:", editPengguna);

            fetch(`http://localhost/KabarE-Web/api/user.php?id=${editPengguna}`)
                .then(response => response.json())
                .then(responseData => {
                    console.log('API Response Data:', responseData); // Log the entire response for debugging

                    if (responseData && responseData.data) {
                        const penggunaData = responseData.data;
                        console.log("Fetched pengguna data:", penggunaData); // Log the pengguna data

                        // Set the values of the form fields
                        document.querySelector('#nama_pengguna').value = penggunaData.nama_pengguna || '';
                        document.querySelector('#email').value = penggunaData.email || '';
                        document.querySelector('#email').readOnly = true;
                        document.querySelector('#email').classList.replace('bg-gray-50', 'bg-gray-200');
                        document.querySelector('#password').value =
                            '*********'; // Clear password field for security
                        document.querySelector('#password').readOnly = true;
                        document.querySelector('#password').classList.replace('bg-gray-50', 'bg-gray-200');
                        document.querySelector('#nama_lengkap').value = penggunaData.nama_lengkap || '';

                        // Set the role
                        const roleSelect = document.querySelector('#roles');
                        if (roleSelect) {
                            roleSelect.value = penggunaData.role ||
                                ''; // Set the current role as the selected option
                        }

                        // Set the profile picture if available
                        const profileImg = document.querySelector('#profile-img');
                        if (profileImg && penggunaData.profile_pic) {
                            profileImg.src = `data:image/jpeg;base64,${penggunaData.profile_pic}` || profileImg.src;
                        }

                        // Show the modal
                        const modal = document.querySelector('.modal_roleUserUbahStatus');
                        if (modal) {
                            modal.classList.remove('hidden'); // Show modal
                        }

                    } else {
                        console.error('Pengguna data not found!');
                        showAlert('error', 'Pengguna data not found!');
                    }
                })
                .catch(error => {
                    console.error("Error fetching pengguna data:", error);
                    showAlert('error', 'Error fetching pengguna data.');
                });
        }

        // Handle form submission for role update or insert
        document.getElementById('penggunaForm').addEventListener('submit', async function (event) {
            event.preventDefault(); // Prevent the default form submission

            console.log("id update:", currentPenggunaId);

            const formData = new FormData(this);
            const nama_pengguna = formData.get('nama_pengguna');
            const nama_lengkap = formData.get('nama_lengkap');
            const email = formData.get('email');
            const password = formData.get('password');
            const role = formData.get('role');
            const profile_pic_file = document.getElementById('profile_pic').files[
            0]; // Explicitly get file input

            // Validation - Required fields
            if (!nama_pengguna || !nama_lengkap || !email || !role) {
                console.error('Some required fields are missing!');
                showAlert('error', 'Please fill all required fields!');
                return;
            }

            // Validate email format
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                console.error('Invalid email format');
                showAlert('error', 'Please enter a valid email address!');
                return;
            }

            // Validate password if provided (if it's required)
            if (!password && !currentPenggunaId) {
                console.error('Password is required');
                showAlert('error', 'Password is required!');
                return;
            }

            // Prepare request body
            const requestBody = {
                nama_pengguna,
                nama_lengkap,
                email,
                role,
            };

            // Add password for new user
            if (!currentPenggunaId && password) {
                requestBody.password = password;
            }

            // If profile picture exists, convert to Base64 and update
            if (profile_pic_file) {
                const reader = new FileReader();
                reader.onloadend = function () {
                    const base64Image = reader.result; // Base64-encoded image data
                    requestBody.profile_pic = base64Image; // Add to the request body
                    // Send the request after profile_pic is processed
                    sendRequestToBackend(requestBody);
                };
                reader.readAsDataURL(profile_pic_file); // Convert image to Base64
            } else {
                // No profile picture - directly send the request without updating image
                sendRequestToBackend(requestBody);
            }
        });

        // Function to send request
        function sendRequestToBackend(body) {
            const url = currentPenggunaId ?
                `http://localhost/KabarE-Web/api/user.php?id=${currentPenggunaId}` :
                `http://localhost/KabarE-Web/api/user.php`;

            const method = currentPenggunaId ? 'PUT' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(body),
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    showAlert('success', 'User data submitted successfully!');
                    fetchDataAndUpdateTable();
                    updateTable();
                    updatePagination();
                    // Optional: Close modal or refresh data
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Something went wrong!');
                });
        }


        // // Function to send request
        // function sendRequest(method, url, data) {
        //     const options = {
        //         method: method,
        //         headers: {
        //             'Content-Type': 'application/json', // Send JSON data
        //         },
        //         body: JSON.stringify(
        //             data), // Stringify the request body (including the profile picture if it exists)
        //     };

        //     fetch(url, options)
        //         .then(response => response.json())
        //         .then(data => {
        //             console.log("Response data:", data);
        //             // Handle the response here (success or failure)
        //         })
        //         .catch(error => {
        //             console.error("Error sending request:", error);
        //         });
        //     resetForm();
        //     updateTable();
        //     updatePagination();
        // }

        // Reset the form and ID
        function resetForm() {
            // Reset currentPenggunaId
            currentPenggunaId = null;

            // Reset the form fields
            const penggunaForm = document.getElementById('penggunaForm');
            if (penggunaForm) {
                penggunaForm.reset(); // Reset the form elements
            }

            // Reset the email input's properties
            const emailInput = document.querySelector('#email');
            if (emailInput) {
                emailInput.readOnly = false; // Make the email input editable again
                emailInput.classList.replace('bg-gray-200', 'bg-gray-50'); // Reset the background color
            }

            // Reset the password input's properties
            const passwordInput = document.querySelector('#password');
            if (passwordInput) {
                passwordInput.value = ''; // Clear password field
                passwordInput.readOnly = false; // Make it editable
                passwordInput.classList.replace('bg-gray-200', 'bg-gray-50'); // Reset the background color
            }

            // Reset the profile picture
            const profileImg = document.querySelector('#profile-img');
            if (profileImg) {
                profileImg.src =
                    'https://img.freepik.com/premium-vector/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-vector-illustration_561158-3407.jpg?w=360';
            }

            // Clear the profile picture file input
            const profilePicInput = document.querySelector('input[name="profile_pic"]');
            if (profilePicInput) {
                profilePicInput.value = ''; // Clear the file input field
            }

            // Log the reset for debugging purposes
            console.log("Form has been reset. currentPenggunaId:", currentPenggunaId);
        }

        function getEditPengguna(editPengguna) {
            // Call resetForm before fetching data
            resetForm();

            currentPenggunaId = editPengguna;
            console.log("Stored currentPenggunaId:", currentPenggunaId);
            console.log('Editing pengguna with ID:', editPengguna);

            document.querySelector('.edit_pengguna_text').textContent = "Edit";

            // Ensure the editPengguna is not undefined or null
            if (!editPengguna) {
                console.error("No ID provided for edit!");
                showAlert('error', 'No valid ID provided for edit.');
                return; // Exit if the ID is invalid
            }

            // Log the ID before fetching to ensure it's valid
            console.log("Attempting to fetch data for ID:", editPengguna);

            fetch(`http://localhost/KabarE-Web/api/user.php?id=${editPengguna}`)
                .then(response => response.json())
                .then(responseData => {
                    console.log('API Response Data:', responseData); // Log the entire response for debugging

                    if (responseData && responseData.data) {
                        const penggunaData = responseData.data;
                        console.log("Fetched pengguna data:", penggunaData); // Log the pengguna data

                        // Set the values of the form fields
                        document.querySelector('#nama_pengguna').value = penggunaData.nama_pengguna || '';
                        document.querySelector('#email').value = penggunaData.email || '';
                        document.querySelector('#email').readOnly = true;
                        document.querySelector('#email').classList.replace('bg-gray-50', 'bg-gray-200');
                        document.querySelector('#password').value =
                            '*********'; // Clear password field for security
                        document.querySelector('#password').readOnly = true;
                        document.querySelector('#password').classList.replace('bg-gray-50', 'bg-gray-200');
                        document.querySelector('#nama_lengkap').value = penggunaData.nama_lengkap || '';

                        // Set the role
                        const roleSelect = document.querySelector('#roles');
                        if (roleSelect) {
                            roleSelect.value = penggunaData.role ||
                                ''; // Set the current role as the selected option
                        }

                        // Set the profile picture if available
                        const profileImg = document.querySelector('#profile-img');
                        if (profileImg && penggunaData.profile_pic) {
                            profileImg.src = `data:image/jpeg;base64,${penggunaData.profile_pic}` || profileImg.src;
                        }

                        // Show the modal
                        const modal = document.querySelector('.modal_roleUserUbahStatus');
                        if (modal) {
                            modal.classList.remove('hidden'); // Show modal
                        }

                    } else {
                        console.error('Pengguna data not found!');
                        showAlert('error', 'Pengguna data not found!');
                    }
                })
                .catch(error => {
                    console.error("Error fetching pengguna data:", error);
                    showAlert('error', 'Error fetching pengguna data.');
                });
        }


        // Fetch data and update table and pagination
        async function fetchDataAndUpdateTable() {
            try {
                const response = await fetch('http://localhost/KabarE-Web/api/user.php');
                const data = await response.json();

                console.log(data); // Log the entire data object to check the structure
                userData = data.data || [];
                totalItems = userData.length;
                console.log(totalItems);
                console.log(userData);

                updateTable();
                updatePagination();
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }
    </script>
</body>

</html>

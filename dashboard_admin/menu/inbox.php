<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
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
            <div class="container mx-auto mt-5">
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
                                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Kotak Masuk</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Table Container -->
                <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                    <div class="mb-4 flex items-center">
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 w-6 h-6 text-black transition" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z" />
                            </svg>
                            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl ml-4">Tentang Kotak Masuk</h1>
                        </div>

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

                    <div class="flex items-center justify-between flex-wrap">
                        <!-- Checkbox section -->
                        <div class="flex items-center w-full sm:w-auto mb-4 sm:mb-0">
                            <label for="checkbox-all" class="flex items-center cursor-pointer ml-4">
                                <input id="checkbox-all" aria-describedby="checkbox-1" type="checkbox"
                                    class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-500"
                                    onchange="toggleSvgVisibility()">
                            </label>

                            <div class="ml-2 cursor-pointer flex items-center">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>

                                <svg class="w-4 h-5 ml-10 checkbox-svg" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 53 53">
                                    <path
                                        d="M26.5013 52.8307C19.1499 52.8307 12.9232 50.2797 7.82109 45.1776C2.71901 40.0755 0.167969 33.8488 0.167969 26.4974C0.167969 19.146 2.71901 12.9193 7.82109 7.81719C12.9232 2.7151 19.1499 0.164063 26.5013 0.164062C30.2867 0.164062 33.9076 0.945284 37.3638 2.50773C40.8201 4.07017 43.7825 6.30631 46.2513 9.21615V0.164062H52.8346V23.2057H29.793V16.6224H43.618C41.8624 13.5502 39.4628 11.1363 36.4191 9.38073C33.3754 7.62517 30.0695 6.7474 26.5013 6.7474C21.0152 6.7474 16.352 8.66753 12.5117 12.5078C8.67144 16.3481 6.7513 21.0113 6.7513 26.4974C6.7513 31.9835 8.67144 36.6467 12.5117 40.487C16.352 44.3273 21.0152 46.2474 26.5013 46.2474C30.7256 46.2474 34.5385 45.0404 37.9398 42.6266C41.3412 40.2127 43.7277 37.0307 45.0992 33.0807H52.0117C50.4756 38.896 47.3485 43.6415 42.6305 47.3172C37.9124 50.9929 32.536 52.8307 26.5013 52.8307Z"
                                        fill="black" />
                                </svg>

                                <svg class="w-5 h-5 ml-3 checkbox-svg" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 2 10" fill="none">
                                    <path
                                        d="M1 0.5C1 0.223858 0.776142 0 0.5 0C0.223858 0 0 0.223858 0 0.5C0 0.776142 0.223858 1 0.5 1C0.776142 1 1 0.776142 1 0.5ZM1 4.5C1 4.22386 0.776142 4 0.5 4C0.223858 4 0 4.22386 0 4.5C0 4.77614 0.223858 5 0.5 5C0.776142 5 1 4.77614 1 4.5ZM1 8.5C1 8.22386 0.776142 8 0.5 8C0.223858 8 0 8.22386 0 8.5C0 8.77614 0.223858 9 0.5 9C0.776142 9 1 8.77614 1 8.5Z"
                                        fill="black" />
                                </svg>

                                <!-- delete -->
                                <svg class="w-5 h-5 ml-10 new-checkbox-svg hidden cursor-pointer hover:bg-gray-200 active:bg-gray-300"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" onclick="deletePesan()">
                                    <path
                                        d="M14 11V17M10 11V17M6 7V19C6 19.5304 6.21071 20.0391 6.58579 20.4142C6.96086 20.7893 7.46957 21 8 21H16C16.5304 21 17.0391 20.7893 17.4142 20.4142C17.7893 20.0391 18 19.5304 18 19V7M4 7H20M7 7L9 3H15L17 7"
                                        stroke="black" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                <!-- Unread -->
                                <svg class="w-5 h-5 ml-3 new-checkbox-svg hidden cursor-pointer hover:bg-gray-200 active:bg-gray-300"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 75 75" onclick="unreadPesan()">
                                    <path
                                        d="M29.6875 4.1875L53.8875 18.4219L50.7187 23.8125L29.6875 11.4375L6.25 25.225V62.5H0V21.65L29.6875 4.1875ZM12.5 28.125H71.875V71.875H12.5V28.125ZM24.975 34.375L42.1875 46.2094L59.4 34.375H24.975ZM65.625 37.6781L42.1875 53.7938L18.75 37.6781V65.625H65.625V37.6781Z"
                                        fill="black" />
                                </svg>

                            </div>
                        </div>

                        <!-- Search input -->
                        <div class="w-full sm:w-auto">
                            <input type="text" placeholder="Search "
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full sm:w-96 p-2.5 mb-2 sm:mb-0"
                                id="search-input" />
                        </div>
                    </div>


                    <div class="mb-2 alert-dynamic"></div>


                    <div class="flex flex-col">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden shadow">
                                    <div class="flex space-x-4 ">
                                        <div>
                                            <div
                                                class="box-border h-14 w-64 p-4 border-b-4 border-blue-500 text-blue-500 hover:bg-gray-100">
                                                Utama
                                            </div>
                                        </div>
                                        <div>
                                            <div class="h-14 w-64 p-4 hover:bg-gray-100">
                                                Masukan
                                            </div>
                                        </div>
                                        <div>
                                            <div class="h-14 w-64 p-4 hover:bg-gray-100">
                                                Laporan
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                        <thead class="">
                                            <tr>
                                                <th></th>
                                                <!-- <th scope="col" class="p-4">
                                                    <div class="flex items-center">
                                                            <label for="checkbox-all" class="flex items-center cursor-pointer">
                                                                <input id="checkbox-all" aria-describedby="checkbox-1" type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-500 ">
                                                            </label>                                                      
                                                        
                                                        <div class="ml-2 cursor-pointer">
                                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                                            </svg>
                                                        </div>
                                                    </div> -->
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="pesan-table-body" class="text-gray-600 text-sm">
                                            <tr
                                                class="bg-white border-t border-b hover:border-gray-350 hover:shadow-md hover:z-10 relative">

                                            </tr>
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

    <!-- drawer component -->
    <div id="overlayInboxDrawer" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>

    <div id="drawer-right-example"
        class="fixed top-0 right-0 z-40 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white w-[600px]"
        tabindex="-1" aria-labelledby="drawer-right-label">

        <!-- Button di kiri -->
        <button type="button" id="close-drawer" aria-controls="drawer-right-example"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 left-2.5 inline-flex items-center justify-center"
            onclick="closeInboxDrawer()">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close menu</span>
        </button>

        <!-- Icon di kanan atas -->
        <svg class="absolute top-4.5 right-5 w-6 h-6 cursor-pointer hover:bg-gray-200 active:bg-gray-300"
            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            onclick="deletePesan(); closeInboxDrawer();">
            <path
                d="M14 11V17M10 11V17M6 7V19C6 19.5304 6.21071 20.0391 6.58579 20.4142C6.96086 20.7893 7.46957 21 8 21H16C16.5304 21 17.0391 20.7893 17.4142 20.4142C17.7893 20.0391 18 19.5304 18 19V7M4 7H20M7 7L9 3H15L17 7"
                stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>

        <!-- H5 di tengah -->
        <h5 id="drawer-right-label" class="flex justify-center items-center mb-4 text-base font-semibold text-gray-700">
            <svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 1 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="status_drawer"></span>
        </h5>

        <hr class="mb-5">
        <div class="flex items-center space-x-4 mb-5">
            <img class="drawer_profile w-10 h-10 rounded-full" src="" alt="">
            <div class="text-sm font-normal text-gray-500 ">
                <div class="drawer_username text-base font-semibold text-gray-900">Username</div>
                <div class="drawer_email text-sm font-normal text-gray-500 ">email@gmail.com</div>
            </div>
        </div>

        <hr class="mb-5">
        <h1 class="text-sm">Title:</h1>
        <p class="title_pesan mb-6 text-lg ">
            Title Pesan
        </p>
        <h1 class="text-sm">Detail pesan:</h1>
        <p class="detail_pesan mb-6 text-base text-gray-500">
            
        </p>
        <h1 class="text-sm">Komentar:</h1>
        <p class="detail_komentar_pesan mb-6 text-base text-blue-500">

        </p>
        <hr class="mb-5">
        <p class="mb-6 text-sm ">
            <a href="" class="drawer_href_berita text-blue-500 underline">

            </a>
        </p>
    </div>




    <script>
        let currentPage = 1;
        const itemsPerPage = 10;
        let totalItems = 0;
        let inboxData = [];

        // Fetch data from the API
        fetch('http://localhost/KabarE-Web/api/pesan.php')
            .then(response => response.json())
            .then(data => {
                inboxData = data.data || [];
                totalItems = inboxData.length;
                console.log(inboxData);
                updateTable();
                updatePagination();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });

        function updateTable(filteredPesan = inboxData) {
            const tableBody = document.getElementById('pesan-table-body');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const uniquePesanIds = new Set(); // Ensure unique pesan entries

            tableBody.innerHTML = ''; // Clear the table body

            // Filter unique pesan and slice for current page
            const paginatedPesan = filteredPesan.filter(pesan => {
                if (!uniquePesanIds.has(pesan.id)) {
                    uniquePesanIds.add(pesan.id);
                    return true;
                }
                return false;
            }).slice(startIndex, endIndex);

            if (paginatedPesan.length === 0) {
                // Display a message when no data is available
                const noDataRow = document.createElement('tr');
                noDataRow.innerHTML = `
            <td colspan="5" class="py-4 px-6 text-center text-base text-gray-900">
                No data available
            </td>
        `;
                tableBody.appendChild(noDataRow);
            } else {
                // Render rows for each pesan
                paginatedPesan.forEach(pesan => {
                    const rowBackground = pesan.status_read === 'belum' ? 'bg-white' : 'bg-[#ECEFF5]';
                    const row = document.createElement('tr');
                    row.className =
                        `${rowBackground} border-t border-b hover:border-gray-350 hover:shadow-md hover:z-10 relative cursor-pointer`;
                    row.setAttribute('data-id', pesan.id);
                    row.setAttribute('data-initial-bg', rowBackground);

                    row.onclick = function () {
                        openInboxDrawer();
                        drawerPopulatePesan(
                            pesan.id,
                            pesan.berita_id,
                            pesan.komen_id,
                            pesan.nama_pengguna,
                            pesan.profile_pic,
                            pesan.email,
                            pesan.pesan,
                            pesan.detail_pesan,
                            pesan.created_at,
                            pesan.status
                        );
                    };

                    let statusElements = '';
                    if (pesan.status === 'masukan') {
                        statusElements = `
                    <div class="px-1 py-1 bg-blue-400 text-white rounded-lg text-center mb-2">
                        Masukan
                    </div>
                `;
                    } else if (pesan.status === 'laporan') {
                        statusElements = `
                    <div class="px-1 py-1 bg-red-400 text-white rounded-lg text-center mb-2">
                        Laporan
                    </div>
                `;
                    }

                    row.innerHTML = `
                <td class="w-4 p-4">
                    <div class="flex items-center">
                        <input id="${pesan.id}" aria-describedby="checkbox-${pesan.id}" type="checkbox" 
                            class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                            onclick="handleCheckboxClick(event, '${pesan.id}', this)">
                        <label for="checkbox-${pesan.id}" class="sr-only">checkbox</label>
                    </div>
                </td>
                <td class="px-4 py-1 w-20 whitespace-nowrap">
                    ${statusElements}
                </td>
                <td class="flex items-center px-6 py-4 whitespace-nowrap">
                    <img id="profilePicPreview" class="w-10 h-10 rounded-full" src="data:image/jpeg;base64,${pesan.profile_pic}">
                    <div class="text-sm font-normal text-gray-500 ml-4">
                        <div class="text-base font-semibold text-gray-900">${pesan.nama_pengguna}</div>
                        <div class="text-sm font-normal text-gray-500">${pesan.email}</div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap overflow-hidden max-w-[70ch] truncate">
                    ${pesan.pesan}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${pesan.created_at}</td>
            `;
                    tableBody.appendChild(row);
                });
            }

            // Update the results display information
            const resultsInfo = document.getElementById('results-info');
            const totalUnique = uniquePesanIds.size;
            const startIndexDisplay = Math.min(startIndex + 1, totalUnique);
            const endIndexDisplay = Math.min(startIndex + itemsPerPage, totalUnique);

            resultsInfo.textContent =
                `Menampilkan ${startIndexDisplay}-${endIndexDisplay} dari ${totalUnique} hasil yang unik`;
        }


        function updatePagination(filteredPesan = inboxData) {
            const paginationLinks = document.getElementById('pagination-links');
            const totalPages = Math.ceil(filteredPesan.length / itemsPerPage);

            paginationLinks.innerHTML = ''; // Clear previous pagination links

            // Add "Previous" button
            const prevButton = document.createElement('li');
            prevButton.innerHTML = `
        <a href="#" class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300" aria-label="Previous" onclick="changePage(currentPage - 1)">
            &laquo;
        </a>
    `;
            prevButton.classList.toggle('cursor-not-allowed', currentPage === 1);
            paginationLinks.appendChild(prevButton);

            // Define the range of pages to display
            const maxPagesToShow = 3;
            let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
            let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

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
            const totalPages = Math.ceil(totalItems / itemsPerPage); // Calculate total pages dynamically

            if (pageNumber >= 1 && pageNumber <= totalPages) {
                currentPage = pageNumber; // Update the current page
                updateTable(); 
                updatePagination(); 
            }
        }

        const overlayInboxDrawer = document.getElementById('overlayInboxDrawer');
        const drawer = document.getElementById('drawer-right-example');

        function openInboxDrawer() {
            drawer.classList.remove('translate-x-full');
            overlayInboxDrawer.classList.remove('hidden');
        }

        function closeInboxDrawer() {
            drawer.classList.add('translate-x-full');
            overlayInboxDrawer.classList.add('hidden');
        }

        overlayInboxDrawer.addEventListener('click', closeInboxDrawer);



        document.getElementById('search-input').addEventListener('input', function () {
            const query = this.value.toLowerCase();
            filterTags(query);
        });

        function filterTags(query) {
            const filteredTags = pesansData.filter(pesan => pesan.nama_pesan.toLowerCase().includes(query));
            totalItems = filteredTags.length;
            updateTable(filteredTags);
            updatePagination();
        }

        function toggleSvgVisibility() {
            const checkbox = document.getElementById('checkbox-all');
            const currentSvgs = document.querySelectorAll('.checkbox-svg');
            const newSvgs = document.querySelectorAll('.new-checkbox-svg');

            // Loop through all the SVGs with the class 'checkbox-svg' and 'new-checkbox-svg'
            currentSvgs.forEach(svg => {
                svg.style.display = checkbox.checked ? 'none' : 'block';
            });

            newSvgs.forEach(svg => {
                svg.style.display = checkbox.checked ? 'block' : 'none';
            });

            const checkboxAll = document.getElementById('checkbox-all');
            const checkboxes = document.querySelectorAll('#pesan-table-body input[type="checkbox"]');
            const isChecked = checkboxAll.checked;

            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        }

        // Function to handle checkbox clicks
        function handleCheckboxClick(event, id, checkbox) {
            event.stopPropagation();

            const row = checkbox.closest('tr');
            const isChecked = checkbox.checked;

            if (isChecked) {
                row.classList.add('bg-blue-200');
                row.classList.remove(row.getAttribute('data-initial-bg'));
            } else {
                row.classList.remove('bg-blue-200');
                row.classList.add(row.getAttribute('data-initial-bg'));
            }

            toggleSvgVisibilityId(id);
        }

        let selectedData = [];

        function toggleSvgVisibilityId(id) {
            const checkbox = document.getElementById(id);
            const currentSvgs = document.querySelectorAll('.checkbox-svg');
            const newSvgs = document.querySelectorAll('.new-checkbox-svg');

            if (checkbox) {
                if (checkbox.checked) {
                    if (!selectedData.includes(id)) {
                        selectedData.push(id);
                    }

                    currentSvgs.forEach(svg => {
                        svg.style.display = 'none';
                    });

                    newSvgs.forEach(svg => {
                        svg.style.display = 'block';
                    });
                } else {
                    const index = selectedData.indexOf(id);
                    if (index > -1) {
                        selectedData.splice(index, 1);
                    }

                    currentSvgs.forEach(svg => {
                        svg.style.display = 'block';
                    });

                    newSvgs.forEach(svg => {
                        svg.style.display = 'none';
                    });
                }
            }
            console.log('Selected Data:', selectedData);
        }

        function deletePesan() {
            if (Array.isArray(selectedData) && selectedData.length > 0) {
                const deleteButton = document.getElementById('deleteButton');
                if (deleteButton) {
                    deleteButton.disabled = true;
                }

                selectedData.forEach(idPesan => {
                    fetch(`http://localhost/KabarE-Web/api/pesan.php?id=${idPesan}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(`Success: Message with ID ${idPesan} deleted.`);

                            const row = document.querySelector(`tr[data-id="${idPesan}"]`);
                            if (row) {
                                row.remove();
                            }
                        })
                        .catch(error => {
                            console.error(`Error deleting message with ID ${idPesan}:`, error);
                        });
                });

                selectedData = [];

                updateTable();
                updatePagination();

                if (deleteButton) {
                    deleteButton.disabled = false;
                }

            } else {
                console.error('No valid IDs selected for deletion.');
            }
        }

        function unreadPesan() {
            if (Array.isArray(selectedData) && selectedData.length > 0) {
                const deleteButton = document.getElementById('deleteButton');
                if (deleteButton) {
                    deleteButton.disabled = true;
                }

                selectedData.forEach(idPesan => {
                    fetch(`http://localhost/KabarE-Web/api/pesan.php?id=${idPesan}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                status_read: "belum"
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(`Success: Message with ID ${idPesan} updated.`);
                            fetchNewData();
                            updateTable();
                        })
                        .catch(error => {
                            console.error(`Error updating message with ID ${idPesan}:`, error);
                        });
                });

                selectedData = [];

                updateTable();
                updatePagination();

                if (deleteButton) {
                    deleteButton.disabled = false;
                }

            } else {
                console.error('No valid IDs selected for update.');
            }
        }


        let idPesan = null;
        let pesanUser = null;
        let titlePesan = null;
        let detailPesan = null;
        let tglPesan = null;
        let jenisPesan = null;
        let profile = null;
        let email = null;

        function drawerPopulatePesan(
            idPesan,
            beritaId,
            komen_id,
            username,
            profileParam,
            emailParam,
            titlePesanParam,
            detailPesan,
            tglPesanParam,
            jenisPesanParam
        ) {
            // Clear the data first
            let pesanUser = null;
            let titlePesan = null;
            let tglPesan = null;
            let jenisPesan = null;
            let profile = null;
            let email = null;

            // Now populate with the new data
            pesanUser = username;
            profile = profileParam;
            email = emailParam;
            titlePesan = titlePesanParam;
            tglPesan = tglPesanParam;
            jenisPesan = null;
            if (jenisPesanParam == "masukan") {
                jenisPesan = "Masukan"
                document.querySelector('.title_pesan').textContent = "";
                document.querySelector('.drawer_href_berita').textContent = "";
            } else {
                fetchBeritaId(beritaId);
                fetchKomenId(komen_id);
                jenisPesan = "Laporan"
                document.querySelector('.title_pesan').textContent = titlePesan;
                document.querySelector('.drawer_href_berita').href =
                    'http://localhost/KabarE-Web/category/news-detail.php?=' + beritaId;

                if (!komen_id) {
                    document.querySelector('.detail_komentar_pesan').textContent = "Link Berita";
                }

                document.querySelector('.drawer_href_berita').textContent = "Link ke Berita";
            }
            selectedData = [];
            selectedData = [idPesan],

                document.querySelector('.detail_pesan').textContent = detailPesan;
            document.querySelector('.status_drawer').textContent = jenisPesan;
            document.querySelector('.drawer_username').textContent = pesanUser;
            document.querySelector('.drawer_email').textContent = email;
            document.querySelector('.drawer_profile').src = 'data:image/jpeg;base64,' + profileParam;



            // Update the database with the status
            fetch(`http://localhost/KabarE-Web/api/pesan.php?id=${idPesan}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status_read: "sudah"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Success:", data);

                    // Refetch the data after the PUT request
                    fetch(
                            'http://localhost/KabarE-Web/api/pesan.php'
                            ) // Make sure this API gives the latest messages
                        .then(response => response.json())
                        .then(fetchedData => {
                            fetchNewData();
                            updateTable(); // Call updateTable to refresh the table
                        })
                        .catch(console.error);
                })
                .catch(console.error);

            // testing
            console.log('data that being populate:');
            console.log(`
            idPesan: ${selectedData}
            beritaId: ${beritaId}
            komen_id: ${komen_id}
            username: ${username}
            profileParam: ${profileParam}
            emailParam: ${emailParam}
            titlePesanParam: ${titlePesanParam}
            detailPesan: ${detailPesan}
            tglPesanParam: ${tglPesanParam}
            jenisPesanParam: ${jenisPesanParam}
            `);
        }

        function fetchNewData() {
            fetch('http://localhost/KabarE-Web/api/pesan.php')
                .then(response => response.json())
                .then(data => {
                    inboxData = data.data || [];
                    totalItems = inboxData.length;
                    console.log(inboxData);
                    updateTable();
                    updatePagination();
                    // Return some data or status if needed after fetching
                    return inboxData; // or another meaningful value based on your use case
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    // You can also return an empty array or error message on failure
                    return []; // Return an empty array in case of error
                });
        }

        function fetchBeritaId(berita_id) {
            console.log("fetch Berita ID: " + berita_id)
            fetch(
                `http://localhost/KabarE-Web/api/berita.php?berita_id=${berita_id}`) // Pass berita_id as a query parameter
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const inboxData = data.data || []; // Use berita_id filtered data
                    // console.log(inboxData);

                    // Update your UI or perform additional actions
                    updateTable(); // Pass filtered data to updateTable
                    updatePagination(); // Adjust pagination for filtered data
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }


        function fetchKomenId(komen_id) {
            console.log("fetch komen_id: " + komen_id);
            fetch(`http://localhost/KabarE-Web/api/komentar.php?berita_id=${komen_id}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data && data.teks_komentar) {
                        // Display the fetched comment text
                        document.querySelector('.detail_komentar_pesan').textContent = data.teks_komentar;
                    } else {
                        // Handle case where 'teks_komentar' is not available
                        document.querySelector('.detail_komentar_pesan').textContent = "";
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    // Handle the error case and inform the user
                    document.querySelector('.detail_komentar_pesan').textContent = "Error fetching comment.";
                });
        }
    </script>
</body>

</html>
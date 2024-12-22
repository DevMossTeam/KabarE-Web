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
                                <a href="#" class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2">Berita</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Tag</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Table Container -->
                <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                    <div class="mb-4 flex items-center">
                        <svg class="flex-shrink-0 w-6 h-6 text-black transition duration-75" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 32 32">
                            <path
                                d="M7.33366 9.33268C6.80323 9.33268 6.29452 9.12197 5.91944 8.7469C5.54437 8.37182 5.33366 7.86311 5.33366 7.33268C5.33366 6.80225 5.54437 6.29354 5.91944 5.91847C6.29452 5.5434 6.80323 5.33268 7.33366 5.33268C7.86409 5.33268 8.3728 5.5434 8.74787 5.91847C9.12295 6.29354 9.33366 6.80225 9.33366 7.33268C9.33366 7.86311 9.12295 8.37182 8.74787 8.7469C8.3728 9.12197 7.86409 9.33268 7.33366 9.33268ZM28.547 15.4393L16.547 3.43935C16.067 2.95935 15.4003 2.66602 14.667 2.66602H5.33366C3.85366 2.66602 2.66699 3.85268 2.66699 5.33268V14.666C2.66699 15.3993 2.96033 16.066 3.45366 16.546L15.4403 28.546C15.9337 29.026 16.6003 29.3327 17.3337 29.3327C18.067 29.3327 18.7337 29.026 19.2137 28.546L28.547 19.2127C29.0403 18.7327 29.3337 18.066 29.3337 17.3327C29.3337 16.586 29.027 15.9193 28.547 15.4393Z"
                                fill="currentColor" />
                        </svg>
                        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl ml-4">Daftar Tag</h1>
                    </div>

                    <!-- Filter Input -->
                    <div class="flex items-center justify-between mb-4 flex-wrap">
                        <!-- Search Input -->
                        <input type="text" placeholder="Search Name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full sm:w-96 p-2.5 mb-2 sm:mb-0"
                            id="search-input" />
                    </div>

                    <div class="mb-4 alert-dynamic">
                    </div>

                    <!-- Table -->
                    <div class="flex flex-col">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden shadow">
                                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                        <thead class="bg-gray-100 text-gray-600 uppercase text-sm">
                                            <tr>
                                                <th class="py-2 px-4 border-b">No</th>
                                                <th class="py-2 px-4 border-b">Nama Tag</th>
                                                <th class="py-2 px-4 border-b">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tag-table-body" class="text-gray-600 text-sm">
                                            <!-- Data rows will be inserted here dynamically -->
                                        </tbody>
                                    </table>
                                </div> <!-- Closing for overflow-hidden shadow -->
                            </div> <!-- Closing for inline-block min-w-full align-middle -->
                        </div> <!-- Closing for overflow-x-auto -->
                    </div> <!-- Closing for flex flex-col -->

                    <!-- <table class="min-w-full bg-white">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-sm">
                            <tr>
                                <th class="py-2 px-4 border-b">ID</th>
                                <th class="py-2 px-4 border-b">Nama Tag</th>
                                <th class="py-2 px-4 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tag-table-body" class="text-gray-600 text-sm">
                            
                        </tbody>
                    </table> -->

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


    <!-- Add Tag Drawer -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>
    <div id="drawer-create-product-default"
        class="fixed top-0 right-0 z-40 w-full h-screen max-w-xs p-4 overflow-y-auto transition-transform transform translate-x-full bg-white"
        tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
        <!-- Modal Header -->
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-lg font-semibold">Add New Tag</h3>
            <button class="close-drawer-btn text-gray-500 hover:text-gray-900">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        <!-- <button type="button" class="close-drawer-btn text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close menu</span>
                </button> -->
        <form id="tagForm" action="#">
            <div class="space-y-4">
                <input type="hidden" id="editTagId" name="editTagId">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Tag</label>
                    <input type="text" name="nama_tag" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Nama Tag" required>
                </div>

                <div class="flex justify-end pt-4 space-x-2">
                    <button type="submit"
                        class="text-white w-full justify-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Simpan
                    </button>
                    <!-- Cancel Button -->
                    <button type="button"
                        class="close-drawer-btn inline-flex w-full justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        <svg aria-hidden="true" class="w-5 h-5 -ml-1 sm:mr-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
    </div>

    <!--script -->
    <script>
        let currentPage = 1;
        const itemsPerPage = 10;
        let tagsData = [];
        let uniqueTags = [];

        // Fetch data from the API
        fetch('../../api/tag.php')
            .then(response => response.json())
            .then(data => {
                tagsData = data.data || [];
                uniqueTags = getUniqueTags(tagsData);
                updateTable();
                updatePagination();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });

        // Function to get unique tags based on `nama_tag`
        function getUniqueTags(data) {
            const uniqueNames = new Set();
            return data.filter(tag => {
                if (!uniqueNames.has(tag.nama_tag)) {
                    uniqueNames.add(tag.nama_tag);
                    return true;
                }
                return false;
            });
        }

        function updateTable(filteredTags = tagsData) {
            const tableBody = document.getElementById('tag-table-body');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            tableBody.innerHTML = '';

            // Slice tags for the current page
            const paginatedTags = filteredTags.slice(startIndex, endIndex);

            if (paginatedTags.length === 0) {
                const noDataRow = document.createElement('tr');
                noDataRow.innerHTML = `
            <td colspan="5" class="py-4 px-6 text-center text-base text-gray-900">
                No data available
            </td>
        `;
                tableBody.appendChild(noDataRow);
            } else {
                paginatedTags.forEach((tag, index) => {
                    const row = document.createElement('tr');
                    const rowNumber = startIndex + index + 1;

                    // Highlight search term in the tag name
                    let highlightedTagName = tag.nama_tag;
                    if (searchTerm) {
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        highlightedTagName = tag.nama_tag.replace(
                            regex,
                            `<span class="bg-yellow-200">$1</span>`
                        );
                    }

                    row.innerHTML = `
                <td class="py-4 px-6 border-b text-center">KT${rowNumber}</td>
                <td class="py-4 px-6 border-b">${highlightedTagName}</td>
                <td class="py-4 px-6 border-b text-center">
                    <button type="button" data-modal-target="delete-user-modal" data-modal-toggle="delete-user-modal" class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 mr-2" onclick="deleteTag('${tag.nama_tag}')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </td>
            `;
                    tableBody.appendChild(row);
                });
            }

            const resultsInfo = document.getElementById('results-info');
            resultsInfo.textContent =
                `Showing ${Math.min(startIndex + 1, filteredTags.length)}-${Math.min(endIndex, filteredTags.length)} of ${filteredTags.length} unique results`;
        }


        function updatePagination(filteredTags = uniqueTags) {
            const paginationLinks = document.getElementById('pagination-links');
            const totalPages = Math.ceil(filteredTags.length / itemsPerPage);

            paginationLinks.innerHTML = '';

            const prevButton = document.createElement('li');
            prevButton.innerHTML = `
        <a href="#" class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300" aria-label="Previous" onclick="changePage(currentPage - 1)">
            &laquo;
        </a>
    `;
            prevButton.classList.toggle('cursor-not-allowed', currentPage === 1);
            paginationLinks.appendChild(prevButton);

            const maxPagesToShow = 3;
            let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
            let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

            if (endPage - startPage < maxPagesToShow - 1) {
                startPage = Math.max(1, endPage - maxPagesToShow + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageButton = document.createElement('li');
                pageButton.innerHTML = `
            <a href="#" class="px-3 py-1 border border-gray-300 rounded  ${currentPage === i ? 'bg-blue-100 text-blue-800 hover:bg-blue-300' : 'text-gray-700 bg-white hover:bg-gray-300'}" onclick="changePage(${i})">${i}</a>
        `;
                paginationLinks.appendChild(pageButton);
            }

            const nextButton = document.createElement('li');
            nextButton.innerHTML = `
        <a href="#" class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300" aria-label="Next" onclick="changePage(currentPage + 1)">
            &raquo;
        </a>
    `;
            nextButton.classList.toggle('cursor-not-allowed', currentPage === totalPages);
            paginationLinks.appendChild(nextButton);
        }

        function changePage(pageNumber) {
            const totalPages = Math.ceil(uniqueTags.length / itemsPerPage);

            if (pageNumber >= 1 && pageNumber <= totalPages) {
                currentPage = pageNumber;
                updateTable();
                updatePagination();
            }
        }

        // Input element event listener
        const searchInput = document.getElementById('search-input');
        let searchTerm = '';

        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                searchTerm = searchInput.value.trim();
                const searchUrl =
                    `http://localhost/KabarE-Web/dashboard_admin/menu/tag.php?search=${searchTerm}`;
                window.history.pushState({}, '', searchUrl);

                // Filter the tags based on the search term
                const filteredTags = searchTerm ?
                    tagsData.filter(tag =>
                        tag.nama_tag.toLowerCase().includes(searchTerm.toLowerCase())
                    ) :
                    tagsData;

                // Reset currentPage to 1 to show results from the first page
                currentPage = 1;

                // Update the table and pagination with the filtered data
                updateTable(filteredTags);
                updatePagination(filteredTags);
            }
        });

        function filterTags(query) {
            const filteredTags = tagsData.filter(tag => tag.nama_tag.toLowerCase().includes(query));
            totalItems = filteredTags.length;
            updateTable(filteredTags);
            updatePagination();
        }

        function deleteTag(tagName) {
            if (confirm('Apa kamu ingin menghapus tag ini?')) {
                // Ensure that the tag name is URL encoded to handle spaces and special characters
                const encodedTagName = encodeURIComponent(tagName);

                fetch(`http://localhost/KabarE-Web/api/tag.php?name=${encodedTagName}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        // No body required for the DELETE request, name is in the URL
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message === `Tags with the name "${tagName}" deleted successfully`) {
                            showAlert('success', 'Tag deleted successfully!');
                            // Close the dropdown
                            const dropdown = document.querySelector(`#dropdown-menu-${tagName}`);
                            if (dropdown) {
                                dropdown.classList.add('hidden');
                            }
                            // Remove the tag from the data and update table/pagination
                            tagsData = tagsData.filter(tag => tag.nama_tag !== tagName);
                            totalItems = tagsData.length;

                            // Check if the current page is now empty after deletion
                            if (tagsData.length <= (currentPage - 1) * itemsPerPage) {
                                currentPage = Math.max(1, currentPage -
                                    1); // Go back to previous page if the current page is now out of range
                            }

                            updateTable();
                            updatePagination();
                        } else {
                            showAlert('error', 'Failed to delete the tag.');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting tag:', error);
                        showAlert('error', 'An error occurred while deleting the tag.');
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

        // Function to handle dropdown actions
        function toggleDropdown(event, index, tagName) {
            const dropdown = document.getElementById(`dropdown-menu-${index}`);
            const isVisible = !dropdown.classList.contains('hidden');

            // Hide all dropdowns first
            const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
            allDropdowns.forEach(drop => drop.classList.add('hidden'));

            // If the dropdown wasn't visible, show it
            if (!isVisible) {
                dropdown.classList.remove('hidden');
            }

            // Add event listener to close the dropdown when clicking outside
            document.addEventListener('click', function closeDropdown(event) {
                // Check if the click is outside the dropdown and button
                if (!dropdown.contains(event.target) && !document.getElementById(`menu-button-${index}`)
                    .contains(event.target)) {
                    dropdown.classList.add('hidden');
                    document.removeEventListener('click',
                        closeDropdown); // Remove the event listener after closing
                }
            });
        }

        // document.getElementById('tagForm').addEventListener('submit', async function (event) {
        //     event.preventDefault(); // Prevent the default form submission

        //     const formData = new FormData(this);
        //     const nama_tag = formData.get('nama_tag'); // Get the 'nama_tag' input value

        //     // Check if we are in edit mode by looking for a data attribute on the form
        //     const tagId = this.getAttribute('data-edit-id');
        //     const url = tagId ?
        //         `http://localhost/KabarE-Web/api/tag.php?id=${tagId}` // Update URL if in edit mode
        //         :
        //         'http://localhost/KabarE-Web/api/tag.php'; // Create URL if creating new tag

        //     const method = tagId ? 'PUT' : 'POST'; // Use PUT for updates, POST for creation

        //     try {
        //         console.log('Form Data to Submit:', {
        //             nama_tag,
        //             tagId
        //         }); // Log the data for debugging

        //         // Make the fetch request with dynamic method and URL
        //         const response = await fetch(url, {
        //             method: method,
        //             headers: {
        //                 'Content-Type': 'application/json',
        //             },
        //             body: JSON.stringify({
        //                 nama_tag
        //             }),
        //         });

        //         if (response.ok) {
        //             showAlert('success', `Data ${tagId ? 'updated' : 'created'} successfully.`);
        //             resetFormAndCloseDrawer(); // Reset the form and close drawer after success

        //             // Fetch new data after form submission
        //             updateTable();
        //             updatePagination();
        //         } else {
        //             const errorData = await response.json();
        //             console.error(`Failed to ${tagId ? 'update' : 'create'} tag: ${errorData.message}`);
        //             showAlert('error',
        //                 `Failed to ${tagId ? 'update' : 'create'} tag: ${errorData.message}`);
        //         }
        //     } catch (error) {
        //         console.error("Error submitting form:", error);
        //         showAlert('error', `An error occurred: ${error.message || "Unknown error"}`);
        //     }
        // });


        // Helper function to reset the form and close the drawer
        function resetFormAndCloseDrawer() {
            const drawer = document.getElementById('drawer-create-product-default');
            const overlay = document.getElementById('overlay');

            // Clear the form input and reset the button text
            const form = document.getElementById('tagForm');
            form.reset(); // Resets all form inputs to their default values

            // Explicitly remove the data-edit-id if present
            form.removeAttribute('data-edit-id');

            // Reset the button text
            document.querySelector('#tagForm button[type="submit"]').textContent = 'Create';

            // Hide drawer and overlay
            drawer.classList.add('translate-x-full');
            overlay.classList.add('hidden');
            overlay.classList.remove('block');
            drawer.setAttribute('aria-hidden', 'true');
        }


        // function getEditTag(editTag) {
        //     console.log('Editing tag with ID:', editTag);
        //     fetch(`http://localhost/KabarE-Web/api/tag.php?id=${editTag}`)
        //         .then(response => response.json())
        //         .then(responseData => {
        //             console.log('API Response Data:', responseData); // Log the whole response to inspect

        //             // Check if responseData contains 'data' and 'data.nama_tag'
        //             if (responseData && responseData.data && responseData.data.nama_tag) {
        //                 const tagData = responseData.data; // Access the 'data' object
        //                 document.getElementById('name').value = tagData.nama_tag; // Set input value with 'nama_tag'
        //                 document.getElementById('tagForm').setAttribute('data-edit-id', editTag);

        //                 const submitButton = document.querySelector('#tagForm button[type="submit"]');
        //                 if (submitButton) {
        //                     submitButton.textContent = 'Update'; // Change button to 'Update' for editing
        //                 }

        //                 document.querySelector('.openDrawerBtn').click(); // Open the drawer after fetching
        //             } else {
        //                 showAlert('error', 'Tag data not found!');
        //             }
        //         })
        //         .catch(error => {
        //             console.error("Error fetching tag data:", error);
        //             showAlert('error', 'Error fetching tag data.');
        //         });
        // }

        function refectch() {
            fetch('http://localhost/KabarE-Web/api/tag.php')
                .then(response => response.json())
                .then(data => {
                    tagsData = data.data || [];
                    totalItems = tagsData.length;
                    updateTable();
                    updatePagination();
                    console.log(tagsData);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }
    </script>
</body>

</html>
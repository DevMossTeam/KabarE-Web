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
                                        <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                        </svg>
                                        Home
                                    </a>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <a href="#" class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2">Pengguna</a>
                                    </div>
                                </li>                              
                            </ol>
                </nav>

                <!-- Table Container -->
                <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                    <div class="mb-4">
                        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">Daftar Pengguna</h1>
                    </div>

                    <!-- Filter Input -->
                        <div class="flex items-center justify-between mb-4">
                            <input 
                                type="text" 
                                placeholder="Search Name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-96 p-2.5" 
                                id="search-input"
                            />                          

                            <!-- Main modal -->
                            <div class="modal_roleUserUbahStatus hidden fixed top-0 left-0 right-0 bottom-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
                                <!-- Modal content wrapper -->
                                <form id="roleForm" action="#" method="POST" class="bg-white rounded-lg shadow-lg max-w-lg w-full p-4 relative">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between pb-4 border-b">
                                        <h3 class="text-lg font-semibold text-gray-900">Ubah Status Pengguna</h3>
                                        <button type="button" class="modal_roleUserUbahStatus-close text-gray-500 hover:text-gray-900">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="py-4">            
                                        <div>
                                            <label for="roles" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                                            <select id="roles" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                <option selected>Pilih Role</option>
                                                <option value="pembaca">pembaca</option>
                                                <option value="penulis">penulis</option>
                                                <option value="admin">admin</option>                
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="flex justify-end pt-4 border-t">
                                        <button type="button" class="modal_roleUserUbahStatus-close ml-2 px-4 py-2 text-gray-700 border rounded-lg hover:bg-gray-100 mr-2">Balik</button>
                                        <button type="submit" class="modal_roleUserUbahStatus-close px-4 py-2 text-white bg-blue-700 rounded-lg hover:bg-blue-800">Submit</button>                                        
                                    </div>
                                </form>
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
                                                <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase ">
                                                    Id
                                                </th>
                                                <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Pengguna
                                                </th>
                                                <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Role
                                                </th>
                                                <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Terakhir Login
                                                </th>
                                                <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
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

     <!--script -->
    <script>
        let currentPage = 1;
        const itemsPerPage = 10;
        let totalItems = 0;
        let userData = [];

        fetch('http://localhost/KabarE-Web/api/user.php')
            .then(response => response.json())
            .then(data => {
                console.log(data);  // Log the entire data object to check the structure
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

        // Function to update table data based on current page
        function updateTable() {
            const tableBody = document.getElementById('pengguna-table-body');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

            // Count how many admin users exist in the data
            const adminCount = userData.filter(pengguna => pengguna.role === 'admin').length;

            // Clear previous rows
            tableBody.innerHTML = '';

            // If there is no data to display
            if (userData.length === 0) {
                const noDataRow = document.createElement('tr');
                noDataRow.innerHTML = `
                    <td colspan="5" class="py-4 px-6 text-center text-base font-semibold text-gray-900">
                        No data available
                    </td>
                `;
                tableBody.appendChild(noDataRow);
            } else {
                // Populate rows with data for the current page
                for (let i = startIndex; i < endIndex; i++) {
                    const pengguna = userData[i];

                    // Create the image element if profile_pic exists
                    let profilePicHtml = '';
                    if (pengguna.profile_pic) {
                        const profilePicUrl = URL.createObjectURL(pengguna.profile_pic);
                        profilePicHtml = `<img class="w-10 h-10 rounded-full" src="${profilePicUrl}" alt="Profile Pic">`;
                    } else {
                        profilePicHtml = `<img class="w-10 h-10 rounded-full" src="default-avatar.jpg" alt="Default Avatar">`; // Default image if profile_pic is not available
                    }

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-4 px-6 border-b text-left text-base text-gray-900 max-w-[100px] overflow-x-auto whitespace-nowrap">
                            ${pengguna.uid}
                        </td>

                        <td class="py-4 px-6 border-b flex items-center">
                            ${profilePicHtml}
                            <div class="text-sm font-normal text-gray-500 ml-4">
                                <div class="text-base font-semibold text-gray-900">${pengguna.nama_pengguna}</div>
                                <div class="text-sm font-normal text-gray-500">${pengguna.email}</div>
                            </div>
                        </td>
                        <td class="py-4 px-6 border-b text-base text-left font-medium text-gray-900 whitespace-nowrap">${pengguna.role}</td>
                        <td class="py-4 px-6 border-b text-base text-left font-sm text-gray-600 whitespace-nowrap">${pengguna.waktu_login || "N/A"}</td>
                        <td class="py-4 px-6 border-b text-right">
                            <button type="button" class="modal_roleUserUbahStatus-toggle inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-yellow-500 rounded-lg hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-300" onclick="getEditPengguna('${pengguna.uid}')">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                </svg>
                                Ubah Role
                            </button>

                            <!-- Only show delete button if not the last admin -->
                            ${adminCount > 1 || pengguna.role !== 'admin' ? `
                            <button type="button" data-modal-target="delete-user-modal" data-modal-toggle="delete-user-modal" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300" onclick="deletePengguna('${pengguna.uid}')">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Hapus
                            </button>` : ''}
                        </td>
                    `;
                    tableBody.appendChild(row);
                }
            }

            // Update results info
            const resultsInfo = document.getElementById('results-info');
            resultsInfo.textContent = `Showing ${startIndex + 1}-${endIndex} of ${totalItems} results`;
        }


        function updateTable(filteredPenggunas = userData) {
            const tableBody = document.getElementById('pengguna-table-body');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredPenggunas.length);

            // Count how many admin users exist in the data
            const adminCount = filteredPenggunas.filter(pengguna => pengguna.role === 'admin').length;

            // Clear previous rows
            tableBody.innerHTML = '';

            // If there is no data to display
            if (filteredPenggunas.length === 0) {
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
                    const pengguna = filteredPenggunas[i];

                    // If profile_pic is a Blob or base64 data, handle it
                    let profilePicSrc = '';
                    if (pengguna.profile_pic) {
                        if (pengguna.profile_pic instanceof Blob) {
                            profilePicSrc = URL.createObjectURL(pengguna.profile_pic); // For Blob data
                        } else if (typeof pengguna.profile_pic === 'string' && pengguna.profile_pic.startsWith('data:image')) {
                            profilePicSrc = pengguna.profile_pic; // If it's already base64
                        }
                    }

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-4 px-6 border-b text-left text-base text-gray-900 max-w-[100px] overflow-x-auto whitespace-nowrap">
                            ${pengguna.uid}
                        </td>

                        <td class="py-4 px-6 border-b flex items-center">
                            <!-- Display image if exists, otherwise show a default avatar -->
                            <img class="w-10 h-10 rounded-full" src="${profilePicSrc || 'path/to/default-avatar.png'}" alt="${pengguna.nama_pengguna} avatar">
                            <div class="text-sm font-normal text-gray-500 ml-4">
                                <div class="text-base font-semibold text-gray-900">${pengguna.nama_pengguna}</div>
                                <div class="text-sm font-normal text-gray-500">${pengguna.email}</div>
                            </div>
                        </td>
                        <td class="py-4 px-6 border-b text-base text-left font-medium text-gray-900 whitespace-nowrap">${pengguna.role}</td>
                        <td class="py-4 px-6 border-b text-base text-left font-sm text-gray-600 whitespace-nowrap">${pengguna.waktu_login || "N/A"}</td>
                        <td class="py-4 px-6 border-b text-right">
                            <!-- Only show delete button if not the last admin -->
                            ${adminCount > 1 || pengguna.role !== 'admin' ? `
                            <button type="button" data-modal-target="delete-user-modal" data-modal-toggle="delete-user-modal" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 mr-2" onclick="deletePengguna('${pengguna.uid}')">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Hapus
                            </button>` : ''}

                            <button type="button" class="modal_roleUserUbahStatus-toggle inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-yellow-500 rounded-lg hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-300" onclick="getEditPengguna('${pengguna.uid}')">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                </svg>
                                Ubah Role
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                }
            }

            // Update results info
            const resultsInfo = document.getElementById('results-info');
            resultsInfo.textContent = `Showing ${startIndex + 1}-${endIndex} of ${filteredPenggunas.length} results`;
        }




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

            // Add page number buttons
            for (let i = 1; i <= totalPages; i++) {
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

        document.getElementById('search-input').addEventListener('input', function () {
            const query = this.value.toLowerCase();
            filterPenggunas(query);
        });

        function filterPenggunas(query) {
            const filteredPenggunas = userData.filter(pengguna => pengguna.nama_pengguna.toLowerCase().includes(query));
            totalItems = filteredPenggunas.length; 
            updateTable(filteredPenggunas); 
            updatePagination(); 
        }

        

        // Get modal elements
        const modal = document.querySelector('.modal_roleUserUbahStatus');
        const modalToggle = document.querySelector('.modal_roleUserUbahStatus-toggle'); // Ensure modalToggle exists if it's a button triggering modal opening
        const modalCloseButtons = modal.querySelectorAll('.modal_roleUserUbahStatus-close');

        // Open modal - Ensure there is a trigger element for modal toggle
        if (modalToggle) {
            modalToggle.addEventListener('click', () => {
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
            if (confirm('Are you sure you want to delete this pengguna?')) {
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
                            currentPage = Math.max(1, currentPage - 1); // Go back to previous page if the current page is now out of range
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

        // Function to change the page
        function changePage(page) {
            if (page < 1 || page > Math.ceil(totalItems / itemsPerPage)) return;
            currentPage = page;
            updateTable();
            updatePagination();
        }

        // Function to handle dropdown actions
        function toggleDropdown(event, index, penggunaName) {
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
                if (!dropdown.contains(event.target) && !document.getElementById(`menu-button-${index}`).contains(event.target)) {
                    dropdown.classList.add('hidden');
                    document.removeEventListener('click', closeDropdown); // Remove the event listener after closing
                }
            });
        }                      


        // Function to fetch and populate user data into the modal        
        let currentPenggunaId = null; 

        function getEditPengguna(editPengguna) {
            currentPenggunaId = editPengguna;
            console.log("Stored currentPenggunaId:", currentPenggunaId);
            console.log('Editing pengguna with ID:', editPengguna);  

            // Ensure the editPengguna is not undefined or null
            if (!editPengguna) {
                console.error("No ID provided for edit!");
                showAlert('error', 'No valid ID provided for edit.');
                return;  // Exit if the ID is invalid
            }

            // Log the ID before fetching to ensure it's valid
            console.log("Attempting to fetch data for ID:", editPengguna);

            fetch(`http://localhost/KabarE-Web/api/user.php?id=${editPengguna}`)
                .then(response => response.json())
                .then(responseData => {
                    console.log('API Response Data:', responseData);  // Log the entire response for debugging

                    if (responseData && responseData.data) {
                        const penggunaData = responseData.data;
                        console.log("Fetched pengguna data:", penggunaData);  // Log the pengguna data

                        const roleSelect = document.querySelector('#roles');
                        if (roleSelect) {
                            roleSelect.value = penggunaData.role;  // Set the current role as the selected option
                        }

                        // Show the modal
                        const modal = document.querySelector('.modal_roleUserUbahStatus');
                        if (modal) {
                            modal.classList.remove('hidden');  // Show modal
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

        // Handle form submission for role update
        document.getElementById('roleForm').addEventListener('submit', async function (event) {
            event.preventDefault();  // Prevent the default form submission
            console.log("id update:" + currentPenggunaId);
            
            // Check if currentPenggunaId is set before continuing
            if (!currentPenggunaId) {
                console.error('User ID is missing!');
                showAlert('error', 'User ID is missing!');
                return;  // Exit if the ID is missing
            }

            const formData = new FormData(this);
            const role = formData.get('role');  // Get selected role value

            const UbahPenggunaId = currentPenggunaId;
            console.log("Retrieved penggunaId for submission:", UbahPenggunaId);
            console.log('Form Data to Submit:', { role, UbahPenggunaId });  // Log role and ID

            // Display selected role in the console
            console.log("Selected role: ", role);

            const url = `http://localhost/KabarE-Web/api/user.php?id=${UbahPenggunaId}`;

            try {
                console.log('Form Data to Submit:', { role, UbahPenggunaId });

                const response = await fetch(url, {
                    method: 'PUT',  // Use PUT method for updating
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ role }),
                });

                if (response.ok) {
                    showAlert('success', `Pengguna role updated successfully.`);
                    document.querySelector('.modal_roleUserUbahStatus').classList.add('hidden');  // Hide modal after success
                    fetchDataAndUpdateTable();

                    currentPenggunaId = null;
                    console.log("Reset currentPenggunaId:", currentPenggunaId);  // Confirm reset
                } else {
                    const errorData = await response.json();
                    console.error('Gagal memperbarui peran pengguna:', errorData.message);
                    showAlert('error', `Gagal memperbarui peran: ${errorData.message}`);
                }
            } catch (error) {
                console.error("Kesalahan saat mengirim formulir:", error);
                showAlert('error', `Terjadi kesalahan: ${error.message || "Kesalahan tidak diketahui"}`);
            }
        });

        function resetFormAndCloseDrawer() {
            const form = document.getElementById('roleForm');
            if (form) {
                form.reset();  // Resets the form fields
                document.querySelector('.modal_roleUserUbahStatus').classList.add('hidden');  // Close the modal
            } else {
                console.error('Form not found.');
            }
        }
        
        // Fetch data and update table and pagination
        async function fetchDataAndUpdateTable() {
            try {
                const response = await fetch('http://localhost/KabarE-Web/api/user.php');
                const data = await response.json();
                
                console.log(data);  // Log the entire data object to check the structure
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

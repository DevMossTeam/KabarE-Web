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
                                        <span class="ml-1 text-gray-400 md:ml-2" aria-current="page">Kotak Masuk</span>
                                    </div>
                                </li>
                            </ol>
                </nav>

                <!-- Table Container -->
                <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                    <div class="mb-4 flex items-center">
                        <svg class="flex-shrink-0 w-6 h-6 text-black transition" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl ml-4">Kotak Masuk</h1>
                    </div>


                 <!-- Filter Input -->
                <div class="flex items-center justify-between flex-wrap">
                    <!-- Checkbox section -->
                    <div class="flex items-center w-full sm:w-auto mb-4 sm:mb-0">
                        <label for="checkbox-all" class="flex items-center cursor-pointer ml-4">
                            <input id="checkbox-all" aria-describedby="checkbox-1" type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-500" onchange="toggleSvgVisibility()">
                        </label>

                        <div class="ml-2 cursor-pointer flex items-center">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                            
                            <svg class="w-4 h-5 ml-10 checkbox-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 53 53">
                                <path d="M26.5013 52.8307C19.1499 52.8307 12.9232 50.2797 7.82109 45.1776C2.71901 40.0755 0.167969 33.8488 0.167969 26.4974C0.167969 19.146 2.71901 12.9193 7.82109 7.81719C12.9232 2.7151 19.1499 0.164063 26.5013 0.164062C30.2867 0.164062 33.9076 0.945284 37.3638 2.50773C40.8201 4.07017 43.7825 6.30631 46.2513 9.21615V0.164062H52.8346V23.2057H29.793V16.6224H43.618C41.8624 13.5502 39.4628 11.1363 36.4191 9.38073C33.3754 7.62517 30.0695 6.7474 26.5013 6.7474C21.0152 6.7474 16.352 8.66753 12.5117 12.5078C8.67144 16.3481 6.7513 21.0113 6.7513 26.4974C6.7513 31.9835 8.67144 36.6467 12.5117 40.487C16.352 44.3273 21.0152 46.2474 26.5013 46.2474C30.7256 46.2474 34.5385 45.0404 37.9398 42.6266C41.3412 40.2127 43.7277 37.0307 45.0992 33.0807H52.0117C50.4756 38.896 47.3485 43.6415 42.6305 47.3172C37.9124 50.9929 32.536 52.8307 26.5013 52.8307Z" fill="black"/>
                            </svg>

                            <svg class="w-5 h-5 ml-3 checkbox-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2 10" fill="none">
                                <path d="M1 0.5C1 0.223858 0.776142 0 0.5 0C0.223858 0 0 0.223858 0 0.5C0 0.776142 0.223858 1 0.5 1C0.776142 1 1 0.776142 1 0.5ZM1 4.5C1 4.22386 0.776142 4 0.5 4C0.223858 4 0 4.22386 0 4.5C0 4.77614 0.223858 5 0.5 5C0.776142 5 1 4.77614 1 4.5ZM1 8.5C1 8.22386 0.776142 8 0.5 8C0.223858 8 0 8.22386 0 8.5C0 8.77614 0.223858 9 0.5 9C0.776142 9 1 8.77614 1 8.5Z" fill="black"/>
                            </svg>

                            <!-- delete -->
                            <svg class="w-5 h-5 ml-10 new-checkbox-svg hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path d="M14 11V17M10 11V17M6 7V19C6 19.5304 6.21071 20.0391 6.58579 20.4142C6.96086 20.7893 7.46957 21 8 21H16C16.5304 21 17.0391 20.7893 17.4142 20.4142C17.7893 20.0391 18 19.5304 18 19V7M4 7H20M7 7L9 3H15L17 7" 
                                    stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Search input -->
                    <div class="w-full sm:w-auto">
                        <input 
                            type="text" 
                            placeholder="Search "
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full sm:w-96 p-2.5 mb-2 sm:mb-0"
                            id="search-input"
                        />
                    </div>
                </div>


                    <div class="mb-2 alert-dynamic"></div>

                   
                    <div class="flex flex-col">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden shadow">
                                    <div class="flex space-x-4 ">
                                        <div>
                                            <div class="box-border h-14 w-64 p-4 border-b-4 border-blue-500 text-blue-500 hover:bg-gray-100">
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
                                        <tbody id="tag-table-body" class="text-gray-600 text-sm">
                                        <tr class="bg-white border-t border-b hover:border-gray-350 hover:shadow-md hover:z-10 relative">
                                                <td class="w-4 p-4">
                                                    <div class="flex items-center">
                                                        <input id="checkbox-1" aria-describedby="checkbox-1" type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                                        <label for="checkbox-1" class="sr-only">checkbox</label>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2 w-20 whitespace-nowrap">
                                                    <div class="px-1 py-2 bg-orange-400 text-white rounded-lg w-14 text-center mb-2">
                                                        New
                                                    </div>
                                                    <div class="px-1 py-2 bg-red-400 text-white rounded-lg text-center mb-2 ">
                                                        Laporan
                                                    </div>
                                                    <div class="px-1 py-2 bg-blue-400 text-white rounded-lg text-center">
                                                        Feedback
                                                    </div>
                                                </td>
                                                <td class="flex items-center  px-6 py-4 whitespace-nowrap">
                                                    <img class="w-10 h-10 rounded-full" src="https://pics.craiyon.com/2023-10-25/b65f72d6d11a48c1bc560059cc36e31f.webp" alt="">
                                                    <div class="text-sm font-normal text-gray-500 ml-4">
                                                        <div class="text-base font-semibold text-gray-900">John Dow</div>
                                                        <div class="text-sm font-normal text-gray-500 ">@gmail.com</div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">Lorem ipsum dolor sit amet.</td>
                                                <td class="px-6 py-4 whitespace-nowrap">2024-11-13</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination below the table -->
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
                updateTable(); // Call updateTable after fetching data
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });

        function updateTable() {
            const tableBody = document.getElementById('tag-table-body');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

            // Clear previous rows
            tableBody.innerHTML = '';

            // Populate rows with data for the current page
            for (let i = startIndex; i < endIndex; i++) {
                const tag = inboxData[i];
                
                // Set background color based on status_read
                const rowBackground = tag.status_read === 'belum' ? 'bg-white' : 'bg-[#ECEFF5]';

                // Check if profile_pic exists; use default image if not found or null
                const profilePicSrc = tag.profile_pic
                    ? URL.createObjectURL(new Blob([tag.profile_pic], { type: 'image/jpeg' }))
                    : 'https://img.freepik.com/premium-vector/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-vector-illustration_561158-3407.jpg?w=360';

                const row = document.createElement('tr');
                row.className = `${rowBackground} border-t border-b hover:border-gray-350 hover:shadow-md hover:z-10 relative`;

                // Condition to check if status is Masukan or Laporan
                let statusElements = '';
                if (tag.status === 'Masukan') {
                    statusElements = `
                        <div class="px-1 py-1 bg-blue-400 text-white rounded-lg text-center mb-2">
                            Masukan
                        </div>
                    `;
                } else if (tag.status === 'Laporan') {
                    statusElements = `
                        <div class="px-1 py-1 bg-red-400 text-white rounded-lg text-center mb-2">
                            Laporan
                        </div>
                    `;
                }

                row.innerHTML = `
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input id="${tag.id}" aria-describedby="checkbox-${tag.id}" type="checkbox" 
                                class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:focus:ring-primary-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                                onclick="toggleSvgVisibilityId('${tag.id}')">
                            <label for="checkbox-${tag.id}" class="sr-only">checkbox</label>
                        </div>
                    </td>
                    <td class="px-4 py-1 w-20 whitespace-nowrap">
                        ${statusElements}
                    </td>
                    <td class="flex items-center px-6 py-4 whitespace-nowrap">
                        <img class="w-10 h-10 rounded-full" src="${profilePicSrc}" alt="User Avatar">
                        <div class="text-sm font-normal text-gray-500 ml-4">
                            <div class="text-base font-semibold text-gray-900">${tag.username}</div>
                            <div class="text-sm font-normal text-gray-500">${tag.email}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">${tag.pesan}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${new Date(tag.created_at).toLocaleDateString()}</td>
                `;
                
                tableBody.appendChild(row);

                // Release the created object URL when no longer needed
                if (tag.profile_pic) {
                    URL.revokeObjectURL(profilePicSrc);
                }
            }


        }

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
            filterTags(query);
        });

        function filterTags(query) {
            const filteredTags = tagsData.filter(tag => tag.nama_tag.toLowerCase().includes(query));
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
            const checkboxes = document.querySelectorAll('#tag-table-body input[type="checkbox"]');
            const isChecked = checkboxAll.checked;
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        }
        
        let selectedData = [];

        function toggleSvgVisibilityId() {
            const checkboxAll = document.getElementById('checkbox-all');
            const currentSvgs = document.querySelectorAll('.checkbox-svg');
            const newSvgs = document.querySelectorAll('.new-checkbox-svg');

            // Loop through all the SVGs with the class 'checkbox-svg' and 'new-checkbox-svg'
            currentSvgs.forEach(svg => {
                svg.style.display = checkboxAll.checked ? 'none' : 'block';
            });

            newSvgs.forEach(svg => {
                svg.style.display = checkboxAll.checked ? 'block' : 'none';
            });

            // Store or remove checkbox data based on the checked state
            const checkboxes = document.querySelectorAll('input[type="checkbox"]'); // Assuming checkboxes are used
            checkboxes.forEach(checkbox => {
                const checkboxId = checkbox.id;
                if (checkbox.checked) {
                    // Add the checkbox ID to the array if checked
                    if (!selectedData.includes(checkboxId)) {
                        selectedData.push(checkboxId);
                    }
                } else {
                    // Remove the checkbox ID from the array if unchecked
                    const index = selectedData.indexOf(checkboxId);
                    if (index > -1) {
                        selectedData.splice(index, 1);
                    }
                }
            });

            console.log('Selected Data:', selectedData); 
        }

    </script>
</body>
</html>
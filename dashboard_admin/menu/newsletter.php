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
    
    <?php
        // The URL of your API endpoint
        $apiUrl = "http://localhost/KabarE-Web/api/newsletter.php";

        // Number of items per page
        $itemsPerPage = 10;

        // Get the current page number from the query string, default to page 1
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $itemsPerPage;

        // Fetch the data using file_get_contents()
        $response = file_get_contents($apiUrl);

        // Check if the response is valid
        if ($response === FALSE) {
            die('Error occurred while fetching data');
        }

        // // Decode the JSON response into a PHP array
        $data = json_decode($response, true);

        // // Check if data is empty or if the 'data' key is not set
        // if (empty($data['data'])) {
        //     echo "No data found.";
        //     exit;
        // }

        // Calculate total pages
        $totalItems = count($data['data']);
        $totalPages = ceil($totalItems / $itemsPerPage);

        // Get the data for the current page
        $pageData = array_slice($data['data'], $offset, $itemsPerPage);
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
                    <div class="mb-4 flex flex-col md:flex-row items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <svg class="flex-shrink-0 w-6 h-6 text-black transition duration-75" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                <path
                                    d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                            </svg>
                            <h1 class="text-xl font-semibold text-gray-900 md:text-2xl">Daftar Newsletter</h1>

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
                                        <p>Fitur buletin email memungkinkan pembaca untuk berlangganan ke situs atau
                                            blog dan menerima email pemberitahuan tentang pos baru yang diterbitkan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="createUser" data-drawer-target="drawer-tambah-product-default"
                            data-drawer-show="drawer-tambah-product-default"
                            aria-controls="drawer-tambah-product-default" data-drawer-placement="right"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 mt-4 sm:mt-0">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Tambah News Letter
                        </button>
                    </div>


                    <!-- Filter Input -->
                    <div
                        class="flex flex-col sm:flex-row items-center justify-between mb-4 space-y-4 sm:space-y-0 sm:space-x-4">
                        <input type="text" placeholder="Search Judul"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full sm:w-96 p-1.5"
                            id="search-input" />

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
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 md:w-56 lg:w-72">
                                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownHoverButton">
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Dashboard</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Earnings</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Sign out</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <script>
                            document.getElementById('search-input').addEventListener('input', function () {
                                const query = this.value.toLowerCase();
                                filterTable(query);
                            });

                            function filterTable(query) {
                                const rows = document.querySelectorAll('#pengguna-table-body tr');

                                rows.forEach(row => {
                                    const judulCell = row.querySelector(
                                        'td:nth-child(2)'); // Adjust the index based on the column
                                    const judulText = judulCell ? judulCell.textContent.toLowerCase() : '';

                                    // Show row if the 'judul_bulletin' matches the search query
                                    if (judulText.includes(query)) {
                                        row.style.display = '';
                                    } else {
                                        row.style.display = 'none';
                                    }
                                });
                            }
                        </script>


                        <!-- Main modal Form -->
                        <div
                            class="modal_roleUserUbahStatus hidden fixed top-0 left-0 right-0 bottom-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
                            <!-- Modal content wrapper -->
                            <form id="roleForm" action="#" method="POST"
                                class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-4 relative">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between pb-4 border-b">
                                    <h3 class="text-lg font-semibold text-gray-900">Form Pengguna</h3>
                                    <button type="button"
                                        class="modal_roleUserUbahStatus-close text-gray-500 hover:text-gray-900">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="py-4">
                                    <div>
                                        <label for="roles"
                                            class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                                        <select id="roles" name="role"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                            <option selected>Pilih Role</option>
                                            <option value="pembaca">pembaca</option>
                                            <option value="penulis">penulis</option>
                                            <option value="admin">admin</option>
                                        </select>
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
                                                    Judul bulletin
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Tipe Content
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    status
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    dijadwalkan
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="pengguna-table-body" class="text-gray-600 text-sm">
                                            <?php
                                            if (!empty($pageData)) {
                                                foreach ($pageData as $item) {
                                                    $statusClass = '';
                                                    $statusText = '';
                                                    
                                                    // Determine the status class and text
                                                    switch ($item['status']) {
                                                        case 'scheduled':
                                                            $statusClass = 'bg-blue-500';
                                                            $statusText = 'Scheduled';
                                                            break;
                                                        case 'sent':
                                                            $statusClass = 'bg-green-500';
                                                            $statusText = 'Sent';
                                                            break;
                                                        case 'draft':
                                                            $statusClass = 'bg-yellow-500';
                                                            $statusText = 'Draft';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-gray-500';
                                                            $statusText = 'Unknown';
                                                    }
                                            ?>
                                            <tr class="hover:bg-gray-100">
                                                <td class="p-4 text-gray-900 whitespace-nowrap border-b"><?php echo $item['id']; ?></td>
                                                <td class="p-4 text-gray-900 whitespace-nowrap border-b"><?php echo $item['judul_bulletin']; ?></td>
                                                <td class="p-4 text-gray-900 whitespace-nowrap border-b"><?php echo $item['tipe_content']; ?></td>
                                                <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                    <span class="px-2 py-1 text-xs font-semibold text-white <?php echo $statusClass; ?> rounded">
                                                        <?php echo $statusText; ?>
                                                    </span>
                                                </td>
                                                <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                    <?php 
                                                    if (isset($item['send_date']) && !is_null($item['send_date'])) {
                                                        echo date('Y-m-d H:i', strtotime($item['send_date']));
                                                    } else {
                                                        echo $item['tipe_penjadwalan'];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="p-4 text-center border-b">
                                                    <button type="button" data-modal-target="delete-user-modal" data-modal-toggle="delete-user-modal"
                                                        class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 lg:mr-2">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>
                                                    <button class="modal_roleUserUbahStatus-toggle inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-center text-white bg-yellow-500 rounded-lg hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-300 mt-0 md:mt-5">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php 
                                                }
                                            } else {
                                                echo "<tr><td colspan='6' class='text-center p-4'>No data available</td></tr>";
                                            }
                                            ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                                    
                    <!-- Pagination -->
                    <div id="pagination" class="flex justify-between items-center mt-4">
                        <div>
                            <span id="results-info" class="text-sm text-gray-600">
                                Showing <?php echo (($page - 1) * $itemsPerPage + 1) . '-' . min($page * $itemsPerPage, $totalItems); ?> of <?php echo $totalItems; ?> results
                            </span>
                        </div>
                        <div>
                            <nav aria-label="Page navigation">
                                <ul id="pagination-links" class="flex space-x-1">
                                    <!-- Previous button -->
                                    <li>
                                        <a href="?page=<?php echo max($page - 1, 1); ?>" class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300 <?php echo ($page === 1) ? 'cursor-not-allowed' : ''; ?>" aria-label="Previous">&laquo;</a>
                                    </li>

                                    <!-- Page number buttons -->
                                    <?php
                                    // Define the max number of pages to show
                                    $maxPagesToShow = 3;

                                    // Calculate start and end page range
                                    $startPage = max(1, $page - floor($maxPagesToShow / 2)); // Starting page
                                    $endPage = min($totalPages, $startPage + $maxPagesToShow - 1); // Ending page

                                    // Adjust the starting page if the end page is the total pages
                                    if ($endPage - $startPage < $maxPagesToShow - 1) {
                                        $startPage = max(1, $endPage - $maxPagesToShow + 1);
                                    }

                                    // Loop through the page range and display the page numbers
                                    for ($i = $startPage; $i <= $endPage; $i++) {
                                        echo '<li>';
                                        // Conditionally add the active class
                                        $activeClass = ($page == $i) ? 'bg-blue-100 text-blue-800' : 'bg-white text-gray-700';
                                        echo '<a href="?page=' . $i . '" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 ' . $activeClass . '">' . $i . '</a>';
                                        echo '</li>';
                                    }
                                    ?>

                                    <!-- Next button -->
                                    <li>
                                        <a href="?page=<?php echo min($page + 1, $totalPages); ?>" class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300 <?php echo ($page === $totalPages) ? 'cursor-not-allowed' : ''; ?>" aria-label="Next">&raquo;</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="NewsletterModal"
        class="hidden fixed top-0 left-0 right-0 bottom-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <!-- Modal content wrapper -->
        <form id="roleForm" action="#" method="POST"
            class="bg-white rounded-lg shadow-lg max-w-[1120px] w-full p-4 relative">
            <!-- Modal header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Form Tambah Newsletter</h3>
                <button type="button" id="closeModal" class="text-gray-500 hover:text-gray-900">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="py-4">
                <div>
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="">
                            <label for="email-address-icon"
                                class="block mb-2 text-sm font-medium text-gray-900 ">Judul</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <input type="email" id="email" aria-describedby="helper-text-explanation"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="Judul untuk Newsletter">
                            </div>
                        </div>
                        <div class="">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <label for="countries"
                                    class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                <select id="countries"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option>Draft</option>
                                    <option>dijadwalkant</option>
                                    <option>dikirm </option>
                                </select>
                            </div>
                        </div>
                        <div class="">
                            <label for="email-address-icon"
                                class="block mb-2 text-sm font-medium text-gray-900 ">Paragraf pembuka</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <textarea id="message" rows="4"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Teks Paragraph embuka"></textarea>
                            </div>
                        </div>
                        <div class="">
                            <label for="email-address-icon" class="block mb-2 text-sm font-medium text-gray-900 ">Footer
                                Content</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <textarea id="message" rows="4"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Teks Footer"></textarea>
                            </div>
                        </div>
                        <div class="">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Pilih
                                    Kategori</label>
                                <select id="countries"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option>Semua Kategori</option>
                                    <option>UMKM</option>
                                    <option>Politik</option>
                                </select>
                            </div>
                        </div>
                        <div class="">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Tipe
                                    Content</label>
                                <select id="countries"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option>Acak</option>
                                    <option>Tebaru</option>
                                    <option>Popular</option>
                                </select>
                            </div>
                        </div>
                        <div class="">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Tipe
                                    Penjadwalan</label>
                                <select id="countries"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option>sehari-hari</option>
                                    <option>mingguan</option>
                                    <option>bulanan</option>
                                </select>
                            </div>
                        </div>
                        <div class="">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Penjadwalan
                                    pengiriman</label>
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 mt-6" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input datepicker id="default-datepicker" type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 max-w-[150px] text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5"
                                    placeholder="Pilih Tanggal">
                            </div>
                        </div>

                        <div class="">
                            <div class="relative">
                                <label for="quantity-input" class="block mb-2 text-sm font-medium text-gray-900">Jumlah
                                    berita yang
                                    dimunculkan</label>
                                <div class="relative flex items-center max-w-[8rem]">
                                    <!-- Decrement Button -->
                                    <button type="button" id="decrement-button"
                                        class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none"
                                        onclick="decrement()">
                                        <svg class="w-3 h-3 text-gray-900" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 1h16" />
                                        </svg>
                                    </button>

                                    <!-- Quantity Input -->
                                    <input type="text" id="quantity-input"
                                        class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5"
                                        placeholder="999" required value="0" readonly />

                                    <!-- Increment Button -->
                                    <button type="button" id="increment-button"
                                        class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none"
                                        onclick="increment()">
                                        <svg class="w-3 h-3 text-gray-900" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M9 1v16M1 9h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <script>
                                // Function to handle decrement
                                function decrement() {
                                    const quantityInput = document.getElementById('quantity-input');
                                    let quantity = parseInt(quantityInput.value) || 0;
                                    if (quantity > 0) {
                                        quantity--;
                                        quantityInput.value = quantity;
                                    }
                                }

                                // Function to handle increment
                                function increment() {
                                    const quantityInput = document.getElementById('quantity-input');
                                    let quantity = parseInt(quantityInput.value) || 0;
                                    quantity++;
                                    quantityInput.value = quantity;
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end pt-4 border-t">
                <button type="button" id="closeModal2"
                    class="ml-2 px-4 py-2 text-gray-700 border rounded-lg hover:bg-gray-100 mr-2">Balik</button>
                <button type="submit"
                    class="px-4 py-2 text-white bg-blue-700 rounded-lg hover:bg-blue-800">Submit</button>
            </div>
        </form>
    </div>

    <!--script -->
    <script>
        // let currentPage = 1;
        // const itemsPerPage = 10;
        // let totalItems = 0;
        // let userData = [];

        // fetch('http://localhost/KabarE-Web/api/user.php')
        //     .then(response => response.json())
        //     .then(data => {
        //         console.log(data); // Log the entire data object to check the structure
        //         userData = data.data || [];
        //         totalItems = userData.length;
        //         console.log(totalItems);
        //         console.log(userData);
        //         updateTable();
        //         updatePagination();
        //     })
        //     .catch(error => {
        //         console.error('Error fetching data:', error);
        //     });

        // // Function to update table data based on current page
        // function updateTable(filteredTags = tagsData) {
        //     const tableBody = document.getElementById('tag-table-body');
        //     const startIndex = (currentPage - 1) * itemsPerPage;
        //     const endIndex = Math.min(startIndex + itemsPerPage, filteredTags.length);

        //     tableBody.innerHTML = '';

        //     // Check if there is no data available
        //     if (filteredTags.length === 0) {
        //         const noDataRow = document.createElement('tr');
        //         noDataRow.innerHTML = `
        //             <td colspan="4" class="py-4 px-6 text-center text-base font-semibold text-gray-900">
        //                 No data available
        //             </td>
        //         `;
        //         tableBody.appendChild(noDataRow);
        //     } else {
        //         // Populate rows with data for the current page
        //         for (let i = startIndex; i < endIndex; i++) {
        //             const tag = filteredTags[i];
        //             const row = document.createElement('tr');
        //             row.innerHTML = `
        //                 <td class="py-4 px-6 border-b text-center">${tag.id}</td>
        //                 <td class="py-4 px-6 border-b">${tag.nama_tag}</td>
        //                 <td class="py-4 px-6 border-b text-center">
        //                     <div class="relative inline-block text-left">
        //                         <div>
        //                             <button 
        //                                 type="button" 
        //                                 class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 focus:ring-1 ring-inset ring-blue-300 hover:bg-gray-50 focus:outline-none"
        //                                 id="menu-button-${i + 1}" 
        //                                 aria-expanded="false" 
        //                                 aria-haspopup="true" 
        //                                 onclick="toggleDropdown(event, ${i + 1}, '${tag.nama_tag}')">
        //                                 <img src="../asset/elipses.svg" class="h-8" alt="dropdown" />
        //                             </button>
        //                         </div>

        //                         <!-- Dropdown menu -->
        //                         <div id="dropdown-menu-${i + 1}" class="hidden absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button-${i + 1}" tabindex="-1">
        //                             <div class="py-1" role="none">            
        //                             <!-- Edit Button -->
        //                                 <a href="#" class="flex items-center gap-2 block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" onclick="getEditTag(${tag.id})">
        //                                     <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        //                                         <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
        //                                         <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
        //                                     </svg>
        //                                     <span>Edit</span>
        //                                 </a>

        //                                 <a href="#" class="flex items-center gap-2 block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" onclick="deleteTag(${tag.id})">
        //                                     <svg aria-hidden="true" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        //                                         <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        //                                     </svg>
        //                                     <span>Delete</span>
        //                                 </a>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </td>
        //             `;
        //             tableBody.appendChild(row);
        //         }
        //     }

        //     // Update results info
        //     const resultsInfo = document.getElementById('results-info');
        //     resultsInfo.textContent = `Showing ${startIndex + 1}-${endIndex} of ${filteredTags.length} results`;
        // }



        // function updateTable(filteredPenggunas = userData) {
        //     const tableBody = document.getElementById('pengguna-table-body');
        //     const startIndex = (currentPage - 1) * itemsPerPage;
        //     const endIndex = Math.min(startIndex + itemsPerPage, filteredPenggunas.length);

        //     // Count how many admin users exist in the data
        //     const adminCount = filteredPenggunas.filter(pengguna => pengguna.role === 'admin').length;

        //     // Clear previous rows
        //     tableBody.innerHTML = '';

        //     // If there is no data to display
        //     if (filteredPenggunas.length === 0) {
        //         const noDataRow = document.createElement('tr');
        //         noDataRow.innerHTML = `
        //             <td colspan="5" class="py-4 px-6 text-center text-base text-gray-900">
        //                 No data available
        //             </td>
        //         `;
        //         tableBody.appendChild(noDataRow);
        //     } else {
        //         // Populate rows with data for the current page
        //         for (let i = startIndex; i < endIndex; i++) {
        //             const pengguna = filteredPenggunas[i];

        //             let profilePicSrc = '';
        //             if (pengguna.profile_pic) {
        //                 if (pengguna.profile_pic instanceof Blob) {
        //                     // Use FileReader to convert the Blob to Base64 string
        //                     const reader = new FileReader();
        //                     reader.onloadend = function () {
        //                         profilePicSrc = reader.result; // This will be a Base64 string
        //                         updateTable(filteredPenggunas); // Re-render the table after the image is loaded
        //                     };
        //                     reader.readAsDataURL(pengguna.profile_pic); // Convert the Blob to a Base64 URL
        //                 } else if (typeof pengguna.profile_pic === 'string' && pengguna.profile_pic.startsWith(
        //                         'data:image')) {
        //                     // If the profile_pic is already a Base64 string
        //                     profilePicSrc = pengguna.profile_pic;
        //                 }
        //             }

        //             const row = document.createElement('tr');
        //             row.innerHTML = `
        //                 <td class="py-4 px-6 border-b text-left text-base text-gray-900 max-w-[100px] overflow-x-auto whitespace-nowrap">
        //                     ${pengguna.uid}
        //                 </td>

        //                 <td class="py-4 px-6 border-b flex items-center">
        //                     <!-- Display image if exists, otherwise show a default avatar -->
        //                     <img class="w-10 h-10 rounded-full" src="${profilePicSrc}" alt="${pengguna.nama_pengguna} avatar">

        //                     <div class="text-sm font-normal text-gray-500 ml-4">
        //                         <div class="text-base font-semibold text-gray-900">${pengguna.nama_pengguna}</div>
        //                         <div class="text-sm font-normal text-gray-500">${pengguna.email}</div>
        //                     </div>
        //                 </td>
        //                 <td class="py-4 px-6 border-b text-base text-left font-medium text-gray-900 whitespace-nowrap">${pengguna.role}</td>
        //                 <td class="py-4 px-6 border-b text-base text-left font-sm text-gray-600 whitespace-nowrap">${pengguna.waktu_login || "N/A"}</td>
        //                 <td class="py-4 px-6 border-b text-right">
        //                     <!-- Only show delete button if not the last admin -->
        //                     ${adminCount > 1 || pengguna.role !== 'admin' ? `
        //                     <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 mr-2" onclick="deletePengguna('${pengguna.uid}')">
        //                         <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        //                             <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        //                         </svg>
        //                         Hapus
        //                     </button>` : ''}

        //                     <button type="button" class="modal_roleUserUbahStatus-toggle inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-yellow-500 rounded-lg hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-300" onclick="getEditPengguna('${pengguna.uid}')">
        //                         <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        //                             <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
        //                             <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
        //                         </svg>
        //                         Ubah
        //                     </button>
        //                 </td>
        //             `;
        //             tableBody.appendChild(row);
        //         }
        //     }

        //     // Update results info
        //     const resultsInfo = document.getElementById('results-info');
        //     resultsInfo.textContent = `Showing ${startIndex + 1}-${endIndex} of ${filteredPenggunas.length} results`;
        // }


        const modal = document.getElementById('NewsletterModal');
        const openButton = document.getElementById('createUser');
        const closeButtons = document.querySelectorAll('#closeModal, #closeModal2');

        openButton.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) { // Check if the click was on the backdrop (modal itself)
                modal.classList.add('hidden');
            }
        });

        // // Function to update pagination buttons
        // function updatePagination() {
        //     const paginationLinks = document.getElementById('pagination-links');
        //     const totalPages = Math.ceil(totalItems / itemsPerPage);

        //     // Clear previous pagination links
        //     paginationLinks.innerHTML = '';

        //     // Add "Previous" button
        //     const prevButton = document.createElement('li');
        //     prevButton.innerHTML = `
        //         <a href="#" class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300" aria-label="Previous" onclick="changePage(currentPage - 1)">
        //             &laquo;
        //         </a>
        //     `;
        //     prevButton.classList.toggle('cursor-not-allowed', currentPage === 1);
        //     paginationLinks.appendChild(prevButton);

        //     // Add page number buttons
        //     for (let i = 1; i <= totalPages; i++) {
        //         const pageButton = document.createElement('li');
        //         pageButton.innerHTML = `
        //             <a href="#" class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100 ${currentPage === i ? 'bg-blue-100' : ''}" onclick="changePage(${i})">${i}</a>
        //         `;
        //         paginationLinks.appendChild(pageButton);
        //     }

        //     // Add "Next" button
        //     const nextButton = document.createElement('li');
        //     nextButton.innerHTML = `
        //         <a href="#" class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300" aria-label="Next" onclick="changePage(currentPage + 1)">
        //             &raquo;
        //         </a>
        //     `;
        //     nextButton.classList.toggle('cursor-not-allowed', currentPage === totalPages);
        //     paginationLinks.appendChild(nextButton);
        // }

        // document.getElementById('search-input').addEventListener('input', function () {
        //     const query = this.value.toLowerCase();
        //     filterPenggunas(query);
        // });

        // function filterPenggunas(query) {
        //     const filteredPenggunas = userData.filter(pengguna => pengguna.nama_pengguna.toLowerCase().includes(query));
        //     totalItems = filteredPenggunas.length;
        //     updateTable(filteredPenggunas);
        //     updatePagination();
        // }



        // // Get modal elements
        // const modal = document.querySelector('.modal_roleUserUbahStatus');
        // const modalToggle = document.querySelector(
        // '.modal_roleUserUbahStatus-toggle'); // Ensure modalToggle exists if it's a button triggering modal opening
        // const modalCloseButtons = modal.querySelectorAll('.modal_roleUserUbahStatus-close');

        // // Open modal - Ensure there is a trigger element for modal toggle
        // if (modalToggle) {
        //     modalToggle.addEventListener('click', () => {
        //         modal.classList.remove('hidden'); // Show the modal
        //     });
        // }

        // // Close modal when clicking close buttons (SVG icon and 'Balik' button)
        // modalCloseButtons.forEach(button => {
        //     button.addEventListener('click', () => {
        //         modal.classList.add('hidden'); // Hide the modal
        //     });
        // });

        // // Close modal when clicking outside the modal content
        // window.addEventListener('click', (event) => {
        //     if (event.target === modal) {
        //         modal.classList.add('hidden'); // Close the modal if clicked outside of it
        //     }
        // });


        // function deletePengguna(penggunaId) {
        //     console.log("delete id" + penggunaId);
        //     if (confirm('Are you sure you want to delete this pengguna?')) {
        //         // Update the URL to match the correct API endpoint
        //         const apiUrl = `http://localhost/KabarE-Web/api/user.php?id=${penggunaId}`;

        //         fetch(apiUrl, {
        //                 method: 'DELETE',
        //                 headers: {
        //                     'Content-Type': 'application/json',
        //                 }
        //             })
        //             .then(response => response.json())
        //             .then(data => {
        //                 if (data.status === 'success') {
        //                     showAlert('success', 'Pengguna deleted successfully!');

        //                     // Close the dropdown if it exists
        //                     const dropdown = document.querySelector(`#dropdown-menu-${penggunaId}`);
        //                     if (dropdown) {
        //                         dropdown.classList.add('hidden');
        //                     }

        //                     // Remove the pengguna from the data and update table/pagination
        //                     userData = userData.filter(pengguna => pengguna.uid !== penggunaId);
        //                     totalItems = userData.length;

        //                     // Check if the current page is now empty after deletion
        //                     if (userData.length <= (currentPage - 1) * itemsPerPage) {
        //                         currentPage = Math.max(1, currentPage -
        //                         1); // Go back to previous page if the current page is now out of range
        //                     }

        //                     // Update table and pagination after deletion
        //                     updateTable();
        //                     updatePagination();
        //                 } else {
        //                     showAlert('error', 'Failed to delete the pengguna.');
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error('Error deleting pengguna:', error);
        //                 showAlert('error', 'An error occurred while deleting the pengguna.');
        //             });
        //     }
        // }




        // function showAlert(type, message) {
        //     // Create the alert div
        //     const alertDiv = document.createElement('div');
        //     alertDiv.classList.add('flex', 'items-center', 'p-4', 'mb-4', 'rounded-lg', 'text-sm', 'font-medium');

        //     // Check the type of alert (success or error)
        //     if (type === 'success') {
        //         alertDiv.classList.add('bg-green-50', 'text-green-800', );
        //         alertDiv.innerHTML = `
        //             <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        //                 <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        //             </svg>
        //             <span class="sr-only">Success</span>
        //             <div class="ms-3">${message}</div>
        //             <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" onclick="closeAlert(this)">
        //                 <span class="sr-only">Close</span>
        //                 <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
        //                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        //                 </svg>
        //             </button>
        //         `;
        //     } else {
        //         alertDiv.classList.add('bg-red-50', 'text-red-800', );
        //         alertDiv.innerHTML = `
        //             <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        //                 <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        //             </svg>
        //             <span class="sr-only">Error</span>
        //             <div class="ms-3">${message}</div>
        //             <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8" onclick="closeAlert(this)">
        //                 <span class="sr-only">Close</span>
        //                 <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
        //                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        //                 </svg>
        //             </button>
        //         `;
        //     }

        //     // Append the alert to the body or a specific container
        //     document.querySelector('.alert-dynamic').appendChild(alertDiv);

        //     // Automatically remove the alert after 5 seconds
        //     setTimeout(() => closeAlert(alertDiv), 5000);
        // }

        // function closeAlert(alert) {
        //     alert.remove();
        // }

        // // Function to change the page
        // function changePage(page) {
        //     if (page < 1 || page > Math.ceil(totalItems / itemsPerPage)) return;
        //     currentPage = page;
        //     updateTable();
        //     updatePagination();
        // }

        // // Function to handle dropdown actions
        // function toggleDropdown(event, index, penggunaName) {
        //     const dropdown = document.getElementById(`dropdown-menu-${index}`);
        //     const isVisible = !dropdown.classList.contains('hidden');

        //     // Hide all dropdowns first
        //     const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
        //     allDropdowns.forEach(drop => drop.classList.add('hidden'));

        //     // If the dropdown wasn't visible, show it
        //     if (!isVisible) {
        //         dropdown.classList.remove('hidden');
        //     }

        //     // Add event listener to close the dropdown when clicking outside
        //     document.addEventListener('click', function closeDropdown(event) {
        //         // Check if the click is outside the dropdown and button
        //         if (!dropdown.contains(event.target) && !document.getElementById(`menu-button-${index}`)
        //             .contains(event.target)) {
        //             dropdown.classList.add('hidden');
        //             document.removeEventListener('click',
        //             closeDropdown); // Remove the event listener after closing
        //         }
        //     });
        // }


        // // Function to fetch and populate user data into the modal        
        // let currentPenggunaId = null;

        // function getEditPengguna(editPengguna) {
        //     currentPenggunaId = editPengguna;
        //     console.log("Stored currentPenggunaId:", currentPenggunaId);
        //     console.log('Editing pengguna with ID:', editPengguna);

        //     // Ensure the editPengguna is not undefined or null
        //     if (!editPengguna) {
        //         console.error("No ID provided for edit!");
        //         showAlert('error', 'No valid ID provided for edit.');
        //         return; // Exit if the ID is invalid
        //     }

        //     // Log the ID before fetching to ensure it's valid
        //     console.log("Attempting to fetch data for ID:", editPengguna);

        //     fetch(`http://localhost/KabarE-Web/api/user.php?id=${editPengguna}`)
        //         .then(response => response.json())
        //         .then(responseData => {
        //             console.log('API Response Data:', responseData); // Log the entire response for debugging

        //             if (responseData && responseData.data) {
        //                 const penggunaData = responseData.data;
        //                 console.log("Fetched pengguna data:", penggunaData); // Log the pengguna data

        //                 const roleSelect = document.querySelector('#roles');
        //                 if (roleSelect) {
        //                     roleSelect.value = penggunaData.role; // Set the current role as the selected option
        //                 }

        //                 // Show the modal
        //                 const modal = document.querySelector('.modal_roleUserUbahStatus');
        //                 if (modal) {
        //                     modal.classList.remove('hidden'); // Show modal
        //                 }

        //             } else {
        //                 console.error('Pengguna data not found!');
        //                 showAlert('error', 'Pengguna data not found!');
        //             }
        //         })
        //         .catch(error => {
        //             console.error("Error fetching pengguna data:", error);
        //             showAlert('error', 'Error fetching pengguna data.');
        //         });
        // }

        // // Handle form submission for role update
        // document.getElementById('roleForm').addEventListener('submit', async function (event) {
        //     event.preventDefault(); // Prevent the default form submission
        //     console.log("id update:" + currentPenggunaId);

        //     // Check if currentPenggunaId is set before continuing
        //     if (!currentPenggunaId) {
        //         console.error('User ID is missing!');
        //         showAlert('error', 'User ID is missing!');
        //         return; // Exit if the ID is missing
        //     }

        //     const formData = new FormData(this);
        //     const role = formData.get('role'); // Get selected role value

        //     const UbahPenggunaId = currentPenggunaId;
        //     console.log("Retrieved penggunaId for submission:", UbahPenggunaId);
        //     console.log('Form Data to Submit:', {
        //         role,
        //         UbahPenggunaId
        //     }); // Log role and ID

        //     // Display selected role in the console
        //     console.log("Selected role: ", role);

        //     const url = `http://localhost/KabarE-Web/api/user.php?id=${UbahPenggunaId}`;

        //     try {
        //         console.log('Form Data to Submit:', {
        //             role,
        //             UbahPenggunaId
        //         });

        //         const response = await fetch(url, {
        //             method: 'PUT', // Use PUT method for updating
        //             headers: {
        //                 'Content-Type': 'application/json',
        //             },
        //             body: JSON.stringify({
        //                 role
        //             }),
        //         });

        //         if (response.ok) {
        //             showAlert('success', `Pengguna role updated successfully.`);
        //             document.querySelector('.modal_roleUserUbahStatus').classList.add(
        //             'hidden'); // Hide modal after success
        //             fetchDataAndUpdateTable();

        //             currentPenggunaId = null;
        //             console.log("Reset currentPenggunaId:", currentPenggunaId); // Confirm reset
        //         } else {
        //             const errorData = await response.json();
        //             console.error('Gagal memperbarui peran pengguna:', errorData.message);
        //             showAlert('error', `Gagal memperbarui peran: ${errorData.message}`);
        //         }
        //     } catch (error) {
        //         console.error("Kesalahan saat mengirim formulir:", error);
        //         showAlert('error', `Terjadi kesalahan: ${error.message || "Kesalahan tidak diketahui"}`);
        //     }
        // });

        // function resetFormAndCloseDrawer() {
        //     const form = document.getElementById('roleForm');
        //     if (form) {
        //         form.reset(); // Resets the form fields
        //         document.querySelector('.modal_roleUserUbahStatus').classList.add('hidden'); // Close the modal
        //     } else {
        //         console.error('Form not found.');
        //     }
        // }

        // // Fetch data and update table and pagination
        // async function fetchDataAndUpdateTable() {
        //     try {
        //         const response = await fetch('http://localhost/KabarE-Web/api/user.php');
        //         const data = await response.json();

        //         console.log(data); // Log the entire data object to check the structure
        //         userData = data.data || [];
        //         totalItems = userData.length;
        //         console.log(totalItems);
        //         console.log(userData);

        //         updateTable();
        //         updatePagination();
        //     } catch (error) {
        //         console.error('Error fetching data:', error);
        //     }
        // }
    </script>
</body>

</html>
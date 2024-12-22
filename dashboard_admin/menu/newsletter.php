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

// Function to fetch newsletters from the API
function fetchNewsletters($apiUrl, $page, $itemsPerPage, $searchQuery = '') {
    // Calculate the offset for pagination
    $offset = ($page - 1) * $itemsPerPage;

    // Fetch the data using file_get_contents()
    $response = file_get_contents($apiUrl);

    if ($response === FALSE) {
        die('Error occurred while fetching data');
    }

    // Decode the JSON response into a PHP array
    $data = json_decode($response, true);

    // Filter the data based on the search query if present
    if ($searchQuery) {
        $data['data'] = array_filter($data['data'], function($item) use ($searchQuery) {
            return stripos($item['judul_bulletin'], $searchQuery) !== false;
        });
    }

    // Calculate total pages
    $totalItems = count($data['data']);
    $totalPages = ceil($totalItems / $itemsPerPage);

    // Get the data for the current page
    $pageData = array_slice($data['data'], $offset, $itemsPerPage);

    return [
        'pageData' => $pageData,
        'totalPages' => $totalPages,
        'totalItems' => $totalItems
    ];
}

$apiUrl = "http://localhost/KabarE-Web/api/newsletter.php";
$itemsPerPage = 10;

// Capture GET parameters for pagination and search
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';  // Capture search query

$data = fetchNewsletters($apiUrl, $page, $itemsPerPage, $searchQuery);

$pageData = $data['pageData'];

// If this is an AJAX request, return the table rows as HTML
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    foreach ($pageData as $item) {
        $statusClass = '';
        $statusText = '';

        // Determine the status class and text
        switch ($item['status']) {
            case 'Aktif':
                $statusClass = 'bg-green-600';
                $statusText = 'Aktif';
                break;
            case 'Tidak Aktif':
                $statusClass = 'bg-red-600';
                $statusText = 'Tidak Aktif';
                break;
            default:
                $statusClass = 'bg-gray-500';
                $statusText = 'Unknown';
        }

        // Highlight the search query in the title if present
        $highlightedTitle = $item['judul_bulletin'];
        if (isset($searchQuery) && $searchQuery) {
            $escapedSearchQuery = htmlspecialchars($searchQuery);
            $highlightedTitle = preg_replace('/(' . preg_quote($escapedSearchQuery, '/') . ')/i', '<span class="bg-yellow-300">$1</span>', $item['judul_bulletin']);
        }
        ?>
        <tr class="hover:bg-gray-100" id="newsletter-<?php echo $item['id']; ?>">
            <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                <?php echo $item['id']; ?>
            </td>
            <td class="p-4 text-gray-900 whitespace-nowrap border-b" id="judul-<?php echo $item['id']; ?>">
                <?php echo $highlightedTitle; ?>
            </td>
            <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                <?php echo $item['kategori']; ?>
            </td>
            <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                <?php echo $item['tipe_content']; ?>
            </td>
            <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                <?php echo $item['hari_pengiriman']; ?>
                <?php echo $item['jam_pengiriman']; ?>
            </td>
            <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                <span class="px-2 py-1 text-xs font-semibold text-white <?php echo $statusClass; ?> rounded">
                    <?php echo $statusText; ?>
                </span>
            </td>
            <td class="p-4 text-center border-b">
                <button type="button" class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 lg:mr-2" onclick="deleteNewsletter('<?php echo $item['id']; ?>')">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <button class="modal_roleUserUbahStatus-toggle inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-center text-white bg-yellow-500 rounded-lg hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-300 mt-0 md:mt-5" onclick="fetchEditNewsletter('<?php echo $item['id']; ?>')">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
            </td>
        </tr>
        <?php
    }
    exit;
}
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
                            <h1 class="text-xl font-semibold text-gray-900 md:text-2xl">Daftar NewsLetter </h1>
                          
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

                        <button type="button" id="createUser" data-drawer-placement="right"
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
                        <form method="get" action="">
                            <input type="text" placeholder="Search Judul" name="search"
                                value="<?php echo htmlspecialchars($searchQuery); ?>"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full sm:w-96 p-1.5">
                        </form>

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
                                <div class="px-4 py-3 text-sm text-gray-900">
                                    <div>Filter Status</div>
                                </div>
                                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownHoverButton">
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Semua</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Aktif</a>
                                    </li>
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Tidak Aktif</a>
                                    </li>
                                </ul>
                            </div>

                        </div>

                        <script>
                            document.getElementById('search-input').addEventListener('input', function() {
                                const query = this.value.toLowerCase();
                                filterTable(query);
                            });

                            function filterTable(query) {
                                const rows = document.querySelectorAll('#pengguna-table-body tr');

                                rows.forEach(row => {
                                    const judulCell = row.querySelector(
                                        'td:nth-child(2)'); // Adjust the index based on the column
                                    const judulText = judulCell ? judulCell.textContent.toLowerCase() : '';

                                    // Show row if the 'judul_newsletter' matches the search query
                                    if (judulText.includes(query)) {
                                        row.style.display = '';
                                    } else {
                                        row.style.display = 'none';
                                    }
                                });
                            }
                        </script>
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
                                                    Judul newsletter
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Kategori
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Tipe Content
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    jadwal pengiriman
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    status
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
                                                            case 'Aktif':
                                                                $statusClass = 'bg-green-600';
                                                                $statusText = 'Aktif';
                                                                break;
                                                            case 'Tidak Aktif':
                                                                $statusClass = 'bg-red-600';
                                                                $statusText = 'Tidak Aktif';
                                                                break;
                                                            default:
                                                                $statusClass = 'bg-gray-500';
                                                                $statusText = 'Unknown';
                                                        }

                                                        // Highlight the search query in the title if present
                                                        $highlightedTitle = $item['judul_bulletin'];
                                                        if (isset($searchQuery) && $searchQuery) {
                                                            // Escape the search query to prevent XSS and handle special characters
                                                            $escapedSearchQuery = htmlspecialchars($searchQuery);
                                                            // Highlight the matched part
                                                            $highlightedTitle = preg_replace('/(' . preg_quote($escapedSearchQuery, '/') . ')/i', '<span class="bg-yellow-300">$1</span>', $item['judul_bulletin']);
                                                        }
                                                ?>
                                                <tr class="hover:bg-gray-100" id="newsletter-<?php echo $item['id']; ?>">
                                                    <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                        <?php echo $item['id']; ?>
                                                    </td>
                                                    <td class="p-4 text-gray-900 whitespace-nowrap border-b" id="judul-<?php echo $item['id']; ?>">
                                                        <?php echo $highlightedTitle; ?>
                                                    </td>
                                                    <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                        <?php echo $item['kategori']; ?>
                                                    </td>
                                                    <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                        <?php echo $item['tipe_content']; ?>
                                                    </td>
                                                    <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                        <?php echo $item['hari_pengiriman']; ?>
                                                        <?php echo $item['jam_pengiriman']; ?>
                                                    </td>
                                                    <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                        <span class="px-2 py-1 text-xs font-semibold text-white <?php echo $statusClass; ?> rounded">
                                                            <?php echo $statusText; ?>
                                                        </span>
                                                    </td>
                                                    <td class="p-4 text-center border-b">
                                                        <button type="button" class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 lg:mr-2" onclick="deleteNewsletter('<?php echo $item['id']; ?>')">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>

                                                        <button class="modal_roleUserUbahStatus-toggle inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-center text-white bg-yellow-500 rounded-lg hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-300 mt-0 md:mt-5" onclick="fetchEditNewsletter('<?php echo $item['id']; ?>')">
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
                                Showing
                                <?php echo (($page - 1) * $itemsPerPage + 1) . '-' . min($page * $itemsPerPage, $totalItems); ?>
                                of <?php echo $totalItems; ?> results
                            </span>
                        </div>
                        <div>
                            <nav aria-label="Page navigation">
                                <ul id="pagination-links" class="flex space-x-1">
                                    <!-- Previous button -->
                                    <li>
                                        <a href="?page=<?php echo max($page - 1, 1); ?>"
                                            class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300 <?php echo ($page === 1) ? 'cursor-not-allowed' : ''; ?>"
                                            aria-label="Previous">&laquo;</a>
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
                                        <a href="?page=<?php echo min($page + 1, $totalPages); ?>"
                                            class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300 <?php echo ($page === $totalPages) ? 'cursor-not-allowed' : ''; ?>"
                                            aria-label="Next">&raquo;</a>
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
        <form id="formnewsletter" action="#" method="POST"
            class="bg-white rounded-lg shadow-lg max-w-[1120px] w-full p-4 relative">
            <!-- Modal header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Form Tambah newsletter</h3>
                <button type="button" id="closeModal" class="text-gray-500 hover:text-gray-900" onclick="resetForm()">
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
                            <label for="judul-label" class="block mb-2 text-sm font-medium text-gray-900 ">Judul</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <input type="judul" id="judul_bulletin" aria-describedby="helper-text-explanation"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="Judul untuk Newsletter">
                            </div>
                        </div>
                        <div class="">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                <select id="status"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option>Aktif</option>
                                    <option>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="">
                            <label for="footer_content" class="block mb-2 text-sm font-medium text-gray-900 ">Footer
                                Content</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <textarea id="footer_content" rows="5"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Teks Footer"></textarea>
                            </div>
                        </div>
                        <div class="">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <label for="pilih_kategori" class="block mb-2 text-sm font-medium text-gray-900">Pilih
                                    Kategori</label>
                                <select id="kategori"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option>Semua Kategori</option>
                                    <option>UMKM</option>
                                    <option>Politik</option>
                                </select>
                            </div>
                            <div class="relative mt-4">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                </div>
                                <label for="tipe_content" class="block mb-2 text-sm font-medium text-gray-900">Tipe
                                    Content</label>
                                <select id="tipe_content"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option>Acak</option>
                                    <option>Tebaru</option>
                                    <option>Popular</option>
                                </select>
                            </div>
                        </div>
                        <div class="">
                            <!-- Penjadwalan Section -->
                            <label for="hari_pengiriman"
                                class="block mb-2 text-sm font-medium text-gray-900">Penjadwalan Pengiriman</label>
                            <div class="flex space-x-4">
                                <div class="w-1/2">
                                    <select id="hari_pengiriman"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option>Senin</option>
                                        <option>Selasa</option>
                                        <option>Rabu</option>
                                        <option>Kamis</option>
                                        <option>Jumat</option>
                                        <option>Sabtu</option>
                                        <option>Minggu</option>
                                    </select>
                                </div>
                                <div class="flex w-300">
                                    <input type="time" id="jam_pengiriman"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        min="09:00" max="18:00" value="00:00" required>
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="relative">
                                <label for="jumlah_berita" class="block mb-2 text-sm font-medium text-gray-900">Jumlah
                                    Berita</label>
                                <div class="relative flex items-center max-w-[8rem]">
                                    <input type="number" id="jumlah_berita" aria-describedby="helper-text-explanation"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="90210" required min="1" value="1" />
                                </div>
                                <label for="time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                                    time:</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end pt-4 border-t">
                <button type="button" id="closeModal2" onclick="resetForm()"
                    class="ml-2 px-4 py-2 text-gray-700 border rounded-lg hover:bg-gray-100 mr-2">Balik</button>
                <button type="submit"
                    class="px-4 py-2 text-white bg-blue-700 rounded-lg hover:bg-blue-800" onclick="refetchData()">Submit</button>
            </div>
        </form>
    </div>

    <!--script -->
    <script>
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

        function resetForm() {
            document.getElementById('judul_bulletin').value = '';
            document.getElementById('status').value = '';
            document.getElementById('tipe_content').value = '';
            document.getElementById('kategori').value = '';
            document.getElementById('footer_content').value = '';
            document.getElementById('hari_pengiriman').value = '';
            document.getElementById('jam_pengiriman').value = '';
            document.getElementById('jumlah_berita').value = '';
            console.log("Form has been reset.");
        }


        let editId = null;

        function fetchEditNewsletter(id) {
            console.log("Fetching newsletter data for id: " + id);
            editId = id;
            // Fetch the data for the specific newsletter using the ID
            fetch(`http://localhost/KabarE-Web/api/newsletter.php?id=${id}`)
                .then(response => response.json()) // Parse the JSON response
                .then(data => {
                    // Check if the response contains valid data
                    if (data.message === "Newsletter fetched successfully" && data.data) {
                        const newsletter = data.data;

                        // Populate the form fields with the fetched data
                        document.getElementById('judul_bulletin').value = newsletter.judul_bulletin;
                        document.getElementById('status').value = newsletter.status;
                        document.getElementById('tipe_content').value = newsletter.tipe_content;
                        document.getElementById('kategori').value = newsletter.kategori;
                        document.getElementById('footer_content').value = newsletter.footer_content;
                        document.getElementById('hari_pengiriman').value = newsletter.hari_pengiriman;
                        document.getElementById('jam_pengiriman').value = newsletter.jam_pengiriman;
                        document.getElementById('jumlah_berita').value = newsletter.jumlah_berita;

                        console.log("editId:" + editId);
                        modal.classList.remove('hidden');
                    } else {
                        console.error("Failed to fetch data: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error fetching newsletter data:", error);
                });
        }

        const form = document.getElementById('formnewsletter');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            // Gather form data
            const formData = {
                "judul_bulletin": document.getElementById('judul_bulletin').value,
                "status": document.getElementById('status').value,
                "tipe_content": document.getElementById('tipe_content').value,
                "kategori": document.getElementById('kategori').value,
                "footer_content": document.getElementById('footer_content').value,
                "hari_pengiriman": document.getElementById('hari_pengiriman').value,
                "jam_pengiriman": document.getElementById('jam_pengiriman').value,
                "jumlah_berita": document.getElementById('jumlah_berita').value
            };

            try {
                let response;
                const url = editId ?
                    `http://localhost/KabarE-Web/api/newsletter.php?id=${editId}` // Update URL
                    :
                    'http://localhost/KabarE-Web/api/newsletter.php'; // Insert URL

                if (editId) {
                    // If `editId` exists, send a PUT request to update
                    response = await fetch(url, {
                        method: 'PUT', // Use PUT for update
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });
                } else {
                    // If `editId` is null, send a POST request to insert
                    response = await fetch(url, {
                        method: 'POST', // Use POST for insert
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });
                }

                if (response.ok) {
                    const data = await response.json();
                    console.log('Form submitted successfully', data);

                    // Show success alert
                    showAlert('success', 'Newsletter submitted successfully!');

                    modal.classList.add('hidden'); // Hide modal after success
                    editId = null; // Reset editId after submission
                } else {
                    console.error('Failed to submit form', response.status);

                    // Show error alert
                    showAlert('error', 'Failed to submit the newsletter.');
                }
            } catch (error) {
                console.error('Error:', error);

                // Show error alert
                showAlert('error', 'An error occurred while submitting the newsletter.');
            }
        });

        // Function to handle delete action with confirmation
        function deleteNewsletter(id) {
            // Show confirmation dialog
            const isConfirmed = confirm("Are you sure you want to delete this newsletter?");

            // If user confirms the deletion, proceed with the API request
            if (isConfirmed) {
                const url = `http://localhost/KabarE-Web/api/newsletter.php?id=${id}`;

                fetch(url, {
                        method: 'DELETE', // Use DELETE method
                    })
                    .then(response => {
                        console.log('Response:', response); // Log the raw response
                        return response.json(); // Convert response to JSON
                    })
                    .then(data => {
                        // Check if the message is 'Newsletter deleted successfully'
                        if (data.message && data.message === 'Newsletter deleted successfully') {
                            // Handle success (e.g., display a success message, update UI)
                            console.log('Deleted successfully:', data);

                            // Show success alert
                            showAlert('success', 'Newsletter deleted successfully!');

                            // Optionally, you can remove the deleted element from the DOM
                            const element = document.getElementById(`newsletter-${id}`);
                            if (element) {
                                element.remove(); // Assuming each element has a unique id like "newsletter-1"
                            }
                        } else {
                            // Handle error response (e.g., display an error message)
                            console.error('Delete failed:', data);

                            // Show error alert
                            showAlert('error', 'Failed to delete the newsletter.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // Show error alert
                        showAlert('error', 'An error occurred while deleting the newsletter.');
                    });
            } else {
                // If user clicks Cancel, simply do nothing
                console.log('Delete action was canceled');
            }
        }

        function showAlert(type, message) {
            // Create the alert div
            const alertDiv = document.createElement('div');
            alertDiv.classList.add('flex', 'items-center', 'p-4', 'mb-4', 'rounded-lg', 'text-sm', 'font-medium');

            // Check the type of alert (success or error)
            if (type === 'success') {
                alertDiv.classList.add('bg-green-50', 'text-green-800');
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
                alertDiv.classList.add('bg-red-50', 'text-red-800');
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

        function closeAlert(alertButton) {
            const alertDiv = alertButton.closest('div');
            alertDiv.remove();
        }

        function refetchData() {
            let currentPage = <?php echo $page; ?>;
            let searchQuery = '<?php echo $searchQuery; ?>';
            
            // Construct the AJAX URL with parameters
            let ajaxUrl = window.location.href.split('?')[0] + '?page=' + currentPage + '&search=' + searchQuery + '&ajax=1';
            
            // Create a new XMLHttpRequest to fetch updated table data
            let xhr = new XMLHttpRequest();
            xhr.open('GET', ajaxUrl, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Inject the new rows into the table body without affecting other parts of the page
                    document.getElementById('pengguna-table-body').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    </script>
</body>

</html>
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
$apiUrl = "../../api/berita.php";

// Number of items per page
$itemsPerPage = 5; // Limit to 5 items per page

// Get the current page number from the query string, default to page 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Get the search query from the URL
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Get the filter value (public/private/semua) from the URL
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Fetch the data using file_get_contents()
$response = file_get_contents($apiUrl);

// Check if the response is valid
// if ($response === FALSE) {
//     die('Error occurred while fetching data');
// }

// Decode the JSON response into a PHP array
$data = json_decode($response, true);

// Check if data is empty or if the 'data' key is not set
if (empty($data['data'])) {
    echo "No data found.";
    exit;
}

// Filter the data based on the search query
if ($searchQuery) {
    $data['data'] = array_filter($data['data'], function($item) use ($searchQuery) {
        return strpos(strtolower($item['judul']), strtolower($searchQuery)) !== false;
    });
}

// Filter the data based on the public/private status (if filter is set)
if ($filter && $filter !== 'semua') {
    $data['data'] = array_filter($data['data'], function($item) use ($filter) {
        return strtolower($item['visibilitas']) === strtolower($filter);
    });
}

// Count total items after filtering to calculate the total pages
$totalItems = count($data['data']);
$totalPages = ceil($totalItems / $itemsPerPage);

// Fetch only the current page data
$currentPageData = array_slice($data['data'], $offset, $itemsPerPage);

echo "<script>console.log(" . json_encode($currentPageData) . ");</script>";
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
                            <h1 class="text-xl font-semibold text-gray-900 md:text-2xl">Daftar Berita</h1>

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
                    </div>

                    <!-- Filter Input -->
                    <div
                        class="flex flex-col sm:flex-row items-center justify-between mb-4 space-y-4 sm:space-y-0 sm:space-x-4">
                        <!-- Search Input -->
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
                                    <div>Filter Visiblits</div>
                                </div>
                                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownHoverButton">
                                    <li>
                                        <a href="?filter=semua" class="block px-4 py-2 hover:bg-gray-100">Semua</a>
                                    </li>
                                    <li>
                                        <a href="?filter=public" class="block px-4 py-2 hover:bg-gray-100">Public</a>
                                    </li>
                                    <li>
                                        <a href="?filter=private" class="block px-4 py-2 hover:bg-gray-100">Private</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

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
                                            <!-- <option value="penulis">penulis</option> -->
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
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Id
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Judul Berita
                                                </th>
                                                <!-- <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Penulis
                                                </th> -->
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Tanggal Terbit
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Kategori
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                                    Visibilitas
                                                </th>
                                                <th scope="col"
                                                    class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="pengguna-table-body" class="text-gray-600 text-sm">
                                            <?php
                                            if (!empty($currentPageData)) {
                                                foreach ($currentPageData as $item) {
                                                    // Highlight the search query in 'judul'
                                                    $highlightedTitle = $item['judul'];
                                                    if ($searchQuery) {
                                                        // Escape the search query to prevent XSS and handle special characters
                                                        $escapedSearchQuery = htmlspecialchars($searchQuery);
                                                        // Highlight the matched part
                                                        $highlightedTitle = preg_replace('/(' . preg_quote($escapedSearchQuery, '/') . ')/i', '<span class="bg-yellow-300">$1</span>', $item['judul']);
                                                    }
                                            ?>
                                            <tr id="row-<?php echo $item['id']; ?>" class="hover:bg-gray-100">
                                                <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                    <?php echo $item['id']; ?>
                                                </td>
                                                <td class="p-4 text-gray-900 border-b">
                                                    <div class="max-w-xs overflow-hidden line-clamp-3">
                                                        <?php echo $highlightedTitle; ?>
                                                    </div>
                                                </td>

                                                <!-- <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                    <div class="flex items-center">
                                                        <img id="profilePicPreview"
                                                            src="data:image/jpeg;base64,<?= $item['profile_pic']; ?>"
                                                            alt="Profile Picture" class="w-10 h-10 rounded-full">
                                                        <div class="ml-4">
                                                            <div class="text-base font-semibold text-gray-900">
                                                                <?php echo $item['nama_pengguna']; ?>
                                                            </div>
                                                            <div class="text-sm font-normal text-gray-500">
                                                                <?php echo $item['email']; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td> -->
                                                <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                    <?php echo date('Y-m-d H:i', strtotime($item['tanggal_diterbitkan'])); ?>
                                                </td>
                                                <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                    <?php echo $item['kategori']; ?>
                                                </td>
                                                <td class="p-4 text-gray-900 whitespace-nowrap border-b">
                                                    <?php echo $item['visibilitas']; ?>
                                                </td>
                                                <td class="p-4 text-center border-b">
                                                    <button type="button"
                                                        class="inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 lg:mr-2"
                                                        onclick="deleteBerita('<?php echo $item['id']; ?>')">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>

                                                    <button
                                                        onclick="window.location.href='../../category/news-detail.php?id=<?php echo $item['id']; ?>';"
                                                        class="modal_roleUserUbahStatus-toggle inline-flex items-center px-1.5 py-1.5 text-sm font-medium text-center text-white bg-blue-500 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 mt-0 md:mt-5">
                                                        <svg width=" 1.25rem" height=" 1.25rem" viewBox="0 0 98 98"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M49 61.25C55.7655 61.25 61.25 55.7655 61.25 49C61.25 42.2345 55.7655 36.75 49 36.75C42.2345 36.75 36.75 42.2345 36.75 49C36.75 55.7655 42.2345 61.25 49 61.25Z"
                                                                fill="white" />
                                                            <path
                                                                d="M94.7524 47.9587C91.1504 38.6415 84.8973 30.584 76.7658 24.782C68.6343 18.98 58.9809 15.6879 48.9987 15.3125C39.0165 15.6879 29.3631 18.98 21.2316 24.782C13.1001 30.584 6.84694 38.6415 3.24495 47.9587C3.00168 48.6316 3.00168 49.3684 3.24495 50.0413C6.84694 59.3585 13.1001 67.416 21.2316 73.218C29.3631 79.02 39.0165 82.3121 48.9987 82.6875C58.9809 82.3121 68.6343 79.02 76.7658 73.218C84.8973 67.416 91.1504 59.3585 94.7524 50.0413C94.9957 49.3684 94.9957 48.6316 94.7524 47.9587ZM48.9987 68.9062C45.0616 68.9062 41.2129 67.7388 37.9394 65.5514C34.6658 63.3641 32.1144 60.2552 30.6077 56.6178C29.1011 52.9804 28.7069 48.9779 29.4749 45.1165C30.243 41.255 32.1389 37.7081 34.9228 34.9242C37.7068 32.1402 41.2537 30.2443 45.1152 29.4762C48.9766 28.7082 52.9791 29.1024 56.6165 30.609C60.2539 32.1157 63.3628 34.6671 65.5501 37.9407C67.7375 41.2142 68.9049 45.0629 68.9049 49C68.8968 54.277 66.797 59.3355 63.0656 63.0669C59.3342 66.7983 54.2757 68.8982 48.9987 68.9062Z"
                                                                fill="white" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php 
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center p-4'>No data available</td></tr>";
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
                                Menampilkan
                                <?php echo (($page - 1) * $itemsPerPage + 1) . '-' . min($page * $itemsPerPage, $totalItems); ?>
                                dari <?php echo $totalItems; ?> hasil yang unik
                            </span>
                        </div>
                        <div>
                            <nav aria-label="Page navigation">
                                <ul id="pagination-links" class="flex space-x-1">
                                    <!-- Previous button -->
                                    <li>
                                        <a href="?page=<?php echo max($page - 1, 1); ?>&search=<?php echo urlencode($searchQuery); ?>"
                                            class="px-3 py-1 text-gray-500 bg-gray-200 rounded hover:bg-gray-300 <?php echo ($page === 1) ? 'cursor-not-allowed' : ''; ?>"
                                            aria-label="Previous">&laquo;</a>
                                    </li>

                                    <!-- Page number buttons -->
                                    <?php
                                    $maxPagesToShow = 3;
                                    $startPage = max(1, $page - floor($maxPagesToShow / 2));
                                    $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);
                                    if ($endPage - $startPage < $maxPagesToShow - 1) {
                                        $startPage = max(1, $endPage - $maxPagesToShow + 1);
                                    }
                                    for ($i = $startPage; $i <= $endPage; $i++) {
                                        echo '<li>';
                                        $activeClass = ($page == $i) ? 'bg-blue-100 text-blue-800 hover:bg-blue-100' : 'bg-white text-gray-700 hover:bg-gray-300';
                                        echo '<a href="?page=' . $i . '&search=' . urlencode($searchQuery) . '" class="px-3 py-1 border border-gray-300 rounded  ' . $activeClass . '">' . $i . '</a>';
                                        echo '</li>';
                                    }
                                    ?>

                                    <!-- Next button -->
                                    <li>
                                        <a href="?page=<?php echo min($page + 1, $totalPages); ?>&search=<?php echo urlencode($searchQuery); ?>"
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

        <!--script -->
        <script>
            function deleteBerita(id) {
                if (!id) {
                    console.error('Invalid ID:', id);
                    return;
                }

                const isConfirmed = confirm('Are you sure you want to delete this newsletter?');

                if (isConfirmed) {
                    const url = `http://localhost/KabarE-Web/api/berita.php?id=${id}`;

                    fetch(url, {
                            method: 'DELETE',
                        })
                        .then(response => {
                            console.log('Response:', response);
                            return response.json();
                        })
                        .then(data => {
                            if (data.message === 'Berita deleted successfully') {
                                showAlert('success', 'Newsletter deleted successfully!');
                                const element = document.getElementById(`row-${id}`);
                                if (element) element.remove();
                            } else {
                                showAlert('failed', 'Failed to delete the newsletter.');
                            }
                        })
                        .catch(error => {
                            // console.error('Fetch error:', error);
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
        </script>
</body>

</html>
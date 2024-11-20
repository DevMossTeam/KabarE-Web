<?php
session_start();
include '../header & footer/header_AuthRev.php';
include '../header & footer/category_header.php';
include '../connection/config.php';

function timeAgo($datetime) {
    $now = new DateTime();
    $posted = new DateTime($datetime);
    $interval = $now->diff($posted);

    if ($interval->y > 0) return $interval->y . " tahun yang lalu";
    if ($interval->m > 0) return $interval->m . " bulan yang lalu";
    if ($interval->d > 0) return $interval->d . " hari yang lalu";
    if ($interval->h > 0) return $interval->h . " jam yang lalu";
    if ($interval->i > 0) return $interval->i . " menit yang lalu";
    return "baru saja";
}

renderCategoryHeader('Publikasi');

// Ambil user_id dari sesi
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id === null) {
    echo "<div class='flex flex-col items-center justify-center mt-8'>
            <i class='fas fa-book-open text-gray-400 text-8xl mb-4'></i>
            <p class='text-gray-500'>Tidak ada konten publikasi saat ini.</p>
          </div>";
    exit;
}

// Pagination
$limit = 30; // Maksimal 30 berita per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk mendapatkan data
$query = "SELECT id, judul, konten_artikel, tanggal_diterbitkan
          FROM berita 
          WHERE user_id = ? AND (visibilitas = 'public' OR visibilitas = 'private') 
          ORDER BY tanggal_diterbitkan DESC 
          LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('iii', $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

echo "<div class='container mx-auto mt-4 lg:px-8' id='publicationContainer'>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $firstImage = '';
        if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
            $firstImage = $image['src'];
        }
        $timeAgo = timeAgo($row['tanggal_diterbitkan']);
        echo "<div class='flex items-center mb-8'> <!-- Jarak atas bawah -->
                <img src='{$firstImage}' class='w-40 h-20 object-cover rounded-lg mr-4'>
                <div class='flex-grow'>
                    <span class='text-gray-400 text-sm'>Dipublish {$timeAgo}</span>
                    <h3 class='text-lg font-bold mt-1'>{$row['judul']}</h3>
                </div>
              </div>";
    }
} else {
    echo "<div class='flex flex-col items-center justify-center mt-8'>
            <i class='fas fa-book-open text-gray-400 text-8xl mb-4'></i>
            <p class='text-gray-500'>Tidak ada konten publikasi saat ini.</p>
          </div>";
}
echo "</div>";

// Pagination controls
$totalQuery = "SELECT COUNT(*) as total FROM berita WHERE user_id = ? AND (visibilitas = 'public' OR visibilitas = 'private')";
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->bind_param('i', $user_id);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);

if ($totalPages > 1) {
    echo "<div class='flex justify-center mt-4'>";
    echo "<nav class='inline-flex shadow-sm -space-x-px' aria-label='Pagination'>";

    // Tombol "prev"
    if ($page > 1) {
        echo "<a href='?page=" . ($page - 1) . "' class='relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50'>
                <span>Prev</span>
              </a>";
    } else {
        echo "<span class='relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-200 text-sm font-medium text-gray-500'>
                <span>Prev</span>
              </span>";
    }

    // Nomor halaman
    $startPage = max(1, $page - 5);
    $endPage = min($totalPages, $startPage + 9);

    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $page) {
            echo "<span class='z-10 bg-blue-500 border-blue-600 text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium'>{$i}</span>";
        } else {
            echo "<a href='?page={$i}' class='bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium'>{$i}</a>";
        }
    }

    // Tombol "next"
    if ($page < $totalPages) {
        echo "<a href='?page=" . ($page + 1) . "' class='relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50'>
                <span>Next</span>
              </a>";
    } else {
        echo "<span class='relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-200 text-sm font-medium text-gray-500'>
                <span>Next</span>
              </span>";
    }

    echo "</nav>";
    echo "</div>";
}

$conn->close();
?>

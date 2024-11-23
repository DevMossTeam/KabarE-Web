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

// Ambil parameter filter
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$visibility = isset($_GET['visibility']) ? $_GET['visibility'] : null;

// Sesuaikan query berdasarkan filter
$orderBy = $sort === 'oldest' ? 'ASC' : 'DESC';
$visibilityCondition = $visibility ? "AND visibilitas = '$visibility'" : '';

// Pagination
$limit = 30; // Maksimal 30 berita per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk mendapatkan data
$query = "SELECT id, judul, konten_artikel, tanggal_diterbitkan
          FROM berita 
          WHERE user_id = ? $visibilityCondition 
          ORDER BY tanggal_diterbitkan $orderBy 
          LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('iii', $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Total data untuk pagination
$totalQuery = "SELECT COUNT(*) as total FROM berita WHERE user_id = ? $visibilityCondition";
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->bind_param('i', $user_id);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);

// Fungsi untuk memformat angka menjadi K, M, B
function formatNumber($num) {
    if ($num >= 1000000000) {
        return round($num / 1000000000, 1) . 'B';
    } elseif ($num >= 1000000) {
        return round($num / 1000000, 1) . 'M';
    } elseif ($num >= 1000) {
        return round($num / 1000, 1) . 'K';
    }
    return $num;
}

// Filter dan Pencarian
echo "<div class='flex flex-col md:flex-row items-center justify-between mb-4'>
    <div class='relative flex items-center w-full md:w-2/3 lg:w-5/6 md:ml-4 mb-4 md:mb-0'>
        <div class='relative flex-grow'>
            <input type='text' id='searchInput' class='pl-10 pr-4 py-2 border rounded-lg w-full text-gray-500' placeholder='Filter'>
            <img src='https://img.icons8.com/ios-filled/50/808080/filter.png' alt='Filter' class='w-5 h-5 absolute left-3 top-2.5 cursor-pointer' id='filterButton'>
            <div id='filterMenu' class='hidden absolute bg-white shadow-md rounded mt-2 w-48 z-10'>
                <a href='?sort=oldest' class='block px-4 py-2 text-gray-700 hover:bg-gray-100'>Terlama</a>
                <a href='?sort=newest' class='block px-4 py-2 text-gray-700 hover:bg-gray-100'>Terbaru</a>
                <a href='?visibility=public' class='block px-4 py-2 text-gray-700 hover:bg-gray-100'>Public</a>
                <a href='?visibility=private' class='block px-4 py-2 text-gray-700 hover:bg-gray-100'>Private</a>
            </div>
        </div>
    </div>
    <div class='flex items-center w-full md:w-auto md:justify-end mr-4'>
        <span class='text-sm text-gray-600'>".formatNumber(($page - 1) * $limit + 1)." - ".formatNumber(min($page * $limit, $totalRow['total']))." dari ".formatNumber($totalRow['total'])."</span>
        <div class='ml-4 flex space-x-2'>
            <a href='?page=".max(1, $page - 1)."' class='px-2 py-1 border rounded-l-md ".($page > 1 ? "bg-white text-gray-600 hover:bg-gray-100" : "bg-gray-200 text-gray-400")."'>Prev</a>
            <a href='?page=".min($totalPages, $page + 1)."' class='px-2 py-1 border rounded-r-md ".($page < $totalPages ? "bg-white text-gray-600 hover:bg-gray-100" : "bg-gray-200 text-gray-400")."'>Next</a>
        </div>
    </div>
</div>";

echo "<div class='container mx-auto mt-4 px-4 md:px-8 lg:px-8' id='publicationContainer'>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $firstImage = '';
        if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
            $firstImage = $image['src'];
        }
        $timeAgo = timeAgo($row['tanggal_diterbitkan']);
        echo "<div class='flex items-start mb-8'> <!-- Menggunakan items-start untuk menyelaraskan ke atas -->
                <div class='flex-none w-40 h-20 mr-4'> <!-- Atur lebar dan tinggi gambar -->
                    <img src='{$firstImage}' class='object-cover rounded-lg w-full h-full'>
                </div>
                <div class='flex-grow'> <!-- Atur lebar maksimum lebih besar -->
                    <h3 class='text-lg md:text-base lg:text-lg font-bold mt-0 break-words'> <!-- Ukuran teks responsif -->
                        {$row['judul']}
                    </h3>
                    <span class='text-gray-400 text-sm'>Dipublish {$timeAgo}</span>
                </div>
                <div class='flex-none flex space-x-2 ml-4'> <!-- Menggunakan flex-none untuk menjaga lebar tetap -->
                    <a href='#' class='text-blue-500 edit-button' data-id='{$row['id']}'>
                        <img src='https://img.icons8.com/ios-filled/50/0000FF/edit.png' alt='Edit' class='w-5 h-5'>
                    </a>
                    <a href='#' class='text-red-500 delete-button' data-id='{$row['id']}'>
                        <img src='https://img.icons8.com/ios-filled/50/FF0000/delete.png' alt='Delete' class='w-5 h-5'>
                    </a>
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

$conn->close();
?>

<script>
    document.getElementById('filterButton').addEventListener('click', function(event) {
        event.stopPropagation();
        var menu = document.getElementById('filterMenu');
        menu.classList.toggle('hidden');
    });

    document.addEventListener('click', function(event) {
        var menu = document.getElementById('filterMenu');
        if (!menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
        }
    });

    document.getElementById('searchInput').addEventListener('input', function() {
        var searchQuery = this.value.toLowerCase();
        var articles = document.querySelectorAll('#publicationContainer .flex.items-center');

        articles.forEach(function(article) {
            var title = article.querySelector('h3').textContent.toLowerCase();
            if (title.includes(searchQuery)) {
                article.style.display = 'flex';
            } else {
                article.style.display = 'none';
            }
        });
    });
</script>

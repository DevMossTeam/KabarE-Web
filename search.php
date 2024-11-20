<?php include 'header & footer/header.php'; ?>
<?php include 'connection/config.php'; ?>

<?php
// Mendapatkan query pencarian dan kategori
$query = isset($_GET['query']) ? $_GET['query'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Pagination
$limit = 30; // Maksimal 30 berita per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    // Melakukan pencarian di database berdasarkan judul dan kategori
    $sql = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan
            FROM berita 
            WHERE (judul LIKE ? OR kategori LIKE ?)
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$query%";
    $stmt->bind_param('ssii', $searchTerm, $searchTerm, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        throw new Exception("Query gagal");
    }
    $numResults = $result->num_rows;

    // Total hasil pencarian
    $totalQuery = "SELECT COUNT(*) as total 
                   FROM berita 
                   WHERE (judul LIKE ? OR kategori LIKE ?)";
    $totalStmt = $conn->prepare($totalQuery);
    $totalStmt->bind_param('ss', $searchTerm, $searchTerm);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRow = $totalResult->fetch_assoc();
    $totalPages = ceil($totalRow['total'] / $limit);
} catch (Exception $e) {
    $result = null;
    $numResults = 0;
    $totalPages = 0;
}
?>

<div class="container mx-auto mt-8 mb-16 px-4 lg:px-12">
    <div class="mb-4">
        <h1 class="text-xl font-bold mb-1">Hasil Pencarian untuk "<?php echo htmlspecialchars($query); ?>"</h1>
        <p class="text-sm text-gray-600"><?php echo $totalRow['total']; ?> data ditemukan</p>
    </div>
    <?php if ($result && $numResults > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while($row = $result->fetch_assoc()): ?>
                <?php
                $firstImage = '';
                if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                    $firstImage = $image['src'];
                }
                $description = strip_tags(html_entity_decode($row['konten_artikel']));
                $description = substr($description, 0, 100) . '...';
                ?>
                <div>
                    <a href="../category/news-detail.php?id=<?php echo $row['id']; ?>">
                        <img src="<?php echo $firstImage ?: 'https://via.placeholder.com/400x300'; ?>" class="w-full h-48 object-cover rounded-lg mb-4">
                    </a>
                    <a href="../category/news-detail.php?id=<?php echo $row['id']; ?>">
                        <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($row['judul']); ?></h2>
                    </a>
                    <p class="text-gray-500 mb-2"><?php echo date('d F Y', strtotime($row['tanggal_diterbitkan'])); ?></p>
                    <p class="text-gray-700 mb-4"><?php echo htmlspecialchars($description); ?></p>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination controls -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center mt-4">
                <nav class="inline-flex shadow-sm -space-x-px" aria-label="Pagination">
                    <!-- Tombol "prev" -->
                    <?php if ($page > 1): ?>
                        <a href="?query=<?php echo urlencode($query); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $page - 1; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span>Prev</span>
                        </a>
                    <?php else: ?>
                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-200 text-sm font-medium text-gray-500">
                            <span>Prev</span>
                        </span>
                    <?php endif; ?>

                    <!-- Nomor halaman -->
                    <?php
                    $startPage = max(1, $page - 5);
                    $endPage = min($totalPages, $startPage + 9);
                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="z-10 bg-blue-500 border-blue-600 text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?query=<?php echo urlencode($query); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $i; ?>" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <!-- Tombol "next" -->
                    <?php if ($page < $totalPages): ?>
                        <a href="?query=<?php echo urlencode($query); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $page + 1; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span>Next</span>
                        </a>
                    <?php else: ?>
                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-200 text-sm font-medium text-gray-500">
                            <span>Next</span>
                        </span>
                    <?php endif; ?>
                </nav>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center">
            <i class="fas fa-search-minus text-gray-400 text-6xl mb-4"></i>
            <h2 class="text-2xl font-bold mb-2">Tidak Ada Hasil Ditemukan</h2>
            <p class="text-gray-700 mb-4">Kami tidak dapat menemukan konten yang sesuai dengan pencarian Anda<?php echo $category ? " dalam kategori '$category'" : ''; ?>. Silakan coba dengan kata kunci yang berbeda.</p>
        </div>
    <?php endif; ?>
</div>

<?php
// Menutup koneksi
$conn->close();
?>

<?php include 'header & footer/footer.php'; ?>

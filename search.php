<?php include 'header & footer/header.php'; ?>
<?php include 'connection/config.php'; ?>

<?php
// Mendapatkan query pencarian dan kategori
$query = isset($_GET['query']) ? $_GET['query'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

try {
    // Melakukan pencarian di database berdasarkan judul dan kategori
    $sql = "SELECT id, judul, konten_artikel, kategori, tanggal_dibuat FROM berita WHERE judul LIKE '%$query%'";
    if ($category) {
        $sql .= " AND kategori = '$category'";
    }
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Query gagal");
    }
    $numResults = $result->num_rows;
} catch (Exception $e) {
    $result = null;
    $numResults = 0;
}
?>

<div class="container mx-auto mt-8 mb-16 px-4 lg:px-12">
    <div class="mb-4">
        <h1 class="text-xl font-bold mb-1">Hasil Pencarian untuk "<?php echo htmlspecialchars($query); ?>"</h1>
        <p class="text-sm text-gray-600"><?php echo $numResults; ?> data ditemukan</p>
    </div>
    <?php if ($result && $numResults > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while($row = $result->fetch_assoc()): ?>
                <?php
                $firstImage = '';
                if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                    $firstImage = $image['src'];
                }
                $description = strip_tags($row['konten_artikel']);
                $description = substr($description, 0, 100) . '...';
                ?>
                <div>
                    <a href="../category/news-detail.php?id=<?php echo $row['id']; ?>">
                        <img src="<?php echo $firstImage ?: 'https://via.placeholder.com/400x300'; ?>" class="w-full h-48 object-cover rounded-lg mb-4">
                        <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($row['judul']); ?></h2>
                        <p class="text-gray-500 mb-2"><?php echo date('d F Y', strtotime($row['tanggal_dibuat'])); ?></p>
                        <p class="text-gray-700 mb-4"><?php echo htmlspecialchars($description); ?></p>
                        <span class="text-blue-500 hover:underline">Baca selengkapnya</span>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center">
            <img src="path/to/logo.png" alt="Logo" class="mx-auto mb-4" style="width: 100px; height: auto;">
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

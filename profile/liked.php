<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../connection/config.php'; // Pastikan jalur relatif ini benar

$user_id = $_SESSION['user_id'] ?? null;
$articles = [];

if ($user_id) {
    $stmt = $conn->prepare("
        SELECT r.berita_id, b.judul, b.konten_artikel, r.tanggal_reaksi
        FROM reaksi r
        JOIN berita b ON r.berita_id = b.id
        WHERE r.user_id = ? AND r.jenis_reaksi = 'Suka'
        ORDER BY r.tanggal_reaksi DESC
        LIMIT 20
    ");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
    $stmt->close();
}
?>

<div class="container mx-auto px-4 lg:px-8 mb-12" style="margin-left: 0;">
    <?php if (empty($articles)): ?>
        <div class="text-center mt-12">
            <i class="fas fa-folder text-6xl text-gray-400"></i>
            <p class="font-bold text-xl mt-4">Kamu belum memiliki artikel yang disukai</p>
            <p class="text-sm text-gray-500 mt-2">Cari artikelnya lalu klik icon <i class="fas fa-thumbs-up text-gray-400"></i></p>
            <a href="/index.php" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded">Lihat Artikel Hari Ini</a>
        </div>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <div class="flex items-start mb-6 relative">
                <div class="flex-grow max-w-3xl pr-4">
                    <h3 class="text-2xl font-medium mt-2 mb-2">
                        <a href="../category/news-detail.php?id=<?= htmlspecialchars($article['berita_id']) ?>" class="hover:underline">
                            <?= htmlspecialchars($article['judul']) ?>
                        </a>
                    </h3>
                    <p class="text-gray-500 text-sm mb-2">
                        | Disukai <?= date('d F Y', strtotime($article['tanggal_reaksi'])) ?>
                    </p>
                </div>
                <div class="relative">
                    <a href="../category/news-detail.php?id=<?= htmlspecialchars($article['berita_id']) ?>">
                        <img src="<?= get_first_image($article['konten_artikel']) ?>" alt="Gambar Disukai" class="w-56 h-28 object-cover rounded ml-2">
                    </a>
                    <div class="absolute top-0 right-[-15px] transform translate-x-1/2 -translate-y-1/2">
                        <i class="fas fa-ellipsis-v text-gray-500"></i>
                    </div>
                </div>
            </div>
            <div class="border-b border-gray-300 mb-4"></div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
function get_first_image($content) {
    preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image);
    return $image['src'] ?? 'https://via.placeholder.com/400x200';
}
?>
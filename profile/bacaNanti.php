<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../connection/config.php'; // Pastikan jalur relatif ini benar

function timeAgo($datetime) {
    $now = new DateTime();
    $posted = new DateTime(datetime: $datetime);
    $interval = $now->diff($posted);

    if ($interval->y > 0) return $interval->y . " tahun yang lalu";
    if ($interval->m > 0) return $interval->m . " bulan yang lalu";
    if ($interval->d > 0) return $interval->d . " hari yang lalu";
    if ($interval->h > 0) return $interval->h . " jam yang lalu";
    if ($interval->i > 0) return $interval->i . " menit yang lalu";
    return "baru saja";
}

$user_id = $_SESSION['user_id'] ?? null;
$articles = [];

if ($user_id) {
    $stmt = $conn->prepare("
        SELECT b.id AS berita_id, b.judul, b.konten_artikel, bm.tanggal_bookmark
        FROM bookmark bm
        JOIN berita b ON bm.berita_id = b.id
        WHERE bm.user_id = ?
        ORDER BY bm.tanggal_bookmark DESC
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

<div class="container mx-auto px-4 lg:px-8 mb-12">
    <?php if (empty($articles)): ?>
        <div class="text-center mt-12">
            <i class="fas fa-folder text-6xl text-gray-400"></i>
            <p class="font-bold text-xl mt-4">Kamu belum memiliki artikel yang dibaca nanti</p>
            <p class="text-sm text-gray-500 mt-2">Cari artikelnya lalu klik icon <i class="fas fa-bookmark text-gray-400"></i></p>
            <a href="/index.php" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded">Lihat Artikel Hari Ini</a>
        </div>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <div class="flex items-start mb-6 relative">
                <div class="flex-grow max-w-4xl pr-6">
                    <h3 class="text-2xl font-medium mt-2 mb-2">
                        <a href="../category/news-detail.php?id=<?= htmlspecialchars($article['berita_id']) ?>" class="hover:underline">
                            <?= htmlspecialchars($article['judul']) ?>
                        </a>
                    </h3>
                    <p class="text-gray-500 text-sm mb-2" data-time="<?= $article['tanggal_bookmark'] ?>">
                        | Disimpan <?= timeAgo($article['tanggal_bookmark']) ?>
                    </p>
                </div>
                <div class="relative">
                    <a href="../category/news-detail.php?id=<?= htmlspecialchars($article['berita_id']) ?>">
                        <img src="<?= get_first_image($article['konten_artikel']) ?>" alt="Gambar Ditandai" class="w-56 h-28 object-cover rounded ml-2">
                    </a>
                    <div class="absolute top-0 right-[-15px] transform translate-x-1/2 -translate-y-1/2">
                        <i class="fas fa-ellipsis-v text-gray-500"></i>
                    </div>
                </div>
            </div>
            <div class="border-b border-gray-300 mb-4 max-w-6xl"></div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    function updateTimeAgo() {
        const elements = document.querySelectorAll('[data-time]');
        elements.forEach(el => {
            const time = el.getAttribute('data-time');
            const localTime = new Date(time);
            localTime.setHours(localTime.getHours() + 7);
            const timeAgo = calculateTimeAgo(localTime);
            el.textContent = `| Disimpan ${timeAgo}`;
        });
    }

    function calculateTimeAgo(date) {
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        const intervals = [
            { label: 'tahun', seconds: 31536000 },
            { label: 'bulan', seconds: 2592000 },
            { label: 'hari', seconds: 86400 },
            { label: 'jam', seconds: 3600 },
            { label: 'menit', seconds: 60 },
            { label: 'detik', seconds: 1 }
        ];

        for (const interval of intervals) {
            const count = Math.floor(seconds / interval.seconds);
            if (count > 0) {
                return `${count} ${interval.label} yang lalu`;
            }
        }
        return 'baru saja';
    }

    setInterval(updateTimeAgo, 60000); // Update setiap menit
    updateTimeAgo(); // Inisialisasi
</script>

<?php
function get_first_image($content) {
    preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image);
    return $image['src'] ?? 'https://via.placeholder.com/400x200';
}
?>
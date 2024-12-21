<?php
include 'header & footer/header.php';
include 'connection/config.php';

function timeAgo($datetime)
{
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
?>

<div class="container mx-auto mt-8 mb-16 px-4 lg:px-12" id="recentNews">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Berita Terbaru</h1>
        <p class="text-gray-600">Informasi terkini yang perlu Anda ketahui</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="newsContainer">
        <?php
        // Query untuk mengambil berita terbaru dalam 7 hari terakhir
        $query = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan 
                  FROM berita 
                  WHERE tanggal_diterbitkan >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                  ORDER BY tanggal_diterbitkan DESC";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()):
            // Ekstrak gambar pertama dari konten artikel
            $firstImage = '';
            if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                $firstImage = $image['src'];
            }

            // Potong deskripsi
            $description = strip_tags(html_entity_decode($row['konten_artikel']));
            $description = substr($description, 0, 100) . '...';
        ?>
            <a href="../category/news-detail.php?id=<?= $row['id'] ?>" class="block">
                <img src="<?= $firstImage ?: 'https://via.placeholder.com/400x300' ?>"
                    class="w-full h-56 object-cover rounded-lg"
                    alt="<?= htmlspecialchars($row['judul']) ?>">
                
                <div class="mt-3">
                    <span class="text-sm text-red-500 font-bold"><?= $row['kategori'] ?></span>
                    <h2 class="text-xl font-bold mb-1"><?= $row['judul'] ?></h2>
                    <span class="text-sm text-gray-500 block mb-2"><?= timeAgo($row['tanggal_diterbitkan']) ?></span>
                    <p class="text-gray-600 text-sm"><?= $description ?></p>
                </div>
            </a>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'header & footer/footer.php'; ?>
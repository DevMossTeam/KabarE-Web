<?php
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
?>

<?php include '../header & footer/header.php'; ?>
<?php include '../header & footer/category_header.php'; ?>
<?php include '../connection/config.php'; ?>

<?php renderCategoryHeader('Kampus'); ?>

<!-- Main Content -->
<div class="container mx-auto mt-8 mb-16 px-6 lg:px-12">
    <!-- 4 Berita -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <?php
        $query = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan FROM berita WHERE kategori = 'Kampus' ORDER BY RAND() LIMIT 4";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()):
            $firstImage = ''; // Default jika tidak ada gambar
            if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                $firstImage = $image['src'];
            }

            // Ambil deskripsi singkat dari konten_artikel
            $description = strip_tags($row['konten_artikel']);
            $description = substr($description, 0, 150) . '...'; // Potong deskripsi

            // Format tanggal
            $formattedDate = date('d F Y', strtotime($row['tanggal_diterbitkan']));
        ?>
            <div class="relative">
                <a href="news-detail.php?id=<?= $row['id'] ?>">
                    <img src="<?= $firstImage ?: 'https://via.placeholder.com/600x350' ?>" class="w-full h-96 object-cover rounded-lg">
                </a>
                <div class="p-4">
                    <span class="text-red-500 font-bold"><?= $row['kategori'] ?></span> <span class="text-gray-500">| <?= $formattedDate ?></span>
                    <a href="news-detail.php?id=<?= $row['id'] ?>">
                        <h3 class="text-lg font-bold mt-1"><?= $row['judul'] ?></h3>
                    </a>
                    <p class="text-gray-700 mt-2"><?= $description ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Berita Populer -->
    <div class="py-8 mt-8">
        <div class="mb-4">
            <span class="inline-block bg-red-600 text-white px-6 py-1 rounded-t-md">Populer</span>
            <div class="border-b-4 border-red-600 mt-0"></div>
        </div>
        <!-- 3 Kartu Gambar -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
            <?php
            $queryPopuler = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan FROM berita WHERE kategori = 'Kampus' ORDER BY tanggal_diterbitkan DESC LIMIT 3";
            $resultPopuler = $conn->query($queryPopuler);

            while ($rowPopuler = $resultPopuler->fetch_assoc()):
                $firstImagePopuler = ''; // Default jika tidak ada gambar
                if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $rowPopuler['konten_artikel'], $image)) {
                    $firstImagePopuler = $image['src'];
                }

                // Waktu relatif
                $timeAgo = timeAgo($rowPopuler['tanggal_diterbitkan']);
            ?>
                <a href="news-detail.php?id=<?= $rowPopuler['id'] ?>" class="relative overflow-hidden rounded-lg">
                    <img src="<?= $firstImagePopuler ?: 'https://via.placeholder.com/300x200' ?>" class="w-full h-56 object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                        <span class="text-white font-bold"><?= $rowPopuler['kategori'] ?> | <?= $timeAgo ?></span>
                        <h3 class="text-white text-lg font-bold mt-1"><?= $rowPopuler['judul'] ?></h3>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Berita Lainnya dan Baru Baru Ini Section -->
    <div class="container mx-auto mt-8 mb-16 px-4 lg:px-20">
        <div class="flex flex-col lg:flex-row">
            <!-- Berita Lainnya Section -->
            <div class="w-full lg:w-2/3 pr-4">
                <div class="mb-4">
                    <span class="inline-block bg-[#45C630] text-white px-6 py-1 rounded-t-md">Berita Lainnya</span>
                    <div class="border-b-4 border-[#45C630] mt-0"></div>
                </div>
                <?php
                $queryLainnya = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan FROM berita WHERE kategori != 'Kampus' ORDER BY RAND() LIMIT 4";
                $resultLainnya = $conn->query($queryLainnya);

                while ($rowLainnya = $resultLainnya->fetch_assoc()):
                    $firstImageLainnya = '';
                    if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $rowLainnya['konten_artikel'], $image)) {
                        $firstImageLainnya = $image['src'];
                    }

                    $descriptionLainnya = strip_tags($rowLainnya['konten_artikel']);
                    $descriptionLainnya = substr($descriptionLainnya, 0, 150) . '...';

                    $timeAgoLainnya = timeAgo($rowLainnya['tanggal_diterbitkan']);
                ?>
                    <div class="flex mb-4 items-start">
                        <span class="text-gray-400 text-sm flex-shrink-0 w-24"><?= $timeAgoLainnya ?></span>
                        <div class="flex-grow ml-4">
                            <a href="news-detail.php?id=<?= $rowLainnya['id'] ?>">
                                <h3 class="text-lg font-bold"><?= $rowLainnya['judul'] ?></h3>
                            </a>
                            <p class="text-gray-500 mt-1"><?= $descriptionLainnya ?></p>
                        </div>
                        <div class="w-full max-w-2xl h-48 bg-gray-200 flex items-center justify-center overflow-hidden rounded-lg ml-4">
                            <img src="<?= $firstImageLainnya ?: 'https://via.placeholder.com/400x300' ?>" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="border-b border-gray-300 mt-1 mb-4"></div>
                <?php endwhile; ?>
            </div>

            <!-- Baru Baru Ini Section -->
            <div class="w-full lg:w-1/3 pl-4 mt-8 md:mt-12 lg:mt-0">
                <div class="mb-4">
                    <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">Baru Baru Ini</span>
                    <div class="border-b-4 border-[#FFC300] mt-0"></div>
                </div>
                <ul class="pl-4">
                    <?php
                    $queryBaru = "SELECT id, judul, tanggal_diterbitkan FROM berita WHERE kategori = 'Kampus' ORDER BY tanggal_diterbitkan DESC LIMIT 8";
                    $resultBaru = $conn->query($queryBaru);

                    while ($rowBaru = $resultBaru->fetch_assoc()):
                        $timeAgoBaru = timeAgo($rowBaru['tanggal_diterbitkan']);
                    ?>
                        <li class="mb-4">
                            <div class="flex items-center">
                                <div class="flex-grow">
                                    <span class="text-gray-400 text-sm"><?= $timeAgoBaru ?></span>
                                    <a href="news-detail.php?id=<?= $rowBaru['id'] ?>">
                                        <h3 class="text-lg font-bold mt-1"><?= $rowBaru['judul'] ?></h3>
                                    </a>
                                    <div class="border-b border-gray-300 mt-2 w-full"></div>
                                </div>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../header & footer/footer.php'; ?>
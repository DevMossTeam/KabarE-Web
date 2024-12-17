<?php
session_start(); // Pastikan sesi dimulai

// Cek apakah pengguna sudah login dan memiliki peran 'Admin'
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {
    header('Location: user-auth/login.php'); // Arahkan ke halaman login
    exit(); // Pastikan untuk menghentikan eksekusi skrip setelah pengalihan
}
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

<?php include 'header & footer/header.php'; ?>
<?php include 'connection/config.php'; ?>

<?php
function formatDate($datetime) {
    $date = new DateTime($datetime);
    return $date->format('d F Y, H:i');
}

// Query untuk mengambil data untuk slider secara acak
$querySlider = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan FROM berita ORDER BY RAND() LIMIT 7";
$resultSlider = $conn->query($querySlider);
$sliderData = [];

if ($resultSlider && $resultSlider->num_rows > 0) {
    while ($row = $resultSlider->fetch_assoc()) {
        $firstImage = '';
        if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
            $firstImage = $image['src'];
        }
        $row['firstImage'] = $firstImage;
        $sliderData[] = $row;
    }
}

// Query untuk mengambil 6 berita terbaru
$query = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan FROM berita ORDER BY tanggal_diterbitkan DESC LIMIT 6";
$result = $conn->query($query);
$beritaTerkini = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $beritaTerkini[] = $row;
    }
}

// Query untuk berita populer
$queryPopuler = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan FROM berita ORDER BY RAND() LIMIT 3";
$resultPopuler = $conn->query($queryPopuler);
$beritaPopuler = [];

if ($resultPopuler && $resultPopuler->num_rows > 0) {
    while ($row = $resultPopuler->fetch_assoc()) {
        $beritaPopuler[] = $row;
    }
}

// Query untuk berita baru
$queryBaru = "SELECT id, judul, tanggal_diterbitkan FROM berita ORDER BY tanggal_diterbitkan DESC LIMIT 6";
$resultBaru = $conn->query($queryBaru);
$beritaBaru = [];

if ($resultBaru && $resultBaru->num_rows > 0) {
    while ($row = $resultBaru->fetch_assoc()) {
        $beritaBaru[] = $row;
    }
}

// Array kategori
$categories = ['Kampus', 'Prestasi', 'Politik', 'Kesehatan', 'Olahraga', 'Ekonomi', 'Bisnis', 'UKM'];

// Pilih dua kategori acak yang berbeda
$randomCategory1 = $categories[array_rand($categories)];
do {
    $randomCategory2 = $categories[array_rand($categories)];
} while ($randomCategory1 === $randomCategory2);

// Query untuk kategori acak pertama (satu gambar besar)
$queryRandomCategory1 = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan FROM berita WHERE kategori = '$randomCategory1' ORDER BY RAND() LIMIT 1";
$resultRandomCategory1 = $conn->query($queryRandomCategory1);
$rowRandomCategory1 = $resultRandomCategory1->fetch_assoc();
$firstImageRandomCategory1 = '';
if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $rowRandomCategory1['konten_artikel'], $image)) {
    $firstImageRandomCategory1 = $image['src'];
}

// Query untuk kategori acak kedua (dua berita)
$queryRandomCategory2 = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan FROM berita WHERE kategori = '$randomCategory2' ORDER BY RAND() LIMIT 2";
$resultRandomCategory2 = $conn->query($queryRandomCategory2);
$beritaRandomCategory2 = [];

if ($resultRandomCategory2 && $resultRandomCategory2->num_rows > 0) {
    while ($row = $resultRandomCategory2->fetch_assoc()) {
        $beritaRandomCategory2[] = $row;
    }
}

// Query untuk berita lainnya
$queryBeritaLainnya = "SELECT id, judul, konten_artikel, tanggal_diterbitkan FROM berita ORDER BY RAND() LIMIT 4";
$resultBeritaLainnya = $conn->query($queryBeritaLainnya);
$beritaLainnya = [];

if ($resultBeritaLainnya && $resultBeritaLainnya->num_rows > 0) {
    while ($row = $resultBeritaLainnya->fetch_assoc()) {
        $beritaLainnya[] = $row;
    }
}
?>

<!-- Main Content -->
<div class="flex-grow container mx-auto mt-8 mb-8 relative z-0 px-4 lg:px-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- News Cards -->
        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-4 order-2 lg:order-1">
            <?php
            $query = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan FROM berita ORDER BY RAND() LIMIT 3";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()):
                $firstImage = '';
                if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $row['konten_artikel'], $image)) {
                    $firstImage = $image['src'];
                }

                $title = substr($row['judul'], 0, 100) . (strlen($row['judul']) > 100 ? '...' : '');
                $description = strip_tags($row['konten_artikel']);
                $description = substr($description, 0, 100) . (strlen($description) > 100 ? '...' : '');
            ?>
                <a href="../category/news-detail.php?id=<?= $row['id'] ?>" class="flex flex-col md:flex-row items-start mb-6">
                    <div class="w-full md:w-48 h-32 bg-gray-200 flex-shrink-0 flex items-center justify-center overflow-hidden rounded-lg">
                        <img src="<?= $firstImage ?: 'https://via.placeholder.com/200x150' ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col justify-start md:ml-4">
                        <h3 class="text-md font-bold mt-1"><?= $title ?></h3>
                        <p class="text-gray-500 mt-1 text-xs"><?= $description ?></p>
                        <span class="text-sm mt-1">
                            <span class="text-red-500 font-bold"><?= $row['kategori'] ?></span>
                            <span class="text-gray-400"> | <?= timeAgo($row['tanggal_diterbitkan']) ?></span>
                        </span>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>

        <!-- Image Slider -->
        <div class="relative mb-8 md:mb-0 group order-1 lg:order-2 z-0">
            <div class="relative overflow-hidden h-96 rounded-lg">
                <div class="flex transition-transform duration-500 ease-in-out" id="slider">
                    <?php foreach ($sliderData as $data): ?>
                        <div class="relative w-full h-full flex-shrink-0">
                            <img src="<?= $data['firstImage'] ?: 'https://via.placeholder.com/800x600' ?>" 
                                 class="w-full h-96 object-cover rounded-lg transition duration-300 ease-in-out brightness-75 hover:brightness-50">
                            <div class="absolute top-4 left-4 text-white">
                                <span class="font-bold"><?= $data['kategori'] ?></span>
                                <a href="../category/news-detail.php?id=<?= $data['id'] ?>">
                                    <h3 class="text-lg font-bold mt-1"><?= $data['judul'] ?></h3>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Left and Right Toggle inside the slider -->
                <button id="prev" class="absolute top-1/2 left-4 transform -translate-y-1/2 p-3 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-md z-10">
                    &#10094;
                </button>
                <button id="next" class="absolute top-1/2 right-4 transform -translate-y-1/2 p-3 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-md z-10">
                    &#10095;
                </button>

                <!-- Indicator Dots inside the slider -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                    <?php for ($i = 0; $i < count($sliderData); $i++): ?>
                        <button class="w-3 h-3 rounded-full bg-gray-400 transition-all duration-300 ease-in-out" data-slide="<?= $i ?>"></button>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Berita Terkini -->
    <div class="py-12 mt-8 bg-black -mx-20 px-20">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="bg-[#FF3232] w-2 h-6"></div>
                <h2 class="text-xl font-bold ml-2 text-white">Berita Terkini</h2>
            </div>
        </div>
        <!-- 3 Kartu Gambar di Atas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <?php for ($i = 0; $i < 3; $i++): ?>
                <?php if (isset($beritaTerkini[$i])): ?>
                    <?php
                    $firstImage = '';
                    if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $beritaTerkini[$i]['konten_artikel'], $image)) {
                        $firstImage = $image['src'];
                    }
                    ?>
                    <a href="../category/news-detail.php?id=<?= $beritaTerkini[$i]['id'] ?>" class="relative overflow-hidden rounded-lg">
                        <img src="<?= $firstImage ?: 'https://via.placeholder.com/300x200' ?>" class="w-full h-56 md:h-56 lg:h-56 object-cover">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                            <span class="text-white font-bold"><?= $beritaTerkini[$i]['kategori'] ?> | <?= timeAgo($beritaTerkini[$i]['tanggal_diterbitkan']) ?></span>
                            <h3 class="text-white text-lg font-bold mt-1"><?= $beritaTerkini[$i]['judul'] ?></h3>
                        </div>
                    </a>
                <?php else: ?>
                    <p class="text-white">Tidak ada berita terkini yang tersedia.</p>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <!-- 3 Gambar di Bawah dengan Teks di Samping Kanan -->
        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-4">
            <?php for ($i = 3; $i < 6; $i++): ?>
                <?php if (isset($beritaTerkini[$i])): ?>
                    <?php
                    $firstImage = '';
                    if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $beritaTerkini[$i]['konten_artikel'], $image)) {
                        $firstImage = $image['src'];
                    }
                    ?>
                    <a href="../category/news-detail.php?id=<?= $beritaTerkini[$i]['id'] ?>" class="flex flex-col md:flex-row items-start">
                        <img src="<?= $firstImage ?: 'https://via.placeholder.com/200x150' ?>" class="w-full md:w-1/3 h-32 md:h-40 object-cover rounded-lg">
                        <div class="ml-4">
                            <h3 class="text-md font-bold text-white"><?= $beritaTerkini[$i]['judul'] ?></h3>
                            <p class="text-gray-300 text-sm"><?= substr(strip_tags($beritaTerkini[$i]['konten_artikel']), 0, 100) . '...' ?></p>
                        </div>
                    </a>
                <?php else: ?>
                    <p class="text-white">Tidak ada berita terkini yang tersedia.</p>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Populer dan Baru Baru Ini Section -->
    <div class="flex flex-col lg:flex-row mt-8">
        <!-- Populer Section -->
        <div class="w-full lg:w-2/3 pr-4">
            <div class="mb-4">
                <span class="inline-block bg-[#FF3232] text-white px-6 py-1 rounded-t-md">Populer</span>
                <div class="border-b-4 border-[#FF3232] mt-0"></div>
            </div>
            <?php foreach ($beritaPopuler as $populer): ?>
                <?php
                $firstImage = '';
                if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $populer['konten_artikel'], $image)) {
                    $firstImage = $image['src'];
                }
                $title = substr($populer['judul'], 0, 100) . (strlen($populer['judul']) > 100 ? '...' : '');
                $description = strip_tags($populer['konten_artikel']);
                $description = substr($description, 0, 300) . (strlen($description) > 300 ? '...' : '');
                ?>
                <div class="flex mb-6 items-start">
                    <div class="flex-grow">
                        <a href="../category/news-detail.php?id=<?= $populer['id'] ?>">
                            <h3 class="text-lg font-bold mt-1"><?= $title ?></h3>
                        </a>
                        <p class="text-gray-500 mt-1"><?= $description ?></p>
                    </div>
                    <a href="../category/news-detail.php?id=<?= $populer['id'] ?>" class="flex-shrink-0 h-48 bg-gray-200 flex items-center justify-center overflow-hidden rounded-lg ml-4" style="width: 300px;">
                        <img src="<?= $firstImage ?: 'https://via.placeholder.com/400x300' ?>" class="w-full h-full object-cover">
                    </a>
                </div>
             <?php endforeach; ?>
        </div>

        <!-- Baru Baru Ini Section -->
        <div class="w-full lg:w-1/3 pl-4 mt-8 md:mt-12 lg:mt-0">
            <div class="mb-4">
                <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">Baru Baru Ini</span>
                <div class="border-b-4 border-[#FFC300] mt-0"></div>
            </div>
            <ul class="pl-4">
                <?php foreach ($beritaBaru as $index => $baru): ?>
                    <li class="mb-4 flex items-center">
                        <span class="text-[#CAD2FF] text-5xl font-semibold italic mr-4 flex-shrink-0"><?= $index + 1 ?></span>
                        <div class="flex-grow">
                            <span class="text-gray-400 text-base"><?= timeAgo($baru['tanggal_diterbitkan']) ?></span>
                            <a href="../category/news-detail.php?id=<?= $baru['id'] ?>">
                                <h3 class="text-lg font-bold mt-1"><?= $baru['judul'] ?></h3>
                            </a>
                            <div class="border-b border-gray-300 mt-2 w-full"></div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Random Category Section -->
<div class="container mx-auto mt-8 mb-16 px-4 lg:px-20">
    <div class="mb-4">
        <span class="inline-block bg-red-600 text-white px-6 py-1 rounded-t-md"><?= $randomCategory1 ?></span>
        <div class="border-b-4 border-red-600 mt-0"></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Gambar Besar -->
        <div>
            <a href="../category/news-detail.php?id=<?= $rowRandomCategory1['id'] ?>">
                <img src="<?= $firstImageRandomCategory1 ?: 'https://via.placeholder.com/600x350' ?>" class="w-full h-96 object-cover rounded-lg">
            </a>
            <div class="p-4" style="padding-left: 0; padding-right: 0;">
                <span class="text-red-500 font-bold"><?= $rowRandomCategory1['kategori'] ?></span> 
                <span class="text-gray-500">| <?= date('d F Y', strtotime($rowRandomCategory1['tanggal_diterbitkan'])) ?></span>
                <a href="../category/news-detail.php?id=<?= $rowRandomCategory1['id'] ?>">
                    <h3 class="text-lg font-bold mt-1"><?= $rowRandomCategory1['judul'] ?></h3>
                </a>
                <p class="text-gray-700 mt-2"><?= substr(strip_tags($rowRandomCategory1['konten_artikel']), 0, 100) . '...' ?></p>
            </div>
        </div>
        <!-- Gambar Kecil dan Daftar -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <?php
                $queryRandomCategorySmall = "SELECT id, judul, konten_artikel FROM berita WHERE kategori = '$randomCategory1' ORDER BY RAND() LIMIT 3";
                $resultRandomCategorySmall = $conn->query($queryRandomCategorySmall);
                while ($rowRandomCategorySmall = $resultRandomCategorySmall->fetch_assoc()):
                    $firstImageRandomCategorySmall = '';
                    if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $rowRandomCategorySmall['konten_artikel'], $image)) {
                        $firstImageRandomCategorySmall = $image['src'];
                    }
                ?>
                    <a href="../category/news-detail.php?id=<?= $rowRandomCategorySmall['id'] ?>" class="block mb-4 relative">
                        <img src="<?= $firstImageRandomCategorySmall ?: 'https://via.placeholder.com/300x200' ?>" class="w-full h-48 object-cover rounded-lg rounded-b-lg">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4 rounded-b-lg">
                            <h3 class="text-white text-sm font-bold"><?= $rowRandomCategorySmall['judul'] ?></h3>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
            <!-- Daftar Judul -->
            <div>
                <ul>
                    <?php
                    $queryRandomCategoryList = "SELECT id, judul, tanggal_diterbitkan FROM berita WHERE kategori = '$randomCategory1' ORDER BY tanggal_diterbitkan DESC LIMIT 6";
                    $resultRandomCategoryList = $conn->query($queryRandomCategoryList);
                    while ($rowRandomCategoryList = $resultRandomCategoryList->fetch_assoc()):
                    ?>
                        <li class="mb-2 border-b border-gray-300 pb-2">
                            <span class="text-gray-400 text-sm block"><?= timeAgo($rowRandomCategoryList['tanggal_diterbitkan']) ?></span>
                            <a href="../category/news-detail.php?id=<?= $rowRandomCategoryList['id'] ?>" class="text-black hover:underline font-bold"><?= $rowRandomCategoryList['judul'] ?></a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Random Category Bottom Section -->
<div class="container mx-auto mt-8 mb-16 px-4 lg:px-20">
    <div class="mb-4">
        <span class="inline-block bg-red-600 text-white px-6 py-1 rounded-t-md"><?= $randomCategory2 ?></span>
        <div class="border-b-4 border-red-600 mt-0"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <?php foreach ($beritaRandomCategory2 as $item): ?>
            <?php
            $firstImageBottom = '';
            if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $item['konten_artikel'], $image)) {
                $firstImageBottom = $image['src'];
            }
            ?>
            <div class="flex flex-col">
                <a href="../category/news-detail.php?id=<?= $item['id'] ?>">
                    <img src="<?= $firstImageBottom ?: 'https://via.placeholder.com/600x350' ?>" class="w-full h-96 object-cover rounded-lg mb-4">
                </a>
                <div>
                    <span class="text-red-500 font-bold"><?= $item['kategori'] ?></span> 
                    <span class="text-gray-500">| <?= date('d F Y', strtotime($item['tanggal_diterbitkan'])) ?></span>
                    <a href="../category/news-detail.php?id=<?= $item['id'] ?>">
                        <h3 class="text-lg font-bold mt-1"><?= $item['judul'] ?></h3>
                    </a>
                    <p class="text-gray-700 mt-2"><?= substr(strip_tags($item['konten_artikel']), 0, 100) . '...' ?></p>
                </div>
            </div>
        <?php endforeach; ?>
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
            $queryLainnya = "SELECT id, judul, konten_artikel, kategori, tanggal_diterbitkan FROM berita WHERE kategori != '$randomCategory1' ORDER BY RAND() LIMIT 4";
            $resultLainnya = $conn->query($queryLainnya);

            while ($rowLainnya = $resultLainnya->fetch_assoc()):
                $firstImageLainnya = '';
                if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $rowLainnya['konten_artikel'], $image)) {
                    $firstImageLainnya = $image['src'];
                }

                $descriptionLainnya = strip_tags($rowLainnya['konten_artikel']);
                $descriptionLainnya = substr($descriptionLainnya, 0, 100) . '...';

                $timeAgoLainnya = timeAgo($rowLainnya['tanggal_diterbitkan']);
            ?>
                <div class="flex mb-4 items-start">
                    <span class="text-gray-400 text-sm flex-shrink-0 w-24"><?= $timeAgoLainnya ?></span>
                    <div class="flex-grow ml-4">
                        <a href="../category/news-detail.php?id=<?= $rowLainnya['id'] ?>">
                            <h3 class="text-lg font-bold"><?= $rowLainnya['judul'] ?></h3>
                        </a>
                        <p class="text-gray-500 mt-1"><?= $descriptionLainnya ?></p>
                    </div>
                    <div class="flex-shrink-0 h-48 bg-gray-200 flex items-center justify-center overflow-hidden rounded-lg ml-4" style="width: 300px;">
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
                $queryBaru = "SELECT id, judul, tanggal_diterbitkan FROM berita WHERE kategori = '$randomCategory1' ORDER BY tanggal_diterbitkan DESC LIMIT 8";
                $resultBaru = $conn->query($queryBaru);

                while ($rowBaru = $resultBaru->fetch_assoc()):
                    $timeAgoBaru = timeAgo($rowBaru['tanggal_diterbitkan']);
                ?>
                    <li class="mb-4">
                        <div class="flex items-center">
                            <div class="flex-grow">
                                <span class="text-gray-400 text-sm"><?= $timeAgoBaru ?></span>
                                <a href="../category/news-detail.php?id=<?= $rowBaru['id'] ?>">
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

<?php include 'header & footer/footer.php'; ?>

<script>
    const slider = document.getElementById('slider');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    const dots = document.querySelectorAll('[data-slide]');
    let currentSlide = 0;
    const slideCount = <?= count($sliderData) ?>;

    function updateSlider() {
        slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach(dot => {
            dot.classList.remove('bg-white', 'w-6', 'h-2');
            dot.classList.add('bg-gray-400', 'w-3', 'h-3', 'rounded-full');
        });
        dots[currentSlide].classList.add('bg-white', 'w-6', 'h-2');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slideCount;
        updateSlider();
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slideCount) % slideCount;
        updateSlider();
    }

    nextButton.addEventListener('click', nextSlide);
    prevButton.addEventListener('click', prevSlide);

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            currentSlide = parseInt(dot.getAttribute('data-slide'));
            updateSlider();
        });
    });

    // Set interval for automatic slide
    setInterval(nextSlide, 5000); // Change slide every 5 seconds

    updateSlider();
</script>
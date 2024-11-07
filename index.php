<?php include 'header & footer/header.php'; ?>
<?php include 'connection/config.php'; ?>

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

// Query untuk mengambil 6 berita terbaru
$query = "SELECT id, judul, konten_artikel, kategori, tanggal_dibuat FROM berita ORDER BY tanggal_dibuat DESC LIMIT 6";
$result = $conn->query($query);
$beritaTerkini = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $beritaTerkini[] = $row;
    }
}
?>

<!-- Main Content -->
<div class="flex-grow container mx-auto mt-8 mb-8 relative z-0 px-4 lg:px-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- News Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-1 gap-4 order-2 lg:order-1">
            <?php
            $query = "SELECT id, judul, konten_artikel, kategori, tanggal_dibuat FROM berita ORDER BY RAND() LIMIT 3";
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
                <a href="news-detail.php?id=<?= $row['id'] ?>" class="flex flex-col lg:flex-row items-center lg:items-start transform lg:scale-105">
                    <img src="<?= $firstImage ?: 'https://via.placeholder.com/200x150' ?>" class="w-48 h-32 object-cover rounded-lg mb-0.5 lg:mb-0 transition-transform duration-300 hover:scale-105 cursor-pointer">
                    <div class="flex flex-col justify-center text-center md:text-center lg:text-left lg:ml-6 lg:w-full lg:pr-8 lg:-mt-2">
                        <h3 class="text-md font-bold mt-1 line-clamp-3"><?= $title ?></h3>
                        <p class="text-gray-500 mt-1 line-clamp-3"><?= $description ?></p>
                        <span class="text-sm mt-1 inline-block">
                            <span class="text-red-500 font-bold"><?= $row['kategori'] ?></span>
                            <span class="text-gray-400"> | <?= timeAgo($row['tanggal_dibuat']) ?></span>
                        </span>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>

        <!-- Image Slider -->
        <div class="relative mb-8 md:mb-0 group order-1 lg:order-2 z-0">
            <!-- Teks dan Label di atas Image Slider -->
            <div class="text-left mb-4">
                <span class="inline-block bg-[#FF3232] text-white px-4 py-1 rounded-md transition-transform duration-500 ease-in-out transform translate-x-full opacity-0" id="category">Kategori 1</span>
                <a href="news-detail.php">
                    <h2 class="text-2xl font-bold mt-2 transition-transform duration-500 ease-in-out transform translate-x-full opacity-0" id="headline">Lorem Ipsum Dolor Sit Amet 1</h2>
                </a>
                <p class="text-gray-600 mt-2 transition-transform duration-500 ease-in-out transform translate-x-full opacity-0" id="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>

            <div class="relative overflow-hidden h-96 rounded-lg">
                <div class="flex transition-transform duration-500 ease-in-out" id="slider">
                    <?php for ($i = 1; $i <= 7; $i++): ?>
                        <img src="https://via.placeholder.com/800x600?text=Image+<?= $i ?>" 
                             class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:brightness-75 transform -translate-y-24"
                             loading="lazy">
                    <?php endfor; ?>
                </div>

                <!-- Left and Right Toggle inside the slider -->
                <button id="prev" class="absolute top-1/2 left-4 transform -translate-y-1/2 p-3 bg-gray-800 text-white rounded-md z-10">
                    &#10094;
                </button>
                <button id="next" class="absolute top-1/2 right-4 transform -translate-y-1/2 p-3 bg-gray-800 text-white rounded-md z-10">
                    &#10095;
                </button>

                <!-- Indicator Dots inside the slider -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                    <?php for ($i = 0; $i < 7; $i++): ?>
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
                    <a href="news-detail.php?id=<?= $beritaTerkini[$i]['id'] ?>" class="relative overflow-hidden rounded-lg">
                        <img src="<?= $firstImage ?: 'https://via.placeholder.com/300x200' ?>" class="w-full h-56 object-cover">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                            <span class="text-white font-bold"><?= $beritaTerkini[$i]['kategori'] ?> | <?= timeAgo($beritaTerkini[$i]['tanggal_dibuat']) ?></span>
                            <h3 class="text-white text-lg font-bold mt-1"><?= $beritaTerkini[$i]['judul'] ?></h3>
                        </div>
                    </a>
                <?php else: ?>
                    <p class="text-white">Tidak ada berita terkini yang tersedia.</p>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <!-- 3 Gambar di Bawah dengan Teks di Samping Kanan -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <?php for ($i = 3; $i < 6; $i++): ?>
                <?php if (isset($beritaTerkini[$i])): ?>
                    <?php
                    $firstImage = '';
                    if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $beritaTerkini[$i]['konten_artikel'], $image)) {
                        $firstImage = $image['src'];
                    }
                    ?>
                    <a href="news-detail.php?id=<?= $beritaTerkini[$i]['id'] ?>" class="flex items-center">
                        <img src="<?= $firstImage ?: 'https://via.placeholder.com/200x150' ?>" class="w-full md:w-1/2 lg:w-1/2 h-32 md:h-40 lg:h-48 object-cover rounded-lg">
                        <div class="ml-4">
                            <h3 class="text-md font-bold text-white"><?= $beritaTerkini[$i]['judul'] ?></h3>
                            <p class="text-gray-300 text-sm"><?= substr(strip_tags($beritaTerkini[$i]['konten_artikel']), 0, 150) . '...' ?></p>
                        </div>
                    </a>
                <?php else: ?>
                    <p class="text-white">Tidak ada berita terkini yang tersedia.</p>
                <?php endif; ?>
            <?php endfor; ?>
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

    const headlines = [
        "Lorem Ipsum Dolor Sit Amet 1",
        "Lorem Ipsum Dolor Sit Amet 2",
        "Lorem Ipsum Dolor Sit Amet 3",
        "Lorem Ipsum Dolor Sit Amet 4",
        "Lorem Ipsum Dolor Sit Amet 5",
        "Lorem Ipsum Dolor Sit Amet 6",
        "Lorem Ipsum Dolor Sit Amet 7"
    ];

    const descriptions = [
        "27 Januari 2024, 20:20 | 2 menit yang lalu",
        "28 Januari 2024, 14:15 | 5 menit yang lalu",
        "29 Januari 2024, 09:30 | 10 menit yang lalu",
        "30 Januari 2024, 18:45 | 15 menit yang lalu",
        "31 Januari 2024, 12:00 | 20 menit yang lalu",
        "1 Februari 2024, 08:00 | 25 menit yang lalu",
        "2 Februari 2024, 16:30 | 30 menit yang lalu"
    ];

    const categories = [
        "Kategori 1",
        "Kategori 2",
        "Kategori 3",
        "Kategori 4",
        "Kategori 5",
        "Kategori 6",
        "Kategori 7"
    ];

    const headlineElement = document.getElementById('headline');
    const descriptionElement = document.getElementById('description');
    const categoryElement = document.getElementById('category');

    function updateSlider() {
        slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach(dot => dot.classList.remove('bg-gray-800', 'w-6', 'h-2'));
        dots.forEach(dot => dot.classList.add('w-3', 'h-3', 'rounded-full'));
        dots[currentSlide].classList.add('bg-gray-800', 'w-6', 'h-2', 'rounded-full');

        headlineElement.classList.remove('translate-x-0', 'opacity-100');
        descriptionElement.classList.remove('translate-x-0', 'opacity-100');
        categoryElement.classList.remove('translate-x-0', 'opacity-100');

        setTimeout(() => {
            headlineElement.textContent = headlines[currentSlide];
            descriptionElement.textContent = descriptions[currentSlide];
            categoryElement.textContent = categories[currentSlide];

            headlineElement.classList.add('translate-x-0', 'opacity-100');
            descriptionElement.classList.add('translate-x-0', 'opacity-100');
            categoryElement.classList.add('translate-x-0', 'opacity-100');
        }, 100);
    }

    nextButton.addEventListener('click', () => {
        currentSlide = (currentSlide + 1) % headlines.length;
        updateSlider();
    });

    prevButton.addEventListener('click', () => {
        currentSlide = (currentSlide - 1 + headlines.length) % headlines.length;
        updateSlider();
    });

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            currentSlide = parseInt(dot.getAttribute('data-slide'));
            updateSlider();
        });
    });

    updateSlider();

    // Latest News Slider
    const latestNewsSlider = document.getElementById('latest-news-slider');
    const latestPrevButton = document.getElementById('latest-prev');
    const latestNextButton = document.getElementById('latest-next');
    let latestCurrentSlide = 0;

    function updateLatestNewsSlider() {
        latestNewsSlider.style.transform = `translateX(-${latestCurrentSlide * 14.2857}%)`;
    }

    latestNextButton.addEventListener('click', () => {
        latestCurrentSlide = (latestCurrentSlide + 1) % 10;
        updateLatestNewsSlider();
    });

    latestPrevButton.addEventListener('click', () => {
        latestCurrentSlide = (latestCurrentSlide - 1 + 10) % 10;
        updateLatestNewsSlider();
    });

    updateLatestNewsSlider();
</script>

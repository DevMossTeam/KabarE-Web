<?php include 'header.php'; ?>

<!-- Main Content -->
<div class="flex-grow container mx-auto mt-8 mb-8 relative z-10">
    <!-- Image Slider and News Cards -->
    <div class="flex flex-col md:flex-row justify-between">
        <!-- Image Slider -->
        <div class="relative w-full md:w-2/3 mb-8 md:mb-0 group">
            <!-- Slider Images -->
            <div class="relative overflow-hidden h-96 rounded-lg">
                <div class="flex transition-transform duration-500 ease-in-out" id="slider">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <img src="https://via.placeholder.com/800x600?text=Image+<?= $i ?>" class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:brightness-75">
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
                    <?php for ($i = 0; $i < 12; $i++): ?>
                        <button class="w-3 h-3 rounded-full bg-gray-400 transition-all duration-300 ease-in-out" data-slide="<?= $i ?>"></button>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- News Cards -->
        <div class="w-full md:w-1/3 flex flex-col space-y-4 md:ml-4">
            <!-- Card 1 -->
            <div class="flex md:flex-row flex-col items-center md:items-start">
                <img src="https://via.placeholder.com/200x150" class="w-40 h-32 object-cover rounded-lg mb-2 md:mb-0 transition-transform duration-300 hover:scale-105 cursor-pointer">
                <div class="md:ml-4 text-center md:text-left">
                    <h3 class="text-lg font-bold mt-2">Judul Berita 1</h3>
                    <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-md mt-2 inline-block">Kategori</span>
                    <span class="text-gray-500 text-sm mt-1 block">12 menit yang lalu</span>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="flex md:flex-row flex-col items-center md:items-start">
                <img src="https://via.placeholder.com/200x150" class="w-40 h-32 object-cover rounded-lg mb-2 md:mb-0 transition-transform duration-300 hover:scale-105 cursor-pointer">
                <div class="md:ml-4 text-center md:text-left">
                    <h3 class="text-lg font-bold mt-2">Judul Berita 2</h3>
                    <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-md mt-2 inline-block">Kategori</span>
                    <span class="text-gray-500 text-sm mt-1 block">30 menit yang lalu</span>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="flex md:flex-row flex-col items-center md:items-start">
                <img src="https://via.placeholder.com/200x150" class="w-40 h-32 object-cover rounded-lg mb-2 md:mb-0 transition-transform duration-300 hover:scale-105 cursor-pointer">
                <div class="md:ml-4 text-center md:text-left">
                    <h3 class="text-lg font-bold mt-2">Judul Berita 3</h3>
                    <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-md mt-2 inline-block">Kategori</span>
                    <span class="text-gray-500 text-sm mt-1 block">1 jam yang lalu</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Berita Terkini -->
    <div class="border-t border-b border-gray-300 py-4 mt-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="bg-orange-500 w-1 h-6"></div>
                <h2 class="text-xl font-bold ml-2">Berita Terkini</h2>
            </div>
            <!-- Toggle Buttons -->
            <div class="flex space-x-2">
                <button id="latest-prev" class="p-2 bg-gray-800 text-white rounded-md z-10">
                    &#10094;
                </button>
                <button id="latest-next" class="p-2 bg-gray-800 text-white rounded-md z-10">
                    &#10095;
                </button>
            </div>
        </div>
        <div class="relative overflow-hidden">
            <div class="flex transition-transform duration-500 ease-in-out" id="latest-news-slider" style="width: 700%;">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <div class="w-1/7 p-2 flex-shrink-0">
                        <div class="bg-white shadow-md rounded-lg overflow-hidden">
                            <img src="https://via.placeholder.com/150x100" class="w-full h-32 object-cover">
                            <div class="p-4">
                                <h3 class="text-md font-bold mt-2">Judul Berita <?= $i ?></h3>
                                <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-md mt-2 inline-block">Kategori</span>
                                <span class="text-gray-500 text-sm mt-1 block">12 menit yang lalu</span>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Berita Populer dan Indept -->
    <div class="border-t border-b border-gray-300 py-4 mt-8">
        <div class="flex">
            <!-- Populer Section -->
            <div class="w-2/3 pr-4">
                <div class="flex items-center mb-4">
                    <div class="bg-orange-500 w-1 h-6"></div>
                    <h2 class="text-xl font-bold ml-2">Populer</h2>
                </div>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <div class="flex mb-4 popular-card <?php if ($i > 5) echo 'hidden'; ?>">
                        <img src="https://via.placeholder.com/400x300" class="w-64 h-48 object-cover rounded-lg">
                        <div class="ml-4">
                            <h3 class="text-lg font-bold mt-2">Judul Populer <?= $i ?></h3>
                            <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-md mt-2 inline-block">Kategori</span>
                            <span class="text-gray-500 text-sm mt-1 block">12 menit yang lalu</span>
                        </div>
                    </div>
                <?php endfor; ?>
                <button id="show-more-popular" class="text-blue-500 mt-4">Populer Lainnya</button>
            </div>

            <!-- Indept Section -->
            <div class="w-1/3 pl-4 border-l border-gray-300">
                <div class="flex items-center mb-4">
                    <div class="bg-orange-500 w-1 h-6"></div>
                    <h2 class="text-xl font-bold ml-2">Indept</h2>
                </div>
                <ul class="list-disc pl-4">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <li class="mb-2">Indep Item <?= $i ?></li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Kategori Lainnya -->
    <div class="border-t border-b border-gray-300 py-4 mt-8">
        <?php 
        $categories = ['Teknologi', 'Olahraga', 'Ekonomi'];
        foreach ($categories as $category): ?>
            <div class="flex mb-8 ">
                <div class="w-1/3">
                    <img src="https://via.placeholder.com/400x300" class="w-full h-48 object-cover rounded-lg">
                </div>
                <div class="w-2/3 pl-4 border-l border-gray-300">
                    <div class="flex items-center mb-4">
                        <div class="bg-orange-500 w-1 h-6"></div>
                        <h2 class="text-xl font-bold ml-2"><?= $category ?></h2>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-lg font-bold mt-2">Judul <?= $category ?> 1</h3>
                        <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-md mt-2 inline-block">Kategori</span>
                        <span class="text-gray-500 text-sm mt-1 block">12 menit yang lalu</span>
                    </div>
                    <ul class="list-disc pl-4">
                        <?php for ($i = 2; $i <= 5; $i++): ?>
                            <li class="mb-2">Judul <?= $category ?> <?= $i ?></li>
                        <?php endfor; ?>
                    </ul>
                    <button class="text-blue-500 mt-4 flex items-center">
                        Lihat Lebih Banyak <span class="ml-2">&#10095;</span>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    const slider = document.getElementById('slider');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    const dots = document.querySelectorAll('[data-slide]');
    let currentSlide = 0;

    // Update slider position and dot indicator
    function updateSlider() {
        slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach(dot => dot.classList.remove('bg-gray-800', 'w-6', 'h-2'));
        dots.forEach(dot => dot.classList.add('w-3', 'h-3', 'rounded-full'));
        dots[currentSlide].classList.add('bg-gray-800', 'w-6', 'h-2', 'rounded-full');
    }

    // Next slide
    nextButton.addEventListener('click', () => {
        currentSlide = (currentSlide + 1) % dots.length;
        updateSlider();
    });

    // Previous slide
    prevButton.addEventListener('click', () => {
        currentSlide = (currentSlide - 1 + dots.length) % dots.length;
        updateSlider();
    });

    // Dot indicator click
    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            currentSlide = parseInt(dot.getAttribute('data-slide'));
            updateSlider();
        });
    });

    // Initialize slider
    updateSlider();

    // Latest News Slider
    const latestNewsSlider = document.getElementById('latest-news-slider');
    const latestPrevButton = document.getElementById('latest-prev');
    const latestNextButton = document.getElementById('latest-next');
    let latestCurrentSlide = 0;

    function updateLatestNewsSlider() {
        latestNewsSlider.style.transform = `translateX(-${latestCurrentSlide * 14.2857}%)`; // Ubah pergeseran menjadi 14.2857% untuk 1/7
    }

    latestNextButton.addEventListener('click', () => {
        latestCurrentSlide = (latestCurrentSlide + 1) % 10; // 10 cards, 7 visible at a time
        updateLatestNewsSlider();
    });

    latestPrevButton.addEventListener('click', () => {
        latestCurrentSlide = (latestCurrentSlide - 1 + 10) % 10; // Menggunakan modulus untuk melingkar
        updateLatestNewsSlider();
    });

    updateLatestNewsSlider();

    // Show more popular cards
    const showMoreButton = document.getElementById('show-more-popular');
    const popularCards = document.querySelectorAll('.popular-card');
    let showAll = false;

    showMoreButton.addEventListener('click', () => {
        showAll = !showAll;
        popularCards.forEach((card, index) => {
            if (showAll) {
                card.classList.remove('hidden');
            } else if (index >= 5) {
                card.classList.add('hidden');
            }
        });
        showMoreButton.textContent = showAll ? 'Tampilkan Lebih Sedikit' : 'Populer Lainnya';
    });
</script>
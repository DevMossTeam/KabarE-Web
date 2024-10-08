<?php include 'header.php'; ?>

<!-- Main Content -->
<div class="flex-grow container mx-auto mt-8 mb-8 relative z-10">
    <!-- Image Slider and News Cards in a responsive grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- News Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-1 gap-2 order-2 lg:order-1">
            <!-- Card 1 -->
            <div class="flex flex-col lg:flex-row items-center lg:items-start">
                <img src="https://via.placeholder.com/200x150" class="w-full md:w-full lg:w-1/4 h-32 object-cover rounded-lg mb-1 lg:mb-0 transition-transform duration-300 hover:scale-105 cursor-pointer">
                <div class="text-center md:text-center lg:text-left lg:ml-4">
                    <h3 class="text-lg font-bold mt-1">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit, Sed Do Eiusmod Tempor</h3>
                    <span class="text-orange-500 font-bold mt-1 inline-block">Kategori</span>
                    <span class="text-gray-500 text-sm mt-1 block">12 menit yang lalu</span>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="flex flex-col lg:flex-row items-center lg:items-start">
                <img src="https://via.placeholder.com/200x150" class="w-full md:w-full lg:w-1/4 h-32 object-cover rounded-lg mb-1 lg:mb-0 transition-transform duration-300 hover:scale-105 cursor-pointer">
                <div class="text-center md:text-center lg:text-left lg:ml-4">
                    <h3 class="text-lg font-bold mt-1">Consectetur Adipiscing Elit, Sed Do Eiusmod Tempor Incididunt Ut Labore Et Dolore</h3>
                    <span class="text-orange-500 font-bold mt-1 inline-block">Kategori</span>
                    <span class="text-gray-500 text-sm mt-1 block">30 menit yang lalu</span>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="flex flex-col lg:flex-row items-center lg:items-start">
                <img src="https://via.placeholder.com/200x150" class="w-full md:w-full lg:w-1/4 h-32 object-cover rounded-lg mb-1 lg:mb-0 transition-transform duration-300 hover:scale-105 cursor-pointer">
                <div class="text-center md:text-center lg:text-left lg:ml-4">
                    <h3 class="text-lg font-bold mt-1">Sed Do Eiusmod Tempor, Magna Aliqua Ut Enim Ad Minim Veniam, Quis Nostrud Exercitation</h3>
                    <span class="text-orange-500 font-bold mt-1 inline-block">Kategori</span>
                    <span class="text-gray-500 text-sm mt-1 block">1 jam yang lalu</span>
                </div>
            </div>
        </div>

        <!-- Image Slider -->
        <div class="relative mb-8 md:mb-0 group order-1 lg:order-2">
            <div class="relative overflow-hidden h-96 rounded-lg">
                <div class="flex transition-transform duration-500 ease-in-out" id="slider">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <img src="https://via.placeholder.com/800x600?text=Image+<?= $i ?>" 
                             class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:brightness-75 transform -translate-y-24">
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
    </div>

    <!-- Berita Terkini -->
    <div class="py-12 mt-8 bg-black -mx-20 px-20">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="bg-orange-500 w-2 h-6"></div>
                <h2 class="text-xl font-bold ml-2 text-white">Berita Terkini</h2>
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
                                <h3 class="text-md font-bold mt-2 text-black">Lorem Ipsum Dolor Sit Amet</h3>
                                <span class="text-orange-500 font-bold mt-2 inline-block">Kategori</span>
                                <span class="text-gray-500 text-sm mt-1 block">12 menit yang lalu</span>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Berita Populer dan Baru Baru Ini -->
    <div class="py-4 mt-16">
        <div class="flex flex-col lg:flex-row">
            <!-- Populer Section -->
            <div class="w-full lg:w-2/3 pr-4">
                <div class="mb-4">
                    <span class="inline-block bg-red-600 text-white px-6 py-1 rounded-t-md ml-4">Populer</span>
                    <div class="border-b-4 border-red-600 mt-0 ml-4"></div>
                </div>
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="flex mb-4 popular-card">
                        <div class="flex-grow ml-4"> <!-- Geser ke kanan -->
                            <h3 class="text-lg font-bold mt-2">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit, Sed Do Eiusmod Tempor</h3>
                            <p class="text-gray-500 mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                        <img src="https://via.placeholder.com/400x300" class="w-64 h-48 object-cover rounded-lg ml-4"> <!-- Gambar di sebelah kanan -->
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Baru Baru Ini Section -->
            <div class="w-full lg:w-1/3 pl-4"> <!-- Tambahkan padding kiri -->
                <div class="mb-4">
                    <span class="inline-block bg-yellow-500 text-white px-6 py-1 rounded-t-md ml-4">Baru Baru Ini</span>
                    <div class="border-b-4 border-yellow-500 mt-0 ml-4"></div>
                </div>
                <ul class="pl-16">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <li class="mb-4">
                            <div class="flex items-center">
                                <span class="text-blue-300 font-semibold italic text-5xl mr-6" style="font-family: 'Inter', sans-serif;"><?= $i ?></span>
                                <div>
                                    <span class="text-gray-400 text-sm">2 jam yang lalu</span>
                                    <h3 class="text-lg font-bold mt-1">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit <?= $i ?></h3>
                                    <?php if ($i === 6): ?> <!-- Tambahkan pembatas hanya pada item terakhir -->
                                        <div class="border-b border-gray-300 mt-2"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>

        <!-- Label dan Kampus Section -->
        <div class="flex flex-col lg:flex-row mt-8">
            <!-- Label Section -->
            <div class="w-full lg:w-1/2">
                <span class="inline-block bg-green-500 text-white px-6 py-1 rounded-t-md">Label</span>
                <div class="border-b-4 border-green-500 mt-0" style="width: 350px;"></div>
                <div class="flex flex-wrap gap-2 mt-4" style="max-width: 350px;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="inline-block bg-white text-black border border-blue-500 px-3 py-1 rounded-full">Tag <?= $i ?></span>
                    <?php endfor; ?>
                </div>
                <ul class="mt-4" style="max-width: 350px;">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <li class="mb-4">
                            <div class="flex flex-col">
                                <span class="text-gray-400 text-sm">2 jam yang lalu</span>
                                <h3 class="text-lg font-bold mt-1">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit <?= $i ?></h3>
                                <div class="border-b border-gray-300 mt-2" style="width: 340px;"></div>
                            </div>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>

          <!-- Kampus Section -->
            <div class="w-full lg:w-1/2 mt-8 lg:mt-1 lg:-ml-72"> <!-- Tambahkan margin kiri negatif untuk menggeser lebih jauh ke kiri -->
                <div class="mb-4">
                    <span class="inline-block bg-[#FF3232] text-white px-6 py-1 rounded-t-md">Kampus</span>
                    <div class="border-b-4 border-[#FF3232] mt-0" style="width: calc(100% + 290px);"></div> <!-- Perlebar border ke kanan hingga ke ujung -->
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 relative"> <!-- Atur grid untuk empat kolom pada lg -->
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-300"></div> <!-- Garis vertikal kiri -->
                    <div class="lg:col-span-3 ml-4"> <!-- Perbesar ukuran kartu besar dan tambahkan margin kiri -->
                        <img src="https://via.placeholder.com/500x400" class="w-full h-auto object-cover rounded-lg"> <!-- Perbesar ukuran gambar besar -->
                        <div class="mt-4">
                            <span class="text-red-500 font-bold">Kategori</span>
                            <span class="text-gray-500 text-sm ml-2">25 Januari 2024</span>
                            <h3 class="text-lg font-bold mt-2">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit</h3>
                            <p class="text-gray-500 mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 lg:grid-cols-1 gap-2 mt-4 lg:mt-0 lg:gap-0"> <!-- Hapus gap antar kartu kecil -->
                        <?php for ($i = 1; $i <= 3; $i++): ?>
                            <img src="https://via.placeholder.com/150x100" class="w-full h-auto object-cover rounded-lg lg:w-31/33 lg:mb-0"> <!-- Perbesar ukuran kartu kecil -->
                        <?php endfor; ?>
                    </div>
                    <div class="absolute right-0 top-0 bottom-0 w-1 bg-gray-300" style="right: -290px;"></div> <!-- Garis vertikal kanan diperluas -->
                </div>
            </div>
        </div>
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
</script>
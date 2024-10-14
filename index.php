<?php include 'header & footer/header.php'; ?>

<!-- Main Content -->
<div class="flex-grow container mx-auto mt-8 mb-8 relative z-10">
    <!-- Image Slider and News Cards in a responsive grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- News Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-1 gap-2 order-2 lg:order-1">
            <!-- Card 1 -->
            <div class="flex flex-col lg:flex-row items-center lg:items-start transform lg:scale-105"> <!-- Perbesar kartu pada ukuran lg -->
                <img src="https://via.placeholder.com/200x150" class="w-full md:w-full lg:w-1/4 h-32 lg:h-40 object-cover rounded-lg mb-1 lg:mb-0 transition-transform duration-300 hover:scale-105 cursor-pointer">
                <div class="text-center md:text-center lg:text-left lg:ml-6 lg:w-full lg:pr-8"> <!-- Kurangi padding kanan -->
                    <h3 class="text-lg font-bold mt-1 line-clamp-3">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit, Sed Do Eiusmod Tempor</h3>
                    <p class="text-gray-500 mt-1 line-clamp-3">Ini adalah deskripsi singkat dari berita yang ditampilkan pada kartu ini.</p>
                    <span class="text-[#FF3232] font-bold mt-1 inline-block">Kategori</span>
                    <span class="text-gray-500 text-sm mt-1 block">12 menit yang lalu</span>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="flex flex-col lg:flex-row items-center lg:items-start transform lg:scale-105">
                <img src="https://via.placeholder.com/200x150" class="w-full md:w-full lg:w-1/4 h-32 lg:h-40 object-cover rounded-lg mb-1 lg:mb-0 transition-transform duration-300 hover:scale-105 cursor-pointer">
                <div class="text-center md:text-center lg:text-left lg:ml-6 lg:w-full lg:pr-8"> <!-- Kurangi padding kanan -->
                    <h3 class="text-lg font-bold mt-1 line-clamp-3">Consectetur Adipiscing Elit, Sed Do Eiusmod Tempor Incididunt Ut Labore Et Dolore</h3>
                    <p class="text-gray-500 mt-1 line-clamp-3">Ini adalah deskripsi singkat dari berita yang ditampilkan pada kartu ini.</p>
                    <span class="text-[#FF3232] font-bold mt-1 inline-block">Kategori</span>
                    <span class="text-gray-500 text-sm mt-1 block">30 menit yang lalu</span>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="flex flex-col lg:flex-row items-center lg:items-start transform lg:scale-105">
                <img src="https://via.placeholder.com/200x150" class="w-full md:w-full lg:w-1/4 h-32 lg:h-40 object-cover rounded-lg mb-1 lg:mb-0 transition-transform duration-300 hover:scale-105 cursor-pointer">
                <div class="text-center md:text-center lg:text-left lg:ml-6 lg:w-full lg:pr-8"> <!-- Kurangi padding kanan -->
                    <h3 class="text-lg font-bold mt-1 line-clamp-3">Sed Do Eiusmod Tempor, Magna Aliqua Ut Enim Ad Minim Veniam, Quis Nostrud Exercitation</h3>
                    <p class="text-gray-500 mt-1 line-clamp-3">Ini adalah deskripsi singkat dari berita yang ditampilkan pada kartu ini.</p>
                    <span class="text-[#FF3232] font-bold mt-1 inline-block">Kategori</span>
                    <span class="text-gray-500 text-sm mt-1 block">1 jam yang lalu</span>
                </div>
            </div>
        </div>

        <!-- Image Slider -->
        <div class="relative mb-8 md:mb-0 group order-1 lg:order-2">
       <!-- Teks dan Label di atas Image Slider -->
            <div class="text-left mb-4">
                <span class="inline-block bg-[#FF3232] text-white px-4 py-1 rounded-md transition-transform duration-500 ease-in-out transform translate-x-full opacity-0" id="category">Kategori 1</span>
                <h2 class="text-2xl font-bold mt-2 transition-transform duration-500 ease-in-out transform translate-x-full opacity-0" id="headline">Lorem Ipsum Dolor Sit Amet 1</h2>
                <p class="text-gray-600 mt-2 transition-transform duration-500 ease-in-out transform translate-x-full opacity-0" id="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>


            <div class="relative overflow-hidden h-96 rounded-lg">
                <div class="flex transition-transform duration-500 ease-in-out" id="slider">
                    <?php for ($i = 1; $i <= 7; $i++): ?> <!-- Ubah jumlah gambar menjadi 7 -->
                        <img src="https://via.placeholder.com/800x600?text=Image+<?= $i ?>" 
                             class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:brightness-75 transform -translate-y-24"
                             loading="lazy"> <!-- Tambahkan lazy loading -->
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
                    <?php for ($i = 0; $i < 7; $i++): ?> <!-- Sesuaikan jumlah dot -->
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
                <div class="bg-[#FF3232] w-2 h-6"></div> <!-- Ubah warna garis menjadi #FF3232 -->
                <h2 class="text-xl font-bold ml-2 text-white">Berita Terkini</h2>
            </div>
        </div>
        <!-- 3 Kartu Gambar di Atas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <?php for ($i = 1; $i <= 3; $i++): ?>
                <div class="relative overflow-hidden rounded-lg">
                    <img src="https://via.placeholder.com/300x200" class="w-full h-56 object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                        <span class="text-white font-bold">Kategori | <?= $i * 3 ?> menit yang lalu</span>
                        <h3 class="text-white text-lg font-bold mt-1">Lorem Ipsum Dolor Sit Amet</h3>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
        <!-- 3 Gambar di Bawah dengan Teks di Samping Kanan -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <?php for ($i = 1; $i <= 3; $i++): ?>
                <div class="flex items-center">
                    <img src="https://via.placeholder.com/200x150" class="w-full md:w-1/2 lg:w-1/2 h-32 md:h-40 lg:h-48 object-cover rounded-lg">
                    <div class="ml-4">
                        <h3 class="text-md font-bold text-white">Lorem Ipsum Dolor Sit Amet</h3>
                        <p class="text-gray-300 text-sm">Ini adalah deskripsi singkat dari berita yang ditampilkan pada kartu ini.</p>
                    </div>
                </div>
            <?php endfor; ?>
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
                        <img src="https://via.placeholder.com/400x300" class="w-64 h-40 object-cover rounded-lg ml-4"> <!-- Gambar di sebelah kanan -->
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
            <div class="w-full lg:w-1/2 mt-8 lg:mt-1 lg:-ml-72">
                <div class="mb-4">
                    <span class="inline-block bg-[#FF3232] text-white px-6 py-1 rounded-t-md">Kampus</span>
                    <div class="border-b-4 border-[#FF3232] mt-0" style="width: calc(100% + 290px);"></div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 relative">
                    <div class="lg:col-span-3">
                        <img src="https://via.placeholder.com/600x350" class="w-full h-auto object-cover rounded-lg">
                        <div class="mt-4">
                            <span class="text-red-500 font-bold">Kategori</span>
                            <span class="text-gray-500 text-sm ml-2">25 Januari 2024</span>
                            <h3 class="text-lg font-bold mt-2">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit</h3>
                            <p class="text-gray-500 mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Politik Section -->
    <div class="mt-8">
        <span class="inline-block bg-[#FF3232] text-white px-6 py-1 rounded-t-md">Politik</span>
        <div class="border-b-4 border-[#FF3232] mt-0 mb-4"></div>
        <div class="flex flex-col lg:flex-row gap-4">
            <?php for ($i = 1; $i <= 2; $i++): ?>
                <div class="flex flex-col w-full lg:w-1/2">
                    <img src="https://via.placeholder.com/600x330" class="w-full h-auto object-cover rounded-lg">
                    <div class="mt-4">
                        <span class="text-red-500 font-bold">Kategori</span>
                        <span class="text-gray-500 text-sm ml-2">25 Januari 2025</span>
                        <h3 class="text-lg font-bold mt-2">Lorem Ipsum Dolor Sit Amet</h3>
                        <p class="text-gray-500 mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Berita Lainnya dan Baru Baru Ini Section -->
    <div class="py-4 mt-16">
        <div class="flex flex-col lg:flex-row">
            <!-- Berita Lainnya Section -->
            <div class="w-full lg:w-2/3 pr-4"> <!-- Lebar lebih besar untuk Berita Lainnya -->
                <div class="mb-4">
                    <span class="inline-block bg-[#45C630] text-white px-6 py-1 rounded-t-md">Berita Lainnya</span>
                    <div class="border-b-4 border-[#45C630] mt-0"></div>
                </div>
                <?php for ($i = 1; $i <= 4; $i++): ?> <!-- Ubah jumlah berita menjadi 4 -->
                    <div class="flex mb-4 items-center"> <!-- Gunakan items-center untuk menyelaraskan -->
                        <div class="flex-grow">
                            <span class="text-gray-400 text-sm block mb-1">2 jam yang lalu</span> <!-- Pindahkan waktu ke pojok kiri atas -->
                            <h3 class="text-lg font-bold mt-1">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit <?= $i ?></h3>
                            <p class="text-gray-500 mt-1 mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p> <!-- Kurangi margin bawah -->
                        </div>
                        <img src="https://via.placeholder.com/200x140" class="w-40 h-28 object-cover rounded-lg ml-4"> <!-- Hapus margin atas -->
                    </div>
                    <div class="border-b border-gray-300 mt-1 mb-4"></div> <!-- Kurangi margin atas pada garis dan tambahkan margin bawah -->
                <?php endfor; ?>
            </div>

            <!-- Baru Baru Ini Section -->
            <div class="w-full lg:w-1/3 pl-4 mt-8 md:mt-12 lg:mt-0"> <!-- Tambahkan margin atas untuk memisahkan dari garis -->
                <div class="mb-4">
                    <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">Baru Baru Ini</span>
                    <div class="border-b-4 border-[#FFC300] mt-0"></div>
                </div>
                <ul class="pl-4">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <li class="mb-4">
                            <div class="flex items-center">
                                <div>
                                    <span class="text-gray-400 text-sm">2 jam yang lalu</span>
                                    <h3 class="text-lg font-bold mt-1">Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit <?= $i ?></h3>
                                    <div class="border-b border-gray-300 mt-2"></div>
                                </div>
                            </div>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
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
        currentSlide = (currentSlide + 1) % headlines.length; // Pastikan menggunakan panjang array headlines
        updateSlider();
    });

    prevButton.addEventListener('click', () => {
        currentSlide = (currentSlide - 1 + headlines.length) % headlines.length; // Pastikan menggunakan panjang array headlines
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
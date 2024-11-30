<?php include 'header & footer/header.php'; ?>
<?php include 'connection/config.php'; ?>

<!-- Main Content -->
<div class="flex-grow container mx-auto mt-8 mb-8 relative z-0 px-4 lg:px-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- News Cards -->
        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-4 order-2 lg:order-1" id="newsCards">
            <!-- Akan diisi oleh JavaScript -->
        </div>

        <!-- Image Slider -->
        <div class="relative mb-8 md:mb-0 group order-1 lg:order-2 z-0">
            <div class="relative overflow-hidden h-96 rounded-lg">
                <div class="flex transition-transform duration-500 ease-in-out" id="slider">
                    <!-- Akan diisi oleh JavaScript -->
                </div>

                <!-- Left and Right Toggle inside the slider -->
                <button id="prev" class="absolute top-1/2 left-4 transform -translate-y-1/2 p-3 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-md z-10">
                    &#10094;
                </button>
                <button id="next" class="absolute top-1/2 right-4 transform -translate-y-1/2 p-3 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-md z-10">
                    &#10095;
                </button>

                <!-- Indicator Dots inside the slider -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10" id="sliderDots">
                    <!-- Akan diisi oleh JavaScript -->
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4" id="beritaTerkiniAtas">
            <!-- Akan diisi oleh JavaScript -->
        </div>
        <!-- 3 Gambar di Bawah dengan Teks di Samping Kanan -->
        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-4" id="beritaTerkiniBawah">
            <!-- Akan diisi oleh JavaScript -->
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
            <div id="beritaPopuler">
                <!-- Akan diisi oleh JavaScript -->
            </div>
        </div>

        <!-- Baru Baru Ini Section -->
        <div class="w-full lg:w-1/3 pl-4 mt-8 md:mt-12 lg:mt-0">
            <div class="mb-4">
                <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">Baru Baru Ini</span>
                <div class="border-b-4 border-[#FFC300] mt-0"></div>
            </div>
            <ul class="pl-4" id="beritaBaru">
                <!-- Akan diisi oleh JavaScript -->
            </ul>
        </div>
    </div>

    <!-- Berita dari Kategori Acak -->
    <div class="mt-8">
        <div class="mb-4">
            <span id="randomCategoryLabel" class="inline-block bg-[#FF3232] text-white px-6 py-1 rounded-t-md"></span>
            <div class="border-b-4 border-[#FF3232] mt-0"></div>
        </div>
        
        <!-- Konten Utama -->
        <div class="flex flex-col lg:flex-row">
            <!-- Gambar Besar -->
            <div class="w-full lg:w-1/2 pr-4 mb-4 lg:mb-0">
                <a id="randomCategoryMainLink" href="#">
                    <img id="randomCategoryImage" src="" class="w-full h-96 object-cover rounded-lg">
                </a>
                <div class="p-4" style="padding-left: 0; padding-right: 0;">
                    <span id="randomCategoryText" class="text-red-500 font-bold"></span>
                    <span id="randomCategoryTime" class="text-gray-500"></span>
                    <a id="randomCategoryTitleLink" href="#">
                        <h3 id="randomCategoryTitle" class="text-lg font-bold mt-1"></h3>
                    </a>
                    <p id="randomCategoryDescription" class="text-gray-700 mt-2"></p>
                </div>
            </div>
            <!-- Gambar Kecil dan Daftar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full lg:w-1/2 pl-4">
                <div id="randomCategorySide" class="mb-4">
                    <!-- Akan diisi oleh JavaScript -->
                </div>
                <div>
                    <ul id="randomCategoryList">
                        <!-- Akan diisi oleh JavaScript -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- 2 Berita Random Tambahan -->
        <div class="mt-8">
            <!-- Label Kategori -->
            <div class="mb-4">
                <span id="randomExtraLabel" class="inline-block bg-[#FF3232] text-white px-6 py-1 rounded-t-md"></span>
                <div class="border-b-4 border-[#FF3232] mt-0"></div>
            </div>
            
            <!-- Konten Berita -->
            <div class="flex flex-col md:flex-row gap-4">
                <div id="randomExtraMain" class="flex flex-col md:flex-row gap-4 w-full"></div>
            </div>
        </div>
    </div>

    <!-- Berita Lainnya dan Baru Baru Ini -->
    <div class="flex flex-col lg:flex-row gap-8 mt-8">
        <!-- Berita Lainnya -->
        <div class="w-full lg:w-2/3">
            <div class="mb-4">
                <span class="inline-block bg-[#45C630] text-white px-6 py-1 rounded-t-md">Berita Lainnya</span>
                <div class="border-b-4 border-[#45C630] mt-0"></div>
            </div>
            <div id="beritaLainnya"></div>
        </div>

        <!-- Baru Baru Ini -->
        <div class="w-full lg:w-1/3">
            <div class="mb-4">
                <span class="inline-block bg-[#FFC300] text-white px-6 py-1 rounded-t-md">Baru Baru Ini</span>
                <div class="border-b-4 border-[#FFC300] mt-0"></div>
            </div>
            <div id="baruBaruIni"></div>
        </div>
    </div>
</div>

<?php include 'header & footer/footer.php'; ?>

<script>
let currentSlide = 0;
let slideInterval;

async function fetchBerandaData() {
    try {
        const response = await fetch('api/berita/beranda.php');
        const data = await response.json();
        
        if (data.status === 'success') {
            updateNewsCards(data.data.newsCards);
            updateSlider(data.data.slider);
            updateBeritaTerkini(data.data.beritaTerkini);
            updateBeritaPopuler(data.data.beritaPopuler);
            updateBeritaBaru(data.data.beritaBaru);
            updateRandomCategory(data.data.randomCategory);
            updateRandomExtra(data.data.randomExtra);
            updateBeritaLainnya(data.data.beritaLainnya);
            updateBaruBaruIni(data.data.baruBaruIni);
        }
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

function updateSlider(sliderData) {
    const sliderContainer = document.getElementById('slider');
    const dotsContainer = document.getElementById('sliderDots');
    
    sliderContainer.innerHTML = sliderData.map(data => `
        <div class="relative w-full h-full flex-shrink-0">
            <img src="${data.firstImage || 'https://via.placeholder.com/800x600'}" 
                 class="w-full h-96 object-cover rounded-lg transition duration-300 ease-in-out brightness-75 hover:brightness-50">
            <div class="absolute top-4 left-4 text-white">
                <span class="font-bold">${data.kategori}</span>
                <a href="category/news-detail.php?id=${data.id}">
                    <h3 class="text-lg font-bold mt-1">${data.judul}</h3>
                </a>
            </div>
        </div>
    `).join('');

    dotsContainer.innerHTML = sliderData.map((_, index) => `
        <button class="w-3 h-3 rounded-full bg-gray-400 transition-all duration-300 ease-in-out" 
                data-slide="${index}"></button>
    `).join('');

    initializeSlider();
}

function updateBeritaTerkini(beritaTerkini) {
    const atasContainer = document.getElementById('beritaTerkiniAtas');
    const bawahContainer = document.getElementById('beritaTerkiniBawah');

    // Update bagian atas (3 kartu)
    atasContainer.innerHTML = beritaTerkini.slice(0, 3).map(berita => `
        <a href="category/news-detail.php?id=${berita.id}" class="relative overflow-hidden rounded-lg">
            <img src="${berita.firstImage || 'https://via.placeholder.com/300x200'}" class="w-full h-56 md:h-56 lg:h-56 object-cover">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                <span class="text-white font-bold">${berita.kategori} | ${berita.timeAgo}</span>
                <h3 class="text-white text-lg font-bold mt-1">${berita.judul}</h3>
            </div>
        </a>
    `).join('');

    // Update bagian bawah (3 berita dengan teks di samping)
    bawahContainer.innerHTML = beritaTerkini.slice(3, 6).map(berita => `
        <a href="category/news-detail.php?id=${berita.id}" class="flex flex-col md:flex-row items-start">
            <img src="${berita.firstImage || 'https://via.placeholder.com/200x150'}" class="w-full md:w-1/3 h-32 md:h-40 object-cover rounded-lg">
            <div class="ml-4">
                <h3 class="text-md font-bold text-white">${berita.judul}</h3>
                <p class="text-gray-300 text-sm">${berita.konten_artikel.replace(/<[^>]*>/g, '').substring(0, 100)}...</p>
            </div>
        </a>
    `).join('');
}

function updateBeritaPopuler(beritaPopuler) {
    const container = document.getElementById('beritaPopuler');
    
    container.innerHTML = beritaPopuler.map(berita => `
        <div class="flex mb-6 items-start">
            <div class="flex-grow">
                <a href="category/news-detail.php?id=${berita.id}">
                    <h3 class="text-lg font-bold mt-1">${berita.judul}</h3>
                </a>
                <p class="text-gray-500 mt-1">${berita.konten_artikel.replace(/<[^>]*>/g, '').substring(0, 300)}...</p>
            </div>
            <a href="category/news-detail.php?id=${berita.id}" class="flex-shrink-0 h-48 bg-gray-200 flex items-center justify-center overflow-hidden rounded-lg ml-4" style="width: 300px;">
                <img src="${berita.firstImage || 'https://via.placeholder.com/400x300'}" class="w-full h-full object-cover">
            </a>
        </div>
    `).join('');
}

function updateBeritaBaru(beritaBaru) {
    const container = document.getElementById('beritaBaru');
    
    container.innerHTML = beritaBaru.map((berita, index) => `
        <li class="mb-4">
            <div class="flex items-center">
                <span class="text-[#CAD2FF] text-5xl font-semibold italic mr-4 flex-shrink-0">${index + 1}</span>
                <div class="flex-grow">
                    <span class="text-gray-400 text-sm">${berita.timeAgo}</span>
                    <a href="category/news-detail.php?id=${berita.id}">
                        <h3 class="text-lg font-bold mt-1">${berita.judul}</h3>
                    </a>
                    <div class="border-b border-gray-300 mt-2 w-full"></div>
                </div>
            </div>
        </li>
    `).join('');
}

function initializeSlider() {
    const slider = document.getElementById('slider');
    const slides = slider.children;
    const dots = document.querySelectorAll('[data-slide]');
    
    function showSlide(index) {
        currentSlide = index;
        slider.style.transform = `translateX(-${index * 100}%)`;
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-white', i === index);
            dot.classList.toggle('bg-gray-400', i !== index);
        });
    }

    function nextSlide() {
        showSlide((currentSlide + 1) % slides.length);
    }

    function prevSlide() {
        showSlide((currentSlide - 1 + slides.length) % slides.length);
    }

    // Event listeners
    document.getElementById('next').addEventListener('click', nextSlide);
    document.getElementById('prev').addEventListener('click', prevSlide);
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => showSlide(index));
    });

    // Auto slide
    if (slideInterval) clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, 5000);

    // Initial display
    showSlide(0);
}

function updateNewsCards(newsCardsData) {
    const container = document.getElementById('newsCards');
    
    container.innerHTML = newsCardsData.map(news => `
        <a href="category/news-detail.php?id=${news.id}" class="flex flex-col md:flex-row items-start mb-6">
            <div class="w-full md:w-48 h-32 bg-gray-200 flex-shrink-0 flex items-center justify-center overflow-hidden rounded-lg">
                <img src="${news.firstImage || 'https://via.placeholder.com/200x150'}" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col justify-start md:ml-4">
                <h3 class="text-md font-bold mt-1">${news.judul}</h3>
                <p class="text-gray-500 mt-1 text-xs">${news.konten_artikel.replace(/<[^>]*>/g, '').substring(0, 100)}...</p>
                <span class="text-sm mt-1">
                    <span class="text-red-500 font-bold">${news.kategori}</span>
                    <span class="text-gray-400"> | ${news.timeAgo}</span>
                </span>
            </div>
        </a>
    `).join('');
}

function updateRandomCategory(randomCategoryData) {
    if (randomCategoryData.length < 7) return; // Pastikan ada cukup data

    const mainNews = randomCategoryData[0];
    const sideNews = randomCategoryData.slice(1, 4);
    const listNews = randomCategoryData.slice(4, 10);

    document.getElementById('randomCategoryLabel').textContent = mainNews.kategori;
    document.getElementById('randomCategoryMainLink').href = `category/news-detail.php?id=${mainNews.id}`;
    document.getElementById('randomCategoryImage').src = mainNews.firstImage || 'https://via.placeholder.com/600x350';
    document.getElementById('randomCategoryText').textContent = mainNews.kategori;
    document.getElementById('randomCategoryTime').textContent = ` | ${new Date(mainNews.tanggal_diterbitkan).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}`;
    document.getElementById('randomCategoryTitleLink').href = `category/news-detail.php?id=${mainNews.id}`;
    document.getElementById('randomCategoryTitle').textContent = mainNews.judul;
    document.getElementById('randomCategoryDescription').textContent = mainNews.konten_artikel.replace(/<[^>]*>/g, '').substring(0, 100) + '...';

    const sideContainer = document.getElementById('randomCategorySide');
    sideContainer.innerHTML = sideNews.map(news => `
        <a href="category/news-detail.php?id=${news.id}" class="block mb-4 relative">
            <img src="${news.firstImage || 'https://via.placeholder.com/300x200'}" class="w-full h-48 object-cover rounded-lg rounded-b-lg">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4 rounded-b-lg">
                <h3 class="text-white text-sm font-bold">${news.judul}</h3>
            </div>
        </a>
    `).join('');

    const listContainer = document.getElementById('randomCategoryList');
    listContainer.innerHTML = listNews.map(news => `
        <li class="mb-2 border-b border-gray-300 pb-2">
            <span class="text-gray-400 text-sm block">${news.timeAgo}</span>
            <a href="category/news-detail.php?id=${news.id}" class="text-black hover:underline font-bold">${news.judul}</a>
        </li>
    `).join('');
}

function updateRandomExtra(randomExtraData) {
    if (randomExtraData.length === 0) return;
    
    // Update label kategori
    document.getElementById('randomExtraLabel').textContent = randomExtraData[0].kategori;
    
    // Update 2 berita sejajar horizontal
    const mainContainer = document.getElementById('randomExtraMain');
    mainContainer.innerHTML = randomExtraData.map(news => `
        <div class="w-full md:w-1/2">
            <a href="category/news-detail.php?id=${news.id}">
                <img src="${news.firstImage || 'https://via.placeholder.com/600x350'}" 
                     class="w-full h-96 object-cover rounded-lg">
            </a>
            <div class="p-4" style="padding-left: 0; padding-right: 0;">
                <span class="text-red-500 font-bold">${news.kategori}</span>
                <span class="text-gray-500"> | ${news.timeAgo}</span>
                <a href="category/news-detail.php?id=${news.id}">
                    <h3 class="text-lg font-bold mt-1">${news.judul}</h3>
                </a>
                <p class="text-gray-700 mt-2">${stripHtml(news.konten_artikel).substring(0, 150)}...</p>
            </div>
        </div>
    `).join('');
}

function updateBeritaLainnya(beritaLainnyaData) {
    const container = document.getElementById('beritaLainnya');
    container.innerHTML = beritaLainnyaData.map(news => `
        <div class="flex mb-6">
            <div class="flex-grow pr-4">
                <a href="category/news-detail.php?id=${news.id}">
                    <h3 class="text-lg font-bold hover:text-blue-600">${news.judul}</h3>
                </a>
                <p class="text-gray-600 mt-2">${stripHtml(news.konten_artikel).substring(0, 100)}...</p>
                <div class="mt-2">
                    <span class="text-red-500 font-bold">${news.kategori}</span>
                    <span class="text-gray-400"> | ${news.timeAgo}</span>
                </div>
            </div>
            <div class="w-64 h-40 flex-shrink-0">
                <img src="${news.firstImage || 'https://via.placeholder.com/300x200'}" 
                     class="w-full h-full object-cover rounded-lg">
            </div>
        </div>
        <div class="border-b border-gray-300 mt-2 mb-4"></div>
    `).join('');
}

function updateBaruBaruIni(baruBaruIniData) {
    const container = document.getElementById('baruBaruIni');
    container.innerHTML = baruBaruIniData.map(news => `
        <div class="mb-4">
            <span class="text-gray-400 text-sm">${news.timeAgo}</span>
            <a href="category/news-detail.php?id=${news.id}">
                <h3 class="text-lg font-bold mt-1 hover:text-blue-600">${news.judul}</h3>
            </a>
            <div class="border-b border-gray-300 mt-2"></div>
        </div>
    `).join('');
}

// Fungsi untuk membersihkan HTML tags
function stripHtml(html) {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
}

// Panggil data saat halaman dimuat
document.addEventListener('DOMContentLoaded', fetchBerandaData);
</script>
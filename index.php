<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-white text-2xl font-bold">BeritaKu</a>
            <div>
                <a href="index.php" class="text-white px-4">Home</a>
                <a href="news.php" class="text-white px-4">News</a>
                <a href="about.php" class="text-white px-4">About</a>
                <a href="contact.php" class="text-white px-4">Contact</a>
                <a href="logout.php" class="text-white px-4">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow">
        <!-- Image Slider -->
        <div class="relative w-full max-w-full mx-auto mb-16 group">
            <!-- Slider Images -->
            <div class="relative overflow-hidden h-80"> <!-- Adjusted height -->
                <div class="flex transition-transform duration-500 ease-in-out" id="slider">
                    <img src="https://via.placeholder.com/1600x400?text=Image+1" class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:brightness-75">
                    <img src="https://via.placeholder.com/1600x400?text=Image+2" class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:brightness-75">
                    <img src="https://via.placeholder.com/1600x400?text=Image+3" class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:brightness-75">
                    <img src="https://via.placeholder.com/1600x400?text=Image+4" class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:brightness-75">
                    <img src="https://via.placeholder.com/1600x400?text=Image+5" class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:brightness-75">
                </div>
            </div>

            <!-- Left and Right Toggle -->
            <button id="prev" class="absolute top-1/2 left-0 transform -translate-y-1/2 p-3 bg-gray-800 text-white rounded-md">
                &#10094;
            </button>
            <button id="next" class="absolute top-1/2 right-0 transform -translate-y-1/2 p-3 bg-gray-800 text-white rounded-md">
                &#10095;
            </button>

            <!-- Indicator Dots -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                <button class="w-3 h-3 rounded-full bg-gray-400 transition-all duration-300 ease-in-out" data-slide="0"></button>
                <button class="w-3 h-3 rounded-full bg-gray-400 transition-all duration-300 ease-in-out" data-slide="1"></button>
                <button class="w-3 h-3 rounded-full bg-gray-400 transition-all duration-300 ease-in-out" data-slide="2"></button>
                <button class="w-3 h-3 rounded-full bg-gray-400 transition-all duration-300 ease-in-out" data-slide="3"></button>
                <button class="w-3 h-3 rounded-full bg-gray-400 transition-all duration-300 ease-in-out" data-slide="4"></button>
            </div>
        </div>

        <!-- Welcome Text -->
        <div class="container mx-auto text-center mb-16">
            <h1 class="text-3xl font-bold mb-6">Welcome to BeritaKu</h1>
            <p class="mb-8">You are logged in as <?php echo $_SESSION['username']; ?>!</p>
        </div>

        <!-- Featured News -->
        <div class="container mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
                <h2 class="text-2xl font-bold mb-2">Featured News</h2>
                <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                <a href="#" class="text-blue-500 hover:underline">Read more</a>
            </div>

            <!-- News Articles -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <!-- Article 1 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold mb-2">Judul Berita 1</h2>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
                <!-- Article 2 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold mb-2">Judul Berita 2</h2>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
                <!-- Article 3 -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold mb-2">Judul Berita 3</h2>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
                <!-- Tambahkan lebih banyak artikel sesuai kebutuhan -->
            </div>

            <!-- Additional Content -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
                <h2 class="text-2xl font-bold mb-2">Latest Updates</h2>
                <ul class="list-disc pl-5">
                    <li class="text-gray-700 mb-2">Update 1: Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                    <li class="text-gray-700 mb-2">Update 2: Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</li>
                    <li class="text-gray-700 mb-2">Update 3: Sed nisi. Nulla quis sem at nibh elementum imperdiet.</li>
                    <li class="text-gray-700 mb-2">Update 4: Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta.</li>
                </ul>
            </div>

            <!-- Popular Articles -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
                <h2 class="text-2xl font-bold mb-2">Popular Articles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Popular Article 1 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Popular Article 1</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                    <!-- Popular Article 2 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Popular Article 2</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                    <!-- Popular Article 3 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Popular Article 3</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                </div>
            </div>

            <!-- More Content -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
                <h2 class="text-2xl font-bold mb-2">Editor's Picks</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Editor's Pick 1 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Editor's Pick 1</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                    <!-- Editor's Pick 2 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Editor's Pick 2</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                    <!-- Editor's Pick 3 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Editor's Pick 3</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                </div>
            </div>

            <!-- Additional Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
                <h2 class="text-2xl font-bold mb-2">Technology News</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Tech News 1 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Tech News 1</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                    <!-- Tech News 2 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Tech News 2</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                    <!-- Tech News 3 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Tech News 3</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                </div>
            </div>

            <!-- Additional Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
                <h2 class="text-2xl font-bold mb-2">Health News</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Health News 1 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Health News 1</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                    <!-- Health News 2 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Health News 2</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                    <!-- Health News 3 -->
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-2">Health News 3</h3>
                        <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                        <a href="#" class="text-blue-500 hover:underline">Read more</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-500 text-white py-4 mt-auto">
        <div class="container mx-auto text-center">
            <p>&copy; 2023 BeritaKu. All rights reserved.</p>
        </div>
    </footer>

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
    </script>
</body>
</html>
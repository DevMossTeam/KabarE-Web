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
    <title>News</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
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

    <!-- Welcome Text -->
    <div class="container mx-auto text-center mb-16">
        <h1 class="text-3xl font-bold mb-6">Latest News</h1>
        <p class="mb-8">Stay updated with the latest news and articles.</p>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto">
        <!-- Breaking News -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
            <h2 class="text-2xl font-bold mb-2">Breaking News</h2>
            <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
            <a href="#" class="text-blue-500 hover:underline">Read more</a>
        </div>

        <!-- News Articles -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <!-- Article 1 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-2">Judul Berita 4</h2>
                <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                <a href="#" class="text-blue-500 hover:underline">Read more</a>
            </div>
            <!-- Article 2 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-2">Judul Berita 5</h2>
                <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                <a href="#" class="text-blue-500 hover:underline">Read more</a>
            </div>
            <!-- Article 3 -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-2">Judul Berita 6</h2>
                <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                <a href="#" class="text-blue-500 hover:underline">Read more</a>
            </div>
            <!-- Tambahkan lebih banyak artikel sesuai kebutuhan -->
        </div>

        <!-- Additional Content -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
            <h2 class="text-2xl font-bold mb-2">Global News</h2>
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
                <!-- Popular Article 4 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Popular Article 4</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
                <!-- Popular Article 5 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Popular Article 5</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
                <!-- Popular Article 6 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Popular Article 6</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
            </div>
        </div>

        <!-- More Content -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
            <h2 class="text-2xl font-bold mb-2">Editor's Picks</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Editor's Pick 4 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Editor's Pick 4</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
                <!-- Editor's Pick 5 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Editor's Pick 5</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
                <!-- Editor's Pick 6 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Editor's Pick 6</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
            </div>
        </div>

        <!-- Additional Section -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
            <h2 class="text-2xl font-bold mb-2">Sports News</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Sports News 1 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Sports News 1</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
                <!-- Sports News 2 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Sports News 2</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
                <!-- Sports News 3 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Sports News 3</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
            </div>
        </div>

        <!-- Additional Section -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-16">
            <h2 class="text-2xl font-bold mb-2">Entertainment News</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Entertainment News 1 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Entertainment News 1</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
                <!-- Entertainment News 2 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Entertainment News 2</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
                <!-- Entertainment News 3 -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-xl font-bold mb-2">Entertainment News 3</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
                    <a href="#" class="text-blue-500 hover:underline">Read more</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>